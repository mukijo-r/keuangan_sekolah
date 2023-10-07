<?php
require 'function.php';
require 'cek.php';
require 'config.php';
$conn = mysqli_connect("localhost:3306","root","","sdk");

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
        <style>
            @media print {
                .mt-4, .breadcrumb-item, .container-fluid, .px-4, .breadcrumb, .mb-4, .active, 
                .px-1, .row-cols-auto, .input-group, .mb-3, .input-group-prepend, .input-group-text,
                .custom-select, .btn, .btn-primary, .card, .mb-3, .h3, .ol, .li, .layoutSidenav, .layoutSidenav_content,
                .form, .option,
                 {
                    display: none;
                }
                
                body {
                margin: 0 !important;
                padding: 0 !important;
                }
            }

            .teks-kecil {
                font-size: 0.8em;
            }

            .row p {
                margin-bottom: 5px; /* Sesuaikan nilai sesuai kebutuhan */
            }

            .row h5 {
                margin-bottom: 5px; /* Sesuaikan nilai sesuai kebutuhan */
            }

        </style>

        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; ?>
        <div id="layoutSidenav" class="layoutSidenav">
            <?php include 'sidebar.php'; ?>
            <div id="layoutSidenav_content" class="layoutSidenav_content">
                <main >
                    <div class="container-fluid px-4">
                        <h3 class="mt-4">Laporan Keuangan Kas Siswa</h3>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Transaksi Siswa / Laporan</li>
                            <?php
                            $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar='$tahunAjarLap'");
                            $rowTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
                            $idTahunAjar = $rowTahunAjar['id_tahun_ajar'];                             
                            ?>           
                            
                        </ol>
  
                        <div class="container-fluid px-1">
                            <form method="post" class="form">  
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
                                                <option value="1">SPP</option>
                                                <?php
                                                // Ambil data kelas dari tabel kelas
                                                $queryKategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori WHERE kelompok='siswa' AND id_kategori <> 1 AND id_kategori <> 8");
                                                while ($kategori = mysqli_fetch_assoc($queryKategori)) {                                                    
                                                    echo '<option value="' . $kategori['id_kategori'] . '">' . $kategori['nama_kategori'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary" name="btnTampilLapSiswa" id="btnTampilLapSiswa">
                                            Tampilkan
                                        </button>
                                    </div>            
                                </div>
                            </form> 
                        </div>
                    </div><br> 

                    <div class="container-fluid px-4">
                    <?php
                    // Tampilkan Laporan Siswa
                    if (isset($_POST['btnTampilLapSiswa'])) {
                        $tahunAjarLap = $_POST['tahunAjar'];
                        $bulanLalu = $_POST['bulan'];
                        $idKategoriLap = $_POST['kategori'];
                
                        $queryKategori = mysqli_query($conn, "SELECT nama_kategori FROM kategori WHERE id_kategori='$idKategoriLap'");
                        $rowKategori = mysqli_fetch_assoc($queryKategori);
                        $namaKategori = $rowKategori['nama_kategori'];

                        if ($bulanLalu == 'Januari') {
                            $bulanNum = 1;
                        } elseif ($bulanLalu == 'Februari') {
                            $bulanNum = 2;
                        } elseif ($bulanLalu == 'Maret') {
                            $bulanNum = 3;
                        } elseif ($bulanLalu == 'April') {
                            $bulanNum = 4;
                        } elseif ($bulanLalu == 'Mei') {
                            $bulanNum = 5;
                        } elseif ($bulanLalu == 'Juni') {
                            $bulanNum = 6;
                        } elseif ($bulanLalu == 'Juli') {
                            $bulanNum = 7;
                        } elseif ($bulanLalu == 'Agustus') {
                            $bulanNum = 8;
                        } elseif ($bulanLalu == 'September') {
                            $bulanNum = 9;
                        } elseif ($bulanLalu == 'Oktober') {
                            $bulanNum = 10;
                        } elseif ($bulanLalu == 'November') {
                            $bulanNum = 11;
                        } elseif ($bulanLalu == 'Desember') {
                            $bulanNum = 12;
                        }

                
                        ?>
                        <div class="row" style="text-align: center; border: none">
                            <div class="col-md-3" style="text-align: right; border: none">
                            </div>
                            <div class="col-md-6">
                                <h5>Daftar Penerimaan Uang <?php if ($namaKategori === "Cash Flow") { echo "SPP";} else { echo $namaKategori;} ?></h5>
                                <h5>Bulan <?= $bulanLalu;?> </h5>
                                <h5>Tahun Ajar <?=$tahunAjarLap; ?></h5>
                            </div>
                        </div><br>
                        <?php
                        // Tampilkan tabel sesuai dengan kondisi
                        if ($idKategoriLap == 4) {
                            // Tampilkan tabel Ekstrakurikuler
                            tabelEkstra($idTahunAjar, $bulanNum, $idKategoriLap);
                        } elseif ($idKategoriLap == 10) {
                            // Tampilkan tabel Ujian
                            tabelUjian($idTahunAjar, $bulanNum, $idKategoriLap);                            
                        } else {
                            // Tampilkan tabel biasa
                            tabelTunggal($idTahunAjar, $bulanNum, $idKategoriLap);
                        }
                    }                

                    ?>
                </div>
                <?php                    
                    function tabelEkstra($idTahunAjar, $bulanNum, $idKategoriLap) {
                        $conn = mysqli_connect("localhost:3306","root","","sdk");
                        for ($kelas = 1; $kelas <= 6; $kelas++) {
                            echo '<div class="card mb-4">';
                            echo '<div class="card-header">';
                            echo '<i class="fas fa-table me-1"></i>';
                            echo 'Daftar Penerimaan kelas ' . $kelas;
                            echo '</div>';
                            echo '<div class="card-body">';
                            echo '<table id="datatablesSimple1" class="table table-bordered">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th rowspan="2" style="vertical-align: middle;">No.</th>';
                            echo '<th rowspan="2"  style="vertical-align: middle;">Nama Siswa</th>';
                            echo '<th colspan="4" style="text-align: center;">Iuran Kegiatan</th>';
                            echo '<th colspan="4" style="text-align: center;">Iuran Pramuka</th>';
                            echo '<th colspan="4" style="text-align: center;">Iuran Komputer</th>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>Penetapan</td>';
                            echo '<td>Bulan ini</td>';
                            echo '<td>Tunggakan</td>';
                            echo '<td>Jumlah</td>';
                            echo '<td>Penetapan</td>';
                            echo '<td>Bulan ini</td>';
                            echo '<td>Tunggakan</td>';
                            echo '<td>Jumlah</td>';
                            echo '<td>Penetapan</td>';
                            echo '<td>Bulan ini</td>';
                            echo '<td>Tunggakan</td>';
                            echo '<td>Jumlah</td>';
                            echo '</tr>'; 
                            echo '</thead>';
                            echo '<tbody>';

                            $queryPenerimaan = "SELECT
                            s.nama AS nama_siswa,
                            SUM(CASE WHEN subkat.nama_sub_kategori = 'Kegiatan' THEN tms.jumlah ELSE 0 END) AS jumlah_kegiatan,
                            SUM(CASE WHEN subkat.nama_sub_kategori = 'Pramuka' THEN tms.jumlah ELSE 0 END) AS jumlah_pramuka,
                            SUM(CASE WHEN subkat.nama_sub_kategori = 'Komputer' THEN tms.jumlah ELSE 0 END) AS jumlah_komputer,
                            SUM(CASE WHEN subkat.nama_sub_kategori = 'Kegiatan' THEN p.nominal ELSE 0 END) AS penetapan_kegiatan,
                            SUM(CASE WHEN subkat.nama_sub_kategori = 'Pramuka' THEN p.nominal ELSE 0 END) AS penetapan_pramuka,
                            SUM(CASE WHEN subkat.nama_sub_kategori = 'Komputer' THEN p.nominal ELSE 0 END) AS penetapan_komputer
                            FROM
                                transaksi_masuk_siswa tms
                            LEFT JOIN
                                siswa s ON tms.id_siswa = s.id_siswa
                            LEFT JOIN
                                sub_kategori_siswa subkat ON tms.id_sub_kategori = subkat.id_sub_kategori
                            LEFT JOIN
                                penetapan p ON tms.id_siswa = p.id_siswa AND tms.id_sub_kategori = p.id_sub_kategori
                            WHERE
                                tms.id_tahun_ajar = $idTahunAjar AND 
                                MONTH(tms.tanggal) = $bulanNum AND
                                s.id_kelas = $kelas
                            GROUP BY
                                s.nama;
                            ";
                            $i = 1;
                            $totalPenetapanKegiatan = 0;
                            $totalJumlahKegiatan = 0;
                            $totalPenetapanPramuka = 0;
                            $totalJumlahPramuka = 0;
                            $totalPenetapanKomputer = 0;
                            $totalJumlahKomputer = 0;

                            $penerimaanEkstra = mysqli_query($conn, $queryPenerimaan);
                            while($rowPenerimaan=mysqli_fetch_array($penerimaanEkstra)){
                                $namaSiswa = $rowPenerimaan['nama_siswa'];                    
                                $penetapanKegiatan = $rowPenerimaan['penetapan_kegiatan'];
                                $penetapanPramuka = $rowPenerimaan['penetapan_pramuka'];
                                $penetapanKomputer = $rowPenerimaan['penetapan_komputer'];
                                $jumlahKegiatan = $rowPenerimaan['jumlah_kegiatan'];
                                $jumlahPramuka = $rowPenerimaan['jumlah_pramuka'];
                                $jumlahKomputer = $rowPenerimaan['jumlah_komputer'];

                            echo '<tr>';
                            echo '<td>' . $i++ . '</td>';
                            echo '<td>' . $namaSiswa . '</td>';
                            echo '<td>Rp. ' . number_format($penetapanKegiatan, 0, ',', '.') . '</td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td>Rp. ' . number_format($jumlahKegiatan, 0, ',', '.') . '</td>';
                            echo '<td>Rp. ' . number_format($penetapanPramuka, 0, ',', '.') . '</td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td>Rp. ' . number_format($jumlahPramuka, 0, ',', '.') . '</td>';
                            echo '<td>Rp. ' . number_format($penetapanKomputer, 0, ',', '.') . '</td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td>Rp. ' . number_format($jumlahKomputer, 0, ',', '.') . '</td>';
                            echo '</tr>';

                            // Tambahkan nilai ke total
                            $totalPenetapanKegiatan += $penetapanKegiatan;
                            $totalJumlahKegiatan += $jumlahKegiatan;
                            $totalPenetapanPramuka += $penetapanPramuka;
                            $totalJumlahPramuka += $jumlahPramuka;
                            $totalPenetapanKomputer += $penetapanKomputer;
                            $totalJumlahKomputer += $jumlahKomputer;
                        }
                                // Tampilkan baris total
                            echo '<tr>';
                            echo '<td colspan="2">Total</td>';
                            echo '<td><strong>Rp. ' . number_format($totalPenetapanKegiatan, 0, ',', '.') . '</strong></td>';
                            echo '<td><strong></strong></td>';
                            echo '<td><strong></td>';
                            echo '<td><strong>Rp. ' . number_format($totalJumlahKegiatan, 0, ',', '.') . '</strong></td>';
                            echo '<td><strong>Rp. ' . number_format($totalPenetapanPramuka, 0, ',', '.') . '</strong></td>';
                            echo '<td><strong></strong></td>';
                            echo '<td><strong></td>';
                            echo '<td><strong>Rp. ' . number_format($totalJumlahPramuka, 0, ',', '.') . '</strong></td>';
                            echo '<td><strong>Rp. ' . number_format($totalPenetapanKomputer, 0, ',', '.') . '</strong></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td><strong>Rp. ' . number_format($totalJumlahKomputer, 0, ',', '.') . '</strong></td>';
                            echo '</tr>';                            
                            echo '</tbody>';
                            echo '</table>'; 
                            echo '</div>';
                            echo '</div>';
                            echo '<br>';
                        }                       
                        
                        // Tampilkan tabel untuk total kolom per kelas
                        echo '<br>';
                        echo '<div class="card mb-4">';
                        echo '<div class="card-header">';
                        echo '<i class="fas fa-table me-1"></i>';
                        echo 'Rekapitulasi Penerimaan Uang Kegiatan, Pramuka, Komputer ';
                        echo '</div>';
                        echo '<div class="card-body">';
                        echo '<table id="datatablesSimple1" class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th rowspan="2"  style="vertical-align: middle;">Kelas</th>';
                        echo '<th colspan="4" style="text-align: center;">Iuran Kegiatan</th>';
                        echo '<th colspan="4" style="text-align: center;">Iuran Pramuka</th>';
                        echo '<th colspan="4" style="text-align: center;">Iuran Komputer</th>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>Penetapan</td>';
                        echo '<td>Bulan ini</td>';
                        echo '<td>Tunggakan</td>';
                        echo '<td>Jumlah</td>';
                        echo '<td>Penetapan</td>';
                        echo '<td>Bulan ini</td>';
                        echo '<td>Tunggakan</td>';
                        echo '<td>Jumlah</td>';
                        echo '<td>Penetapan</td>';
                        echo '<td>Bulan ini</td>';
                        echo '<td>Tunggakan</td>';
                        echo '<td>Jumlah</td>';
                        echo '</tr>'; 
                        echo '</thead>';
                        echo '<tbody>';

                        // Simpan total kolom per kelas
                        $penetapanKegiatanKelas = 0;
                        $penetapanPramukaKelas = 0;
                        $penetapanKomputerKelas = 0;
                        $totalKegiatanKelas = 0;
                        $totalPramukaKelas = 0;
                        $totalKomputerKelas = 0;

                        $finalPenetapanKegiatan = 0;
                        $finalJumlahKegiatan = 0;
                        $finalPenetapanPramuka = 0;
                        $finalJumlahPramuka = 0;
                        $finalPenetapanKomputer = 0;
                        $finalJumlahKomputer = 0;

                        // Loop untuk menghitung total kolom per kelas
                        for ($kelas = 1; $kelas <= 6; $kelas++) {

                        $queryTotal = "SELECT
                        SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Kegiatan' THEN tms.jumlah ELSE 0 END) AS total_kegiatan_kelas,
                        SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Pramuka' THEN tms.jumlah ELSE 0 END) AS total_pramuka_kelas,
                        SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Komputer' THEN tms.jumlah ELSE 0 END) AS total_komputer_kelas,
                        SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Kegiatan' THEN p.nominal ELSE 0 END) AS penetapan_kegiatan_kelas,
                        SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Pramuka' THEN p.nominal ELSE 0 END) AS penetapan_pramuka_kelas,
                        SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Komputer' THEN p.nominal ELSE 0 END) AS penetapan_komputer_kelas
                        FROM
                            transaksi_masuk_siswa tms
                        LEFT JOIN
                            siswa s ON tms.id_siswa = s.id_siswa
                        LEFT JOIN
                            sub_kategori_siswa subkat ON tms.id_sub_kategori = subkat.id_sub_kategori
                        LEFT JOIN
                            penetapan p ON tms.id_siswa = p.id_siswa AND tms.id_sub_kategori = p.id_sub_kategori
                        WHERE
                            tms.id_tahun_ajar = $idTahunAjar AND 
                            MONTH(tms.tanggal) = $bulanNum";
                            
                        $resultTotal = mysqli_query($conn, $queryTotal);

                        $rowKelas=mysqli_fetch_array($resultTotal);
                        $penetapanKegiatanKelas =  $rowKelas['penetapan_kegiatan_kelas'];
                        $penetapanPramukaKelas = $rowKelas['penetapan_pramuka_kelas'];
                        $penetapanKomputerKelas = $rowKelas['penetapan_komputer_kelas'];
                        $totalKegiatanKelas = $rowKelas['total_kegiatan_kelas'];
                        $totalPramukaKelas = $rowKelas['total_pramuka_kelas'];
                        $totalKomputerKelas = $rowKelas['total_komputer_kelas'];
                        
                        echo '<tr>';
                        echo '<td>Kelas ' . $kelas . '</td>';
                        echo '<td>Rp. ' . number_format($penetapanKegiatanKelas, 0, ',', '.') . '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>Rp. ' . number_format($totalKegiatanKelas, 0, ',', '.') . '</td>';
                        echo '<td>Rp. ' . number_format($penetapanPramukaKelas, 0, ',', '.') . '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>Rp. ' . number_format($totalPramukaKelas, 0, ',', '.') . '</td>';
                        echo '<td>Rp. ' . number_format($penetapanKomputerKelas, 0, ',', '.') . '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>Rp. ' . number_format($totalKomputerKelas, 0, ',', '.') . '</td>';
                        echo '</tr>';
                        
                        // Tambahkan nilai ke total
                        $finalPenetapanKegiatan += $penetapanKegiatanKelas;
                        $finalJumlahKegiatan += $totalKegiatanKelas;
                        $finalPenetapanPramuka += $penetapanPramukaKelas;
                        $finalJumlahPramuka += $totalPramukaKelas;
                        $finalPenetapanKomputer += $penetapanKomputerKelas;
                        $finalJumlahKomputer += $totalKomputerKelas;
                        
                        }

                        echo '<tr>';
                        echo '<td><strong>Total</strong></td>';
                        echo '<td><strong>Rp. ' . number_format($finalPenetapanKegiatan, 0, ',', '.') . '</strong></td>';
                        echo '<td><strong></strong></td>';
                        echo '<td><strong></strong></td>';
                        echo '<td><strong>Rp. ' . number_format($finalJumlahKegiatan, 0, ',', '.') . '</strong></td>';
                        echo '<td><strong>Rp. ' . number_format($finalPenetapanPramuka, 0, ',', '.') . '</strong></td>';
                        echo '<td><strong></strong></td>';
                        echo '<td><strong></strong></td>';
                        echo '<td><strong>Rp. ' . number_format($finalJumlahPramuka, 0, ',', '.') . '</strong></td>';
                        echo '<td><strong>Rp. ' . number_format($finalPenetapanKomputer, 0, ',', '.') . '</strong></td>';
                        echo '<td><strong></strong></td>';
                        echo '<td></strong></td>';
                        echo '<td><strong>Rp. ' . number_format($finalJumlahKomputer, 0, ',', '.') . '</strong></td>';
                        echo '</tr>';

                        echo '</tbody>';
                        echo '</table>'; 
                        echo '</div>';
                        echo '</div>';
                }

                function tabelUjian($idTahunAjar, $bulanNum, $idKategoriLap) {
                    $conn = mysqli_connect("localhost:3306","root","","sdk");
                    for ($kelas = 1; $kelas <= 6; $kelas++) {
                        echo '<div class="card mb-4">';
                        echo '<div class="card-header">';
                        echo '<i class="fas fa-table me-1"></i>';
                        echo 'Daftar Penerimaan kelas ' . $kelas;
                        echo '</div>';
                        echo '<div class="card-body">';
                        echo '<table id="datatablesSimple1" class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th rowspan="2" style="vertical-align: middle;">No.</th>';
                        echo '<th rowspan="2"  style="vertical-align: middle;">Nama Siswa</th>';
                        echo '<th colspan="4" style="text-align: center;">Iuran PTS</th>';
                        echo '<th colspan="4" style="text-align: center;">Iuran PAS</th>';
                        echo '<th colspan="4" style="text-align: center;">Iuran US</th>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>Penetapan</td>';
                        echo '<td>Bulan ini</td>';
                        echo '<td>Tunggakan</td>';
                        echo '<td>Jumlah</td>';
                        echo '<td>Penetapan</td>';
                        echo '<td>Bulan ini</td>';
                        echo '<td>Tunggakan</td>';
                        echo '<td>Jumlah</td>';
                        echo '<td>Penetapan</td>';
                        echo '<td>Bulan ini</td>';
                        echo '<td>Tunggakan</td>';
                        echo '<td>Jumlah</td>';
                        echo '</tr>'; 
                        echo '</thead>';
                        echo '<tbody>';

                        $queryPenerimaan = "SELECT
                        s.nama AS nama_siswa,
                        SUM(CASE WHEN subkat.nama_sub_kategori = 'PTS' THEN tms.jumlah ELSE 0 END) AS jumlah_pts,
                        SUM(CASE WHEN subkat.nama_sub_kategori = 'PAS' THEN tms.jumlah ELSE 0 END) AS jumlah_pas,
                        SUM(CASE WHEN subkat.nama_sub_kategori = 'US' THEN tms.jumlah ELSE 0 END) AS jumlah_us,
                        SUM(CASE WHEN subkat.nama_sub_kategori = 'PTS' THEN p.nominal ELSE 0 END) AS penetapan_pts,
                        SUM(CASE WHEN subkat.nama_sub_kategori = 'PAS' THEN p.nominal ELSE 0 END) AS penetapan_pas,
                        SUM(CASE WHEN subkat.nama_sub_kategori = 'US' THEN p.nominal ELSE 0 END) AS penetapan_us
                        FROM
                            transaksi_masuk_siswa tms
                        LEFT JOIN
                            siswa s ON tms.id_siswa = s.id_siswa
                        LEFT JOIN
                            sub_kategori_siswa subkat ON tms.id_sub_kategori = subkat.id_sub_kategori
                        LEFT JOIN
                            penetapan p ON tms.id_siswa = p.id_siswa AND tms.id_sub_kategori = p.id_sub_kategori
                        WHERE
                            tms.id_tahun_ajar = $idTahunAjar AND 
                            MONTH(tms.tanggal) = $bulanNum AND
                            s.id_kelas = $kelas
                        GROUP BY
                            s.nama;
                        ";
                        $i = 1;
                        $totalPenetapanPts = 0;
                        $totalJumlahPts = 0;
                        $totalPenetapanPas = 0;
                        $totalJumlahPas = 0;
                        $totalPenetapanUs = 0;
                        $totalJumlahUs = 0;

                        $penerimaanEkstra = mysqli_query($conn, $queryPenerimaan);
                        while($rowPenerimaan=mysqli_fetch_array($penerimaanEkstra)){
                            $namaSiswa = $rowPenerimaan['nama_siswa'];                    
                            $penetapanPts = $rowPenerimaan['penetapan_pts'];
                            $penetapanPas = $rowPenerimaan['penetapan_pas'];
                            $penetapanUs = $rowPenerimaan['penetapan_us'];
                            $jumlahPts = $rowPenerimaan['jumlah_pts'];
                            $jumlahPas = $rowPenerimaan['jumlah_pas'];
                            $jumlahUs = $rowPenerimaan['jumlah_us'];

                        echo '<tr>';
                        echo '<td>' . $i++ . '</td>';
                        echo '<td>' . $namaSiswa . '</td>';
                        echo '<td>Rp. ' . number_format($penetapanPts, 0, ',', '.') . '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>Rp. ' . number_format($jumlahPts, 0, ',', '.') . '</td>';
                        echo '<td>Rp. ' . number_format($penetapanPas, 0, ',', '.') . '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>Rp. ' . number_format($jumlahPas, 0, ',', '.') . '</td>';
                        echo '<td>Rp. ' . number_format($penetapanUs, 0, ',', '.') . '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>Rp. ' . number_format($jumlahUs, 0, ',', '.') . '</td>';
                        echo '</tr>';

                        // Tambahkan nilai ke total
                        $totalPenetapanPts += $penetapanPts;
                        $totalJumlahPts += $jumlahPts;
                        $totalPenetapanPas += $penetapanPas;
                        $totalJumlahPas += $jumlahPas;
                        $totalPenetapanUs += $penetapanUs;
                        $totalJumlahUs += $jumlahUs;
                    }
                            // Tampilkan baris total
                        echo '<tr>';
                        echo '<td colspan="2">Total</td>';
                        echo '<td><strong>Rp. ' . number_format($totalPenetapanPts, 0, ',', '.') . '</strong></td>';
                        echo '<td></td>';
                        echo '<td><strong></td>';
                        echo '<td><strong>Rp. ' . number_format($totalJumlahPts, 0, ',', '.') . '</strong></td>';
                        echo '<td><strong>Rp. ' . number_format($totalPenetapanPas, 0, ',', '.') . '</strong></td>';
                        echo '<td></td>';
                        echo '<td><strong></td>';
                        echo '<td><strong>Rp. ' . number_format($totalJumlahPas, 0, ',', '.') . '</strong></td>';
                        echo '<td><strong>Rp. ' . number_format($totalPenetapanUs, 0, ',', '.') . '</strong></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td><strong>Rp. ' . number_format($totalJumlahUs, 0, ',', '.') . '</strong></td>';
                        echo '</tr>';                            
                        echo '</tbody>';
                        echo '</table>'; 
                        echo '</div>';
                        echo '</div>';
                        echo '<br>';
                    }                       
                    
                    // Tampilkan tabel untuk total kolom per kelas
                    echo '<br>';
                    echo '<div class="card mb-4">';
                    echo '<div class="card-header">';
                    echo '<i class="fas fa-table me-1"></i>';
                    echo 'Rekapitulasi Penerimaan Uang PTS, PAS, US ';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<table id="datatablesSimple1" class="table table-bordered">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th rowspan="2"  style="vertical-align: middle;">Kelas</th>';
                    echo '<th colspan="4" style="text-align: center;">Iuran PTS</th>';
                    echo '<th colspan="4" style="text-align: center;">Iuran PAS</th>';
                    echo '<th colspan="4" style="text-align: center;">Iuran US</th>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td>Penetapan</td>';
                    echo '<td>Bulan ini</td>';
                    echo '<td>Tunggakan</td>';
                    echo '<td>Jumlah</td>';
                    echo '<td>Penetapan</td>';
                    echo '<td>Bulan ini</td>';
                    echo '<td>Tunggakan</td>';
                    echo '<td>Jumlah</td>';
                    echo '<td>Penetapan</td>';
                    echo '<td>Bulan ini</td>';
                    echo '<td>Tunggakan</td>';
                    echo '<td>Jumlah</td>';
                    echo '</tr>'; 
                    echo '</thead>';
                    echo '<tbody>';

                    // Simpan total kolom per kelas
                    $penetapanPtsKelas = 0;
                    $penetapanPasKelas = 0;
                    $penetapanUsKelas = 0;
                    $totalPtsKelas = 0;
                    $totalPasKelas = 0;
                    $totalUsKelas = 0;

                    $finalPenetapanPts = 0;
                    $finalJumlahPts = 0;
                    $finalPenetapanPas = 0;
                    $finalJumlahPas = 0;
                    $finalPenetapanUs = 0;
                    $finalJumlahUs = 0;

                    // Loop untuk menghitung total kolom per kelas
                    for ($kelas = 1; $kelas <= 6; $kelas++) {

                    $queryTotal = "SELECT
                    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PTS' THEN tms.jumlah ELSE 0 END) AS total_pts_kelas,
                    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PAS' THEN tms.jumlah ELSE 0 END) AS total_pas_kelas,
                    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'US' THEN tms.jumlah ELSE 0 END) AS total_us_kelas,
                    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PTS' THEN p.nominal ELSE 0 END) AS penetapan_pts_kelas,
                    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PAS' THEN p.nominal ELSE 0 END) AS penetapan_pas_kelas,
                    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'US' THEN p.nominal ELSE 0 END) AS penetapan_us_kelas
                    FROM
                        transaksi_masuk_siswa tms
                    LEFT JOIN
                        siswa s ON tms.id_siswa = s.id_siswa
                    LEFT JOIN
                        sub_kategori_siswa subkat ON tms.id_sub_kategori = subkat.id_sub_kategori
                    LEFT JOIN
                        penetapan p ON tms.id_siswa = p.id_siswa AND tms.id_sub_kategori = p.id_sub_kategori
                    WHERE
                        tms.id_tahun_ajar = $idTahunAjar AND 
                        MONTH(tms.tanggal) = $bulanNum";
                        
                    $resultTotal = mysqli_query($conn, $queryTotal);

                    $rowKelas=mysqli_fetch_array($resultTotal);
                    $penetapanPtsKelas =  $rowKelas['penetapan_pts_kelas'];
                    $penetapanPasKelas = $rowKelas['penetapan_pas_kelas'];
                    $penetapanUsKelas = $rowKelas['penetapan_us_kelas'];
                    $totalPtsKelas = $rowKelas['total_pts_kelas'];
                    $totalPasKelas = $rowKelas['total_pas_kelas'];
                    $totalUsKelas = $rowKelas['total_us_kelas'];
                    
                    echo '<tr>';
                    echo '<td>Kelas ' . $kelas . '</td>';
                    echo '<td>Rp. ' . number_format($penetapanPtsKelas, 0, ',', '.') . '</td>';
                    echo '<td></td>';
                    echo '<td></td>';
                    echo '<td>Rp. ' . number_format($totalPtsKelas, 0, ',', '.') . '</td>';
                    echo '<td>Rp. ' . number_format($penetapanPasKelas, 0, ',', '.') . '</td>';
                    echo '<td></td>';
                    echo '<td></td>';
                    echo '<td>Rp. ' . number_format($totalPasKelas, 0, ',', '.') . '</td>';
                    echo '<td>Rp. ' . number_format($penetapanUsKelas, 0, ',', '.') . '</td>';
                    echo '<td></td>';
                    echo '<td></td>';
                    echo '<td>Rp. ' . number_format($totalUsKelas, 0, ',', '.') . '</td>';
                    echo '</tr>';
                    
                    // Tambahkan nilai ke total
                    $finalPenetapanPts += $penetapanPtsKelas;
                    $finalJumlahPts += $totalPtsKelas;
                    $finalPenetapanPas += $penetapanPasKelas;
                    $finalJumlahPas += $totalPasKelas;
                    $finalPenetapanUs += $penetapanUsKelas;
                    $finalJumlahUs += $totalUsKelas;
                    
                    }

                    echo '<tr>';
                    echo '<td><strong>Total</strong></td>';
                    echo '<td><strong>Rp. ' . number_format($finalPenetapanPts, 0, ',', '.') . '</strong></td>';
                    echo '<td><strong></strong></td>';
                    echo '<td><strong></strong></td>';
                    echo '<td><strong>Rp. ' . number_format($finalJumlahPts, 0, ',', '.') . '</strong></td>';
                    echo '<td><strong>Rp. ' . number_format($finalPenetapanPas, 0, ',', '.') . '</strong></td>';
                    echo '<td><strong></strong></td>';
                    echo '<td><strong></strong></td>';
                    echo '<td><strong>Rp. ' . number_format($finalJumlahPas, 0, ',', '.') . '</strong></td>';
                    echo '<td><strong>Rp. ' . number_format($finalPenetapanUs, 0, ',', '.') . '</strong></td>';
                    echo '<td><strong></strong></td>';
                    echo '<td></strong></td>';
                    echo '<td></strong>Rp. ' . number_format($finalJumlahUs, 0, ',', '.') . '</td>';
                    echo '</tr>';

                    echo '</tbody>';
                    echo '</table>'; 
                    echo '</div>';
                    echo '</div>';
                }

                function tabelTunggal($idTahunAjar, $bulanNum, $idKategoriLap) {
                    $conn = mysqli_connect("localhost:3306","root","","sdk");
                    for ($kelas = 1; $kelas <= 6; $kelas++) {
                        echo '<div class="card mb-4">';
                        echo '<div class="card-header">';
                        echo '<i class="fas fa-table me-1"></i>';
                        echo 'Daftar Penerimaan kelas ' . $kelas;
                        echo '</div>';
                        echo '<div class="card-body">';
                        echo '<table id="datatablesSimple1" class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th rowspan="2" style="vertical-align: middle;">No.</th>';
                        echo '<th rowspan="2"  style="vertical-align: middle;">Nama Siswa</th>';
                        echo '<th colspan="4" style="text-align: center;">Iuran SPP</th>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td>Penetapan</td>';
                        echo '<td>Bulan ini</td>';
                        echo '<td>Tunggakan</td>';
                        echo '<td>Jumlah</td>';
                        echo '</tr>'; 
                        echo '</thead>';
                        echo '<tbody>';

                        $queryPenerimaan = "SELECT
                        s.nama AS nama_siswa,
                        SUM(CASE WHEN subkat.nama_sub_kategori = 'SPP' THEN tms.jumlah ELSE 0 END) AS jumlah_spp,
                        SUM(CASE WHEN subkat.nama_sub_kategori = 'SPP' THEN p.nominal ELSE 0 END) AS penetapan_spp
                        FROM
                            transaksi_masuk_siswa tms
                        LEFT JOIN
                            siswa s ON tms.id_siswa = s.id_siswa
                        LEFT JOIN
                            sub_kategori_siswa subkat ON tms.id_sub_kategori = subkat.id_sub_kategori
                        LEFT JOIN
                            penetapan p ON tms.id_siswa = p.id_siswa AND tms.id_sub_kategori = p.id_sub_kategori
                        WHERE
                            tms.id_tahun_ajar = $idTahunAjar AND 
                            MONTH(tms.tanggal) = $bulanNum AND
                            s.id_kelas = $kelas
                        GROUP BY
                            s.nama;
                        ";
                        $i = 1;
                        $totalPenetapanSpp = 0;
                        $totalJumlahSpp = 0;

                        $penerimaanEkstra = mysqli_query($conn, $queryPenerimaan);
                        while($rowPenerimaan=mysqli_fetch_array($penerimaanEkstra)){
                            $namaSiswa = $rowPenerimaan['nama_siswa'];                    
                            $penetapanSpp = $rowPenerimaan['penetapan_spp'];
                            $jumlahSpp = $rowPenerimaan['jumlah_spp'];

                        echo '<tr>';
                        echo '<td>' . $i++ . '</td>';
                        echo '<td>' . $namaSiswa . '</td>';
                        echo '<td>Rp. ' . number_format($penetapanSpp, 0, ',', '.') . '</td>';
                        echo '<td>Rp. ' . number_format($jumlahSpp, 0, ',', '.') . '</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '</tr>';

                        // Tambahkan nilai ke total
                        $totalPenetapanSpp += $penetapanSpp;
                        $totalJumlahSpp += $jumlahSpp;
                    }
                            // Tampilkan baris total
                        echo '<tr>';
                        echo '<td colspan="2">Total</td>';
                        echo '<td><strong>Rp. ' . number_format($totalPenetapanSpp, 0, ',', '.') . '</strong></td>';
                        echo '<td></td>';
                        echo '<td><strong></td>';
                        echo '<td><strong>Rp. ' . number_format($totalJumlahSpp, 0, ',', '.') . '</strong></td>';
                        echo '</tr>';                            
                        echo '</tbody>';
                        echo '</table>'; 
                        echo '</div>';
                        echo '</div>';
                        echo '<br>';
                    }                       
                    
                    // Tampilkan tabel untuk total kolom per kelas
                    echo '<br>';
                    echo '<div class="card mb-4">';
                    echo '<div class="card-header">';
                    echo '<i class="fas fa-table me-1"></i>';
                    echo 'Rekapitulasi Penerimaan Uang SPP ';
                    echo '</div>';
                    echo '<div class="card-body">';
                    echo '<table id="datatablesSimple1" class="table table-bordered">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th rowspan="2"  style="vertical-align: middle;">Kelas</th>';
                    echo '<th colspan="4" style="text-align: center;">Iuran SPP</th>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td>Penetapan</td>';
                    echo '<td>Bulan ini</td>';
                    echo '<td>Tunggakan</td>';
                    echo '<td>Jumlah</td>';
                    echo '</tr>'; 
                    echo '</thead>';
                    echo '<tbody>';

                    // Simpan total kolom per kelas
                    $penetapanSppKelas = 0;
                    $totalSppKelas = 0;

                    $finalPenetapanSpp = 0;
                    $finalJumlahSpp = 0;

                    // Loop untuk menghitung total kolom per kelas
                    for ($kelas = 1; $kelas <= 6; $kelas++) {

                    $queryTotal = "SELECT
                    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'SPP' THEN tms.jumlah ELSE 0 END) AS total_spp_kelas,
                    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'SPP' THEN p.nominal ELSE 0 END) AS penetapan_spp_kelas

                    FROM
                        transaksi_masuk_siswa tms
                    LEFT JOIN
                        siswa s ON tms.id_siswa = s.id_siswa
                    LEFT JOIN
                        sub_kategori_siswa subkat ON tms.id_sub_kategori = subkat.id_sub_kategori
                    LEFT JOIN
                        penetapan p ON tms.id_siswa = p.id_siswa AND tms.id_sub_kategori = p.id_sub_kategori
                    WHERE
                        tms.id_tahun_ajar = $idTahunAjar AND 
                        MONTH(tms.tanggal) = $bulanNum";
                        
                    $resultTotal = mysqli_query($conn, $queryTotal);

                    $rowKelas=mysqli_fetch_array($resultTotal);
                    $penetapanSppKelas =  $rowKelas['penetapan_spp_kelas'];
                    $totalSppKelas = $rowKelas['total_spp_kelas'];
                    
                    echo '<tr>';
                    echo '<td>Kelas ' . $kelas . '</td>';
                    echo '<td>Rp. ' . number_format($penetapanSppKelas, 0, ',', '.') . '</td>';
                    echo '<td></td>';
                    echo '<td></td>';
                    echo '<td>Rp. ' . number_format($totalSppKelas, 0, ',', '.') . '</td>';
                    echo '</tr>';
                    
                    // Tambahkan nilai ke total
                    $finalPenetapanSpp += $penetapanSppKelas;
                    $finalJumlahSpp += $totalSppKelas;
                    
                    }

                    echo '<tr>';
                    echo '<td><strong>Total</strong></td>';
                    echo '<td><strong>Rp. ' . number_format($finalPenetapanSpp, 0, ',', '.') . '</strong></td>';
                    echo '<td><strong></td>';
                    echo '<td><strong></strong></td>';
                    echo '<td><strong>Rp. ' . number_format($finalJumlahSpp, 0, ',', '.') . '</strong></strong></td>';
                    echo '</tr>';

                    echo '</tbody>';
                    echo '</table>'; 
                    echo '</div>';
                    echo '</div>';
                }
                
                ?>              
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
