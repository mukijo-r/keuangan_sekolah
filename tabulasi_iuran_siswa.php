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
        <title>Halaman Tabungan</title>
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
                        <h2 class="mt-4">Tabulasi Pembayaran Siswa</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Transaksi Siswa / Tabulasi</li>
                            <?php
                            $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar='$tahun_ajar'");
                            $rowTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
                            $idTahunAjar = $rowTahunAjar['id_tahun_ajar'];                            
                            $tahunAjar = $tahun_ajar;
                            $idSubKategori = 5;
                            $namaSubKategori = "SPP"
                            ?>                             
                        </ol>                  

                        <div class="container-fluid px-4">
                        <form method="post" class="form">  
                                <div class="row row-cols-auto">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" for="kategori">Kategori</label>
                                            </div>
                                            <select class="custom-select" name="idSubKategori" id="idSubKategori">
                                                <option value="">Pilih Sub Kategori</option>
                                                <?php
                                                $querySubKategori = mysqli_query($conn, "SELECT id_sub_kategori, nama_sub_kategori FROM sub_kategori_siswa");
                                                while ($subKategori = mysqli_fetch_assoc($querySubKategori)) {
                                                    echo '<option value="' . $subKategori['id_sub_kategori'] . '">' . $subKategori['nama_sub_kategori'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary" name="btnTampilTabulasiSiswa" id="btnTampilTabulasiSiswa">
                                            Tampilkan
                                        </button>
                                    </div>            
                                </div>
                            </form> 
                        </div>                    
                        <?php
                        // Tampilkan Tabulasi
                        if(isset($_POST['btnTampilTabulasiSiswa'])){
                            $idSubKategori = $_POST['idSubKategori'];                    
                        
                            $querySubKategori = mysqli_query($conn, "SELECT nama_sub_kategori FROM sub_kategori_siswa WHERE id_sub_kategori='$idSubKategori'");
                            $rowSubKategori = mysqli_fetch_assoc($querySubKategori);
                            $namaSubKategori = $rowSubKategori['nama_sub_kategori'];
                        }  
                        ?>

                    <div class="row" style="text-align: center; border:none">
                        <div class="col-md-3" style="text-align: right; border:none">
                        </div>
                        <div class="col-md-6">
                            <h5>Tabulasi Pembayaran <?= $namaSubKategori;?> </h5>
                            <h5>Tahun Ajar <?=$tahunAjar; ?> </h5>
                        </div>     
                    </div><br><br>

                        <?php
                        // Loop untuk membuat tabel untuk kelas 1 hingga 6
                        for ($kelas = 1; $kelas <= 6; $kelas++) {
                            ?>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-table me-1"></i>
                                    <b>Daftar Pembayaran - Kelas <?php echo $kelas; ?></b>
                                </div>
                                <div class="card-body">                                
                                    <table id="datatablesSimple1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Siswa</th>
                                                <th>Juli</th>
                                                <th>Agustus</th>
                                                <th>September</th>
                                                <th>Oktober</th>
                                                <th>November</th>
                                                <th>Desember</th>
                                                <th>Januari</th>
                                                <th>Februari</th>
                                                <th>Maret</th>
                                                <th>April</th>
                                                <th>Mei</th>
                                                <th>Juni</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1; 
                                            $saldoTabunganKelas = array();
                                            
                                            $queryDaftarPembayaran = "SELECT
                                            s.nama AS nama,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'Juli' THEN t.jumlah END), 0) AS Juli,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'Agustus' THEN t.jumlah END), 0) AS Agustus,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'September' THEN t.jumlah END), 0) AS September,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'Oktober' THEN t.jumlah END), 0) AS Oktober,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'November' THEN t.jumlah END), 0) AS November,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'Desember' THEN t.jumlah END), 0) AS Desember,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'Januari' THEN t.jumlah END), 0) AS Januari,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'Februari' THEN t.jumlah END), 0) AS Februari,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'Maret' THEN t.jumlah END), 0) AS Maret,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'April' THEN t.jumlah END), 0) AS April,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'Mei' THEN t.jumlah END), 0) AS Mei,
                                            COALESCE(SUM(CASE WHEN t.bulan = 'Juni' THEN t.jumlah END), 0) AS Juni
                                            FROM
                                                siswa s
                                            LEFT JOIN
                                                transaksi_masuk_siswa t ON s.id_siswa = t.id_siswa
                                            WHERE
                                                t.id_sub_kategori = $idSubKategori AND 
                                                t.id_tahun_ajar = $idTahunAjar AND
                                                s.id_kelas = $kelas
                                            GROUP BY
                                                s.nama;
                                                ";
                                            $result = mysqli_query($conn, $queryDaftarPembayaran);

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $namaSiswa = $row['nama'];
                                                $juli = $row['Juli'];
                                                $agustus = $row['Agustus'];
                                                $september = $row['September'];
                                                $oktober = $row['Oktober'];
                                                $november = $row['November'];
                                                $desember = $row['Desember'];
                                                $januari = $row['Januari'];
                                                $februari = $row['Februari'];
                                                $maret = $row['Maret'];
                                                $april = $row['April'];
                                                $mei = $row['Mei'];
                                                $juni = $row['Juni'];                                                
                                                ?>
                                                
                                                <tr>
                                                    <td><?=$no;?></td>
                                                    <td><?=$namaSiswa;?></td>
                                                    <td><span style="font-weight: <?= $juli != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($juli);?></td>
                                                    <td><span style="font-weight: <?= $agustus != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($agustus);?></td>
                                                    <td><span style="font-weight: <?= $september != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($september);?></td>
                                                    <td><span style="font-weight: <?= $oktober != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($oktober);?></td>
                                                    <td><span style="font-weight: <?= $november != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($november);?></td>
                                                    <td><span style="font-weight: <?= $desember != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($desember);?></td>
                                                    <td><span style="font-weight: <?= $januari != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($januari);?></td>
                                                    <td><span style="font-weight: <?= $februari != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($februari);?></td>
                                                    <td><span style="font-weight: <?= $maret != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($maret);?></td>
                                                    <td><span style="font-weight: <?= $april != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($april);?></td>
                                                    <td><span style="font-weight: <?= $mei != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($mei);?></td>
                                                    <td><span style="font-weight: <?= $juni != 0 ? 'bold' : 'normal' ?>">Rp. <?= number_format($juni);?></td>
                                                    </td>
                                                </tr>
                                                
                                                <?php
                                                $no++; // Tingkatkan nomor baris
                                            }
                                            ?>
                                        </tbody>
                                    </table>   
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <br>                        
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
</html>
