<?php
include 'config.php';
include 'sidebar_function.php';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SIM Keuangan</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
    </head>
    <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Transaksi</div>
                            <a class="nav-link collapsed" href="transaksi.php" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Transaksi Siswa
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="siswa.php">Kategori</a>
                                    <a class="nav-link" href="guru.php">Penetapan</a>
                                    <a class="nav-link" href="guru.php">Transaksi Masuk</a>
                                    <a class="nav-link" href="guru.php">Transaksi Keluar</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="transaksi.php" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts2">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Transaksi Non Siswa
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="siswa.php">Kategori</a>
                                    <a class="nav-link" href="guru.php">Transaksi Masuk</a>
                                    <a class="nav-link" href="guru.php">Transaksi Keluar</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="transaksi.php" data-bs-toggle="collapse" data-bs-target="#collapseLayouts3" aria-expanded="false" aria-controls="collapseLayouts3">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Cashflow
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="siswa.php">Kategori</a>
                                    <a class="nav-link" href="guru.php">Sub Kategori</a>
                                    <a class="nav-link" href="guru.php">Transaksi Masuk</a>
                                    <a class="nav-link" href="guru.php">Transaksi Keluar</a>
                                </nav>
                            </div>
                            <a class="nav-link" href="transaksi.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Konsolidasi
                                <div></div>
                            </a>
                            <div class="sb-sidenav-menu-heading">Tabungan</div>
                            <a class="nav-link" href="tabung.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Tabung
                            </a>
                            <a class="nav-link" href="tabung_list.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Daftar Tabungan
                            </a>
                            <div class="sb-sidenav-menu-heading">Data</div>
                            <a class="nav-link" href="guru.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Guru
                            </a>
                            <a class="nav-link" href="siswa.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Siswa
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Tahun ajar:</div>
                        <a href=# data-bs-toggle="modal" data-bs-target="#modalTahunAjar">
                        <?=$tahun_ajar;?>
                        </a>
                    </div>
                </nav>
            </div>

            <!-- Modal Ganti Tahun ajar-->
    <div class="modal fade" id="modalTahunAjar">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Ganti Tahun Ajar</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->      
                <form method="post">
                <div class="modal-body">
                    <h6>Tambahkan Tahun Ajar baru atau pilih dari menu<h6>
                    <br>
                    <input type="text" name="newTahunAjar" placeholder="Tahun/Tahun" class="form-control">
                    <br>
                    <div class="text-center">
                    <button type="submit" class="btn btn-success" name="tambahTahunAjar">Tambah</button> 
                    </div>
                    <br>
                    <select class="form-select" name="tahunAjar" aria-label="Pilih TA">
                        <option selected>Pilih Tahun Ajar</option>
                        <?php
                            // Ambil data kelas dari tabel kelas
                            $queryTA = mysqli_query($conn, "SELECT id_tahun_ajar, tahun_ajar FROM tahun_ajar");
                            while ($ta = mysqli_fetch_assoc($queryTA)) {
                                echo '<option value="' . $ta['tahun_ajar'] . '">' . $ta['tahun_ajar'] . '</option>';
                            }
                            ?>
                    </select>
                    <br>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="ubahTahunAjar">Ubah</button> 
                </div>
                <br> 
            </form>   
            </div>
        </div>
    </div>


