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
        <title>Halaman Transaksi Cash Flow</title>
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
                        <h2 class="mt-4">Pemasukan Cash Flow</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Cash Flow / Transaksi Masuk</li>                            
                        </ol>                        
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahCashflowMasuk">
                                        Pemasukan Baru
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
                                            <th>Group</th>
                                            <th>Sub Kategori</th>
                                            <th>Bulan</th>
                                            <th>Jumlah</th>
                                            <th>Saldo Cash Flow</th>
                                            <th>Pencatat</th>
                                            <th>Keterangan</th>
                                            <th colspan='2'>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $dataCashflowMasuk = mysqli_query($conn, "SELECT
                                    tmc.*,
                                    ta.tahun_ajar AS tahun_ajar,
                                    g.nama_lengkap AS nama_guru,
                                    skc.id_group_cashflow AS id_group,
                                    skc.nama_sub_kategori AS sub_kategori,
                                    gc.groop AS groop,
                                    gc.jenis AS jenis
                                    FROM transaksi_masuk_cashflow tmc
                                    LEFT JOIN tahun_ajar ta ON tmc.id_tahun_ajar = ta.id_tahun_ajar
                                    LEFT JOIN guru g ON tmc.id_guru = g.id_guru
                                    LEFT JOIN sub_kategori_cashflow skc ON tmc.id_subkategori_cashflow = skc.id_subkategori_cashflow
                                    LEFT JOIN group_cashflow gc ON skc.id_group_cashflow = gc.id_group_cashflow
                                    ORDER BY tmc.id_tmc DESC
                                    ;");

                                    $totalEntries = mysqli_num_rows($dataCashflowMasuk);
                                    $i = $totalEntries;
                                    
                                    while($data=mysqli_fetch_array($dataCashflowMasuk)){
                                        $idCashflowMasuk = $data['id_tmc'];                                         
                                        $tanggal = $data['tanggal'];
                                        $tanggalTampil = date("d-m-Y", strtotime($tanggal));  
                                        $tanggalBayar = date("Y-m-d H:i", strtotime($tanggal));                                       
                                        $tahunAjar = $data['tahun_ajar'];
                                        $idGroup = $data['id_group'];                                                                 
                                        $group = $data['groop'];
                                        $idSubCashflow = $data['id_subkategori_cashflow'];
                                        $subCashflow = $data['sub_kategori'];
                                        $bulan = $data['bulan'];                                        
                                        $jumlah = $data['jumlah'];
                                        $idGuru = $data['id_guru'];
                                        $guru = $data['nama_guru'];
                                        $keterangan = $data['keterangan'];                                      

                                        // Menghitung saldo
                                        $queryMasuk = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_cashflow WHERE  tanggal <= '$tanggal'");
                                        $queryKeluar = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_cashflow WHERE tanggal <= '$tanggal'");

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
                                            <td><?=$tanggalTampil;?></td>
                                            <td><?=$tahunAjar;?></td>                                          
                                            <td><?=$group;?></td>
                                            <td><?=$subCashflow;?></td>
                                            <td><?=$bulan;?></td>                                            
                                            <td><?="Rp " . number_format($jumlah, 0, ',', '.');?></td>
                                            <td><?="Rp " . number_format($saldo, 0, ',', '.');?></td>
                                            <td><?=$guru;?></td>
                                            <td><?=$keterangan;?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalEditCashflowMasuk<?=$idCashflowMasuk;?>">Edit</button>        
                                                <button type="button" class="btn btn-danger" name="tblHapus" data-bs-toggle="modal" data-bs-target="#modalHapusCashflowMasuk<?=$idCashflowMasuk;?>">Hapus</button> 
                                            </td>
                                        </tr>

                                        <!-- Modal Edit Transaksi Masuk Cashflow -->
                                        <div class="modal fade" id="modalEditCashflowMasuk<?=$idCashflowMasuk;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Ubah Transaksi Masuk </h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <!-- Modal Body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="tanggal">Tanggal :</label>       
                                                                <input type="date" name="tanggal" value="<?=$tanggalBayar;?>" class="form-control">
                                                            </div> 
                                                            <div class="mb-3"> 
                                                                    <label for="groop">Group :</label>
                                                                    <select class="form-select" name="groopMasuk" id="groopMasuk" aria-label="Group">
                                                                        <option value="<?=$idGroup;?>"><?=$group;?></option>
                                                                        <?php
                                                                        // Ambil data kelas dari tabel kelas
                                                                        $queryGroop = mysqli_query($conn, "SELECT `id_group_cashflow`, `groop` FROM `group_cashflow` WHERE jenis='Pendapatan'");
                                                                        while ($groopData = mysqli_fetch_assoc($queryGroop)) {
                                                                            echo '<option value="' . $groopData['id_group_cashflow'] . '">' . $groopData['groop'] . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                    <label for="groop">Sub Kategori  :</label>
                                                                    <select class="form-select" name="subKategoriMasuk" id="subKategoriMasuk" aria-label="Group">
                                                                        <option value="<?=$idSubCashflow;?>"><?=$subCashflow;?></option>
                                                                        <!-- Opsi siswa akan diisi secara dinamis menggunakan JavaScript --> 
                                                                    </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="bulan">Periode/Bulan :</label><br>
                                                                <select class="form-select" name="bulan" aria-label="Bulan">
                                                                    <option selected><?=$bulan;?></option>
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
                                                                <label for="jumlah">Jumlah Pemasukan :</label>                        
                                                                <input type="number" name="jumlah" id="jumlah" value="<?=$jumlah;?>" class="form-control">
                                                            </div>
                                                            <div class="mb-3">   
                                                                <label for="guru">Pencatat :</label>                     
                                                                <select name="guru" class="form-select" id="guru" aria-label="Guru">>
                                                                <option value="<?=$idGuru;?>"><?=$guru;?></option>
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
                                                            <input type="hidden" name="idTmc" value="<?=$idCashflowMasuk;?>">
                                                            <button type="submit" class="btn btn-primary" name="ubahTransMasukCashflow">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus Transaksi Masuk Cashflow-->
                                        <div class="modal fade" id="modalHapusCashflowMasuk<?=$idCashflowMasuk;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus Transaksi Masuk ini?</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <!-- Modal body -->
                                                
                                                <form method="post">
                                                <div class="modal-body" style="text-align: center;">
                                                    <h5>Anda yakin ingin menghapus data pemasukan : <br> "<u> <?=$subCashflow;?> </u> " <br>dengan nominal <br> <b><?="Rp " . number_format($jumlah, 0, ',', '.');?>?</h5>
                                                    
                                                </div>
                                                <div class="text-center">
                                                    <input type="hidden" name="idTmc" value="<?=$idCashflowMasuk;?>">
                                                    <button type="submit" class="btn btn-danger" name="hapusTransaksiMasukCashflow">Hapus</button> 
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

    <!-- Modal Tambah Transaksi Keluar Cashflow -->
    <div class="modal fade" id="modalTambahCashflowMasuk">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Transaksi Masuk </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal Body -->
                <form method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal">Tanggal :</label>       
                            <?php $tanggalSaatIni = date('Y-m-d\TH:i', time());?>
                        <input type="datetime-local" name="tanggal" value="<?=$tanggalSaatIni;?>" class="form-control">
                        </div> 
                        <div class="mb-3">
                                <label for="groop">Group :</label>
                                <select class="form-select" name="groop" id="groop" aria-label="Group">
                                    <option selected disabled>Pilih Group</option>
                                    <?php
                                    // Ambil data kelas dari tabel kelas
                                    $queryGroop = mysqli_query($conn, "SELECT `id_group_cashflow`, `groop` FROM `group_cashflow` WHERE jenis='Pendapatan'");
                                    while ($groopData = mysqli_fetch_assoc($queryGroop)) {
                                        echo '<option value="' . $groopData['id_group_cashflow'] . '">' . $groopData['groop'] . '</option>';
                                    }
                                    ?>
                                </select>
                        </div>
                        <div class="mb-3">
                                <label for="groop">Sub Kategori  :</label>
                                <select class="form-select" name="subKategori" id="subKategori" aria-label="Group">
                                    <option selected disabled>Pilih Sub Kategori</option>
                                    <!-- Opsi siswa akan diisi secara dinamis menggunakan JavaScript --> 
                                </select>
                        </div>
                        <div class="mb-3">
                            <label for="bulan">Periode/Bulan :</label><br>
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
                            <label for="jumlah">Jumlah Pemasukan :</label>                        
                            <input type="number" name="jumlah" id="jumlah" class="form-control">
                        </div>
                        <div class="mb-3">   
                            <label for="guru">Pencatat :</label>                     
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
                        </div>
                        <div class="mb-3">
                        <label for="keterangan">Keterangan :</label>   
                            <textarea name="keterangan" class="form-control" id="keterangan" rows="2"></textarea>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" name="tambahTransMasukCashflow">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Temukan elemen "groop" dan "subKategori" pada Tambah Transaksi Cashflow
        var groopDropdown = document.getElementById('groop');
        var subKategoriDropdown = document.getElementById('subKategori');

        // Tambahkan event listener ketika nilai "groop" berubah pada Tambah Transaksi Cashflow
        groopDropdown.addEventListener('change', function() {
            var selectedGroop = groopDropdown.value;            

            // Gunakan AJAX untuk mengambil data subKategori berdasarkan groop
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_sub_kategori_by_group.php?groop=' + selectedGroop, true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Parse data JSON yang diterima
                    var dataSubKategori = JSON.parse(xhr.responseText);                    

                    // Bersihkan dropdown "subKategori" dan tambahkan opsi-opsi baru
                    subKategoriDropdown.innerHTML = '<option selected disabled>Pilih Sub Kategori</option>';
                    dataSubKategori.forEach(function(subKategori) {
                        subKategoriDropdown.innerHTML += '<option value="' + subKategori.id_subkategori_cashflow + '">' + subKategori.nama_sub_kategori + '</option>';
                        
                    });                
                                      
                }
            };
            xhr.send();
        });
    });
</script>
    
</html>
