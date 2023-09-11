<?php
require 'function.php';
require 'cek.php';
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
                        <h2 class="mt-4">Daftar Tabungan</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">TABUNGAN / Daftar Tabungan</li>                            
                        </ol>
                        <!-- <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Primary Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Warning Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Success Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Area Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Bar Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div> -->
                        
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-8">
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
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Daftar Transaksi Menabung
                            </div>
                            <div class="card-body">                                
                                <table id="datatablesSimple">
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
                                            <th>Saldo Tabungan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $dataTabungan = mysqli_query($conn, "SELECT 
                                    s.id_siswa,
                                    s.nama,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 7 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS juli,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 8 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS agustus,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 9 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS september,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 10 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS oktober,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 11 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS november,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 12 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS desember,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 1 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS januari,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 2 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS februari,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 3 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS maret,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 4 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS april,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 5 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS mei,
                                    SUM(CASE WHEN MONTH(tm.tanggal) = 6 THEN tm.jumlah ELSE 0 END) - SUM(ta.jumlah) AS juni,
                                    COALESCE((SELECT SUM(tm.jumlah) - SUM(ta.jumlah) FROM tabung_masuk tm LEFT JOIN tabung_ambil ta ON tm.id_siswa = ta.id_siswa WHERE tm.id_siswa = s.id_siswa), 0) AS saldo_total
                                FROM siswa s
                                LEFT JOIN tabung_masuk tm ON s.id_siswa = tm.id_siswa
                                LEFT JOIN tabung_ambil ta ON s.id_siswa = ta.id_siswa
                                GROUP BY s.id_siswa, s.nama");
                                
                                   
                                    $i = 1;
                                    $bulanNames = array(
                                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'
                                    );
                                    
                                    while($data=mysqli_fetch_assoc($dataTabungan)){
                                            echo '<tr>';
                                            echo '<td>' . $i++ . '</td>';
                                            echo '<td>' . $data['nama'] . '</td>';                                         
                    
                                        foreach ($bulanNames as $bulanName) {
                                            echo '<td>' . "Rp " . number_format($data[strtolower($bulanName)], 0, ',', '.') . '</td>';
                                        }                                        
                                        ?>
                                            <td> <?="Rp " . number_format($data['saldo_total'], 0, ',', '.') ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalAmbilTabungan<?=$idSiswa;?>">Edit</button>
                                            </td>
                                        </tr>

                                        <!-- Modal Ambil Tabungan-->
                                    <div class="modal fade" id="modalAmbilTabungan<?=$idSiswa;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus Transaksi Menabung ini?</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal body -->
                                            
                                            <form method="post">
                                            <div class="modal-body">
                                                <h5>Anda yakin ingin menghapus data menabung <u> <?=$namaSiswa;?> </u> dengan nominal <b><?=$nominal?></b> pada tangg <?=$tanggal?>?</h5>
                                                
                                            </div>
                                            <div class="text-center">
                                                <input type="hidden" name="id_tb_masuk" value="<?=$idTbMasuk;?>">
                                                <button type="submit" class="btn btn-danger" name="hapusTransaksiMenabung">Hapus</button> 
                                            </div>
                                            <br> 
                                            </form>       
                                            </div>
                                        </div>


                                    <?php
                                    }
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Temukan elemen "kelas" dan "siswa" pada Tambah Transaksi Tabungan
        var kelasDropdown = document.getElementById('kelas');
        var siswaDropdown = document.getElementById('siswa');

        // Temukan elemen "kelas" dan "siswa" pada Edit Transaksi Tabungan
        var kelasDropdown2 = document.getElementById('kelasEdit');
        var siswaDropdown2 = document.getElementById('siswaEdit');


        // Tambahkan event listener ketika nilai "kelas" berubah pada Tambah Transaksi Tabungan
        kelasDropdown.addEventListener('change', function() {
            var selectedKelas = kelasDropdown.value;

            // Gunakan AJAX untuk mengambil data siswa berdasarkan kelas
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_siswa_by_kelas.php?kelas=' + selectedKelas, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Parse data JSON yang diterima
                    var dataSiswa = JSON.parse(xhr.responseText);

                    // Bersihkan dropdown "siswa" dan tambahkan opsi-opsi baru
                    siswaDropdown.innerHTML = '<option selected disabled>Pilih Siswa</option>';
                    dataSiswa.forEach(function(siswa) {
                        siswaDropdown.innerHTML += '<option value="' + siswa.id_siswa + '">' + siswa.nama + '</option>';
                    });
                }
            };
            xhr.send();
        });

        // Tambahkan event listener ketika nilai "kelas" berubah pada Edit Transaksi Tabungan
        kelasDropdown2.addEventListener('change', function() {
            var selectedKelas = kelasDropdown2.value;

            // Gunakan AJAX untuk mengambil data siswa berdasarkan kelas
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_siswa_by_kelas.php?kelas=' + selectedKelas, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Parse data JSON yang diterima
                    var dataSiswa = JSON.parse(xhr.responseText);

                    // Bersihkan dropdown "siswa" dan tambahkan opsi-opsi baru
                    siswaDropdown2.innerHTML = '<option selected disabled>Pilih Siswa</option>';
                    dataSiswa.forEach(function(siswa) {
                        siswaDropdown2.innerHTML += '<option value="' + siswa.id_siswa + '">' + siswa.nama + '</option>';
                    });
                }
            };
            xhr.send();
        });
    });
</script>

    
</html>
