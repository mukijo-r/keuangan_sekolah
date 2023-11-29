<?php
require 'function.php';
require 'cek.php';
require 'config.php';
date_default_timezone_set('Asia/Jakarta');
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                            <li class="breadcrumb-item active">Transaksi Siswa / Transaksi Keluar</li>                            
                        </ol>                        
                        <br>
                        <?php

                        ?>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalTambahTransSiswa">
                                        Pengeluaran Baru
                                    </button><br><br>
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalKeluarCashflow">
                                        Tarik ke Cash Flow
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
                                Daftar Transaksi Keluar
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>    
                                            <th>Tahun Ajar</th>
                                            <th>Kategori</th>
                                            <th>Sub Kategori</th>
                                            <th>Periode</th>
                                            <th>Uraian</th>                                            
                                            <th>Jumlah</th>
                                            <th>Saldo</th>
                                            <th>Pencatat</th>
                                            <th>Keterangan</th>
                                            <th colspan='2'>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $dataTransaksiSiswa = mysqli_query($conn, "SELECT
                                    tks.*,
                                    ta.tahun_ajar AS tahun_ajar, 
                                    kat.nama_kategori AS kategori,
                                    subkat.nama_sub_kategori AS sub_kategori_siswa
                                    FROM transaksi_keluar_siswa tks
                                    LEFT JOIN tahun_ajar ta ON tks.id_tahun_ajar = ta.id_tahun_ajar
                                    LEFT JOIN kategori kat ON tks.id_kategori = kat.id_kategori
                                    LEFT JOIN sub_kategori_siswa subkat ON tks.id_sub_kategori = subkat.id_sub_kategori
                                    ORDER BY tks.tanggal DESC
                                    ;");

                                    $totalEntries = mysqli_num_rows($dataTransaksiSiswa);
                                    $i = $totalEntries;
                                    
                                    while($data=mysqli_fetch_array($dataTransaksiSiswa)){
                                        $idTransaksiKeluarSiswa = $data['id_tks'];                                         
                                        $tanggal =  $data['tanggal'];
                                        $tanggalTampil = date("d-m-Y", strtotime($tanggal));
                                        $tanggalKeluar = date("Y-m-d H:i", strtotime($tanggal));
                                        $idTahunAjar = $data['id_tahun_ajar']; 
                                        $tahunAjar = $data['tahun_ajar'];
                                        $idKategori = $data['id_kategori'];                                                                 
                                        $kategori = $data['kategori'];
                                        $idSubKategori = $data['id_sub_kategori'];
                                        $subKategori = $data['sub_kategori_siswa'];
                                        $bulan = $data['bulan'];
                                        $uraian = $data['uraian'];
                                        $jumlah = $data['jumlah'];
                                        $guru = $data['pencatat'];
                                        $keterangan = $data['keterangan'];                                      

                                        // Menghitung saldo
                                        $queryMasuk = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_siswa WHERE id_sub_kategori = '$idSubKategori' AND tanggal <= '$tanggal'");
                                        $queryKeluar = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_siswa WHERE id_sub_kategori = '$idSubKategori' AND tanggal <= '$tanggal'");

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
                                            <td><?=$kategori;?></td>
                                            <td><?=$subKategori;?></td>
                                            <td><?=$bulan;?></td>
                                            <td><?=$uraian;?></td>
                                            <td><?="Rp " . number_format($jumlah, 0, ',', '.');?></td>
                                            <td><?="Rp " . number_format($saldo, 0, ',', '.');?></td>
                                            <td><?=$guru;?></td>
                                            <td><?=$keterangan;?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalEditTransSiswa<?=$idTransaksiKeluarSiswa;?>">Edit</button>
                                                <input type="hidden" name="idTks" value="<?=$idTransaksiKeluarSiswa;?>">
                                                <button type="button" class="btn btn-danger" name="tblHapus" data-bs-toggle="modal" data-bs-target="#modalHapusTransSiswa<?=$idTransaksiKeluarSiswa;?>">Hapus</button> 
                                            </td>
                                        </tr>

                                        <!-- Modal Edit Transaksi Keluar Siswa -->
                                        <div class="modal fade" id="modalEditTransSiswa<?=$idTransaksiKeluarSiswa;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Transaksi Keluar </h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <!-- Modal Body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <div>
                                                                <label for="tanggal">Tanggal :</label>       
                                                                <input type="datetime-local" name="tanggal" value="<?=$tanggalKeluar?>" class="form-control">
                                                            </div> 
                                                            <div class="mb-3">
                                                                    <label for="subKategori">Sumber Kas :</label>
                                                                    <select class="form-select" name="subKategori" id="subKategori" value= "<?=$subKategori;?>" aria-label="subKategori">
                                                                        <option value="<?=$idSubKategori;?>" ><?=$subKategori;?></option>
                                                                        <?php
                                                                        // Ambil data kelas dari tabel kelas
                                                                        $querySubKategori = mysqli_query($conn, "SELECT id_sub_kategori, nama_sub_kategori FROM sub_kategori_siswa");
                                                                        while ($subKategori = mysqli_fetch_assoc($querySubKategori)) {
                                                                            echo '<option value="' . $subKategori['id_sub_kategori'] . '">' . $subKategori['nama_sub_kategori'] . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                            </div>
                                                            <!-- <div class="mb-3">
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
                                                            </div>               -->
                                                            <div class="mb-3">
                                                                <label for="uraian">Uraian :</label>                        
                                                                <input type="text" name="uraian" id="uraian" value="<?=$uraian;?>" class="form-control">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="jumlah">Jumlah Pengeluaran :</label>                        
                                                                <input type="number" name="jumlah" id="jumlah" value="<?=$jumlah;?>" class="form-control">
                                                            </div>                                                           
                                                            <div class="mb-3">
                                                            <label for="keterangan">Keterangan :</label>   
                                                                <textarea name="keterangan" class="form-control" id="keterangan" value="<?=$keterangan;?>" rows="2"></textarea>
                                                            </div>
                                                        </div>
                                                        <!-- Modal Footer -->
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                            <input type="hidden" name="idTks" value="<?=$idTransaksiKeluarSiswa;?>">
                                                            <button type="submit" class="btn btn-primary" name="editTransKeluarSiswa">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus Transaksi Masuk Siswa-->
                                        <div class="modal fade" id="modalHapusTransSiswa<?=$idTransaksiKeluarSiswa;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus Transaksi Keluar ini?</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <!-- Modal body -->
                                                
                                                <form method="post">
                                                <div class="modal-body" style="text-align: center;">
                                                    <h5>Anda yakin ingin menghapus data pembayaran : <br> "<u> <?=$uraian;?> </u> " <br>dengan nominal <br> Rp. <b><?=$jumlah;?>?</h5>
                                                    
                                                </div>
                                                <div class="text-center">
                                                    <input type="hidden" name="idTks" value="<?=$idTransaksiKeluarSiswa;?>s">
                                                    <button type="submit" class="btn btn-danger" name="hapusTransaksiKeluarSiswa">Hapus</button> 
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
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>

    </body>   

    <!-- Modal Tambah Transaksi Keluar Siswa -->
    <div class="modal fade" id="modalTambahTransSiswa">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Transaksi Keluar </h4>
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
                                <label for="subKategori">Sumber Kas :</label>
                                <select class="form-select" name="subKategori" id="subKategori" aria-label="subKategori">
                                    <option selected disabled>Pilih Sumber Kas</option>
                                    <?php
                                    // Ambil data kelas dari tabel kelas
                                    $querySubKategori = mysqli_query($conn, "SELECT id_sub_kategori, nama_sub_kategori FROM sub_kategori_siswa");
                                    while ($subKategori = mysqli_fetch_assoc($querySubKategori)) {
                                        echo '<option value="' . $subKategori['id_sub_kategori'] . '">' . $subKategori['nama_sub_kategori'] . '</option>';
                                    }
                                    ?>
                                </select>
                        </div>
                        <!-- <div class="mb-3">
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
                        </div>               -->
                        <div class="mb-3">
                            <label for="uraian">Uraian :</label>                        
                            <input type="text" name="uraian" id="uraian" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="jumlah">Jumlah Pengeluaran :</label>                        
                            <input type="number" name="jumlah" id="jumlah" class="form-control">
                        </div>                        
                        <div class="mb-3">
                        <label for="keterangan">Keterangan :</label>   
                            <textarea name="keterangan" class="form-control" id="keterangan" rows="2"></textarea>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" name="tambahTransKeluarSiswa">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tarik ke Cashflow -->
    <div class="modal fade" id="modalKeluarCashflow">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tarik ke Cash Flow </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal Body -->
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="idTahunAjar" value="<?=$idTahunAjar;?>">
                        <div class="mb-3">
                            <label for="tanggal">Tanggal :</label>       
                            <?php $tanggalSaatIni = date('Y-m-d\TH:i', time());?>
                            <input type="datetime-local" name="tanggal" value="<?=$tanggalSaatIni;?>" class="form-control">
                        </div> 
                        <div class="mb-3">
                                <label for="idSubKategoriSiswa">Pilih Transaksi :</label>
                                <select class="form-select" name="idSubKategoriSiswa" id="idSubKategoriSiswa" aria-label="idSubKategoriSiswa">
                                    <option selected disabled>Transaksi</option>
                                    <option value="5">SPP</option>
                                    <option value="8">15% Ekstra Komputer</option>
                                    <option value="9">20% Penilaian Tengah Semester</option>
                                    <option value="10">20% Penilaian Akhir Semester</option>
                                    <option value="11">20% Ujian Sekolah</option>
                                    
                                </select>
                        </div>
                        <div class="mb-3">
                            <label for="bulan">Periode/Bulan :</label><br>
                            <select class="form-select" name="bulan" id="bulan" aria-label="Bulan">
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
                            <label for="jumlahTarik">Jumlah :</label>
                            <input type="number" name="jumlahTarik" id="jumlahTarik" class="form-control">
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" name="tarik2Cashflow">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var idSubKategoriSiswaDropdown = document.getElementById('idSubKategoriSiswa');
        var bulanDropdown = document.getElementById('bulan');
        var jumlahInput = document.getElementById('jumlahTarik');

        // Tambahkan event listener ketika nilai "idSubKategoriSiswa" berubah pada Tambah Transaksi siswa
        idSubKategoriSiswaDropdown.addEventListener('change', function() {
            var selectedIdSubKategoriSiswa = idSubKategoriSiswaDropdown.value;            
        });

        // Tambahkan event listener ketika nilai "bulan" berubah
        bulanDropdown.addEventListener('change', function() {
            var selectedBulan = bulanDropdown.value;
            updateJumlahValue();
        });

        // Fungsi untuk mengambil nilai jumlah yang sesuai
        function updateJumlahValue() {
            // Dapatkan nilai terpilih dari dropdown
            var selectedidSubKategoriSiswa = idSubKategoriSiswaDropdown.value;
            var selectedBulan = bulanDropdown.value;

            // Lakukan AJAX request untuk mengambil nilai jumlah
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_jumlah_tarik.php?idSubKategoriSiswa=' + selectedidSubKategoriSiswa + '&bulan=' + selectedBulan, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var responseText = xhr.responseText.trim();
                    console.log(responseText); // Hapus spasi di awal dan akhir
                    if (/^\d+$/.test(responseText)) { // Periksa apakah respons hanya mengandung angka
                        var jumlahValue = parseInt(responseText.replace(/"/g, '')); // Hapus tanda kutip ganda
                        jumlahInput.value = jumlahValue;
                    } else {
                        console.error('Nilai jumlah tidak valid: ' + responseText);
                    }
                }
            };
            xhr.send();
        }

    });
</script>


    
</html>
