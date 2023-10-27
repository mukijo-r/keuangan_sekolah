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
        <title>Halaman Konsolidasi</title>
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
                        <h2 class="mt-4">Daftar Kategori Kas</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">KONSOLIDASI / Kategori Kas</li>                            
                        </ol>                       
                        
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKategoriKas">
                                        Tambah Kategori
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
                                Daftar Kategori
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>    
                                            <th>Jenis Kas</th>
                                            <th>Kelompok</th>
                                            <th>Penanggung Jawab</th>
                                            <th>Kode</th>
                                            <th>Keterangan</th>
                                            <th colspan='2'>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $dataKategori = mysqli_query($conn, "SELECT
                                    kat.*,
                                    g.nama_lengkap AS nama_guru
                                FROM
                                    kategori kat
                                JOIN
                                    guru g ON kat.id_guru = g.id_guru
                                ORDER BY kat.id_kategori ASC
                                ");
                                    $i = 1;
                                    while($data=mysqli_fetch_array($dataKategori)){
                                        $idKat = $data['id_kategori'];
                                        $kategoriKas = $data['nama_kategori'];
                                        $kelompok = $data['kelompok'];
                                        $idg = $data['id_guru'];
                                        $namaGuru = $data['nama_guru'];
                                        $kode = $data['kode'];
                                        $keterangan = $data['keterangan'];                                    
                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=$kategoriKas;?></td>
                                        <td><?=$kelompok;?></td>
                                        <td><?=$namaGuru;?></td>
                                        <td><?php if ($kode == 'ys'){echo 'Sekolah & Yayasan';} elseif ($kode == 's'){echo 'Sekolah';}?></td>
                                        <td><?=$keterangan;?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalEditKategori<?=$idKat;?>">Edit</button>
                                            <input type="hidden" name="idKat" value="<?=$idKat;?>">
                                            <button type="button" class="btn btn-danger" name="tblHapus" data-bs-toggle="modal" data-bs-target="#modalHapusKategori<?=$idKat;?>">Hapus</button> 
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Kategori-->
                                    <div class="modal fade" id="modalEditKategori<?=$idKat;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit Kategori Kas</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <!-- Modal body -->      
                                            <form method="post">
                                            <div class="modal-body">
                                                <label for="jenisKas">Nama Kas :</label> 
                                                <input type="text" name="jenisKas" value="<?=$kategoriKas;?>" class="form-control" required>
                                                <br>
                                                <label for="kelompok">Kelompok :</label> 
                                                <select class="form-select" name="kelompok" aria-label="Pilih Kelompok">
                                                        <option value="<?=$kelompok;?>"><?=$kelompok;?></option>
                                                        <option value="siswa">Siswa</option>
                                                        <option value="umum">Umum</option>
                                                    </select>
                                                <br>
                                                <label for="guru">Penannggung Jawab :</label>                     
                                                <select name="guru" class="form-select" id="guru" aria-label="Guru">>
                                                    <option value="<?=$idg;?>"><?=$namaGuru;?></option>
                                                    <?php
                                                        // Ambil data guru dari tabel guru
                                                        $queryGuru = mysqli_query($conn, "SELECT id_guru, nama_lengkap FROM guru");
                                                        while ($guru = mysqli_fetch_assoc($queryGuru)) {
                                                            echo '<option value="' . $guru['id_guru'] . '">' . $guru['nama_lengkap'] . '</option>';
                                                        }
                                                    ?>
                                                    </select>
                                                <br>
                                                <label for="kode">Kode :</label> 
                                                <select class="form-select" name="kode" aria-label="Pilih Kode">
                                                        <option value="<?=$kode;?>"><?php if ($kode == 'ys'){echo 'Sekolah & Yayasan';} elseif ($kode == 's'){echo 'Sekolah';}?></option>
                                                        <option value="ys">Sekolah & Yayasan</option>
                                                        <option value="s">Sekolah</option>
                                                    </select>
                                                <br>
                                                <label for="keterangan">Keterangan :</label>   
                                                <textarea name="keterangan" class="form-control" id="keterangan" rows="2"><?=$keterangan;?></textarea>
                                            </div>
                                            <div class="text-center">
                                                <input type="hidden" name="idk" value="<?=$idKat;?>">
                                                <button type="submit" class="btn btn-success" name="ubahKategoriKas">Ubah</button> 
                                            </div>
                                            <br> 
                                        </form>   
                                        </div>
                                    </div>
                                    </div>

                                    <!-- Modal Hapus Kategori-->
                                    <div class="modal fade" id="modalHapusKategori<?=$idKat;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hapus Kategori?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal body -->
                                            
                                            <form method="post">
                                            <div class="modal-body">
                                                <h6>Anda yakin ingin menghapus kategori kas <?=$kategoriKas;?>?</h6>
                                                
                                            </div>
                                            <div class="text-center">
                                                <input type="hidden" name="idk" value="<?=$idKat;?>">
                                                <button type="submit" class="btn btn-danger" name="hapusKategoriKas">Hapus</button> 
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
    <!-- Modal Tambah Kategori-->
    <div class="modal fade" id="modalTambahKategoriKas">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Tambah Kategori Kas</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <!-- Modal body -->      
            <form method="post">
            <div class="modal-body">
                <label for="jenisKas">Nama Kas :</label> 
                <input type="text" name="jenisKas" placeholder="Nama Kas" class="form-control" required>
                <br>
                <label for="kelompok">Kelompok :</label> 
                <select class="form-select" name="kelompok" aria-label="Pilih Kelompok">
                        <option selected>Kelompok</option>
                        <option value="siswa">Siswa</option>
                        <option value="umum">Umum</option>
                    </select>
                <br>
                <label for="guru">Penanggung Jawab :</label>                     
                <select name="guru" class="form-select" id="guru" aria-label="Guru">>
                    <option selected disabled>Pilih Guru</option>
                    <?php
                        // Ambil data guru dari tabel guru
                        $queryGuru = mysqli_query($conn, "SELECT id_guru, nama_lengkap FROM guru");
                        while ($guru = mysqli_fetch_assoc($queryGuru)) {
                            echo '<option value="' . $guru['id_guru'] . '">' . $guru['nama_lengkap'] . '</option>';
                        }
                    ?>
                    </select>
                <br>
                <label for="kode">Kode :</label> 
                <select class="form-select" name="kode" aria-label="Pilih Kode">
                        <option selected>Kode</option>
                        <option value="ys">Sekolah & Yayasan</option>
                        <option value="s">Sekolah</option>
                    </select>
                <br>
                <label for="keterangan">Keterangan :</label>   
                <textarea name="keterangan" class="form-control" id="keterangan" rows="2"></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success" name="tambahKategoriKas">Tambah</button> 
            </div>
            <br> 
        </form>   
        </div>
    </div>
    </div>

    
</html>
