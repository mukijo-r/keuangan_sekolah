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
                            <h5>Bulan <?= $bulanLalu . ' ' . date("Y");?> </h5>
                            <h5>Tahun Ajar <?=$tahunAjarLap; ?> </h5>
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
                                
                                $queryDebetCashflow = mysqli_query($conn, "SELECT SUM(jumlah) AS debet FROM transaksi_masuk_cashflow WHERE bulan = '$bulanLalu' AND id_tahun_ajar = $idTahunAjar");
                                $queryKreditCashflow = mysqli_query($conn, "SELECT SUM(jumlah) AS kredit FROM transaksi_keluar_cashflow WHERE bulan = '$bulanLalu' AND id_tahun_ajar = $idTahunAjar");
                                $queryMasukCashflow = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_cashflow WHERE tanggal <= '$tanggalAkhir'");
                                $queryKeluarCashflow = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_cashflow WHERE tanggal <= '$tanggalAkhir'");

                                $debetCashflow = 0;
                                $kreditCashflow = 0;
                                $totalMasukCashflow = 0;
                                $totalKeluarCashflow = 0;

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

                                $saldoCashflow = $totalMasukCashflow - $totalKeluarCashflow;
                                $selisihDebetKredit = $debetCashflow - $kreditCashflow;
                                $saldoBulanLalu = $saldoCashflow - $selisihDebetKredit;
                                
                                ?>
                                <tr>
                                    <td style="width: 10%">1</td>
                                    <td style="width: 30%">Cash Flow</td>
                                    <td style="width: 10%"><?php echo ($saldoBulanLalu == 0) ? '' : "Rp " . number_format($saldoBulanLalu, 0, ',', '.');?></td>
                                    <td style="width: 10%"><?php echo ($debetCashflow == 0) ? '' : "Rp " . number_format($debetCashflow, 0, ',', '.');?></td>
                                    <td style="width: 10%"><?php echo ($kreditCashflow == 0) ? '' : "Rp " . number_format($kreditCashflow, 0, ',', '.');?></td>
                                    <td style="width: 10%"><?php echo ($saldoCashflow == 0) ? '' : "Rp " . number_format($saldoCashflow, 0, ',', '.');?></td>
                                    <td style="width: 20%"></td>
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
                                            AND tba.bulan = '$bulanLalu'
                                        GROUP BY tba.id_kategori
                                    ) AS keluar
                                ON k.id_kategori = keluar.id_kategori
                                WHERE
                                    k.kode = 'ys'
                                GROUP BY
                                    k.id_kategori, k.nama_kategori;
                                ";

                                $dataKasYs = mysqli_query($conn, $queryKasYs);
                                $i = 2;
                                while($data=mysqli_fetch_array($dataKasYs)){
                                    $idKategori = $data['id_kategori'];

                                    if ($idKategori == 1) {
                                        continue;
                                    }

                                    $kategori = $data['nama_kategori'];
                                    $debet = $data['total_masuk'];
                                    $kredit = $data['total_keluar'];
                                    
                                    // Menghitung saldo
                                    $querySaldo = mysqli_query($conn, "SELECT
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
                                        UNION ALL
                                        SELECT
                                            tn.id_kategori,
                                            SUM(tn.jumlah) AS total_masuk
                                        FROM transaksi_masuk_nonsiswa tn
                                        JOIN tahun_ajar ta ON tn.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tn.id_kategori = $idKategori
                                        UNION ALL
                                        SELECT
                                            tbm.id_kategori,
                                            SUM(tbm.jumlah) AS total_masuk
                                        FROM tabung_masuk tbm
                                        JOIN tahun_ajar ta ON tbm.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tbm.id_kategori = $idKategori
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
                                        UNION ALL
                                        SELECT
                                            tkn.id_kategori,
                                            SUM(tkn.jumlah) AS total_keluar
                                        FROM transaksi_keluar_nonsiswa tkn
                                        JOIN tahun_ajar ta ON tkn.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tkn.id_kategori = $idKategori
                                        UNION ALL
                                        SELECT
                                            tba.id_kategori,
                                            SUM(tba.jumlah) AS total_keluar
                                        FROM tabung_ambil tba
                                        JOIN tahun_ajar ta ON tba.id_tahun_ajar = ta.id_tahun_ajar
                                        WHERE
                                            tba.id_kategori = $idKategori
                                    ) AS keluar
                                ON k.id_kategori = keluar.id_kategori
                                WHERE
                                    k.kode = 'ys'
                                ");

                                if ($rowSaldo = mysqli_fetch_assoc($querySaldo)) {
                                    $saldoAkhir = $rowSaldo['saldo'];
                                }

                                $selisihDebetKredit = $debet - $kredit;
                                $saldoAwal = $saldoAkhir - $selisihDebetKredit;

                                    ?>
                                    <tr>
                                        <td><?=$i++;?></td>                                        
                                        <td><?=$kategori;?></td>
                                        <td><?php echo ($saldoAwal == 0) ? '' : "Rp " . number_format($saldoAwal, 0, ',', '.');?></td>
                                        <td><?php echo ($debet == 0) ? '' : "Rp " . number_format($debet, 0, ',', '.');?></td>
                                        <td><?php echo ($kredit == 0) ? '' : "Rp " . number_format($kredit, 0, ',', '.');?></td>
                                        <td><?php echo ($saldoAkhir == 0) ? '' : "Rp " . number_format($saldoAkhir, 0, ',', '.');?></td>
                                        <td></td>
                                    </tr>
                                    <?php 
                                    }
                                    ?>

                                
                            
                            
                            


        

                            <tbody>
                        </table>

                                
                </main>
            </div>
        </div>
    </body>
</html>