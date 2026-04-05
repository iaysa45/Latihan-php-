<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Daftar Transaksi Peminjaman</h1>

    <?php
    $total_transaksi = 0;
    $total_dipinjam = 0;
    $total_dikembalikan = 0;

    // Loop statistik
    for ($i = 1; $i <= 10; $i++) {
        if ($i % 2 == 0) continue;
        if ($i == 8) break;

        $status = ($i % 3 == 0) ? "Dikembalikan" : "Dipinjam";

        $total_transaksi++;
        if ($status == "Dipinjam") {
            $total_dipinjam++;
        } else {
            $total_dikembalikan++;
        }
    }
    ?>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white p-3">
                Total: <?php echo $total_transaksi; ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark p-3">
                Dipinjam: <?php echo $total_dipinjam; ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white p-3">
                Dikembalikan: <?php echo $total_dikembalikan; ?>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Hari</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            for ($i = 1; $i <= 10; $i++) {
                if ($i % 2 == 0) continue;
                if ($i == 8) break;

                $id_transaksi = "TRX-" . str_pad($i, 4, "0", STR_PAD_LEFT);
                $nama_peminjam = "Anggota " . $i;
                $judul_buku = "Buku Teknologi Vol. " . $i;
                $tanggal_pinjam = date('Y-m-d', strtotime("-$i days"));
                $tanggal_kembali = date('Y-m-d', strtotime("+7 days", strtotime($tanggal_pinjam)));
                $status = ($i % 3 == 0) ? "Dikembalikan" : "Dipinjam";
                $hari = $i;

                $badge = ($status == "Dipinjam") ? "bg-warning text-dark" : "bg-success";
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $id_transaksi; ?></td>
                <td><?php echo $nama_peminjam; ?></td>
                <td><?php echo $judul_buku; ?></td>
                <td><?php echo $tanggal_pinjam; ?></td>
                <td><?php echo $tanggal_kembali; ?></td>
                <td><?php echo $hari; ?> hari</td>
                <td><span class="badge <?php echo $badge; ?>"><?php echo $status; ?></span></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>