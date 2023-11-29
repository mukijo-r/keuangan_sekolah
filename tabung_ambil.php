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
                            <li class="breadcrumb-item active">TABUNGAN/Daftar Pengambilan</li>                            
                        </ol>
                                                
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <!-- <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAmbilTransTabung">
                                        Mengambil
                                    </button> -->
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
                                Daftar Pengambilan Tabungan
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No.</th>    
                                            <th>Tanggal Pengambilan</th>
                                            <th>Nama Siswa</th>
                                            <th>Jumlah Pengambilan</th>
                                            <th>Guru Pencatat</th>
                                            <th>Saldo</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

                                    if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
                                        $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
                                        $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
                                    } else {
                                        // Kelas tidak ditemukan, tangani kesalahan di sini
                                        $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
                                        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
                                        header('location: tabung_ambil.php');
                                    }  

                                    $dataTabungan = mysqli_query($conn, "SELECT
                                        ta.id_tb_ambil,
                                        ta.tanggal,
                                        ta.id_siswa,
                                        ta.pencatat,
                                        ta.keterangan,
                                        s.nama AS nama_siswa,
                                        ta.jumlah
                                    FROM
                                        tabung_ambil ta
                                    JOIN
                                        siswa s ON ta.id_siswa = s.id_siswa
                                    WHERE
                                        ta.id_tahun_ajar = $idTahunAjar
                                    ORDER BY ta.tanggal DESC
                                    ");

                                    $totalEntries = mysqli_num_rows($dataTabungan);
                                    $i = $totalEntries;
                                    
                                    while($data=mysqli_fetch_array($dataTabungan)){
                                        $idTbAmbil = $data['id_tb_ambil'];
                                        $tanggal = $data['tanggal'];
                                        $tanggalTampil = date("d-m-Y", strtotime($tanggal));
                                        $tanggalAmbil = date("Y-m-d H:i", strtotime($tanggal));
                                        $idSiswa = $data['id_siswa'];
                                        $namaSiswa = $data['nama_siswa'];
                                        $jumlah = $data['jumlah'];
                                        $namaGuru = $data['pencatat'];
                                        $keterangan = $data['keterangan'];

                                        // Menghitung saldo
                                        $querySaldo = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM tabung_masuk WHERE id_siswa = $idSiswa");
                                        $querySaldoAmbil = mysqli_query($conn, "SELECT SUM(jumlah) AS total_ambil FROM tabung_ambil WHERE id_siswa = $idSiswa");

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
                                            <td><?=$tanggalTampil;?></td>
                                            <td><?=$namaSiswa;?></td>
                                            <td><?="Rp " . number_format($jumlah, 0, ',', '.');?></td>
                                            <td><?=$namaGuru;?></td>
                                            <td><?="Rp " . number_format($saldo, 0, ',', '.');?></td>
                                            <td><?=$keterangan;?></td>
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

    <!-- Modal Ambil-->
    <div class="modal fade" id="modalAmbilTransTabung">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Ambil Tabungan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            
            <form method="post">
            <div class="modal-body">
                <label for="tanggal">Tanggal Pengambilan :</label>       
                <?php $tanggalSaatIni = date('Y-m-d\TH:i', time());?>
                <input type="datetime-local" name="tanggal" value="<?=$tanggalSaatIni;?>" class="form-control">
                <br>               
                <label for="nama">Nama :</label>       
                <input type="text" name="nama" class="form-control">
                <br>
                <label for="jumlahTab">Jumlah Tabungan :</label>       
                <input type="text" name="jumlahTab" class="form-control" readonly>
                <br>
                <label for="jumlahAmbil">Jumlah Tabungan yang akan diambil :</label>
                <input type="text" name="jumlahAmbil" class="form-control">
                <br>
                <label for="guru">Guru Pencatat :</label>                     
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
                <br>                                                             
            </div>
            <div class="text-center">
                <input type="hidden" name="ids" value="<?=$ids;?>">
                <button type="submit" class="btn btn-warning" name="#ambilTab">Ambil</button> 
            </div>
            <br> 
            </form>        
            </div>
        </div>
    </div>

    
</html>
