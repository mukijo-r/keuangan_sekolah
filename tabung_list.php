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
                        <?php
                        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

                        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
                            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
                            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
                        } else {
                            // Kelas tidak ditemukan, tangani kesalahan di sini
                            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
                            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
                            header('location: tabung.php');
                            exit;
                        }

                        // Loop untuk membuat tabel untuk kelas 1 hingga 6
                        for ($kelas = 1; $kelas <= 6; $kelas++) {
                            ?>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-table me-1"></i>
                                    <b>Daftar Tabungan - Kelas <?php echo $kelas; ?></b>
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
                                                <th>Saldo Tabungan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1; 
                                            $saldoTabunganKelas = array();

                                            $queryDaftarTabungan = "SELECT
                                                    s.id_siswa,
                                                    s.nama AS Siswa,
                                                    COALESCE((SELECT SUM(tm1.jumlah) FROM tabung_masuk tm1 WHERE tm1.id_siswa = s.id_siswa AND tm1.bulan = 'Januari' AND tm1.id_tahun_ajar = '$idTahunAjar'), 0) AS Januari,
                                                    COALESCE((SELECT SUM(tm2.jumlah) FROM tabung_masuk tm2 WHERE tm2.id_siswa = s.id_siswa AND tm2.bulan = 'Februari' AND tm2.id_tahun_ajar = '$idTahunAjar'), 0) AS Februari,
                                                    COALESCE((SELECT SUM(tm3.jumlah) FROM tabung_masuk tm3 WHERE tm3.id_siswa = s.id_siswa AND tm3.bulan = 'Maret' AND tm3.id_tahun_ajar = '$idTahunAjar'), 0) AS Maret,
                                                    COALESCE((SELECT SUM(tm4.jumlah) FROM tabung_masuk tm4 WHERE tm4.id_siswa = s.id_siswa AND tm4.bulan = 'April' AND tm4.id_tahun_ajar = '$idTahunAjar'), 0) AS April,
                                                    COALESCE((SELECT SUM(tm5.jumlah) FROM tabung_masuk tm5 WHERE tm5.id_siswa = s.id_siswa AND tm5.bulan = 'Mei' AND tm5.id_tahun_ajar = '$idTahunAjar'), 0) AS Mei,
                                                    COALESCE((SELECT SUM(tm6.jumlah) FROM tabung_masuk tm6 WHERE tm6.id_siswa = s.id_siswa AND tm6.bulan = 'Juni' AND tm6.id_tahun_ajar = '$idTahunAjar'), 0) AS Juni,
                                                    COALESCE((SELECT SUM(tm7.jumlah) FROM tabung_masuk tm7 WHERE tm7.id_siswa = s.id_siswa AND tm7.bulan = 'Juli' AND tm7.id_tahun_ajar = '$idTahunAjar'), 0) AS Juli,
                                                    COALESCE((SELECT SUM(tm8.jumlah) FROM tabung_masuk tm8 WHERE tm8.id_siswa = s.id_siswa AND tm8.bulan = 'Agustus' AND tm8.id_tahun_ajar = '$idTahunAjar'), 0) AS Agustus,
                                                    COALESCE((SELECT SUM(tm9.jumlah) FROM tabung_masuk tm9 WHERE tm9.id_siswa = s.id_siswa AND tm9.bulan = 'September' AND tm9.id_tahun_ajar = '$idTahunAjar'), 0) AS September,
                                                    COALESCE((SELECT SUM(tm10.jumlah) FROM tabung_masuk tm10 WHERE tm10.id_siswa = s.id_siswa AND tm10.bulan = 'Oktober' AND tm10.id_tahun_ajar = '$idTahunAjar'), 0) AS Oktober,
                                                    COALESCE((SELECT SUM(tm11.jumlah) FROM tabung_masuk tm11 WHERE tm11.id_siswa = s.id_siswa AND tm11.bulan = 'November' AND tm11.id_tahun_ajar = '$idTahunAjar'), 0) AS November,
                                                    COALESCE((SELECT SUM(tm12.jumlah) FROM tabung_masuk tm12 WHERE tm12.id_siswa = s.id_siswa AND tm12.bulan = 'Desember' AND tm12.id_tahun_ajar = '$idTahunAjar'), 0) AS Desember,
                                                    COALESCE(SUM(tm.jumlah), 0) - COALESCE(saldo_ambil, 0) AS Saldo_Total
                                                FROM
                                                    siswa s
                                                LEFT JOIN
                                                    tabung_masuk tm ON s.id_siswa = tm.id_siswa AND tm.id_tahun_ajar = '$idTahunAjar'
                                                LEFT JOIN (
                                                    SELECT
                                                        id_siswa,
                                                        SUM(jumlah) AS saldo_ambil
                                                    FROM
                                                        tabung_ambil
                                                    WHERE
                                                        id_tahun_ajar = '$idTahunAjar'
                                                    GROUP BY
                                                        id_siswa
                                                ) ta ON s.id_siswa = ta.id_siswa
                                                LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
                                                WHERE k.id_kelas = '$kelas'
                                                GROUP BY
                                                    id_siswa
                                                ORDER BY
                                                    s.nama;
                                                ";
                                            $result = mysqli_query($conn, $queryDaftarTabungan); // Eksekusi query

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Ambil data dari setiap kolom
                                                $ids = $row['id_siswa']; 
                                                $namaSiswa = $row['Siswa'];
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
                                                $saldoTabunganSiswa = $row['Saldo_Total'];                                                


                                                if (!isset($saldoTabunganKelas[$kelas])) {
                                                    $saldoTabunganKelas[$kelas] = 0; // Inisialisasi total saldo untuk kelas baru
                                                }
                                                $saldoTabunganKelas[$kelas] += $saldoTabunganSiswa;

                                                ?>
                                                
                                                <tr>
                                                    <td><?=$no;?></td>
                                                    <td><?=$namaSiswa;?></td>
                                                    <td>Rp. <?=$juli;?></td>
                                                    <td>Rp. <?=$agustus;?></td>
                                                    <td>Rp. <?=$september;?></td>
                                                    <td>Rp. <?=$oktober;?></td>
                                                    <td>Rp. <?=$november;?></td>
                                                    <td>Rp. <?=$desember;?></td>
                                                    <td>Rp. <?=$januari;?></td>
                                                    <td>Rp. <?=$februari;?></td>
                                                    <td>Rp. <?=$maret;?></td>
                                                    <td>Rp. <?=$april;?></td>
                                                    <td>Rp. <?=$mei;?></td>
                                                    <td>Rp. <?=$juni;?></td>
                                                    <td>Rp. <?=$saldoTabunganSiswa;?></td>
                                                    <td>
                                                        <input type="hidden" name="idsis" value="<?=$ids;?>">
                                                        <button type="button" class="btn btn-warning" name="tblAmbil" data-bs-toggle="modal" data-bs-target="#modalAmbilTab<?=$ids;?>">Ambil</button> 
                                                    </td>
                                                    </td>
                                                </tr>
                                                <!-- Modal Ambil-->
                                                <div class="modal fade" id="modalAmbilTab<?=$ids;?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Ambil Tabungan</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        
                                                        <form method="post">
                                                        <div class="modal-body">
                                                            <h6>Ambil tabungan <?=$namaSiswa;?>?<h6>
                                                            <br>
                                                            <label for="tanggal">Tanggal Pengambilan :</label>       
                                                            <?php $tanggalSaatIni = date('Y-m-d\TH:i', time());?>
                                                            <input type="datetime-local" name="tanggal" value="<?=$tanggalSaatIni;?>" class="form-control">
                                                            <br>
                                                            <label for="jumlahTab">Jumlah Tabungan :</label>       
                                                            <input type="text" name="jumlahTab" value="<?=$saldoTabunganSiswa;?>" class="form-control" readonly>
                                                            <br>
                                                            <label for="jumlahAmbil">Jumlah Tabungan yang akan diambil :</label>
                                                            <input type="text" name="jumlahAmbil" value="<?=$saldoTabunganSiswa;?>" class="form-control">
                                                            <br>
                                                            <label for="guru">Guru Pencatat :</label>                     
                                                            <select name="guru" class="form-select" id="guru" aria-label="Guru">>
                                                            <option selected disabled>Guru Pencatat</option>
                                                                <?php
                                                                // Ambil data guru dari tabel guru
                                                                $queryGuru = mysqli_query($conn, "SELECT id_guru, nama_lengkap FROM guru");
                                                                while ($guru = mysqli_fetch_assoc($queryGuru)) {
                                                                    echo '<option value="' . $guru['id_guru'] . '">' . $guru['nama_lengkap'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                            <br>                                                             
                                                        </div>
                                                        <div class="text-center">
                                                            <input type="hidden" name="ids" value="<?=$ids;?>">
                                                            <button type="submit" class="btn btn-warning" name="ambilTab">Ambil</button> 
                                                        </div>
                                                        <br> 
                                                        </form>        
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $no++; // Tingkatkan nomor baris
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="total-saldo">
                                        <b>Total Saldo Tabungan Kelas <?=$kelas;?> : Rp. <?=$saldoTabunganKelas[$kelas];?></b>
                                    </div>     
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <br>                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                <b>Saldo Tabungan Semua Kelas</b>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple1" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%;">No.</th>
                                            <th style="width: 20%;">Kelas</th>
                                            <th style="width: 30%;">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1; 
                                        $totalSaldoSemuaKelas = 0; // Inisialisasi total saldo untuk semua kelas
                                        
                                        // Loop dari kelas 1 hingga 6
                                        for ($kelas = 1; $kelas <= 6; $kelas++) {
                                            // Query untuk mengambil saldo untuk kelas tertentu
                                            $querySaldoSiswa = "SELECT
                                            s.nama AS Siswa,
                                            COALESCE(SUM(tm.jumlah), 0) - COALESCE(saldo_ambil, 0) AS Saldo_Total
                                            FROM
                                            siswa s
                                            LEFT JOIN
                                                tabung_masuk tm ON s.id_siswa = tm.id_siswa AND tm.id_tahun_ajar = $idTahunAjar
                                            LEFT JOIN (
                                                SELECT
                                                    id_siswa,
                                                    SUM(jumlah) AS saldo_ambil
                                                    FROM
                                                        tabung_ambil
                                                    WHERE
                                                        id_tahun_ajar = $idTahunAjar
                                                    GROUP BY
                                                        id_siswa
                                                ) ta ON s.id_siswa = ta.id_siswa
                                            LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
                                                WHERE k.id_kelas = $kelas
                                                GROUP BY
                                                    s.nama
                                                ORDER BY
                                                    s.nama;";
                                            
                                            $result = mysqli_query($conn, $querySaldoSiswa); // Eksekusi query

                                            $totalSaldo = 0; // Inisialisasi variabel penjumlahan saldo

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Ambil nilai saldo dari setiap baris dan tambahkan ke variabel penjumlahan
                                                $saldo = $row['Saldo_Total'];
                                                $totalSaldo += $saldo;
                                            }
                                            ?>
                                                
                                                <tr>
                                                    <td><?=$no;?></td>
                                                    <td><?=$kelas;?></td>
                                                    <td>Rp. <?=$totalSaldo;?></td>
                                                </tr>
                                            <?php
                                             $no++;
                                             $totalSaldoSemuaKelas += $totalSaldo;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="total-saldo">
                                    <b>Total Saldo Semua Kelas: <?php echo $totalSaldoSemuaKelas; ?></b>
                                </div>
                            </div>
                        </div>

                        <?php ?>
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
