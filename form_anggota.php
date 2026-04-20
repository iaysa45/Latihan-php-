<?php
// =============================================
// TUGAS 1: Form Registrasi Anggota Perpustakaan
// =============================================
// Konsep: Form Handling dengan PHP
// - $_POST: Mengambil data dari form method="post"
// - Validasi server-side: Validasi dilakukan di PHP
// - Sticky form: Nilai form tetap ada setelah submit (pakai htmlspecialchars)
// - htmlspecialchars(): Mencegah XSS attack
// =============================================

$errors = [];
$success = false;
$data = [];

// Cek apakah form sudah di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---- Ambil & sanitasi input dari $_POST ----
    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telepon  = trim($_POST['telepon'] ?? '');
    $alamat   = trim($_POST['alamat'] ?? '');
    $gender   = $_POST['gender'] ?? '';
    $tgl_lahir = $_POST['tgl_lahir'] ?? '';
    $pekerjaan = $_POST['pekerjaan'] ?? '';

    // ---- VALIDASI ----

    // 1. Nama Lengkap: required, min 3 karakter
    if (empty($nama)) {
        $errors['nama'] = 'Nama lengkap wajib diisi.';
    } elseif (strlen($nama) < 3) {
        $errors['nama'] = 'Nama minimal 3 karakter.';
    }

    // 2. Email: required, format valid
    if (empty($email)) {
        $errors['email'] = 'Email wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // filter_var dengan FILTER_VALIDATE_EMAIL = cara resmi validasi email PHP
        $errors['email'] = 'Format email tidak valid.';
    }

    // 3. Telepon: required, format 08xxxxxxxxxx (10-13 digit)
    if (empty($telepon)) {
        $errors['telepon'] = 'Nomor telepon wajib diisi.';
    } elseif (!preg_match('/^08[0-9]{8,11}$/', $telepon)) {
        // preg_match = validasi dengan Regular Expression (Regex)
        // ^08       = harus dimulai dengan "08"
        // [0-9]{8,11} = diikuti 8-11 digit angka
        // $         = harus berakhir di sini (tidak ada karakter lain)
        $errors['telepon'] = 'Format telepon tidak valid. Gunakan format 08xxxxxxxxxx (10-13 digit).';
    }

    // 4. Alamat: required, min 10 karakter
    if (empty($alamat)) {
        $errors['alamat'] = 'Alamat wajib diisi.';
    } elseif (strlen($alamat) < 10) {
        $errors['alamat'] = 'Alamat minimal 10 karakter.';
    }

    // 5. Jenis Kelamin: required
    if (empty($gender)) {
        $errors['gender'] = 'Jenis kelamin wajib dipilih.';
    } elseif (!in_array($gender, ['Laki-laki', 'Perempuan'])) {
        $errors['gender'] = 'Pilihan jenis kelamin tidak valid.';
    }

    // 6. Tanggal Lahir: required, umur minimal 10 tahun
    if (empty($tgl_lahir)) {
        $errors['tgl_lahir'] = 'Tanggal lahir wajib diisi.';
    } else {
        // Hitung umur dari tanggal lahir
        $lahir = new DateTime($tgl_lahir);       // Buat objek tanggal lahir
        $sekarang = new DateTime();               // Tanggal hari ini
        $umur = $sekarang->diff($lahir)->y;       // ->diff() = selisih, ->y = dalam tahun

        if ($umur < 10) {
            $errors['tgl_lahir'] = 'Anggota harus berumur minimal 10 tahun.';
        }
    }

    // 7. Pekerjaan: required
    $pekerjaan_valid = ['Pelajar', 'Mahasiswa', 'Pegawai', 'Lainnya'];
    if (empty($pekerjaan)) {
        $errors['pekerjaan'] = 'Pekerjaan wajib dipilih.';
    } elseif (!in_array($pekerjaan, $pekerjaan_valid)) {
        $errors['pekerjaan'] = 'Pilihan pekerjaan tidak valid.';
    }

    // ---- Jika tidak ada error, set success ----
    if (empty($errors)) {
        $success = true;
        // Simpan data untuk ditampilkan di success card
        $data = [
            'nama'      => $nama,
            'email'     => $email,
            'telepon'   => $telepon,
            'alamat'    => $alamat,
            'gender'    => $gender,
            'tgl_lahir' => date('d F Y', strtotime($tgl_lahir)),
            'pekerjaan' => $pekerjaan,
            'umur'      => $umur,
        ];
    }
}

// Helper function: tampilkan kelas Bootstrap berdasarkan ada/tidaknya error
// is-invalid = input merah (ada error), is-valid = input hijau (valid)
function fieldClass($field, $errors, $posted) {
    if (!$posted) return '';
    return isset($errors[$field]) ? 'is-invalid' : 'is-valid';
}

$posted = $_SERVER['REQUEST_METHOD'] === 'POST';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f0f4f8; }
        .card { border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 12px; }
        .card-header { background: linear-gradient(135deg, #1e3a5f, #2d6a9f); border-radius: 12px 12px 0 0 !important; }
        .btn-primary { background: #1e3a5f; border-color: #1e3a5f; }
        .btn-primary:hover { background: #2d6a9f; border-color: #2d6a9f; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <!-- SUCCESS CARD: Tampil hanya jika form valid -->
            <?php if ($success): ?>
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i>Registrasi Berhasil!</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Data anggota berhasil disimpan:</p>
                    <table class="table table-bordered table-striped">
                        <tr><th style="width:35%">Nama Lengkap</th><td><?= htmlspecialchars($data['nama']) ?></td></tr>
                        <tr><th>Email</th><td><?= htmlspecialchars($data['email']) ?></td></tr>
                        <tr><th>Telepon</th><td><?= htmlspecialchars($data['telepon']) ?></td></tr>
                        <tr><th>Alamat</th><td><?= htmlspecialchars($data['alamat']) ?></td></tr>
                        <tr><th>Jenis Kelamin</th><td><?= htmlspecialchars($data['gender']) ?></td></tr>
                        <tr><th>Tanggal Lahir</th><td><?= htmlspecialchars($data['tgl_lahir']) ?> (<?= $data['umur'] ?> tahun)</td></tr>
                        <tr><th>Pekerjaan</th><td><?= htmlspecialchars($data['pekerjaan']) ?></td></tr>
                    </table>
                    <a href="form_anggota.php" class="btn btn-outline-success">
                        <i class="bi bi-plus-circle me-1"></i>Daftarkan Anggota Lain
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- FORM REGISTRASI -->
            <div class="card">
                <div class="card-header text-white py-3">
                    <h4 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Registrasi Anggota Perpustakaan</h4>
                    <small class="opacity-75">Isi semua data dengan benar</small>
                </div>
                <div class="card-body p-4">

                    <!--
                        method="post" → Data dikirim via $_POST (tidak terlihat di URL)
                        action=""     → Submit ke halaman yang sama (self-processing)
                    -->
                    <form method="post" action="" novalidate>

                        <!-- NAMA LENGKAP -->
                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control <?= fieldClass('nama', $errors, $posted) ?>"
                                id="nama"
                                name="nama"
                                placeholder="Masukkan nama lengkap"
                                value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                            >
                            <!-- htmlspecialchars() di value = sticky form + cegah XSS -->
                            <?php if (isset($errors['nama'])): ?>
                                <div class="invalid-feedback"><?= $errors['nama'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                class="form-control <?= fieldClass('email', $errors, $posted) ?>"
                                id="email"
                                name="email"
                                placeholder="contoh@email.com"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            >
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- TELEPON -->
                        <div class="mb-3">
                            <label for="telepon" class="form-label fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control <?= fieldClass('telepon', $errors, $posted) ?>"
                                id="telepon"
                                name="telepon"
                                placeholder="08xxxxxxxxxx"
                                value="<?= htmlspecialchars($_POST['telepon'] ?? '') ?>"
                            >
                            <div class="form-text">Format: 08xxxxxxxxxx (10-13 digit)</div>
                            <?php if (isset($errors['telepon'])): ?>
                                <div class="invalid-feedback"><?= $errors['telepon'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- ALAMAT -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                            <textarea
                                class="form-control <?= fieldClass('alamat', $errors, $posted) ?>"
                                id="alamat"
                                name="alamat"
                                rows="3"
                                placeholder="Masukkan alamat lengkap (min 10 karakter)"
                            ><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                            <?php if (isset($errors['alamat'])): ?>
                                <div class="invalid-feedback"><?= $errors['alamat'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- JENIS KELAMIN (Radio Button) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="<?= ($posted && isset($errors['gender'])) ? 'is-invalid' : '' ?>">
                                <?php
                                // Cek apakah radio sebelumnya dipilih (sticky form untuk radio)
                                $selectedGender = $_POST['gender'] ?? '';
                                foreach (['Laki-laki', 'Perempuan'] as $g):
                                    $checked = ($selectedGender === $g) ? 'checked' : '';
                                ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_<?= $g ?>" value="<?= $g ?>" <?= $checked ?>>
                                    <label class="form-check-label" for="gender_<?= $g ?>"><?= $g ?></label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (isset($errors['gender'])): ?>
                                <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i><?= $errors['gender'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- TANGGAL LAHIR -->
                        <div class="mb-3">
                            <label for="tgl_lahir" class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input
                                type="date"
                                class="form-control <?= fieldClass('tgl_lahir', $errors, $posted) ?>"
                                id="tgl_lahir"
                                name="tgl_lahir"
                                value="<?= htmlspecialchars($_POST['tgl_lahir'] ?? '') ?>"
                                max="<?= date('Y-m-d', strtotime('-10 years')) ?>"
                            >
                            <div class="form-text">Anggota harus berumur minimal 10 tahun</div>
                            <?php if (isset($errors['tgl_lahir'])): ?>
                                <div class="invalid-feedback"><?= $errors['tgl_lahir'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- PEKERJAAN (Select Dropdown) -->
                        <div class="mb-4">
                            <label for="pekerjaan" class="form-label fw-semibold">Pekerjaan <span class="text-danger">*</span></label>
                            <select class="form-select <?= fieldClass('pekerjaan', $errors, $posted) ?>" id="pekerjaan" name="pekerjaan">
                                <option value="">-- Pilih Pekerjaan --</option>
                                <?php
                                $pekerjaan_list = ['Pelajar', 'Mahasiswa', 'Pegawai', 'Lainnya'];
                                $selectedPekerjaan = $_POST['pekerjaan'] ?? '';
                                foreach ($pekerjaan_list as $p):
                                    $selected = ($selectedPekerjaan === $p) ? 'selected' : '';
                                ?>
                                <option value="<?= $p ?>" <?= $selected ?>><?= $p ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['pekerjaan'])): ?>
                                <div class="invalid-feedback"><?= $errors['pekerjaan'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- TOMBOL SUBMIT -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send-fill me-2"></i>Daftarkan Anggota
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Form
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
