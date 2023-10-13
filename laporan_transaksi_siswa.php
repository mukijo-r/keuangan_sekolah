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
                            $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar='$tahun_ajar'");
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
                                                <?php
                                                $queryKategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori WHERE kelompok='siswa' AND id_kategori <> 8");
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
                    </div> 
                    <div class="container-fluid px-4">
                    <?php
                    // Tampilkan Laporan siswa
                    if(isset($_POST['btnTampilLapSiswa'])){
                        $tahunAjarLap = $_POST['tahunAjar'];
                        $bulanLalu = $_POST['bulan'];
                        $idKategoriLap = $_POST['kategori'];                    
                    } 

                    $queryKategori = mysqli_query($conn, "SELECT nama_kategori FROM kategori WHERE id_kategori='$idKategoriLap'");
                    $rowKategori = mysqli_fetch_assoc($queryKategori);
                    $namaKategori = $rowKategori['nama_kategori'];

                    $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar='$tahunAjarLap'");
                    $rowTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
                    $idTahunAjar = $rowTahunAjar['id_tahun_ajar']; 

                    // Mendapatkan tahun bulan tanggal dari tahun ajar dan bulan
                    list($tahunAwal, $tahunAkhir) = explode('/', $tahunAjarLap);

                    // Konversi nama bulan ke angka
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
                    } else {
                        $bulanNum = 'Bulan Tidak valid';
                    }

                    // Daftar bulan-bulan yang menggunakan tahun awal
                    $bulanTahunAwal = range(7, 12); 

                    if (in_array($bulanNum, $bulanTahunAwal)) {
                        $tahunYangDigunakan = $tahunAwal;
                    } else {
                        $tahunYangDigunakan = $tahunAkhir;
                    }

                    // Mencari tanggal akhir dalam bulan sesuai tahun yang ditentukan
                    $tanggalAkhir = date('Y-m-t', strtotime("$tahunYangDigunakan-$bulanNum-01"));
                    $tanggalAkhir2 = date('Y-m-t H:i:s', strtotime($tanggalAkhir . ' 23:59:59'));

                    ?>    

                    </div>
                    <div class="row" style="text-align: center; border:none">
                        <div class="col-md-3" style="text-align: right; border:none">
                        </div>
                        <div class="col-md-6">
                            <h5>Laporan Keuangan <?=$namaKategori?> </h5>
                            <h5>Bulan <?= $bulanLalu;?> </h5>
                            <h5>Tahun Ajar <?=$tahunAjarLap; ?> </h5>
                        </div>     
                    </div><br><br>  
                    
                    
                    <div class="card-body px-3">        
                        <div class="card mb-4">
                            <div class="card-body">
                                <table id="datatablesSimple1" class="table table-bordered border-dark teks-kecil">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">Tanggal</th>
                                            <th style="width: 30%">Uraian</th>                                            
                                            <th style="width: 10%">Debet</th>
                                            <th style="width: 10%">Kredit</th>
                                            <th style="width: 10%">Saldo</th>
                                            <th style="width: 20%">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $queryTransaksiSiswa = "SELECT
                                        tms.id_tms, 
                                        tms.tanggal,
                                        tms.id_kategori,
                                        k.nama_kategori AS kategori,
                                        s.nama AS uraian,
                                        tms.jumlah AS jumlah_masuk,
                                        0 AS jumlah_keluar,
                                        tms.keterangan
                                        FROM 
                                            transaksi_masuk_siswa tms
                                        JOIN
                                            kategori k ON tms.id_kategori = k.id_kategori
                                        JOIN 
                                            siswa s ON tms.id_siswa = s.id_siswa
                                        WHERE 
                                            tms.id_tahun_ajar = '$idTahunAjar'
                                            AND tms.id_kategori = '$idKategoriLap'
                                            AND tms.bulan = '$bulanLalu'
                                        GROUP BY 
                                            uraian
                                        UNION ALL
                                        SELECT
                                            tks.id_tks,
                                            tks.tanggal,
                                            tks.id_kategori,
                                            k.nama_kategori AS id_kategori,
                                            tks.uraian,
                                            0 AS jumlah_masuk,
                                            tks.jumlah AS jumlah_keluar,
                                            tks.keterangan
                                        FROM 
                                            transaksi_keluar_siswa tks
                                        JOIN
                                            kategori k ON tks.id_kategori = k.id_kategori
                                        WHERE 
                                            tks.id_tahun_ajar = '$idTahunAjar'
                                            AND tks.id_kategori = '$idKategoriLap'
                                            AND tks.bulan = '$bulanLalu'
                                        GROUP BY 
                                            tks.uraian
                                        ORDER BY tanggal ASC";

                                        $dataTransaksiSiswa = mysqli_query($conn, $queryTransaksiSiswa);
                                        // menghitung saldo bulan lalu
                                        $queryDebet = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_siswa WHERE id_kategori = '$idKategoriLap' AND tanggal <= '$tanggalAkhir2'");
                                        $queryKredit = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_siswa WHERE id_kategori = '$idKategoriLap' AND tanggal <= '$tanggalAkhir2'");
                                        $queryDebetBulanLalu = mysqli_query($conn, "SELECT SUM(jumlah) AS total_debet FROM transaksi_masuk_siswa WHERE id_kategori = '$idKategoriLap' AND bulan='$bulanLalu' AND id_tahun_ajar = '$idTahunAjar'");
                                        $queryKreditBulanLalu = mysqli_query($conn, "SELECT SUM(jumlah) AS total_kredit FROM transaksi_keluar_siswa WHERE id_kategori = '$idKategoriLap' AND bulan='$bulanLalu' AND id_tahun_ajar = '$idTahunAjar'");

                                        $totalDebet = 0;
                                        $totalKredit = 0;
                                        $DebetBulanLalu = 0;

                                        if ($rowDebet = mysqli_fetch_assoc($queryDebet)) {
                                            $totalDebet = $rowDebet['total_masuk'];
                                        }

                                        if ($rowKredit = mysqli_fetch_assoc($queryKredit)) {
                                            $totalKredit = $rowKredit['total_keluar'];
                                        }

                                        if ($rowDebetBulanLalu = mysqli_fetch_assoc($queryDebetBulanLalu)) {
                                            $DebetBulanLalu = $rowDebetBulanLalu['total_debet'];
                                        }

                                        if ($rowKreditBulanLalu = mysqli_fetch_assoc($queryKreditBulanLalu)) {
                                            $KreditBulanLalu = $rowKreditBulanLalu['total_kredit'];
                                        }

                                        $selisihBulanLalu = $DebetBulanLalu - $KreditBulanLalu;

                                        $saldo = $totalDebet - $totalKredit;
                                        $saldoBulanLalu = $saldo - $selisihBulanLalu;
                                        ?>
                                        <tr>
                                            <td style="width: 10%"></td>
                                            <td style="width: 30%">Saldo bulan lalu</td>
                                            <td style="width: 10%"><?php echo ($saldoBulanLalu == 0) ? '' : "Rp " . number_format($saldoBulanLalu, 0, ',', '.');?></td>
                                            <td style="width: 10%"></td>
                                            <td style="width: 10%"><?php echo ($saldoBulanLalu == 0) ? '' : "Rp " . number_format($saldoBulanLalu, 0, ',', '.');?></td>
                                            <td style="width: 20%"></td>
                                        </tr>
                                        <?php

                                        $totalMasuk = 0;
                                        $totalKeluar = 0;
                                        
                                        while($data=mysqli_fetch_array($dataTransaksiSiswa)){
                                            $idTransaksiMasukSiswa = $data['id_tms'];
                                            $tanggal =  $data['tanggal'];
                                            $tanggalMasuk = date("Y-m-d", strtotime($tanggal));
                                            $tanggalTampil = date("d-m-Y", strtotime($tanggal));
                                            $tanggalBayar = date("Y-m-d H:i:s", strtotime($tanggal));
                                            $idKategori = $data['id_kategori']; 
                                            $kategori = $data['kategori'];                                          
                                            $uraian = $data['uraian'];
                                            $jumlahMasuk = $data['jumlah_masuk'];
                                            $jumlahKeluar = $data['jumlah_keluar'];                                        
                                            $keterangan = $data['keterangan'];                                      

                                            // Menghitung saldo
                                            $queryMasuk = "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_siswa WHERE id_kategori = '$idKategoriLap' AND tanggal <= '$tanggalBayar'";
                                            $queryKeluar = "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_siswa WHERE id_kategori = '$idKategoriLap' AND tanggal <= '$tanggalBayar'";

                                            $masuk = mysqli_query($conn, $queryMasuk);
                                            $keluar = mysqli_query($conn, $queryKeluar);                                           

                                            if ($rowMasuk = mysqli_fetch_assoc($masuk)) {
                                                $totalMasuk = $rowMasuk['total_masuk'];
                                            }

                                            if ($rowKeluar = mysqli_fetch_assoc($keluar)) {
                                                $totalKeluar = $rowKeluar['total_keluar'];
                                            }

                                            $saldo = $totalMasuk - $totalKeluar;                                           
                                            
                                            ?>
                                            
                                            <tr>
                                                <td style="width: 10%"><?=$tanggalTampil;?></td>
                                                <td style="width: 30%"><?=$uraian;?></td>
                                                <td style="width: 10%"><?php echo ($jumlahMasuk == 0) ? '' : "Rp " . number_format($jumlahMasuk, 0, ',', '.');?></td>
                                                <td style="width: 10%"><?php echo ($jumlahKeluar == 0) ? '' : "Rp " . number_format($jumlahKeluar, 0, ',', '.');?></td>
                                                <td style="width: 10%"><?php echo ($saldo == 0) ? '' : "Rp " . number_format($saldo, 0, ',', '.');?></td>
                                                <td style="width: 20%"><?=$keterangan;?></tds>
                                            </tr>  
                                        <?php                                        
                                        };                                        
                                        ?>  
                                    </tbody>
                                        <tr>
                                            <td colspan="2" style="text-align: center;">Total</td>
                                            <td style="width: 10%"><?= "Rp "  . number_format($totalMasuk, 0, ',', '.') ;?></td>
                                            <td style="width: 10%"><?= "Rp " . number_format($totalKeluar, 0, ',', '.') ;?></td>
                                            <td style="width: 10%"><?= "Rp " . number_format($saldo, 0, ',', '.') ;?></td>
                                            <td style="width: 20%"></td>
                                        </tr>
                                </table><br><br>
                                
                            </div>
                            <?php 
                            $queryJabatan = mysqli_query($conn, "SELECT
                            MAX(CASE WHEN jabatan = 'Kepala Sekolah' THEN nama_lengkap END) AS kepala_sekolah,
                            MAX(CASE WHEN jabatan = 'Bendahara Sekolah' THEN nama_lengkap END) AS bendahara_sekolah,
                            MAX(CASE WHEN jabatan = 'Pembuat Laporan' THEN nama_lengkap END) AS pembuat_laporan,
                            MAX(CASE WHEN jabatan = 'Kepala Sekolah' THEN nip END) AS nip_kepala_sekolah,
                            MAX(CASE WHEN jabatan = 'Bendahara Sekolah' THEN nip END) AS nip_bendahara_sekolah,
                            MAX(CASE WHEN jabatan = 'Pembuat Laporan' THEN nip END) AS nip_pembuat_laporan
                            FROM guru;");

                            $rowJabatan = mysqli_fetch_assoc($queryJabatan);
                            $bendahara = $rowJabatan['bendahara_sekolah'];
                            $pembuatLaporan = $rowJabatan['pembuat_laporan'];
                            $kepalaSekolah = $rowJabatan['kepala_sekolah'];
                            $nipBendahara = $rowJabatan['nip_bendahara_sekolah'];
                            $nipPembuatLaporan = $rowJabatan['nip_pembuat_laporan'];
                            $nipKepalaSekolah = $rowJabatan['nip_kepala_sekolah'];

                            ?>
                            
                        </div>
                    </div>
                    <div style="text-align: center;" class="sb-sidenav-footer">
                        <form method="post" action="pdf_lap_siswa.php" target="_blank">
                            <input type="hidden" name="idTahunAjar" value="<?= $idTahunAjar; ?>">
                            <input type="hidden" name="tahunAjar" value="<?= $tahunAjarLap; ?>">
                            <input type="hidden" name="bulan" value="<?=$bulanLalu; ?>">
                            <input type="hidden" name="idKategori" value="<?=$idKategoriLap; ?>">
                            <input type="hidden" name="queryTransaksiSiswa" value="<?=$queryTransaksiSiswa; ?>">
                            <input type="hidden" name="saldoBulanLalu" value="<?=$saldoBulanLalu; ?>">
                            <button type="submit" class="btn btn-primary" name="btnCetakLaporanSiswa" id="btnCetakLaporanSiswa">Cetak</button>  
                        </form>                      
                    </div><br>                   
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
