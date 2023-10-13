<?php
include 'config.php';
require 'sidebar_function.php';

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
        <style>
            @media print {
                .sb-sidenav, .accordion, .sb-sidenav-dark, .sb-sidenav-menu, .nav, .nav-link, .sb-nav-link-icon, .fas, .fa-tachometer-alt, .sb-sidenav-menu-heading, .nav-link, .collapsed, .sb-nav-link-icon, .sb-sidenav-collapse-arrow, .collapse, .sb-sidenav-menu-nested, .nav, .nav-link, .sb-sidenav-footer, .small, .modal {
                    display: none !important;
                }
            }

            /* Style untuk kontainer gambar dengan border melingkar */
            .logo {
                border-radius: 100%; /* Membuat border melingkar dengan radius 50% dari lebar atau tinggi kontainer */
                overflow: hidden; /* Menghilangkan bagian gambar yang mungkin melampaui border */

 /* Mengatur konten vertikal ke tengah */
            }

            /* Style untuk gambar di dalam kontainer */
            .logo img {
                width: 40%;
                height: 40%;
                object-fit: cover; /* Mengatur bagaimana gambar mengisi kontainer */
            }

        </style>

        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
    </head>
    <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="logo" style="text-align: center; margin-right: 10px;">
                                <img src="assets/img/karmel-logo.png" width="50px" height="50px">
                                <br><br>
                                <div style="text-align: center;">
                                    <h6>Tahun Ajar</h6>
                                    <a href=# data-bs-toggle="modal" data-bs-target="#modalTahunAjar">
                                    <h5><?=$tahun_ajar;?><h5>
                                    </a>
                                </div>
                            </div>
                            <div class="sb-sidenav-menu-heading">Transaksi</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                                Transaksi Siswa
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="penetapan.php">Penetapan</a>
                                    <a class="nav-link" href="transaksi_masuk_siswa.php">Transaksi Masuk</a>
                                    <a class="nav-link" href="transaksi_keluar_siswa.php">Transaksi Keluar</a>
                                    <a class="nav-link" href="tabulasi_iuran_siswa.php">Tabulasi</a>
                                    <a class="nav-link" href="rekapan_transaksi_siswa.php">Rekapan</a>
                                    <a class="nav-link" href="laporan_transaksi_siswa.php">Laporan</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts1">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-piggy-bank"></i></div>
                                Tabungan
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="tabung.php">Log Tabung</a>
                                    <a class="nav-link" href="tabung_ambil.php">Log Ambil</a>
                                    <a class="nav-link" href="tabung_list.php">Daftar Tabungan</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts2">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-money-check-dollar"></i></div>
                                Transaksi Umum
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="transaksi_masuk_umum.php">Transaksi Masuk</a>
                                    <a class="nav-link" href="transaksi_keluar_umum.php">Transaksi Keluar</a>
                                    <a class="nav-link" href="laporan_transaksi_umum.php">Laporan</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts3" aria-expanded="false" aria-controls="collapseLayouts3">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-money-bill-transfer"></i></i></div>
                                Cashflow
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts3" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="group_cashflow.php">Group</a>
                                    <a class="nav-link" href="sub_kategori_cashflow.php">Sub Kategori</a>
                                    <a class="nav-link" href="transaksi_masuk_cashflow.php">Transaksi Masuk</a>
                                    <a class="nav-link" href="transaksi_keluar_cashflow.php">Transaksi Keluar</a>
                                    <a class="nav-link" href="laporan_transaksi_cashflow.php">Laporan</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts4" aria-expanded="false" aria-controls="collapseLayouts4">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-layer-group"></i></div>
                                Konsolidasi
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts4" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="kategori_kas.php">Kategori Kas</a>
                                    <a class="nav-link" href="pinjam_kas.php">Kas Dipinjam</a>
                                    <a class="nav-link" href="laporan_konsolidasi.php">Laporan</a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Master</div>
                            <a class="nav-link" href="guru.php" >
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                                Guru
                            </a>
                            <a class="nav-link" href="siswa.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-people-group"></i></div>
                                Siswa
                            </a>
                            <a class="nav-link" href="alumni.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-user-graduate"></i></div>
                                Alumni
                            </a>

                            <div class="sb-sidenav-menu-heading">Info</div>
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalAbout">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-info"></i></div>
                                About
                            </a>
                        </div>                            
                    </div>
                    <div style="text-align: center;" class="sb-sidenav-footer">
                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalTahunAjar">Ganti Tahun Ajar</button>
                        
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



    <!-- Modal About-->
    <div class="modal fade" id="modalAbout">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Info</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->      
                <form method="post">
                <div class="modal-body">
                    <h5>Tentang Aplikasi</h5>
                    <p>Sistem pengelolaan keuangan untuk SD Katolik Bhakti Rogojampi. </p>
                    <h5>Fitur</h5>
                    <ul>
                        <li>Mengelola data siswa, guru, dan alumni,</li>
                        <li>Mengelola iuran siswa,</li>
                        <li>Mengelola tabungan siswa,</li>
                        <li>Mengelola berbagai kategori kas,</li>
                        <li>Membuat laporan keuangan bulanan.</li>
                    </ul>
                    <h5>Versi</h5>
                    <p>1.0.0 </p>
                    <h5>Tanggal Rilis</h5>
                    <p>15 Oktober 2023 </p>
                    <h5>Pengembangan</h5>
                    <ul>
                        <li>Pengembang : Mukijo</li>
                        <li>Email : mkjjaya@gmail.com</li>
                        <li>No.tlp : 0856-4334-6785</li>
                        <li>Afiliasi : Universitas Siber Asia</li>
                    </ul>
                    <h5>Dukungan</h5>
                    <p>Aplikasi didukung oleh XAMPP versi 8.0.19 ke atas.</p>
                    <h5>Bantuan</h5>
                    <p>Jika Anda memiliki pertanyaan atau masalah, silakan hubungi melalui email atau WA.</p>
                    <p>Terima kasih telah menggunakan aplikasi ini!</p>                    
                </div>
                <br> 
            </form>   
            </div>
        </div>
    </div> 


