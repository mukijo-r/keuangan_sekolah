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
        <style>
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
                        <h3 class="mt-4">Laporan Keuangan Konsolidasi</h3>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Konsolidasi / Laporan</li>                                     
                            
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
                                        <button type="submit" class="btn btn-primary" name="btnTampilLapCashflow" id="btnTampilLapCashflow">
                                            Tampilkan
                                        </button>
                                    </div>            
                                </div>
                            </form> 
                        </div>
                    </div> 
                    <div class="container-fluid px-4">
                    <?php
                    // Tampilkan Laporan Umum
                    if(isset($_POST['btnTampilLapCashflow'])){
                        $tahunAjarLap = $_POST['tahunAjar'];
                        $bulanLalu = $_POST['bulan'];
                    } 
                    ?>
 
                    </div>
                    <div class="row" style="text-align: center; border:none">
                        <div class="col-md-3" style="text-align: right; border:none">
                        </div>
                        <div class="col-md-6">
                            <h5>Laporan Keuangan Konsolidasi</h5>
                            <h5>Bulan <?= $bulanLalu ;?></h5>
                            <h5>Tahun Ajar <?=$tahunAjarLap; ?></h5>
                            <?php
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
                    </div><br><br>                    
                    <div class="card-body">
                        <table id="datatablesSimple" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>    
                                    <th>Macam Keuangan</th>
                                    <th>Saldo Awal</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Saldo Akhir</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $debetCashflow = 0;
                                $kreditCashflow = 0;
                                $totalMasukCashflow = 0;
                                $totalKeluarCashflow = 0;
                                
                                $queryDebetCashflow = mysqli_query($conn, "SELECT SUM(jumlah) AS debet FROM transaksi_masuk_cashflow WHERE bulan = '$bulanLalu' AND id_tahun_ajar = $idTahunAjar");
                                $queryKreditCashflow = mysqli_query($conn, "SELECT SUM(jumlah) AS kredit FROM transaksi_keluar_cashflow WHERE bulan = '$bulanLalu' AND id_tahun_ajar = $idTahunAjar");
                                $queryMasukCashflow = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_cashflow WHERE tanggal <= '$tanggalAkhir2'");
                                $queryKeluarCashflow = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_cashflow WHERE tanggal <= '$tanggalAkhir2'");                                

                                if ($rowDebetCashflow = mysqli_fetch_assoc($queryDebetCashflow)) {
                                    $debetCashflow = $rowDebetCashflow['debet'];
                                }

                                if ($rowKreditCashflow = mysqli_fetch_assoc($queryKreditCashflow)) {
                                    $kreditCashflow = $rowKreditCashflow['kredit'];
                                }

                                if ($rowMasukCashflow = mysqli_fetch_assoc($queryMasukCashflow)) {
                                    $totalMasukCashflow = $rowMasukCashflow['total_masuk'];
                                }

                                if ($rowKeluarCashflow = mysqli_fetch_assoc($queryKeluarCashflow)) {
                                    $totalKeluarCashflow = $rowKeluarCashflow['total_keluar'];
                                }

                                $saldoAkhirCashflow = $totalMasukCashflow - $totalKeluarCashflow;
                                $selisihDebetKreditCashflow = $debetCashflow - $kreditCashflow;
                                $saldoAwalCashflow = $saldoAkhirCashflow - $selisihDebetKreditCashflow;

                                $queryKeteranganCf = mysqli_query($conn, "SELECT `keterangan` FROM `kategori` WHERE `nama_kategori` = 'Cash Flow'");

                                if ($rowKeteranganCf = mysqli_fetch_assoc($queryKeteranganCf)) {
                                    $keteranganCf = $rowKeteranganCf['keterangan'];
                                }                              

                                
                                ?>
                                <tr>
                                    <td style="width: 10%">1</td>
                                    <td style="width: 30%">Cash Flow</td>
                                    <td style="width: 10%"><?php echo ($saldoAwalCashflow == 0) ? '' : "Rp " . number_format($saldoAwalCashflow, 0, ',', '.');?></td>
                                    <td style="width: 10%"><?php echo ($debetCashflow == 0) ? '' : "Rp " . number_format($debetCashflow, 0, ',', '.');?></td>
                                    <td style="width: 10%"><?php echo ($kreditCashflow == 0) ? '' : "Rp " . number_format($kreditCashflow, 0, ',', '.');?></td>
                                    <td style="width: 10%"><?php echo ($saldoAkhirCashflow == 0) ? '' : "Rp " . number_format($saldoAkhirCashflow, 0, ',', '.');?></td>
                                    <td style="width: 20%"><?=$keteranganCf;?></td>
                                </tr>

                                <?php 

                                $queryKasYs = "SELECT
                                k.id_kategori,
                                k.nama_kategori,
                                SUM(COALESCE(masuk.total_masuk, 0)) AS total_masuk,
                                SUM(COALESCE(keluar.total_keluar, 0)) AS total_keluar
                                FROM kategori k
                                LEFT JOIN
                                    (
                                        SELECT
                                            tm.id_kategori,
                                            SUM(tm.jumlah) AS total_masuk
                                        FROM transaksi_masuk_siswa tm
                                        JOIN tahun_ajar ta ON tm.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tm.id_kategori <> 1
                                            AND ta.id_tahun_ajar = $idTahunAjar
                                            AND tm.bulan = '$bulanLalu'
                                        GROUP BY tm.id_kategori
                                        UNION ALL
                                        SELECT
                                            tn.id_kategori,
                                            SUM(tn.jumlah) AS total_masuk
                                        FROM transaksi_masuk_nonsiswa tn
                                        JOIN tahun_ajar ta ON tn.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tn.id_kategori <> 1
                                            AND ta.id_tahun_ajar = $idTahunAjar
                                            AND tn.bulan = '$bulanLalu'
                                        GROUP BY tn.id_kategori
                                        UNION ALL
                                        SELECT
                                            tbm.id_kategori,
                                            SUM(tbm.jumlah) AS total_masuk
                                        FROM tabung_masuk tbm
                                        JOIN tahun_ajar ta ON tbm.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tbm.id_kategori <> 1
                                            AND ta.id_tahun_ajar = $idTahunAjar
                                            AND tbm.bulan = '$bulanLalu'
                                        GROUP BY tbm.id_kategori
                                    ) AS masuk
                                ON k.id_kategori = masuk.id_kategori
                                LEFT JOIN
                                    (
                                        SELECT
                                            tks.id_kategori,
                                            SUM(tks.jumlah) AS total_keluar
                                        FROM transaksi_keluar_siswa tks
                                        JOIN tahun_ajar ta ON tks.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tks.id_kategori <> 1
                                            AND ta.id_tahun_ajar = $idTahunAjar
                                            AND tks.bulan = '$bulanLalu'                                            
                                        GROUP BY tks.id_kategori
                                        UNION ALL
                                        SELECT
                                            tkn.id_kategori,
                                            SUM(tkn.jumlah) AS total_keluar
                                        FROM transaksi_keluar_nonsiswa tkn
                                        JOIN tahun_ajar ta ON tkn.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tkn.id_kategori <> 1
                                            AND ta.id_tahun_ajar = $idTahunAjar
                                            AND tkn.bulan = '$bulanLalu'
                                        GROUP BY tkn.id_kategori
                                        UNION ALL
                                        SELECT
                                            tba.id_kategori,
                                            SUM(tba.jumlah) AS total_keluar
                                        FROM tabung_ambil tba
                                        JOIN tahun_ajar ta ON tba.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tba.id_kategori <> 1
                                            AND ta.id_tahun_ajar = $idTahunAjar
                                        GROUP BY tba.id_kategori
                                    ) AS keluar
                                ON k.id_kategori = keluar.id_kategori
                                WHERE
                                    k.kode = 'ys'
                                GROUP BY
                                    k.id_kategori, k.nama_kategori;
                                ";
                                // Perhitungan kas ys
                                $dataKasYs = mysqli_query($conn, $queryKasYs);
                                $i = 2;
                                $totalSaldoAwalYs = 0;
                                $totalDebetYs = 0;
                                $totalKreditYs = 0;
                                $totalSaldoAkhirYs = 0;
                                while($data=mysqli_fetch_array($dataKasYs)){
                                    $idKategori = $data['id_kategori'];

                                    if ($idKategori == 1) {
                                        continue;
                                    }

                                    $kategori = $data['nama_kategori'];
                                    $debetYs = $data['total_masuk'];
                                    $kreditYs = $data['total_keluar'];
                                    
                                    // Menghitung saldo ys
                                    $querySaldoYs = "SELECT
                                    k.id_kategori,
                                    k.nama_kategori,
                                    (SUM(COALESCE(masuk.total_masuk, 0)) - SUM(COALESCE(keluar.total_keluar, 0))) AS saldo
                                FROM kategori k
                                LEFT JOIN
                                    (
                                        SELECT
                                            tm.id_kategori,
                                            SUM(tm.jumlah) AS total_masuk
                                        FROM transaksi_masuk_siswa tm
                                        JOIN tahun_ajar ta ON tm.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tm.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                        UNION ALL
                                        SELECT
                                            tn.id_kategori,
                                            SUM(tn.jumlah) AS total_masuk
                                        FROM transaksi_masuk_nonsiswa tn
                                        JOIN tahun_ajar ta ON tn.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tn.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                        UNION ALL
                                        SELECT
                                            tbm.id_kategori,
                                            SUM(tbm.jumlah) AS total_masuk
                                        FROM tabung_masuk tbm
                                        JOIN tahun_ajar ta ON tbm.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tbm.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                    ) AS masuk
                                ON k.id_kategori = masuk.id_kategori
                                LEFT JOIN
                                    (
                                        SELECT
                                            tks.id_kategori,
                                            SUM(tks.jumlah) AS total_keluar
                                        FROM transaksi_keluar_siswa tks
                                        JOIN tahun_ajar ta ON tks.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tks.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                        UNION ALL
                                        SELECT
                                            tkn.id_kategori,
                                            SUM(tkn.jumlah) AS total_keluar
                                        FROM transaksi_keluar_nonsiswa tkn
                                        JOIN tahun_ajar ta ON tkn.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tkn.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                        UNION ALL
                                        SELECT
                                            tba.id_kategori,
                                            SUM(tba.jumlah) AS total_keluar
                                        FROM tabung_ambil tba
                                        JOIN tahun_ajar ta ON tba.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tba.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                    ) AS keluar
                                ON k.id_kategori = keluar.id_kategori
                                WHERE
                                    k.kode = 'ys'
                                ";
                                $saldoYs = mysqli_query($conn, $querySaldoYs);

                                if ($rowSaldoYs = mysqli_fetch_assoc($saldoYs)) {
                                    $saldoAkhirYs = $rowSaldoYs['saldo'];
                                }

                                $selisihDebetKreditYs = $debetYs - $kreditYs;
                                $saldoAwalYs = $saldoAkhirYs - $selisihDebetKreditYs;

                                $queryGetKeteranganKasYs = "SELECT `keterangan` FROM `kategori` WHERE `id_kategori` = $idKategori";
                                $queryKeteranganYs = mysqli_query($conn, $queryGetKeteranganKasYs);

                                if ($rowKeteranganYs = mysqli_fetch_assoc($queryKeteranganYs)) {
                                    $keteranganYs = $rowKeteranganYs['keterangan'];
                                } 

                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>                                        
                                        <td><?=$kategori;?></td>
                                        <td><?php echo ($saldoAwalYs == 0) ? '' : "Rp " . number_format($saldoAwalYs, 0, ',', '.');?></td>
                                        <td><?php echo ($debetYs == 0) ? '' : "Rp " . number_format($debetYs, 0, ',', '.');?></td>
                                        <td><?php echo ($kreditYs == 0) ? '' : "Rp " . number_format($kreditYs, 0, ',', '.');?></td>
                                        <td><?php echo ($saldoAkhirYs == 0) ? '' : "Rp " . number_format($saldoAkhirYs, 0, ',', '.');?></td>
                                        <td><?=$keteranganYs;?></td>
                                    </tr>
                                    <?php
                                    $totalSaldoAwalYs += $saldoAwalYs;
                                    $totalDebetYs += $debetYs;
                                    $totalKreditYs += $kreditYs;
                                    $totalSaldoAkhirYs += $saldoAkhirYs;                                    
                                    }                                    
                                    $ysSaldoAwal = $saldoAwalCashflow + $totalSaldoAwalYs;
                                    $ysDebet = $debetCashflow + $totalDebetYs;
                                    $ysKredit = $kreditCashflow + $totalKreditYs;
                                    $ysSaldo = $saldoAkhirCashflow + $totalSaldoAkhirYs;
                                    ?>
                                    <tr>
                                        <td></td>                                        
                                        <td></td>
                                        <td><strong><?php echo ($ysSaldoAwal == 0) ? '' : "Rp " . number_format($ysSaldoAwal, 0, ',', '.');?></strong></td>
                                        <td><strong><?php echo ($ysDebet == 0) ? '' : "Rp " . number_format($ysDebet, 0, ',', '.');?></strong></td>
                                        <td><strong><?php echo ($ysKredit == 0) ? '' : "Rp " . number_format($ysKredit, 0, ',', '.');?></strong></td>
                                        <td><strong><?php echo ($ysSaldo == 0) ? '' : "Rp " . number_format($ysSaldo, 0, ',', '.');?></strong></td>
                                        <td></td>
                                    </tr>

                                <?php
                                $queryKasS = "SELECT
                                k.id_kategori,
                                k.nama_kategori,
                                SUM(COALESCE(masuk.total_masuk, 0)) AS total_masuk,
                                SUM(COALESCE(keluar.total_keluar, 0)) AS total_keluar
                                FROM kategori k
                                LEFT JOIN
                                    (
                                        SELECT
                                            tm.id_kategori,
                                            SUM(tm.jumlah) AS total_masuk
                                        FROM transaksi_masuk_siswa tm
                                        JOIN tahun_ajar ta ON tm.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            ta.id_tahun_ajar = $idTahunAjar
                                            AND tm.bulan = '$bulanLalu'
                                        GROUP BY tm.id_kategori
                                        UNION ALL
                                        SELECT
                                            tn.id_kategori,
                                            SUM(tn.jumlah) AS total_masuk
                                        FROM transaksi_masuk_nonsiswa tn
                                        JOIN tahun_ajar ta ON tn.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            ta.id_tahun_ajar = $idTahunAjar
                                            AND tn.bulan = '$bulanLalu'
                                        GROUP BY tn.id_kategori
                                        UNION ALL
                                        SELECT
                                            tbm.id_kategori,
                                            SUM(tbm.jumlah) AS total_masuk
                                        FROM tabung_masuk tbm
                                        JOIN tahun_ajar ta ON tbm.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            ta.id_tahun_ajar = $idTahunAjar
                                            AND tbm.bulan = '$bulanLalu'
                                        GROUP BY tbm.id_kategori
                                    ) AS masuk
                                ON k.id_kategori = masuk.id_kategori
                                LEFT JOIN
                                    (
                                        SELECT
                                            tks.id_kategori,
                                            SUM(tks.jumlah) AS total_keluar
                                        FROM transaksi_keluar_siswa tks
                                        JOIN tahun_ajar ta ON tks.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            ta.id_tahun_ajar = $idTahunAjar
                                            AND tks.bulan = '$bulanLalu'
                                        GROUP BY tks.id_kategori
                                        UNION ALL
                                        SELECT
                                            tkn.id_kategori,
                                            SUM(tkn.jumlah) AS total_keluar
                                        FROM transaksi_keluar_nonsiswa tkn
                                        JOIN tahun_ajar ta ON tkn.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            ta.id_tahun_ajar = $idTahunAjar
                                            AND tkn.bulan = '$bulanLalu'
                                        GROUP BY tkn.id_kategori
                                        UNION ALL
                                        SELECT
                                            tba.id_kategori,
                                            SUM(tba.jumlah) AS total_keluar
                                        FROM tabung_ambil tba
                                        JOIN tahun_ajar ta ON tba.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            ta.id_tahun_ajar = $idTahunAjar
                                        GROUP BY tba.id_kategori
                                    ) AS keluar
                                ON k.id_kategori = keluar.id_kategori
                                WHERE
                                    k.kode = 's'
                                GROUP BY
                                    k.id_kategori, k.nama_kategori;
                                ";
                                
                                $dataKasS = mysqli_query($conn, $queryKasS);
                                $i = 5;
                                $totalSaldoAwalS = 0;
                                $totalDebetS = 0;
                                $totalKreditS = 0;
                                $totalSaldoAkhirS = 0;
                                while($data=mysqli_fetch_array($dataKasS)){
                                    $idKategori = $data['id_kategori'];

                                    if ($idKategori == 1) {
                                        continue;
                                    }

                                    $kategori = $data['nama_kategori'];
                                    $debetS = $data['total_masuk'];
                                    $kreditS = $data['total_keluar'];
                                    
                                    // Menghitung saldo

                                    $querySaldoS = "SELECT
                                    k.id_kategori,
                                    k.nama_kategori,
                                    (SUM(COALESCE(masuk.total_masuk, 0)) - SUM(COALESCE(keluar.total_keluar, 0))) AS saldo
                                FROM kategori k
                                LEFT JOIN
                                    (
                                        SELECT
                                            tm.id_kategori,
                                            SUM(tm.jumlah) AS total_masuk
                                        FROM transaksi_masuk_siswa tm
                                        JOIN tahun_ajar ta ON tm.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tm.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                        UNION ALL
                                        SELECT
                                            tn.id_kategori,
                                            SUM(tn.jumlah) AS total_masuk
                                        FROM transaksi_masuk_nonsiswa tn
                                        JOIN tahun_ajar ta ON tn.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tn.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                        UNION ALL
                                        SELECT
                                            tbm.id_kategori,
                                            SUM(tbm.jumlah) AS total_masuk
                                        FROM tabung_masuk tbm
                                        JOIN tahun_ajar ta ON tbm.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tbm.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                    ) AS masuk
                                ON k.id_kategori = masuk.id_kategori
                                LEFT JOIN
                                    (
                                        SELECT
                                            tks.id_kategori,
                                            SUM(tks.jumlah) AS total_keluar
                                        FROM transaksi_keluar_siswa tks
                                        JOIN tahun_ajar ta ON tks.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tks.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                        UNION ALL
                                        SELECT
                                            tkn.id_kategori,
                                            SUM(tkn.jumlah) AS total_keluar
                                        FROM transaksi_keluar_nonsiswa tkn
                                        JOIN tahun_ajar ta ON tkn.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tkn.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                        UNION ALL
                                        SELECT
                                            tba.id_kategori,
                                            SUM(tba.jumlah) AS total_keluar
                                        FROM tabung_ambil tba
                                        JOIN tahun_ajar ta ON tba.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tba.id_kategori = $idKategori
                                            AND tanggal <= '$tanggalAkhir2'
                                    ) AS keluar
                                ON k.id_kategori = keluar.id_kategori
                                WHERE
                                    k.kode = 's'
                                ";
                                    $saldoS = mysqli_query($conn, $querySaldoS);

                                if ($rowSaldoS = mysqli_fetch_assoc($saldoS)) {
                                    $saldoAkhirS = $rowSaldoS['saldo'];
                                }

                                $selisihDebetKreditS = $debetS - $kreditS;
                                $saldoAwalS = $saldoAkhirS - $selisihDebetKreditS;

                                $queryGetKeteranganKasS = "SELECT `keterangan` FROM `kategori` WHERE `id_kategori` = $idKategori";
                                $queryKeteranganS = mysqli_query($conn, $queryGetKeteranganKasS);
                                if ($rowKeteranganS = mysqli_fetch_assoc($queryKeteranganS)) {
                                    $keteranganS = $rowKeteranganS['keterangan'];
                                }

                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>                                        
                                        <td><?=$kategori;?></td>
                                        <td><?php echo ($saldoAwalS == 0) ? '' : "Rp " . number_format($saldoAwalS, 0, ',', '.');?></td>
                                        <td><?php echo ($debetS == 0) ? '' : "Rp " . number_format($debetS, 0, ',', '.');?></td>
                                        <td><?php echo ($kreditS == 0) ? '' : "Rp " . number_format($kreditS, 0, ',', '.');?></td>
                                        <td><?php echo ($saldoAkhirS == 0) ? '' : "Rp " . number_format($saldoAkhirS, 0, ',', '.');?></td>
                                        <td><?=$keteranganS;?></td>
                                    </tr>
                                    <?php
                                    $totalSaldoAwalS += $saldoAwalS;
                                    $totalDebetS += $debetS;
                                    $totalKreditS += $kreditS;
                                    $totalSaldoAkhirS += $saldoAkhirS;                                    
                                    }                                    

                                    $konsolidasiSaldoAwal = $ysSaldoAwal + $totalSaldoAwalS;
                                    $konsolidasiDebet = $ysDebet + $totalDebetS;
                                    $konsolidasiKredit = $ysKredit + $totalKreditS;
                                    $konsolidasiSaldo = $ysSaldo + $totalSaldoAkhirS;
                                    ?>
                                    <tr>
                                        <td></td>                                        
                                        <td></td>
                                        <td><strong><?php echo ($konsolidasiSaldoAwal == 0) ? '' : "Rp " . number_format($konsolidasiSaldoAwal, 0, ',', '.');?></strong></td>
                                        <td><strong><?php echo ($konsolidasiDebet == 0) ? '' : "Rp " . number_format($konsolidasiDebet, 0, ',', '.');?></strong></td>
                                        <td><strong><?php echo ($konsolidasiKredit == 0) ? '' : "Rp " . number_format($konsolidasiKredit, 0, ',', '.');?></strong></td>
                                        <td><strong><?php echo ($konsolidasiSaldo == 0) ? '' : "Rp " . number_format($konsolidasiSaldo, 0, ',', '.');?></strong></td>
                                        <td></td>
                                    </tr>
                            <tbody>
                        </table><br>
                        <div class="row" style="text-align: center;"></i>
                            <h5>Daftar Pemegang Kas Keuangan Sekolah</h5>
                        </div><br>

                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Pemegang Kas</th>    
                                        <th>Macam Keuangan</th>
                                        <th>Jumlah Total</th>                                            
                                        <th>Tunai</th>
                                        <th>Dipinjam</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php

                                $queryGuru = mysqli_query($conn, "SELECT
                                kat.nama_kategori,
                                g.nama_lengkap AS nama_guru 
                                FROM kategori kat
                                LEFT JOIN guru g ON kat.id_guru = g.id_guru
                                WHERE nama_kategori = 'Cash Flow';");

                                $rowGuru = mysqli_fetch_assoc($queryGuru);
                                $namaGuru =  $rowGuru['nama_guru'];

                                $queryPinjamCf = "SELECT 
                                SUM(`jumlah`) AS total_jumlah, 
                                (SELECT 
                                `keterangan`
                                FROM 
                                `pinjam`
                                WHERE 
                                `tanggal` <= '$tanggalAkhir2' 
                                AND `id_kategori` = 1
                                ORDER BY tanggal DESC limit 1) AS keterangan_terakhir 
                                FROM 
                                `pinjam` 
                                WHERE 
                                `tanggal` <= '$tanggalAkhir2' 
                                AND `id_kategori` = 1;";                                
                                
                                $pinjamCf = mysqli_query($conn, $queryPinjamCf);
                                $rowPinjamCf = mysqli_fetch_assoc($pinjamCf);
                                $jumlahPinjamCf = $rowPinjamCf['total_jumlah'];
                                $keteranganPinjamCf = $rowPinjamCf['keterangan_terakhir'];                                

                                $tunaiKasCf = $saldoAkhirCashflow - $jumlahPinjamCf;

                                //echo $queryPinjamCf;

                                ?>                                
                                    <tr>
                                        <td>1</td>                                        
                                        <td><?=$namaGuru;?></td>
                                        <td>Cash Flow</td>
                                        <td><?php echo ($saldoAkhirCashflow == 0) ? '' : "Rp " . number_format($saldoAkhirCashflow, 0, ',', '.');?></td>
                                        <td><?php echo ($tunaiKasCf == 0) ? '' : "Rp " . number_format($tunaiKasCf, 0, ',', '.');?></td>
                                        <td><?php echo ($jumlahPinjamCf == 0) ? '' : "Rp " . number_format($jumlahPinjamCf, 0, ',', '.');?></td>
                                        <td><?=$keteranganPinjamCf;?></td>
                                    </tr>
                                                                    
                                    <?php 

                                    $queryPemegang = "SELECT
                                    k.id_kategori,
                                    k.nama_kategori,
                                    g.nama_lengkap,
                                    SUM(COALESCE(masuk.total_masuk, 0)) AS total_masuk,
                                    SUM(COALESCE(keluar.total_keluar, 0)) AS total_keluar
                                    FROM kategori k
                                    LEFT JOIN
                                        (
                                            SELECT
                                                tm.id_kategori,
                                                SUM(tm.jumlah) AS total_masuk
                                            FROM transaksi_masuk_siswa tm
                                            JOIN tahun_ajar ta ON tm.id_tahun_ajar = ta.id_tahun_ajar
                                            WHERE
                                                tanggal <= '$tanggalAkhir2'
                                            GROUP BY tm.id_kategori
                                            UNION ALL
                                            SELECT
                                                tn.id_kategori,
                                                SUM(tn.jumlah) AS total_masuk
                                            FROM transaksi_masuk_nonsiswa tn
                                            JOIN tahun_ajar ta ON tn.id_tahun_ajar = ta.id_tahun_ajar
                                            WHERE
                                                tanggal <= '$tanggalAkhir2'
                                            GROUP BY tn.id_kategori
                                            UNION ALL
                                            SELECT
                                                tbm.id_kategori,
                                                SUM(tbm.jumlah) AS total_masuk
                                            FROM tabung_masuk tbm
                                            JOIN tahun_ajar ta ON tbm.id_tahun_ajar = ta.id_tahun_ajar
                                            WHERE
                                                tanggal <= '$tanggalAkhir2'
                                            GROUP BY tbm.id_kategori
                                        ) AS masuk
                                    ON k.id_kategori = masuk.id_kategori
                                    LEFT JOIN
                                        (
                                            SELECT
                                                tks.id_kategori,
                                                SUM(tks.jumlah) AS total_keluar
                                            FROM transaksi_keluar_siswa tks
                                            JOIN tahun_ajar ta ON tks.id_tahun_ajar = ta.id_tahun_ajar
                                            WHERE
                                                tanggal <= '$tanggalAkhir2'
                                            GROUP BY tks.id_kategori
                                            UNION ALL
                                            SELECT
                                                tkn.id_kategori,
                                                SUM(tkn.jumlah) AS total_keluar
                                            FROM transaksi_keluar_nonsiswa tkn
                                            JOIN tahun_ajar ta ON tkn.id_tahun_ajar = ta.id_tahun_ajar
                                            WHERE
                                                tanggal <= '$tanggalAkhir2'
                                            GROUP BY tkn.id_kategori
                                            UNION ALL
                                            SELECT
                                                tba.id_kategori,
                                                SUM(tba.jumlah) AS total_keluar
                                            FROM tabung_ambil tba
                                            JOIN tahun_ajar ta ON tba.id_tahun_ajar = ta.id_tahun_ajar
                                            WHERE
                                                tanggal <= '$tanggalAkhir2'
                                            GROUP BY tba.id_kategori
                                        ) AS keluar
                                    ON k.id_kategori = keluar.id_kategori
                                    LEFT JOIN guru g ON k.id_guru = g.id_guru
                                    GROUP BY
                                        k.id_kategori, k.nama_kategori, g.nama_lengkap
                                    ;";

                                $pemegangKas = mysqli_query($conn, $queryPemegang);
                                $i = 2;
                                $tunaiKas = 0;

                                while($data=mysqli_fetch_array($pemegangKas)){
                                    $idKategori = $data['id_kategori'];

                                    if ($idKategori == 1) {
                                        continue;
                                    }

                                    $kategori = $data['nama_kategori'];
                                    $nama = $data['nama_lengkap'];
                                    $kasMasuk = $data['total_masuk'];
                                    $kasKeluar = $data['total_keluar'];

                                    $queryPinjamKas = "SELECT 
                                    SUM(`jumlah`) AS total_jumlah, 
                                    (SELECT 
                                    `keterangan`
                                    FROM 
                                    `pinjam`
                                    WHERE 
                                    `tanggal` <= '$tanggalAkhir2' 
                                    AND `id_kategori` = $idKategori
                                    ORDER BY tanggal DESC limit 1) AS keterangan_terakhir 
                                    FROM 
                                    `pinjam` 
                                    WHERE 
                                    `tanggal` <= '$tanggalAkhir2' 
                                    AND `id_kategori` = $idKategori;";

                                    $pinjamKas = mysqli_query($conn, $queryPinjamKas);
                                    $rowPinjamKas = mysqli_fetch_assoc($pinjamKas);
                                    $jumlahPinjamKas = $rowPinjamKas['total_jumlah'];
                                    $keteranganKas = $rowPinjamKas['keterangan_terakhir'];                                  
 
                                    $jumlahKas = $kasMasuk - $kasKeluar;
                                    $tunaiKas = $jumlahKas - $jumlahPinjamKas;

                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>                                        
                                        <td><?=$nama;?></td>
                                        <td><?=$kategori;?></td>
                                        <td><?php echo ($jumlahKas == 0) ? '' : "Rp " . number_format($jumlahKas, 0, ',', '.');?></td>
                                        <td><?php echo ($tunaiKas == 0) ? '' : "Rp " . number_format($tunaiKas, 0, ',', '.');?></td>
                                        <td><?php echo ($jumlahPinjamKas == 0) ? '' : "Rp " . number_format($jumlahPinjamKas, 0, ',', '.');?></td>
                                        <td><?=$keteranganKas;?></td>
                                    </tr>                                   

                                    <?php 
                                }
                                ?>
                                <tbody>
                        </table><br><br>

                    <div style="text-align: center;" class="sb-sidenav-footer">
                        <form method="post" action="pdf_konsolidasi.php" target="_blank">
                            <input type="hidden" name="idTahunAjar" value="<?= $idTahunAjar; ?>">
                            <input type="hidden" name="tahunAjar" value="<?= $tahunAjarLap; ?>">
                            <input type="hidden" name="bulan" value="<?=$bulanLalu; ?>">
                            <input type="hidden" name="tanggalAkhir2" value="<?=$tanggalAkhir2; ?>">                            
                            <input type="hidden" name="saldoAwalCashflow" value="<?=$saldoAwalCashflow; ?>">
                            <input type="hidden" name="debetCashflow" value="<?=$debetCashflow; ?>">
                            <input type="hidden" name="kreditCashflow" value="<?=$kreditCashflow; ?>">
                            <input type="hidden" name="saldoAkhirCashflow" value="<?=$saldoAkhirCashflow; ?>">
                            <input type="hidden" name="queryKasYs" value="<?=$queryKasYs; ?>">
                            <input type="hidden" name="queryKasS" value="<?=$queryKasS; ?>">
                            <input type="hidden" name="tunaiKasCf" value="<?=$tunaiKasCf; ?>">
                            <input type="hidden" name="namaGuru" value="<?=$namaGuru; ?>">
                            <input type="hidden" name="jumlahPinjamCf" value="<?=$jumlahPinjamCf;?>">
                            <input type="hidden" name="keteranganCf" value="<?=$keteranganCf; ?>">
                            <input type="hidden" name="keteranganPinjamCf" value="<?=$keteranganPinjamCf; ?>">
                            <input type="hidden" name="queryPemegang" value="<?=$queryPemegang; ?>">
                            <button type="submit" class="btn btn-primary" name="btnCetakLapKonsol" id="btnCetakLapKonsol">Cetak</button>  
                        </form>                      
                    </div><br>
                                
                </main>
            </div>
        </div>
    </body>
</html>

