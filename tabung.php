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
                        <h2 class="mt-4">Tabungan</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">TABUNGAN/Tabung</li>                            
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
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTransTabung">
                                        Menabung
                                    </button>
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
                                            <th>Tanggal</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th>Jumlah Ditabung</th>
                                            <th>Guru Pencatat</th>
                                            <th>Saldo</th>
                                            <th>Keterangan</th>
                                            <th colspan='2'>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $dataTabungan = mysqli_query($conn, "SELECT tm.*, s.nama AS nama_siswa, g.nama_lengkap AS nama_guru, k.nama_kelas AS kelas
                                    FROM tabung_masuk tm
                                    LEFT JOIN siswa s ON tm.id_siswa = s.id_siswa
                                    LEFT JOIN guru g ON tm.id_guru = g.id_guru
                                    LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
                                    ORDER BY tm.id_tb_masuk DESC");

                                    $totalEntries = mysqli_num_rows($dataTabungan);
                                    $i = $totalEntries;
                                    
                                    while($data=mysqli_fetch_array($dataTabungan)){
                                        $idTbMasuk = $data['id_tb_masuk'];
                                        $tanggal = $data['tanggal'];
                                        $namaSiswa = $data['nama_siswa'];
                                        $kelas = $data['kelas'];
                                        $nominal = $data['jumlah'];
                                        $namaGuru = $data['nama_guru'];
                                        $keterangan = $data['keterangan'];
                                        $idSiswa = $data['id_siswa'];

                                        // Menghitung saldo
                                        $querySaldo = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM tabung_masuk WHERE id_siswa = $idSiswa AND tanggal <= '$tanggal'");
                                        $querySaldoAmbil = mysqli_query($conn, "SELECT SUM(jumlah) AS total_ambil FROM tabung_ambil WHERE id_siswa = $idSiswa AND tanggal <= '$tanggal'");

                                        $saldo_masuk = 0;
                                        $saldo_ambil = 0;

                                        if ($rowSaldo = mysqli_fetch_assoc($querySaldo)) {
                                            $saldo_masuk = $rowSaldo['total_masuk'];
                                        }

                                        if ($rowSaldoAmbil = mysqli_fetch_assoc($querySaldoAmbil)) {
                                            $saldo_ambil = $rowSaldoAmbil['total_ambil'];
                                        }

                                        $saldo = $saldo_masuk - $saldo_ambil;
                                        ?>
                                        <tr>
                                            <td><?=$i--;?></td>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$namaSiswa;?></td>
                                            <td><?=$kelas;?></td>
                                            <td><?="Rp " . number_format($nominal, 0, ',', '.');?></td>
                                            <td><?=$namaGuru;?></td>
                                            <td><?="Rp " . number_format($saldo, 0, ',', '.');?></td>
                                            <td><?=$keterangan;?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalEditTransTabung<?=$idTbMasuk;?>">Edit</button>
                                            <input type="hidden" name="idgis" value="<?=$idSiswa;?>">
                                            <button type="button" class="btn btn-danger" name="tblHapus" data-bs-toggle="modal" data-bs-target="#modalHapusTransTabung<?=$idTbMasuk;?>">Hapus</button> 
                                        </td>
                                    </tr>


                                        <!-- Modal edit Transaksi Tabungan -->
                                    <div class="modal fade" id="modalEditTransTabung<?=$idTbMasuk;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit Transaksi Tabungan</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <!-- Modal Body -->
                                                <form method="post">
                                                    <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="tanggal">Tanggal :</label><br>
                                                        <input type="date" name="tanggal" class="form-control" value="<?=$tanggal;?>">
                                                    </div>

                                                        <div class="mb-3">
                                                            <label for="kelas">Kelas :</label>
                                                            <select class="form-select" name="kelas" id="kelasEdit" aria-label="Kelas">
                                                                <option selected><?=$kelas;?></option>
                                                                <?php
                                                                // Ambil data kelas dari tabel kelas
                                                                $queryKelas = mysqli_query($conn, "SELECT id_kelas, nama_kelas FROM kelas");
                                                                while ($kelas = mysqli_fetch_assoc($queryKelas)) {
                                                                    echo '<option value="' . $kelas['id_kelas'] . '">' . $kelas['nama_kelas'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="siswa">Siswa :</label>
                                                            <select name="id_siswa" class="form-select" id="siswaEdit" aria-label="Siswa">
                                                                <option selected><?=$namaSiswa;?></option>
                                                                <!-- Opsi siswa akan diisi secara dinamis menggunakan JavaScript -->
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nominal">Nominal :</label>                        
                                                            <input type="number" name="nominal" value="<?=$nominal;?>" class="form-control">
                                                        </div>
                                                        <div class="mb-3">   
                                                            <label for="guru">Penerima :</label>                     
                                                            <select name="guru" class="form-select" id="guruEdit" aria-label="Guru">>
                                                            <option selected><?=$namaGuru;?></option>
                                                                <?php
                                                                // Ambil data guru dari tabel guru
                                                                $queryGuru = mysqli_query($conn, "SELECT id_guru, nama_lengkap FROM guru");
                                                                while ($guru = mysqli_fetch_assoc($queryGuru)) {
                                                                    echo '<option value="' . $guru['id_guru'] . '">' . $guru['nama_lengkap'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                        <label for="keterangan">Keterangan :</label>   
                                                            <textarea name="keterangan" class="form-control" id="keterangan" rows="2"><?=$keterangan;?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        <input type="hidden" name="id_tb_masuk" value="<?=$idTbMasuk;?>">
                                                        <button type="submit" class="btn btn-primary" name="editTransTabung">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Hapus Transaksi Tabungan-->
                                    <div class="modal fade" id="modalHapusTransTabung<?=$idTbMasuk;?>">
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

    <!-- Modal Tambah Transaksi Tabungan -->
<div class="modal fade" id="modalTambahTransTabung">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Transaksi Tabungan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal Body -->
            <form method="post">
                <div class="modal-body">
                <div class="mb-3">
                    <label for="tanggal">Tanggal :</label><br>
                    <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                </div>

                    <div class="mb-3">
                        <label for="kelas">Kelas :</label>
                        <select class="form-select" name="kelas" id="kelas" aria-label="Kelas">
                            <option selected disabled>Pilih Kelas</option>
                            <?php
                            // Ambil data kelas dari tabel kelas
                            $queryKelas = mysqli_query($conn, "SELECT id_kelas, nama_kelas FROM kelas");
                            while ($kelas = mysqli_fetch_assoc($queryKelas)) {
                                echo '<option value="' . $kelas['id_kelas'] . '">' . $kelas['nama_kelas'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="siswa">Siswa :</label>
                        <select name="id_siswa" class="form-select" id="siswa" aria-label="Siswa">
                            <option selected disabled>Pilih Kelas Terlebih Dahulu</option>
                            <!-- Opsi siswa akan diisi secara dinamis menggunakan JavaScript -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nominal">Nominal :</label>                        
                        <input type="number" name="nominal" class="form-control">
                    </div>
                    <div class="mb-3">   
                        <label for="guru">Penerima :</label>                     
                        <select name="guru" class="form-select" id="guru" aria-label="Guru">>
                        <option selected disabled>Guru Penerima</option>
                            <?php
                            // Ambil data guru dari tabel guru
                            $queryGuru = mysqli_query($conn, "SELECT id_guru, nama_lengkap FROM guru");
                            while ($guru = mysqli_fetch_assoc($queryGuru)) {
                                echo '<option value="' . $guru['id_guru'] . '">' . $guru['nama_lengkap'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                    <label for="keterangan">Keterangan :</label>   
                        <textarea name="keterangan" class="form-control" id="keterangan" rows="2"></textarea>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="tambahTransTabung">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
