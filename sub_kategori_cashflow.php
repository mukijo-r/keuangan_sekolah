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
        <title>Halaman Cash Flow</title>
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
                        <h2 class="mt-4">Daftar Sub Kategori Cash Flow</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Cash Flow / Sub Kategori</li>                            
                        </ol>                       
                        
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahGroupCashflow">
                                        Tambah Sub Kategori
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
                                Daftar Sub Kategori Cash Flow
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Jenis</th>    
                                            <th>Group</th>
                                            <th>Sub Kategori</th>                                             
                                            <th>Keterangan</th>
                                            <th colspan='2'>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $dataSubKategori = mysqli_query($conn, "SELECT
                                    skc.*,
                                    gc.jenis AS jenis, 
                                    gc.group AS grup                                    
                                    FROM sub_kategori_cashflow skc
                                    LEFT JOIN group_cashflow gc ON skc.id_group_cashflow = gc.id_group_cashflow
                                    ORDER BY skc.id_subkategori_cashflow;");
                                    $i = 1;
                                    while($data=mysqli_fetch_array($dataSubKategori)){
                                        $idSubCashflow = $data['id_subkategori_cashflow'];
                                        $jenis = $data['jenis'];
                                        $groupCashflow = $data['grup'];
                                        $subKategori = $data['nama_sub_kategori'];
                                        $keterangan = $data['keterangan'];                                    
                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=$jenis;?></td>
                                        <td><?=$groupCashflow;?></td>
                                        <td><?=$subKategori;?></td>
                                        <td><?=$keterangan;?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalEditSubCash<?=$idSubCashflow;?>">Edit</button>
                                            <button type="button" class="btn btn-danger" name="tblHapus" data-bs-toggle="modal" data-bs-target="#modalHapusSubCash<?=$idSubCashflow;?>">Hapus</button> 
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Sub Kategori-->
                                    <div class="modal fade" id="modalEditSubCash<?=$idSubCashflow;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit Sub Kategori Cash Flow</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <!-- Modal body -->      
                                            <form method="post">
                                            <div class="modal-body">
                                                <label for="jenis">Jenis :</label> 
                                                <select class="form-select" name="jenis" aria-label="Pilih Jenis">
                                                    <option selected><?=$jenis;?></option>
                                                    <option value="Pendapatan">Pendapatan</option>
                                                    <option value="Pengeluaran">Pengeluaran</option>
                                                </select>
                                                <br>
                                                <label for="group">Nama Group :</label> 
                                                <input type="text" name="group" value="<?=$groupCashflow;?>" class="form-control" required>
                                                <br>
                                                <label for="subKategori">Sub Kategori :</label>
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
                                                <label for="keterangan">Keterangan :</label>   
                                                <textarea name="keterangan" class="form-control" id="keterangan" rows="2"><?=$keterangan;?></textarea>
                                            </div>
                                            <div class="text-center">
                                                <input type="hidden" name="idGroupCashflow" value="<?=$idGroupCashflow;?>">
                                                <button type="submit" class="btn btn-success" name="ubahGroupCashflow">Ubah</button><br> 
                                            </div>
                                            <br> 
                                        </form><br>   
                                        </div><br> 
                                    </div><br> 
                                    </div>

                                    <!-- Modal Hapus Group-->
                                    <div class="modal fade" style="text-align: center;" id="modalHapusGroup<?=$idGroupCashflow;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hapus Group?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal body -->
                                            
                                            <form method="post">
                                            <div class="modal-body">
                                                <h6>Anda yakin ingin menghapus group Cash Flow <br><u><?=$groupCashflow;?></u>?</h6>
                                                
                                            </div>
                                            <div class="text-center">
                                                <input type="hidden" name="idGroupCashflow" value="<?=$idGroupCashflow;?>">
                                                <button type="submit" class="btn btn-danger" name="hapusGroupCashflow">Hapus</button> 
                                            </div>
                                            <div></div>  
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
    <!-- Modal Tambah Group-->
    <div class="modal fade" id="modalTambahGroupCashflow">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Tambah Group Cash Flow</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <!-- Modal body -->      
            <form method="post">
            <div class="modal-body">
                <div class="mb-3">               
                    <label for="jenis">Jenis :</label> 
                    <select class="form-select" name="jenis" aria-label="Pilih Jenis">
                            <option selected>Jenis</option>
                            <option value="Pendapatan">Pendapatan</option>
                            <option value="Pengeluaran">Pengeluaran</option>
                        </select>
                </div>
                <div class="mb-3">
                <label for="group">Nama Group :</label> 
                <input type="text" name="group" placeholder="Nama Group" class="form-control" required>
                </div>
                <div class="mb-3">
                <label for="keterangan">Keterangan :</label>   
                <textarea name="keterangan" class="form-control" id="keterangan" rows="2"></textarea>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success" name="tambahGroupCashflow">Tambah</button> 
            </div>
            <br> 
        </form>   
        </div>
    </div>
    </div>

    
</html>
