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
                        <h2 class="mt-4">Daftar Siswa</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">DATA/Siswa</li>
                        </ol>
                        <div class="row">
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
                        </div>
                        <br>
                        <div class="container-fluid px-4">                          
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                                    Tambah Siswa
                            </button>
                        </div>
                        <br>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Daftar Siswa
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>    
                                            <th>Nama</th>
                                            <th>NISN</th>
                                            <th>Kelas</th>
                                            <th>Tampat Lahir</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Agama</th>
                                            <th>Alamat</th>
                                            <th colspan='2'>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $datasiswa = mysqli_query($conn, "select * from siswa");
                                    $i = 1;
                                    while($data=mysqli_fetch_array($datasiswa)){
                                        $namaSiswa = $data['nama'];
                                        $nisn = $data['nisn'];
                                        $kelas = $data['id_kelas'];
                                        $tempatLahir = $data['tempat_lahir'];
                                        $tanggalLahir = $data['tanggal_lahir'];
                                        $agama = $data['agama'];
                                        $alamat = $data['alamat']; 
                                        $ids = $data['id_siswa'];                                       
                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>
                                        <td><?=$namaSiswa;?></td>
                                        <td><?=$nisn;?></td>
                                        <td><?=$kelas;?></td>
                                        <td><?=$tempatLahir;?></td>
                                        <td><?=$tanggalLahir;?></td>
                                        <td><?=$agama;?></td>
                                        <td><?=$alamat;?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalEditSiswa<?=$i;?>">Edit</button>
                                            <input type="hidden" name="idsis" value="<?=$ids;?>">
                                            <button type="button" class="btn btn-danger" name="tblHapus" data-bs-toggle="modal" data-bs-target="#modalHapusSiswa<?=$ids;?>">Hapus</button> 
                                        </td>
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
    <div class="modal fade" id="modalTambahSiswa">
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
                <textarea name="alamat" rows="5" cols="60">Alamat</textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success" name="tambahSiswa">Tambah</button> 
            </div>
            <br> 
        </form>   
        </div>
    </div>
    </div>

    <!-- Modal Edit Siswa-->
    <div class="modal fade" id="modalEditSiswa<?=$ids;?>">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Siswa</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            
            <form method="get">
            <div class="modal-body">
                <input type="text" name="nisn" value="<?=$nisn;?>" class="form-control">
                <br>
                <input type="text" name="namaSiswa" value="<?=$namaSiswa;?> class="form-control">
                <br>
                <select class="form-select" name="kelas" aria-label="Pilih Kelas">
                    <option selected><?=$kelas;?></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
                <br>
                <select class="form-select" name="jk" aria-label="Jenis Kelamin">
                    <option selected><?=$jk;?></option>
                    <option value="L">L</option>
                    <option value="P">P</option>
                </select>
                <br>
                <input type="text" name="tempatLahir" value="<?=$tempatLahir;?>" class="form-control">
                <br>
                <input type="date" name="tanggalLahir" value="<?=$tanggalLahir;?>" class="form-control">
                <br>
                <select class="form-select" name="agama" aria-label="Agama">
                    <option selected><?=$agama;?></option>
                    <option value="Katolik">Katolik</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Islam">Islam</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Khonghucu">Khonghucu</option>
                </select>
                <br>
                <textarea name="alamat" rows="5" cols="60"><?=$alamat;?></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-warning" name="editSiswa">Edit</button> 
            </div>
            <br> 
            </form>        
            </div>
        </div>
    </div>

    <!-- Modal Hapus Siswa-->
    <div class="modal fade" id="#modalHapusSiswa<?=$ids;?>">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Hapus Siswa?</h4>
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
                <select class="form-select" name="jk" aria-label="Jenis Kelamin"required>
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
                <textarea name="alamat" rows="5" cols="60">Alamat</textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-danger" name="tambahSiswa">Hapus</button> 
            </div>
            <br> 
            </form>       
            </div>
        </div>
    </div>
</html>
