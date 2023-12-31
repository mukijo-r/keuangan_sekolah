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
        <title>Halaman Siswa</title>
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
                        <h2 class="mt-4">Daftar alumni</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">DATA/Alumni</li>
                        </ol>                       
                        
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
                            <br>
                            <div>
                                <!-- <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                                    Export Excel
                                </button> -->
                            </div>
                        </div> 
                        <br>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Daftar Alumni
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>    
                                            <th>Nama</th>
                                            <th>NISN</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Tampat Lahir</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Agama</th>
                                            <th>Alamat</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $datasiswa = mysqli_query($conn, "select * from siswa WHERE id_kelas='404' and status<>'aktif'");
                                    $i = 1;
                                    while($data=mysqli_fetch_array($datasiswa)){
                                        $namaSiswa = $data['nama'];
                                        $nisn = $data['nisn'];
                                        $jk = $data['jk'];
                                        $tempatLahir = $data['tempat_lahir'];
                                        $tanggalLahir = $data['tanggal_lahir'];
                                        $agama = $data['agama'];
                                        $alamat = $data['alamat']; 
                                        $ids = $data['id_siswa']; 
                                        $status = $data['status']                                      
                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=$namaSiswa;?></td>
                                        <td><?=$nisn;?></td>
                                        <td><?=$jk;?></td>
                                        <td><?=$tempatLahir;?></td>
                                        <td><?=$tanggalLahir;?></td>
                                        <td><?=$agama;?></td>
                                        <td><?=$alamat;?></td>
                                        <td><?=$status;?></td>
                                    </tr>
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

    <!-- Modal Tambah Siswa-->
    <div class="modal fade" id="#">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Siswa</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->      
                <form method="post">
                <div class="modal-body">
                    <input type="text" name="nisn" placeholder="NISN" class="form-control">
                    <br>
                    <input type="text" name="namaSiswa" placeholder="Nama Siswa" class="form-control" required>
                    <br>
                    <select class="form-select" name="kelas" aria-label="Pilih Kelas">
                        <option selected>Kelas</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                    <br>
                    <select class="form-select" name="jk" aria-label="Jenis Kelamin">
                        <option selected>Jenis Kelamin</option>
                        <option value="L">L</option>
                        <option value="P">P</option>
                    </select>
                    <br>
                    <input type="text" name="tempatLahir" placeholder="Tempat Lahir" class="form-control">
                    <br>
                    <input type="date" name="tanggalLahir" placeholder="Tanggal Lahir" class="form-control">
                    <br>
                    <select class="form-select" name="agama" aria-label="Agama">
                        <option selected>Agama</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Islam">Islam</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Khonghucu">Khonghucu</option>
                    </select>
                    <br>
                    <textarea name="alamat" rows="5" cols="45">Alamat</textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success" name="tambahSiswa">Tambah</button> 
                </div>
                <br> 
            </form>   
            </div>
        </div>
    </div>

    <!-- Modal Import Excel-->
    <div class="modal fade" id="modalImportExcel">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Import Excel</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal body -->      
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                    <div class="mb-3">
                    <label for="formFile" class="form-label">Pilih file Excel yang akan diimport</label>
                    <input type="file" name="formFile" id="formFile" class="form-control">
                    </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success" name="importExcel">Import</button> 
                    </div>
                    <br> 
                </form>
                <div class="container-fluid px-4">
                    <h6>Siapkan file Excel (.xls, .xlsx) dengan format seperti di bawah :</h6>
                    <h6>Format tanggal : yyyy-mm-dd</h6>
                    <h6>Kolom status isikan dengan "aktif" </h6><br>
                        <table class="table table-bordered">
                            <tr> 
                                <th>Nama</th>
                                <th>Kelas</th>                                                       
                                <th>Jenis Kelamin</th>
                                <th>NISN</th> 
                                <th>Tampat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>Agama</th>
                                <th>Alamat</th>
                                <th>Status</th>
                            </tr>
                        </table>
                </div>
            </div>
        </div>
    </div>

    
</div>

    
</html>
