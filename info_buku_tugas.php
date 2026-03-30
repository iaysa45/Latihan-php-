<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Buku Tugas - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Informasi Buku Perpustakaan</h1>

    <?php
    $daftar_buku = [
        [
            "judul" => "Pemrograman Web dengan PHP",
            "pengarang" => "Budi Raharjo",
            "penerbit" => "Informatika",
            "tahun_terbit" => 2023,
            "harga" => 85000,
            "stok" => 15,
            "isbn" => "978-602-1234-56-7",
            "kategori" => "Programming",
            "bahasa" => "Indonesia",
            "halaman" => 320,
            "berat" => 400
        ],
        [
            "judul" => "MySQL Database Administration",
            "pengarang" => "Andi Saputra",
            "penerbit" => "Elex Media",
            "tahun_terbit" => 2022,
            "harga" => 95000,
            "stok" => 10,
            "isbn" => "978-602-5678-11-2",
            "kategori" => "Database",
            "bahasa" => "Inggris",
            "halaman" => 450,
            "berat" => 500
        ],
        [
            "judul" => "Web Design Essentials",
            "pengarang" => "Sinta Dewi",
            "penerbit" => "Gramedia",
            "tahun_terbit" => 2021,
            "harga" => 78000,
            "stok" => 8,
            "isbn" => "978-602-9999-88-1",
            "kategori" => "Web Design",
            "bahasa" => "Indonesia",
            "halaman" => 280,
            "berat" => 350
        ],
        [
            "judul" => "Laravel Advanced",
            "pengarang" => "Rizky Pratama",
            "penerbit" => "Deepublish",
            "tahun_terbit" => 2024,
            "harga" => 120000,
            "stok" => 12,
            "isbn" => "978-602-7777-22-5",
            "kategori" => "Programming",
            "bahasa" => "Inggris",
            "halaman" => 500,
            "berat" => 550
        ]
    ];

    foreach ($daftar_buku as $buku) {
        $badge = "bg-secondary";

        if ($buku["kategori"] == "Programming") {
            $badge = "bg-primary";
        } elseif ($buku["kategori"] == "Database") {
            $badge = "bg-success";
        } elseif ($buku["kategori"] == "Web Design") {
            $badge = "bg-warning text-dark";
        }
    ?>
        <div class="card mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between">
                <h5 class="mb-0"><?php echo $buku["judul"]; ?></h5>
                <span class="badge <?php echo $badge; ?>">
                    <?php echo $buku["kategori"]; ?>
                </span>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Pengarang</th>
                        <td>: <?php echo $buku["pengarang"]; ?></td>
                    </tr>
                    <tr>
                        <th>Penerbit</th>
                        <td>: <?php echo $buku["penerbit"]; ?></td>
                    </tr>
                    <tr>
                        <th>Tahun Terbit</th>
                        <td>: <?php echo $buku["tahun_terbit"]; ?></td>
                    </tr>
                    <tr>
                        <th>ISBN</th>
                        <td>: <?php echo $buku["isbn"]; ?></td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>: Rp <?php echo number_format($buku["harga"], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>: <?php echo $buku["stok"]; ?> buku</td>
                    </tr>
                    <tr>
                        <th>Bahasa</th>
                        <td>: <?php echo $buku["bahasa"]; ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Halaman</th>
                        <td>: <?php echo $buku["halaman"]; ?> halaman</td>
                    </tr>
                    <tr>
                        <th>Berat Buku</th>
                        <td>: <?php echo $buku["berat"]; ?> gram</td>
                    </tr>
                </table>
            </div>
        </div>
    <?php } ?>
</div>
</body>
</html>