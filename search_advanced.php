<?php
// =============================================
// TUGAS 2: Sistem Pencarian Buku Lanjutan
// =============================================
// Konsep yang dipakai:
// - $_GET: Mengambil parameter dari URL (?keyword=...&kategori=...)
// - GET method = parameter muncul di URL → bagus untuk pencarian/filter
// - array_filter(): menyaring array berdasarkan kondisi
// - usort(): sorting array berdasarkan kolom tertentu
// - Pagination: membagi hasil ke beberapa halaman
// - Highlight keyword: str_ireplace() untuk menandai kata yang dicari
// =============================================

// ---- DATA BUKU (minimal 10) ----
$buku_list = [
    ['kode' => 'BK001', 'judul' => 'Clean Code', 'kategori' => 'Teknologi', 'pengarang' => 'Robert C. Martin', 'penerbit' => 'Prentice Hall', 'tahun' => 2008, 'harga' => 285000, 'stok' => 5],
    ['kode' => 'BK002', 'judul' => 'Laskar Pelangi', 'kategori' => 'Fiksi', 'pengarang' => 'Andrea Hirata', 'penerbit' => 'Bentang Pustaka', 'tahun' => 2005, 'harga' => 95000, 'stok' => 8],
    ['kode' => 'BK003', 'judul' => 'Belajar PHP Modern', 'kategori' => 'Teknologi', 'pengarang' => 'Budi Raharjo', 'penerbit' => 'Informatika', 'tahun' => 2022, 'harga' => 145000, 'stok' => 3],
    ['kode' => 'BK004', 'judul' => 'Atomic Habits', 'kategori' => 'Pengembangan Diri', 'pengarang' => 'James Clear', 'penerbit' => 'Gramedia', 'tahun' => 2019, 'harga' => 105000, 'stok' => 0],
    ['kode' => 'BK005', 'judul' => 'Sejarah Indonesia Modern', 'kategori' => 'Sejarah', 'pengarang' => 'M.C. Ricklefs', 'penerbit' => 'Serambi', 'tahun' => 2001, 'harga' => 185000, 'stok' => 2],
    ['kode' => 'BK006', 'judul' => 'The Pragmatic Programmer', 'kategori' => 'Teknologi', 'pengarang' => 'David Thomas', 'penerbit' => 'Addison Wesley', 'tahun' => 2019, 'harga' => 320000, 'stok' => 4],
    ['kode' => 'BK007', 'judul' => 'Bumi Manusia', 'kategori' => 'Fiksi', 'pengarang' => 'Pramoedya Ananta Toer', 'penerbit' => 'Hasta Mitra', 'tahun' => 1980, 'harga' => 89000, 'stok' => 6],
    ['kode' => 'BK008', 'judul' => 'Rich Dad Poor Dad', 'kategori' => 'Pengembangan Diri', 'pengarang' => 'Robert T. Kiyosaki', 'penerbit' => 'Gramedia', 'tahun' => 2000, 'harga' => 79000, 'stok' => 0],
    ['kode' => 'BK009', 'judul' => 'Pengantar Algoritma', 'kategori' => 'Teknologi', 'pengarang' => 'Thomas H. Cormen', 'penerbit' => 'MIT Press', 'tahun' => 2009, 'harga' => 450000, 'stok' => 1],
    ['kode' => 'BK010', 'judul' => 'Sapiens: Riwayat Singkat Umat Manusia', 'kategori' => 'Sejarah', 'pengarang' => 'Yuval Noah Harari', 'penerbit' => 'KPG', 'tahun' => 2015, 'harga' => 129000, 'stok' => 7],
    ['kode' => 'BK011', 'judul' => 'JavaScript: The Good Parts', 'kategori' => 'Teknologi', 'pengarang' => 'Douglas Crockford', 'penerbit' => "O'Reilly", 'tahun' => 2008, 'harga' => 195000, 'stok' => 2],
    ['kode' => 'BK012', 'judul' => 'Filosofi Teras', 'kategori' => 'Pengembangan Diri', 'pengarang' => 'Henry Manampiring', 'penerbit' => 'Kompas', 'tahun' => 2019, 'harga' => 85000, 'stok' => 9],
];

// ---- AMBIL PARAMETER DARI $_GET ----
// ?? '' = null coalescing operator: jika tidak ada, gunakan nilai default
$keyword   = trim($_GET['keyword'] ?? '');
$kategori  = $_GET['kategori'] ?? '';
$min_harga = $_GET['min_harga'] ?? '';
$max_harga = $_GET['max_harga'] ?? '';
$tahun     = $_GET['tahun'] ?? '';
$status    = $_GET['status'] ?? 'semua';
$sort      = $_GET['sort'] ?? 'judul';
$page      = max(1, intval($_GET['page'] ?? 1));   // Halaman sekarang (min 1)
$per_page  = 10;

// ---- VALIDASI INPUT PENCARIAN ----
$errors = [];

if (!empty($min_harga) && !empty($max_harga)) {
    if (intval($min_harga) > intval($max_harga)) {
        $errors[] = 'Harga minimum tidak boleh lebih besar dari harga maksimum.';
    }
}

if (!empty($tahun)) {
    $tahun_int = intval($tahun);
    $tahun_sekarang = intval(date('Y'));
    if ($tahun_int < 1900 || $tahun_int > $tahun_sekarang) {
        $errors[] = "Tahun harus antara 1900 sampai $tahun_sekarang.";
    }
}

// ---- FILTER DATA ----
// array_filter() = menyaring array, callback return true = data masuk, false = data dibuang
$hasil = array_filter($buku_list, function ($buku) use ($keyword, $kategori, $min_harga, $max_harga, $tahun, $status) {

    // Filter keyword: cari di judul ATAU pengarang (case-insensitive)
    if (!empty($keyword)) {
        $kwLower = strtolower($keyword);
        $judulMatch    = strpos(strtolower($buku['judul']), $kwLower) !== false;
        $pengarangMatch = strpos(strtolower($buku['pengarang']), $kwLower) !== false;
        if (!$judulMatch && !$pengarangMatch) return false;
    }

    // Filter kategori
    if (!empty($kategori) && $buku['kategori'] !== $kategori) return false;

    // Filter harga minimum
    if (!empty($min_harga) && $buku['harga'] < intval($min_harga)) return false;

    // Filter harga maksimum
    if (!empty($max_harga) && $buku['harga'] > intval($max_harga)) return false;

    // Filter tahun
    if (!empty($tahun) && $buku['tahun'] != intval($tahun)) return false;

    // Filter status ketersediaan
    if ($status === 'tersedia' && $buku['stok'] <= 0) return false;
    if ($status === 'habis' && $buku['stok'] > 0) return false;

    return true; // Lolos semua filter
});

// ---- SORTING ----
// usort() = sorting array dengan fungsi pembanding kustom
usort($hasil, function ($a, $b) use ($sort) {
    switch ($sort) {
        case 'harga_asc':  return $a['harga'] - $b['harga'];           // harga: rendah ke tinggi
        case 'harga_desc': return $b['harga'] - $a['harga'];           // harga: tinggi ke rendah
        case 'tahun':      return $b['tahun'] - $a['tahun'];           // tahun: terbaru dulu
        default:           return strcmp($a['judul'], $b['judul']);     // judul: A-Z (strcmp = compare string)
    }
});

// ---- PAGINATION ----
$total_hasil  = count($hasil);                              // Total item ditemukan
$total_halaman = ceil($total_hasil / $per_page);            // ceil() = pembulatan ke atas
$offset       = ($page - 1) * $per_page;                   // Item ke berapa yang mulai ditampilkan
$hasil_paged  = array_slice($hasil, $offset, $per_page);   // array_slice = potong array sesuai halaman

// Ambil daftar kategori unik untuk dropdown
$kategori_list = array_unique(array_column($buku_list, 'kategori'));
sort($kategori_list);

// ---- HELPER: Buat URL dengan parameter (untuk pagination & sorting) ----
// http_build_query() = mengubah array jadi string URL query
function buildUrl($params = []) {
    $current = $_GET;
    unset($current['page']); // Reset ke halaman 1 saat filter berubah
    $merged = array_merge($current, $params);
    $query = http_build_query(array_filter($merged, fn($v) => $v !== ''));
    return '?' . $query;
}

// ---- HELPER: Highlight keyword dalam teks ----
// str_ireplace() = replace case-insensitive, bungkus dengan <mark>
function highlight($text, $keyword) {
    if (empty($keyword)) return htmlspecialchars($text);
    $escaped = htmlspecialchars($text);
    $escapedKw = preg_quote(htmlspecialchars($keyword), '/');
    return preg_replace("/($escapedKw)/i", '<mark class="p-0">$1</mark>', $escaped);
}

// Format harga ke Rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

$adaFilter = !empty($keyword) || !empty($kategori) || !empty($min_harga) || !empty($max_harga) || !empty($tahun) || $status !== 'semua';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Buku - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f0f4f8; }
        .card { border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-radius: 12px; }
        .card-header { background: linear-gradient(135deg, #1e3a5f, #2d6a9f); border-radius: 12px 12px 0 0 !important; }
        .table thead th { background-color: #1e3a5f; color: white; }
        mark { background-color: #fff176; border-radius: 2px; }
        .badge-tersedia { background-color: #198754; }
        .badge-habis { background-color: #dc3545; }
        .stok-badge { font-size: 0.8rem; }
    </style>
</head>
<body>
<div class="container py-5">

    <!-- HEADER -->
    <div class="card mb-4">
        <div class="card-header text-white py-3">
            <h4 class="mb-0"><i class="bi bi-search me-2"></i>Pencarian Buku Lanjutan</h4>
            <small class="opacity-75">Sistem Perpustakaan Digital</small>
        </div>
        <div class="card-body p-4">

            <!--
                method="get" → Data dikirim via $_GET, tampil di URL
                Kelebihan GET untuk pencarian: URL bisa di-bookmark/share
                Contoh URL: search_advanced.php?keyword=php&kategori=Teknologi
            -->
            <form method="get" action="">

                <!-- ROW 1: Keyword + Kategori -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kata Kunci (Judul/Pengarang)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" name="keyword"
                                   placeholder="Cari judul atau pengarang..."
                                   value="<?= htmlspecialchars($keyword) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select class="form-select" name="kategori">
                            <option value="">-- Semua Kategori --</option>
                            <?php foreach ($kategori_list as $kat): ?>
                                <option value="<?= $kat ?>" <?= ($kategori === $kat) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- ROW 2: Harga Range + Tahun -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga Minimum (Rp)</label>
                        <input type="number" class="form-control <?= (!empty($errors) && !empty($min_harga)) ? 'is-invalid' : '' ?>"
                               name="min_harga" placeholder="0" min="0"
                               value="<?= htmlspecialchars($min_harga) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga Maksimum (Rp)</label>
                        <input type="number" class="form-control"
                               name="max_harga" placeholder="500000" min="0"
                               value="<?= htmlspecialchars($max_harga) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tahun Terbit</label>
                        <input type="number" class="form-control"
                               name="tahun" placeholder="2023" min="1900" max="<?= date('Y') ?>"
                               value="<?= htmlspecialchars($tahun) ?>">
                    </div>
                </div>

                <!-- ROW 3: Status + Sort -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status Ketersediaan</label>
                        <div class="d-flex gap-3 pt-1">
                            <?php
                            $statusOptions = ['semua' => 'Semua', 'tersedia' => 'Tersedia', 'habis' => 'Habis'];
                            foreach ($statusOptions as $val => $label):
                                $checked = ($status === $val) ? 'checked' : '';
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status"
                                       id="status_<?= $val ?>" value="<?= $val ?>" <?= $checked ?>>
                                <label class="form-check-label" for="status_<?= $val ?>"><?= $label ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Urutkan Berdasarkan</label>
                        <select class="form-select" name="sort">
                            <option value="judul"      <?= ($sort === 'judul')      ? 'selected' : '' ?>>Judul (A-Z)</option>
                            <option value="harga_asc"  <?= ($sort === 'harga_asc')  ? 'selected' : '' ?>>Harga (Rendah ke Tinggi)</option>
                            <option value="harga_desc" <?= ($sort === 'harga_desc') ? 'selected' : '' ?>>Harga (Tinggi ke Rendah)</option>
                            <option value="tahun"      <?= ($sort === 'tahun')      ? 'selected' : '' ?>>Tahun (Terbaru)</option>
                        </select>
                    </div>
                </div>

                <!-- TOMBOL -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search me-1"></i>Cari Buku
                    </button>
                    <a href="search_advanced.php" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Reset Filter
                    </a>
                </div>
            </form>

        </div>
    </div>

    <!-- PESAN ERROR VALIDASI -->
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?php foreach ($errors as $e): ?>
            <div><?= htmlspecialchars($e) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- HASIL PENCARIAN -->
    <div class="card">
        <div class="card-body p-4">

            <!-- INFO JUMLAH HASIL -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <?php if ($adaFilter || !empty($keyword)): ?>
                        <h6 class="mb-0">
                            <i class="bi bi-funnel-fill text-primary me-1"></i>
                            Ditemukan <span class="badge bg-primary"><?= $total_hasil ?></span> buku
                            <?php if (!empty($keyword)): ?>
                                untuk kata kunci "<strong><?= htmlspecialchars($keyword) ?></strong>"
                            <?php endif; ?>
                        </h6>
                    <?php else: ?>
                        <h6 class="mb-0">
                            <i class="bi bi-collection-fill text-primary me-1"></i>
                            Menampilkan semua <span class="badge bg-primary"><?= $total_hasil ?></span> buku
                        </h6>
                    <?php endif; ?>
                </div>
                <?php if ($total_halaman > 1): ?>
                <small class="text-muted">Halaman <?= $page ?> dari <?= $total_halaman ?></small>
                <?php endif; ?>
            </div>

            <!-- TABEL HASIL -->
            <?php if (empty($errors) && !empty($hasil_paged)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width:60px">Kode</th>
                            <th>
                                Judul
                                <a href="<?= buildUrl(['sort' => 'judul', 'page' => 1]) ?>" class="text-white ms-1">
                                    <i class="bi bi-sort-alpha-down"></i>
                                </a>
                            </th>
                            <th>Kategori</th>
                            <th>Pengarang</th>
                            <th>Tahun</th>
                            <th>
                                Harga
                                <a href="<?= buildUrl(['sort' => 'harga_asc', 'page' => 1]) ?>" class="text-white ms-1">
                                    <i class="bi bi-sort-numeric-down"></i>
                                </a>
                            </th>
                            <th style="width:100px">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hasil_paged as $buku): ?>
                        <tr>
                            <td><small class="text-muted"><?= htmlspecialchars($buku['kode']) ?></small></td>
                            <td>
                                <!-- highlight() membungkus keyword dengan <mark> -->
                                <strong><?= highlight($buku['judul'], $keyword) ?></strong>
                                <br><small class="text-muted"><?= htmlspecialchars($buku['penerbit']) ?></small>
                            </td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($buku['kategori']) ?></span></td>
                            <td><?= highlight($buku['pengarang'], $keyword) ?></td>
                            <td><?= $buku['tahun'] ?></td>
                            <td class="fw-semibold text-success"><?= formatRupiah($buku['harga']) ?></td>
                            <td class="text-center">
                                <?php if ($buku['stok'] > 0): ?>
                                    <span class="badge badge-tersedia stok-badge">
                                        <i class="bi bi-check-circle me-1"></i>Tersedia (<?= $buku['stok'] ?>)
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-habis stok-badge">
                                        <i class="bi bi-x-circle me-1"></i>Habis
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <?php if ($total_halaman > 1): ?>
            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center mt-3">

                    <!-- Tombol Previous -->
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= buildUrl(['page' => $page - 1]) ?>">
                            <i class="bi bi-chevron-left"></i> Sebelumnya
                        </a>
                    </li>

                    <!-- Nomor Halaman -->
                    <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                    <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= buildUrl(['page' => $i]) ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>

                    <!-- Tombol Next -->
                    <li class="page-item <?= ($page >= $total_halaman) ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= buildUrl(['page' => $page + 1]) ?>">
                            Berikutnya <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>

                </ul>
                <p class="text-center text-muted small">
                    Menampilkan <?= $offset + 1 ?>–<?= min($offset + $per_page, $total_hasil) ?>
                    dari <?= $total_hasil ?> buku
                </p>
            </nav>
            <?php endif; ?>

            <?php elseif (empty($errors)): ?>
            <!-- TIDAK ADA HASIL -->
            <div class="text-center py-5">
                <i class="bi bi-search display-1 text-muted opacity-25"></i>
                <h5 class="mt-3 text-muted">Tidak ada buku yang ditemukan</h5>
                <p class="text-muted">Coba ubah kata kunci atau filter pencarian Anda.</p>
                <a href="search_advanced.php" class="btn btn-outline-primary">Reset Filter</a>
            </div>
            <?php endif; ?>

        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
