<?php
setlocale(LC_TIME, 'id_ID.UTF-8');
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
        <title>Halaman Transaksi Umum</title>
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
                    <?php
                         

                    ?>

                    <div class="container-fluid px-4">
                        <h3 class="mt-4">Laporan Keuangan Kas Umum</h3>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Transaksi Umum / Laporan</li>                            
                        </ol>                        
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#">
                                        Cetak
                                    </button>
                                </div>
                                <div class="col-md-8" style="text-align: center;">
                                    <?php                                        
                                                                               
                                        $bulanLalu = strftime('%B', strtotime(date('Y-m', strtotime('-1 month')))); 

                                        if ($bulanLalu == 'January') {
                                            $bulanLalu = 'Januari';
                                        } elseif ($bulanLalu == 'February') {
                                            $bulanLalu = 'Februari';
                                        } elseif ($bulanLalu == 'March') {
                                            $bulanLalu = 'Maret';
                                        } elseif ($bulanLalu == 'April') {
                                            $bulanLalu = 'April';
                                        } elseif ($bulanLalu == 'May') {
                                            $bulanLalu = 'Mei';
                                        } elseif ($bulanLalu == 'June') {
                                            $bulanLalu = 'Juni';
                                        } elseif ($bulanLalu == 'July') {
                                            $bulanLalu = 'Juli';
                                        } elseif ($bulanLalu == 'August') {
                                            $bulanLalu = 'Agustus';
                                        } elseif ($bulanLalu == 'September') {
                                            $bulanLalu = 'September';
                                        } elseif ($bulanLalu == 'October') {
                                            $bulanLalu = 'Oktober';
                                        } elseif ($bulanLalu == 'November') {
                                            $bulanLalu = 'November';
                                        } elseif ($bulanLalu == 'December') {
                                            $bulanLalu = 'Desember';
                                        } else {
                                            $bulanLalu = '';
                                        }                                     
                                         echo $bulanLalu;

                                        
                                    ?>
                                    
                                <h5>Laporan Keuangan Bulan <?=$bulanLalu;?> </h5>


                                </div>
                            </div>
                        </div>                    
                        <br>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Daftar Transaksi Masuk
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Uraian</th>                                            
                                            <th>Jumlah</th>
                                            <th>Saldo</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $dataTransaksiUmum = mysqli_query($conn, "SELECT 
                                    tanggal, uraian, jumlah AS jumlah_masuk, 0 AS jumlah_keluar, keterangan
                                    FROM transaksi_masuk_nonsiswa WHERE id_tahun_ajar='$tahun_ajar' AND bulan='$bulanLalu' AND id_kategori=2
                                    GROUP BY uraian
                                    UNION ALL
                                    SELECT tanggal, uraian, 0 AS jumlah_masuk, jumlah AS jumlah_keluar, keterangan
                                    FROM transaksi_keluar_nonsiswa WHERE id_tahun_ajar='$tahun_ajar' AND bulan='$bulanLalu' AND id_kategori=2
                                    GROUP BY uraian
                                    ;");

                                    $totalEntries = mysqli_num_rows($dataTransaksiUmum);
                                    $i = $totalEntries;
                                    
                                    while($data=mysqli_fetch_array($dataTransaksiUmum)){                                                                                
                                        $tanggal =  $data['tanggal'];
                                        $tanggalMasuk = date("Y-m-d", strtotime($tanggal));                                         
                                        $uraian = $data['uraian'];
                                        $jumlahMasuk = $data['jumlah_masuk'];
                                        $jumlahKeluar = $data['jumlah_keluar'];                                        
                                        $keterangan = $data['keterangan'];                                      

                                        // Menghitung saldo
                                        $queryMasuk = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_nonsiswa WHERE id_kategori = '$idKategori' AND tanggal <= '$tanggal'");
                                        $queryKeluar = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_nonsiswa WHERE id_kategori = '$idKategori' AND tanggal <= '$tanggal'");

                                        $totalMasuk = 0;
                                        $totalKeluar = 0;

                                        if ($rowMasuk = mysqli_fetch_assoc($queryMasuk)) {
                                            $totalMasuk = $rowMasuk['total_masuk'];
                                        }

                                        if ($rowKeluar = mysqli_fetch_assoc($queryKeluar)) {
                                            $totalKeluar = $rowKeluar['total_keluar'];
                                        }

                                        $saldo = $totalMasuk - $totalKeluar;

                                        ?>
                                        <tr>
                                            <td><?=$i--;?></td>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$uraian;?></td>
                                            <td><?="Rp " . number_format($jumlah, 0, ',', '.');?></td>
                                            <td><?="Rp " . number_format($saldo, 0, ',', '.');?></td>
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
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
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
    
</html>
