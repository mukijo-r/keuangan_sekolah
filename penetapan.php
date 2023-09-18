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
        <title>Halaman Transaksi Siswa</title>
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
                        <h2 class="mt-4">Daftar Penetapan</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Transaksi Siswa / Penetapan</li>                            
                        </ol>                       
                        
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPenetapan">
                                        Tambah Entry
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
                                Daftar Penetapan
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kelas</th>    
                                            <th>Nama</th>
                                            <th>SPP</th>
                                            <th>Ekstrakurikuler</th>
                                            <th>Les</th>
                                            <th>PTS</th>
                                            <th>PAS</th>
                                            <th>US</th>
                                            <th colspan='2'>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $dataPenetapan = mysqli_query($conn, "SELECT pn.*, s.nama AS nama_siswa, k.nama_kelas AS kelas
                                    FROM penetapan pn
                                    LEFT JOIN siswa s ON pn.id_siswa = s.id_siswa
                                    LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
                                    ORDER BY kelas ASC");
                                    $i = 1;
                                    while($data=mysqli_fetch_array($dataPenetapan)){
                                        $idPenetapan = $data['id_penetapan'];
                                        $idsiswa = $data['id_siswa'];
                                        $spp = $data['spp'];
                                        $ekstra = $data['ekstra'];
                                        $les = $data['les'];
                                        $pts = $data['PTS'];
                                        $pas = $data['PAS'];
                                        $us = $data['US'];
                                        $namaSiswa = $data['nama_siswa'];
                                        $kelas = $data['kelas'];                                       
                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=$kelas;?></td>
                                        <td><?=$namaSiswa;?></td>
                                        <td><?=$spp;?></td>
                                        <td><?=$ekstra;?></td>
                                        <td><?=$les;?></td>
                                        <td><?=$pts;?></td>
                                        <td><?=$pas;?></td>
                                        <td><?=$us;?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalEditPenetapan<?=$idPenetapan;?>">Edit</button>
                                            <input type="hidden" name="idKat" value="<?=$idPenetapan;?>">
                                            <button type="button" class="btn btn-danger" name="tblHapus" data-bs-toggle="modal" data-bs-target="#modalHapusPenetapan<?=$idPenetapan;?>">Hapus</button> 
                                        </td>
                                    </tr>
                                    <!-- Modal Edit Guru-->
                                    <div class="modal fade" id="modalEditGuru<?=$idg;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit Guru</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal body -->
                                            
                                            <form method="post">
                                            <div class="modal-body">
                                                <input type="text" name="namaGuru" value="<?=$namaGuru;?>" class="form-control">
                                                <br>
                                                <input type="text" name="nip" value="<?=$nip;?>" class="form-control">
                                                <br>
                                                <select class="form-select" name="jk" aria-label="Jenis Kelamin">
                                                    <option selected><?=$jk;?></option>
                                                    <option value="L">L</option>
                                                    <option value="P">P</option>
                                                </select>
                                                <br>
                                                <select class="form-select" name="jabatan" aria-label="Jabatan">
                                                    <option selected><?=$jabatan;?></option>
                                                    <option value="Guru">Guru</option>
                                                    <option value="Bendahara Sekolah">Bendahara Sekolah</option>
                                                    <option value="Kepala Sekolah">Kepala Sekolah</option>
                                                </select>
                                                <br>
                                                <input type="hidden" name="idg" value="<?=$idg;?>">
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-warning" name="editGuru">Edit</button> 
                                            </div>
                                            <br> 
                                            </form>        
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Hapus Guru-->
                                    <div class="modal fade" id="modalHapusGuru<?=$idg;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus Guru?</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal body -->
                                            
                                            <form method="post">
                                            <div class="modal-body">
                                                <h4>Anda yakin ingin menghapus data guru <?=$namaGuru;?>?</h4>
                                                
                                            </div>
                                            <div class="text-center">
                                                <input type="hidden" name="idg" value="<?=$idg;?>">
                                                <button type="submit" class="btn btn-danger" name="hapusGuru">Hapus</button> 
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
    
        <!-- Modal Tambah Item Penetapan -->
    <div class="modal fade" id="modalTambahPenetapan">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Penetapan Pembayaran</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal Body -->
                <form method="post">
                    <div class="modal-body">
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
                            <label for="spp">SPP :</label>                        
                            <input type="number" name="spp" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="ekstra">Ekstrakurikuler :</label>                        
                            <input type="number" name="ekstra" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="les">Les :</label>                        
                            <input type="number" name="les" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="pts">PTS :</label>                        
                            <input type="number" name="pts" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="pas">PAS :</label>                        
                            <input type="number" name="pas" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="us">US :</label>                        
                            <input type="number" name="us" class="form-control">
                        </div>
                        

                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" name="tambahPenetapan">Simpan</button>
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
