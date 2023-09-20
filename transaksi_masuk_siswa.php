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
                        <h2 class="mt-4">Transaksi Siswa</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Transaksi Siswa / Transaksi Masuk</li>                            
                        </ol>                        
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTransSiswa">
                                        Transaksi Baru
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
                                Daftar Transaksi Masuk
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>    
                                            <th>Tahun Ajar</th>
                                            <th>Kelas</th>
                                            <th>Nama Siswa</th>                                            
                                            <th>Kategori</th>
                                            <th>Sub Kategori</th>
                                            <th>Periode</th>
                                            <th>Jumlah</th>
                                            <th>Pencatat</th>
                                            <th>Keterangan</th>
                                            <th colspan='2'>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $dataTransaksiSiswa = mysqli_query($conn, "SELECT
                                    tms.*,
                                    ta.tahun_ajar AS tahun_ajar,
                                    s.nama AS nama_siswa, 
                                    g.nama_lengkap AS nama_guru, 
                                    kat.nama_kategori AS kategori,
                                    s.id_kelas AS id_kelas,
                                    subkat.nama_sub_kategori AS sub_kategori_siswa
                                    FROM transaksi_masuk_siswa tms
                                    LEFT JOIN tahun_ajar ta ON tms.id_tahun_ajar = ta.id_tahun_ajar
                                    LEFT JOIN siswa s ON tms.id_siswa = s.id_siswa
                                    LEFT JOIN guru g ON tms.id_guru = g.id_guru
                                    LEFT JOIN kategori kat ON tms.id_kategori = kat.id_kategori
                                    LEFT JOIN sub_kategori_siswa subkat ON tms.id_sub_kategori = subkat.id_kategori;");

                                    $totalEntries = mysqli_num_rows($dataTransaksiSiswa);
                                    $i = $totalEntries;
                                    
                                    while($data=mysqli_fetch_array($dataTransaksiSiswa)){                                        
                                        $tanggal =  $data['tanggal'];
                                        $tanggalTabung = date("Y-m-d", strtotime($tanggal));
                                        $tahunAjar = $data['tahun_ajar'];                                        
                                        $kelas = $data['id_kelas'];
                                        $namaSiswa = $data['nama_siswa'];                                        
                                        $kategori = $data['kategori'];
                                        $subKategori = $data['sub_kategori_siswa'];                                        
                                        $namaGuru = $data['nama_guru'];
                                        $bulan = $data['bulan'];
                                        $nominal = $data['jumlah'];
                                        $keterangan = $data['keterangan'];
                                        $idSiswa = $data['id_siswa'];
                                        
                                        ?>
                                        <tr>
                                            <td><?=$i--;?></td>
                                            <td><?=$tanggalTabung;?></td>
                                            <td><?=$tahunAjar;?></td>
                                            <td><?=$namaSiswa;?></td>
                                            <td><?=$kelas;?></td>
                                            <td><?=$kategori;?></td>
                                            <td><?=$subKategori;?></td>
                                            <td><?=$bulan;?></td>
                                            <td><?="Rp " . number_format($nominal, 0, ',', '.');?></td>
                                            <td><?=$namaGuru;?></td>
                                            <td><?=$keterangan;?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalEditTransSiswa<?=$idTbMasuk;?>">Edit</button>
                                                <input type="hidden" name="idSis" value="<?=$idSiswa;?>">
                                                <button type="button" class="btn btn-danger" name="tblHapus" data-bs-toggle="modal" data-bs-target="#modalHapusTransSiswa<?=$idTbMasuk;?>">Hapus</button> 
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
                                                            <label for="bulan">Bulan :</label>
                                                            <select class="form-select" name="bulan" id="bulanEdit" aria-label="Bulan">
                                                                <option selected><?=$bulan;?></option>
                                                                <?php
                                                                // Ambil data bulan dari tabel tabung_masuk
                                                                $queryBulan = mysqli_query($conn, "SELECT bulan FROM tabung_masuk");
                                                                while ($bulan = mysqli_fetch_assoc($queryBulan)) {
                                                                    echo '<option value="' . $bulan['bulan'] . '">' . $bulan['bulan'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
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
                                                <h5>Anda yakin ingin menghapus data menabung <u> <?=$namaSiswa;?> </u> dengan nominal Rp. <b><?=$nominal;?>?</h5>
                                                
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

    <!-- Modal Tambah Transaksi Siswa -->
<div class="modal fade" id="modalTambahTransSiswa">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Transaksi Siswa</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal Body -->
            <form method="post">
                <div class="modal-body">
                    <div>
                        <label for="tanggal">Tanggal Input :</label>       
                        <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" class="form-control">
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
                            <label for="subKategori">Kategori :</label>
                            <select class="form-select" name="kelas" id="subKategori" aria-label="subKategori">
                                <option selected disabled>Pilih Kategori</option>
                                <?php
                                // Ambil data kelas dari tabel kelas
                                $querySubKategori = mysqli_query($conn, "SELECT id_sub_kategori, nama_sub_kategori FROM sub_kategori_siswa");
                                while ($subKategori = mysqli_fetch_assoc($querySubKategori)) {
                                    echo '<option value="' . $subKategori['id_sub_kategori'] . '">' . $subKategori['nama_sub_kategori'] . '</option>';
                                }
                                ?>
                            </select>
                    </div>
                    <div class="mb-3">
                        <label for="bulan">Bulan :</label><br>
                        <select class="form-select" name="bulan" aria-label="Bulan">
                            <option selected>Pilih bulan</option>
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus">Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                            </select>
                    </div>                 
                    
                    <div class="mb-3">
                        <label for="nominal">Nominal :</label>                        
                        <input type="number" name="nominal" id="nominal" class="form-control">
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

        // Temukan elemen "kelas" dan "siswa" pada Edit Transaksi Tabungan
        var kelasDropdown2 = document.getElementById('kategori');
        var siswaDropdown2 = document.getElementById('subKategori');



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