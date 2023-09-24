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
        <title>Halaman Transaksi Umum</title>
        <style>
            .no-link-style {
            text-decoration: none; /* Menghapus garis bawah */
            color: inherit; /* Menggunakan warna teks bawaan dari elemen induk (h4) */
            cursor: pointer; /* Mengubah ikon kursor menjadi tangan ketika mengarahkan tautan */
            }
        </style>
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
                        <h3 class="mt-4">Laporan Keuangan Kas Umum</h3>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Transaksi Umum / Laporan</li>
                            <?php
                            $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar='$tahun_ajar'");
                            $rowTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
                            $idTahunAjar = $rowTahunAjar['id_tahun_ajar']; 
                            ?>           
                            
                        </ol>
  
                        <div class="container-fluid px-1">
                            <form method="post">    
                                <div class="row row-cols-auto">                                
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" for="tahunAjar">Tahun Ajar</label>
                                            </div>
                                            <select class="custom-select" id="tahunAjar" name="tahunAjar">
                                                <option value="">Pilih Tahun Ajar</option>
                                                <?php
                                                $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar, tahun_ajar FROM tahun_ajar");
                                                while ($rowTahunAjar = mysqli_fetch_assoc($queryTahunAjar)) {
                                                    $selected = ($rowTahunAjar['tahun_ajar'] == $tahunAjarLap) ? 'selected' : '';
                                                    echo '<option value="' . $rowTahunAjar['tahun_ajar'] . '" ' . $selected . '>' . $rowTahunAjar['tahun_ajar'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>                
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" for="bulan">Bulan</label>
                                            </div>
                                            <select class="custom-select" id="bulan" name="bulan">
                                                <option value="">Pilih Bulan </option>                                                
                                                <option value="Juli">Juli</option>
                                                <option value="Agustus">Agustus</option>
                                                <option value="September">September</option>
                                                <option value="Oktober">Oktober</option>
                                                <option value="November">November</option>
                                                <option value="Desember">Desember</option>
                                                <option value="Januari">Januari</option>
                                                <option value="Februari">Februari</option>
                                                <option value="Maret">Maret</option>
                                                <option value="April">April</option>
                                                <option value="Mei">Mei</option>
                                                <option value="Juni">Juni</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" for="kategori">Kategori</label>
                                            </div>
                                            <select class="custom-select" name="kategori" id="kategori">
                                                <option value="">Pilih Kategori</option>
                                                <?php
                                                // Ambil data kelas dari tabel kelas
                                                $queryKategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori WHERE kelompok='umum'");
                                                while ($kategori = mysqli_fetch_assoc($queryKategori)) {
                                                    echo '<option value="' . $kategori['id_kategori'] . '">' . $kategori['nama_kategori'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary" name="btnTampilLapUmum" id="btnTampilLapUmum">
                                            Tampilkan
                                        </button>
                                    </div>            
                                </div>
                            </form> 
                        </div>
                    </div><br><br>  
                    <div class="container-fluid px-4">
                    <?php
                    // Tampilkan Laporan Umum
                    if(isset($_POST['btnTampilLapUmum'])){
                        $tahunAjarLap = $_POST['tahunAjar'];
                        $bulanLalu = $_POST['bulan'];
                        $idKategoriLap = $_POST['kategori'];
                    } 

                    $queryKategori = mysqli_query($conn, "SELECT nama_kategori FROM kategori WHERE id_kategori='$idKategoriLap'");
                    $rowKategori = mysqli_fetch_assoc($queryKategori);
                    $namaKategori = $rowKategori['nama_kategori'];

                    ?>    
                    </div><br>                    
                                
                    <div class="card mb-4">
                    <div class="row" style="text-align: center;">


                        <h5>Laporan Keuangan <?=$namaKategori?> </h5>
                        <h5>Bulan <?= $bulanLalu;?> </h5>
                        <h5>Tahun Ajar <?=$tahunAjarLap; ?> </h5>  
                    </div>


                        <div class="card-body">
                            <table id="datatablesSimple1" class="table table-bordered border-dark">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Uraian</th>                                            
                                        <th>Debet</th>
                                        <th>Kredit</th>
                                        <th>Saldo</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $queryTransaksiUmum = "SELECT
                                    tmn.id_tmn, 
                                    tmn.tanggal,
                                    tmn.id_kategori,
                                    k.nama_kategori AS kategori,
                                    tmn.uraian,
                                    tmn.jumlah AS jumlah_masuk,
                                    0 AS jumlah_keluar,
                                    tmn.keterangan
                                    FROM 
                                        transaksi_masuk_nonsiswa tmn
                                    JOIN
                                        kategori k ON tmn.id_kategori = k.id_kategori
                                    WHERE 
                                        tmn.id_tahun_ajar = '$idTahunAjar'
                                        AND tmn.id_kategori = '$idKategoriLap'
                                        AND tmn.bulan = '$bulanLalu'
                                    GROUP BY 
                                        tmn.uraian
                                    UNION ALL
                                    SELECT
                                        tkn.id_tkn,
                                        tkn.tanggal,
                                        tkn.id_kategori,
                                        k.nama_kategori AS id_kategori,
                                        tkn.uraian,
                                        0 AS jumlah_masuk,
                                        tkn.jumlah AS jumlah_keluar,
                                        tkn.keterangan
                                    FROM 
                                        transaksi_keluar_nonsiswa tkn
                                    JOIN
                                        kategori k ON tkn.id_kategori = k.id_kategori
                                    WHERE 
                                        tkn.id_tahun_ajar = '$idTahunAjar'
                                        AND tkn.id_kategori = '$idKategoriLap'
                                        AND tkn.bulan = '$bulanLalu'
                                    GROUP BY 
                                        tkn.uraian
                                    ORDER BY tanggal ASC";

                                    $dataTransaksiUmum = mysqli_query($conn, $queryTransaksiUmum);

                                    $totalEntries = mysqli_num_rows($dataTransaksiUmum);
                                    $i = $totalEntries;
                                    
                                    while($data=mysqli_fetch_array($dataTransaksiUmum)){
                                        $idTransaksiMasukUmum = $data['id_tmn'];
                                        $tanggal =  $data['tanggal'];
                                        $tanggalMasuk = date("Y-m-d", strtotime($tanggal));
                                        $idKategori = $data['id_kategori']; 
                                        $kategori = $data['kategori'];                                          
                                        $uraian = $data['uraian'];
                                        $jumlahMasuk = $data['jumlah_masuk'];
                                        $jumlahKeluar = $data['jumlah_keluar'];                                        
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
                                            <td><?=$tanggalMasuk;?></td>
                                            <td><?=$uraian;?></td>
                                            <td><?php echo ($jumlahMasuk == 0) ? '' : "Rp " . number_format($jumlahMasuk, 0, ',', '.');?></td>
                                            <td><?php echo ($jumlahKeluar == 0) ? '' : "Rp " . number_format($jumlahKeluar, 0, ',', '.');?></td>
                                            <td><?="Rp " . number_format($saldo, 0, ',', '.');?></td>
                                            <td><?=$keterangan;?></tds>
                                        </tr>                                        
                                    <?php
                                    };

                                    ?>
                                </tbody>
                            </table>
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
            // Ambil referensi ke elemen-elemen dropdown
            var tahunAjarDropdown = document.getElementById('tahunAjar');
            var bulanDropdown = document.getElementById('bulan');
            var kategoriDropdown = document.getElementById('kategori');
            var tampilkanButton = document.getElementById('btnTampilLapUmum');

            // Tambahkan event listener ke setiap dropdown
            tahunAjarDropdown.addEventListener('change', checkDropdowns);
            bulanDropdown.addEventListener('change', checkDropdowns);
            kategoriDropdown.addEventListener('change', checkDropdowns);

            // Fungsi untuk memeriksa setiap dropdown
            function checkDropdowns() {
                if (tahunAjarDropdown.value !== '' && bulanDropdown.value !== '' && kategoriDropdown.value !== '') {
                    tampilkanButton.disabled = false;
                } else {
                    tampilkanButton.disabled = true;
                }
            }
        </script>
    
    </body>
</html>
