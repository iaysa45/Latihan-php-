<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debugging - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Debugging PHP dengan var_dump() dan print_r()</h1>
        
        <?php
        // Data untuk debugging
        $judul = "Pemrograman PHP";
        $harga = 85000;
        $stok = 15;
        $tersedia = true;
        $kategori = null;
        
        $buku = [
            "judul" => "Laravel for Beginners",
            "pengarang" => "John Doe",
            "harga" => 125000,
            "stok" => 8
        ];
        ?>
        
        <!-- var_dump untuk String -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">1. var_dump() untuk String</h5>
            </div>
            <div class="card-body">
                <p><strong>Code:</strong> <code>var_dump($judul);</code></p>
                <pre><?php var_dump($judul); ?></pre>
                <p class="mb-0">
                    <small class="text-muted">
                        Menampilkan tipe data (string) dan panjang karakter (16)
                    </small>
                </p>
            </div>
        </div>
        
        <!-- var_dump untuk Integer -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">2. var_dump() untuk Integer</h5>
            </div>
            <div class="card-body">
                <p><strong>Code:</strong> <code>var_dump($harga);</code></p>
                <pre><?php var_dump($harga); ?></pre>
                <p class="mb-0">
                    <small class="text-muted">
                        Menampilkan tipe data (int) dan nilai (85000)
                    </small>
                </p>
            </div>
        </div>
        
        <!-- var_dump untuk Boolean -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">3. var_dump() untuk Boolean</h5>
            </div>
            <div class="card-body">
                <p><strong>Code:</strong> <code>var_dump($tersedia);</code></p>
                <pre><?php var_dump($tersedia); ?></pre>
                <p class="mb-0">
                    <small class="text-muted">
                        Menampilkan tipe data (bool) dan nilai (true)
                    </small>
                </p>
            </div>
        </div>
        
        <!-- var_dump untuk NULL -->
        <div class="card mb-3">
            <div class="card-header bg-warning">
                <h5 class="mb-0">4. var_dump() untuk NULL</h5>
            </div>
            <div class="card-body">
                <p><strong>Code:</strong> <code>var_dump($kategori);</code></p>
                <pre><?php var_dump($kategori); ?></pre>
                <p class="mb-0">
                    <small class="text-muted">
                        Menampilkan NULL jika variabel tidak memiliki nilai
                    </small>
                </p>
            </div>
        </div>
        
        <!-- var_dump untuk Array -->
        <div class="card mb-3">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">5. var_dump() untuk Array</h5>
            </div>
            <div class="card-body">
                <p><strong>Code:</strong> <code>var_dump($buku);</code></p>
                <pre><?php var_dump($buku); ?></pre>
                <p class="mb-0">
                    <small class="text-muted">
                        Menampilkan struktur array lengkap dengan tipe data setiap elemen
                    </small>
                </p>
            </div>
        </div>
        
        <!-- print_r untuk Array -->
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">6. print_r() untuk Array (Lebih Readable)</h5>
            </div>
            <div class="card-body">
                <p><strong>Code:</strong> <code>print_r($buku);</code></p>
                <pre><?php print_r($buku); ?></pre>
                <p class="mb-0">
                    <small class="text-muted">
                        print_r() lebih mudah dibaca untuk array, tapi tidak menampilkan tipe data
                    </small>
                </p>
            </div>
        </div>
        
        <!-- Multiple var_dump -->
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">7. Multiple var_dump()</h5>
            </div>
            <div class="card-body">
                <p><strong>Code:</strong> <code>var_dump($judul, $harga, $stok, $tersedia);</code></p>
                <pre><?php var_dump($judul, $harga, $stok, $tersedia); ?></pre>
                <p class="mb-0">
                    <small class="text-muted">
                        Bisa debug beberapa variabel sekaligus
                    </small>
                </p>
            </div>
        </div>
        
        <!-- Perbandingan gettype() -->
        <div class="card">
            <div class="card-header" style="background-color: #6610f2; color: white;">
                <h5 class="mb-0">8. Alternatif: gettype()</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Variabel</th>
                            <th>Tipe Data</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>$judul</td>
                            <td><?php echo gettype($judul); ?></td>
                            <td><?php echo $judul; ?></td>
                        </tr>
                        <tr>
                            <td>$harga</td>
                            <td><?php echo gettype($harga); ?></td>
                            <td><?php echo $harga; ?></td>
                        </tr>
                        <tr>
                            <td>$stok</td>
                            <td><?php echo gettype($stok); ?></td>
                            <td><?php echo $stok; ?></td>
                        </tr>
                        <tr>
                            <td>$tersedia</td>
                            <td><?php echo gettype($tersedia); ?></td>
                            <td><?php echo $tersedia ? 'true' : 'false'; ?></td>
                        </tr>
                        <tr>
                            <td>$kategori</td>
                            <td><?php echo gettype($kategori); ?></td>
                            <td><em>NULL</em></td>
                        </tr>
                        <tr>
                            <td>$buku</td>
                            <td><?php echo gettype($buku); ?></td>
                            <td><em>Array (4 elements)</em></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>