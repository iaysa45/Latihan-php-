<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Status Peminjaman Anggota</h1>

    <?php
    $nama_anggota = "Budi Santoso";
    $total_pinjaman = 2;
    $buku_terlambat = 1;
    $hari_keterlambatan = 5;

    // Hitung denda
    $denda = $buku_terlambat * $hari_keterlambatan * 1000;
    if ($denda > 50000) {
        $denda = 50000;
    }

    // Cek status pinjam
    if ($buku_terlambat > 0) {
        $status = "Tidak bisa pinjam lagi karena ada buku terlambat";
    } elseif ($total_pinjaman >= 3) {
        $status = "Tidak bisa pinjam lagi karena sudah mencapai batas";
    } else {
        $status = "Bisa pinjam buku lagi";
    }

    // Level member pakai switch
    switch (true) {
        case ($total_pinjaman >= 0 && $total_pinjaman <= 5):
            $level = "Bronze";
            break;
        case ($total_pinjaman >= 6 && $total_pinjaman <= 15):
            $level = "Silver";
            break;
        default:
            $level = "Gold";
    }
    ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            Informasi Anggota
        </div>
        <div class="card-body">
            <p><strong>Nama:</strong> <?php echo $nama_anggota; ?></p>
            <p><strong>Total Pinjaman:</strong> <?php echo $total_pinjaman; ?></p>
            <p><strong>Buku Terlambat:</strong> <?php echo $buku_terlambat; ?></p>
            <p><strong>Hari Keterlambatan:</strong> <?php echo $hari_keterlambatan; ?> hari</p>
            <p><strong>Denda:</strong> Rp <?php echo number_format($denda, 0, ',', '.'); ?></p>
            <p><strong>Level Member:</strong> 
                <span class="badge bg-warning text-dark"><?php echo $level; ?></span>
            </p>
            <div class="alert alert-danger">
                <?php echo $status; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>