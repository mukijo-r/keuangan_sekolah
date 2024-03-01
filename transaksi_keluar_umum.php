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
        <title>Halaman Transaksi Umum</title>
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
                        <h2 class="mt-4">Pengeluaran Umum</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Transaksi Umum / Transaksi Keluar</li>                            
                        </ol>                        
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalTambahTransUmum">
                                        Pengeluaran Baru
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
                        <?php 
                        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar='$tahun_ajar'");
                        while ($rowTahunAjar = mysqli_fetch_assoc($queryTahunAjar)) {                            
                            $idTahunAjar = $rowTahunAjar['id_tahun_ajar'];
                        }
                        ?>
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
                                            <th>Sumber Kas</th>                                            
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
                                    $dataTransaksiUmum = mysqli_query($conn, "SELECT
                                    tkn.*,
                                    ta.tahun_ajar AS tahun_ajar,
                                    kat.nama_kategori AS kategori                                    
                                    FROM transaksi_keluar_nonsiswa tkn
                                    LEFT JOIN tahun_ajar ta ON tkn.id_tahun_ajar = ta.id_tahun_ajar
                                    LEFT JOIN kategori kat ON tkn.id_kategori = kat.id_kategori
                                    WHERE tkn.id_tahun_ajar = '$idTahunAjar'                                    
                                    ORDER BY tkn.tanggal DESC;
                                    ;");

                                    $totalEntries = mysqli_num_rows($dataTransaksiUmum);
                                    $i = $totalEntries;
                                    
                                    while($data=mysqli_fetch_array($dataTransaksiUmum)){
                                        $idTransaksiKeluarUmum = $data['id_tkn'];                                         
                                        $tanggal =  $data['tanggal'];
                                        $tanggalTampil = date("d-m-Y", strtotime($tanggal));
                                        $tanggalBayar = date("Y-m-d H:i", strtotime($tanggal));                                         
                                        $tahunAjar = $data['tahun_ajar'];
                                        $idKategori = $data['id_kategori'];                                                                 
                                        $kategori = $data['kategori'];
                                        $bulan = $data['bulan'];
                                        $uraian = $data['uraian'];
                                        $jumlah = $data['jumlah'];
                                        $guru = $data['pencatat'];
                                        $keterangan = $data['keterangan'];                                      

                                        // Menghitung saldo
                                        $queryMasuk = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_nonsiswa WHERE id_kategori = '$idKategori' AND tanggal <= '$tanggal'");
                                        $queryKeluar = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_nonsiswa WHERE id_kategori = '$idKategori' AND tanggal <= '$tanggal'");

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
                                            <td><?=$bulan;?></td>
                                            <td><?=$uraian;?></td>
                                            <td><?="Rp " . number_format($jumlah, 0, ',', '.');?></td>
                                            <td><?="Rp " . number_format($saldo, 0, ',', '.');?></td>
                                            <td><?=$guru;?></td>
                                            <td><?=$keterangan;?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalEditTransUmum<?=$idTransaksiKeluarUmum;?>">Edit</button>        
                                                <button type="button" class="btn btn-danger" name="tblHapus" data-bs-toggle="modal" data-bs-target="#modalHapusTransUmum<?=$idTransaksiKeluarUmum;?>">Hapus</button> 
                                            </td>
                                        </tr>

                                        <!-- Modal Edit Transaksi Keluar Umum -->
                                        <div class="modal fade" id="modalEditTransUmum<?=$idTransaksiKeluarUmum;?>">
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
                                                            <div class="mb-3">
                                                                <label for="tanggal">Tanggal :</label>       
                                                                <input type="datetime-local" name="tanggal" value="<?=$tanggalBayar?>" class="form-control">
                                                            </div> 
                                                            <div class="mb-3">
                                                                <label for="kategori">Sumber Kas :</label>
                                                                <select class="form-select" name="kategori" id="kategori" aria-label="subKategori">
                                                                    <option value="<?=$idKategori;?>"><?=$kategori;?></option>
                                                                    <?php
                                                                    // Ambil data kelas dari tabel kelas
                                                                    $queryKategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori WHERE kelompok='umum' AND id_kategori<>1");
                                                                    while ($kategori = mysqli_fetch_assoc($queryKategori)) {
                                                                        echo '<option value="' . $kategori['id_kategori'] . '">' . $kategori['nama_kategori'] . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>                                                                         
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
                                                            <input type="hidden" name="idTkn" value="<?=$idTransaksiKeluarUmum;?>">
                                                            <button type="submit" class="btn btn-primary" name="editTransKeluarUmum">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus Transaksi Masuk Siswa-->
                                        <div class="modal fade" id="modalHapusTransUmum<?=$idTransaksiKeluarUmum;?>">
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
                                                    <h5>Anda yakin ingin menghapus data pengeluaran : <br> "<u> <?=$uraian;?> </u> " <br>dengan nominal <br> Rp. <b><?=$jumlah;?>?</h5>
                                                    
                                                </div>
                                                <div class="text-center">
                                                    <input type="hidden" name="idTkn" value="<?=$idTransaksiKeluarUmum;?>">
                                                    <button type="submit" class="btn btn-danger" name="hapusTransaksiKeluarUmum">Hapus</button> 
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
        <script>
        </script>
    </body>   

    <!-- Modal Tambah Transaksi Keluar Keluar -->
    <div class="modal fade" id="modalTambahTransUmum">
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
                        <div  class="mb-3">
                            <label for="tanggal">Tanggal :</label>       
                            <?php $tanggalSaatIni = date('Y-m-d\TH:i', time());?>
                            <input type="datetime-local" name="tanggal" value="<?=$tanggalSaatIni;?>" class="form-control">
                        </div> 
                        <div class="mb-3">
                                <label for="kategori">Sumber Kas :</label>
                                <select class="form-select" name="kategori" id="kategori" aria-label="subKategori">
                                    <option selected disabled>Pilih Sumber Kas</option>
                                    <?php
                                    // Ambil data kelas dari tabel kelas
                                    $queryKategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori WHERE kelompok='umum' AND id_kategori<>1");
                                    while ($kategori = mysqli_fetch_assoc($queryKategori)) {
                                        echo '<option value="' . $kategori['id_kategori'] . '">' . $kategori['nama_kategori'] . '</option>';
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
                        <button type="submit" class="btn btn-primary" name="tambahTransKeluarUmum">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</html>
