<?php
require 'function.php';
require 'cek.php';
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Halaman Konsolidasi</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; ?>
        <div id="layoutSidenav">
            <?php include 'sidebar.php'; ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h2 class="mt-4">Daftar Peminjaman Kas</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">KONSOLIDASI / Kas Dipinjam</li>                            
                        </ol>                       
                        
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-1">
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalPinjamKas">
                                            Pinjam
                                        </button>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKembalikanKas">
                                            Kembalikan
                                        </button>
                                </div>
                                <div class="col-md-10">
                                    <?php
                                    if (isset($_SESSION['flash_message'])) {
                                        $message_class = isset($_SESSION['flash_message_class']) ? $_SESSION['flash_message_class'] : 'alert-success';
                                        echo '<div class="alert ' . $message_class . ' text-center">' . $_SESSION['flash_message'] . '</div>';
                                        unset($_SESSION['flash_message']); // Hapus pesan flash setelah ditampilkan
                                    }                                    
                                    ?>
                                </div>
                            </div>
                        </div>                    
                        <br>
                        <div class="container-fluid px-2">
                            <div class="row">
                                <br>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Daftar Kategori
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kategori</th>    
                                            <th>Terakhir Update</th>                                            
                                            <th>Jumlah dipinjam</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    
                                    $dataPinjam = mysqli_query($conn, "SELECT
                                    kategori.nama_kategori AS kategori,
                                    MIN(pinjam.tanggal) AS tanggal,
                                    SUM(pinjam.jumlah) AS total_jumlah,
                                    MIN(pinjam.keterangan) AS keterangan
                                FROM
                                    pinjam
                                INNER JOIN
                                    kategori ON pinjam.id_kategori = kategori.id_kategori
                                GROUP BY
                                    kategori.id_kategori;");
                                    $i = 1;
                                    while($rowPinjam=mysqli_fetch_array($dataPinjam)){
                                        $tanggal = $rowPinjam['tanggal'];
                                        $tanggalTampil = date("d-m-Y", strtotime($tanggal));
                                        $kategoriKas = $rowPinjam['kategori'];
                                        $jumlahDipinjam = $rowPinjam['total_jumlah'];
                                        $keterangan = $rowPinjam['keterangan'];                                    
                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=$kategoriKas;?></td>
                                        <td><?=$tanggalTampil ;?></td>                                        
                                        <td><?=$jumlahDipinjam;?></td>
                                        <td><?=$keterangan;?></td>
                                    </tr>                                    
                                    <?php
                                    };

                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script>
        </script>
    </body>
    <!-- Modal Pinjam Kas-->
    <div class="modal fade" id="modalPinjamKas">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Pinjam Kas</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <!-- Modal body -->      
            <form method="post">
            <div class="modal-body">
                <label for="tanggal">Tanggal :</label>
                <?php $tanggalSaatIni = date('Y-m-d', time());?> 
                <input type="date" name="tanggal" value="<?=$tanggalSaatIni;?>" class="form-control" required>
                <br>
                <label for="kategori">Kategori Kas :</label>
                    <select class="form-select" name="kategori" id="kategori" aria-label="subKategori">
                        <option selected disabled>Pilih Kategori Kas</option>
                        <?php
                        // Ambil data kelas dari tabel kelas
                        $queryKategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori");
                        while ($kategori = mysqli_fetch_assoc($queryKategori)) {
                            echo '<option value="' . $kategori['id_kategori'] . '">' . $kategori['nama_kategori'] . '</option>';
                        }
                        ?>
                    </select>
                <br>
                <label for="jumlah">Jumlah :</label>                        
                <input type="number" name="jumlah" id="jumlah" class="form-control"> 
                <br>               
                <label for="keterangan">Keterangan :</label>   
                <input type="text" name="keterangan" placeholder="Keterangan" class="form-control">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success" name="pinjamKas">Tambah</button> 
            </div>
            <br> 
        </form>   
        </div>
    </div>
    </div>

    <!-- Modal Kembalikan Kas-->
    <div class="modal fade" id="modalKembalikanKas">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Kembalikan Pinjaman Kas</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <!-- Modal body -->      
            <form method="post">
            <div class="modal-body">
                <label for="tanggal">Tanggal :</label>
                <?php $tanggalSaatIni = date('Y-m-d', time());?> 
                <input type="date" name="tanggal" value="<?=$tanggalSaatIni;?>" class="form-control" required>
                <br>
                <label for="kategori">Kategori Kas :</label>
                    <select class="form-select" name="kategori" id="kategori" aria-label="subKategori">
                        <option selected disabled>Pilih Kategori Kas</option>
                        <?php
                        // Ambil data kelas dari tabel kelas
                        $queryKategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori");
                        while ($kategori = mysqli_fetch_assoc($queryKategori)) {
                            echo '<option value="' . $kategori['id_kategori'] . '">' . $kategori['nama_kategori'] . '</option>';
                        }
                        ?>
                    </select>
                <br>
                <label for="jumlah">Jumlah :</label>                        
                <input type="number" name="jumlah" id="jumlah" class="form-control"> 
                <br>               
                <label for="keterangan">Keterangan :</label>   
                <input type="text" name="keterangan" placeholder="Keterangan" class="form-control">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success" name="kembalikanKas">Tambah</button> 
            </div>
            <br> 
        </form>   
        </div>
    </div>
    </div>
    
</html>
