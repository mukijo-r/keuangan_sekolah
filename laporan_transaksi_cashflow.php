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
                        <h3 class="mt-4">Laporan Keuangan Cash Flow</h3>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Cash Flow / Laporan</li>
                                      
                            
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
                            <h5>Laporan Keuangan Cash Flow </h5>
                            <h5>Bulan <?= $bulanLalu;?> </h5>
                            <h5>Tahun Ajar <?=$tahunAjarLap; ?> </h5>
                            <?php
                            $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar='$tahunAjarLap'");
                            $rowTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
                            $idTahunAjar = $rowTahunAjar['id_tahun_ajar']; 
                            ?> 
                        </div>      
                    </div><br><br>
                    <div style="display: flex; justify-content: space-between;">
                        <!-- Tabel Pendapatan -->
                        <div style="flex-basis: 48%;">
                            <?php
                            // Ambil data dari tabel group_cashflow
                            $queryGroupCashflow = mysqli_query($conn, "SELECT * FROM group_cashflow WHERE jenis='Pendapatan'");
                            $groupCashflowData = array();

                            while ($row = mysqli_fetch_assoc($queryGroupCashflow)) {
                                $groupCashflowData[$row['id_group_cashflow']] = $row;
                            }

                            // Ambil data dari tabel sub_kategori_cashflow
                            $querySubKategoriCashflow = mysqli_query($conn, "SELECT * FROM sub_kategori_cashflow");
                            $subKategoriCashflowData = array();

                            while ($row = mysqli_fetch_assoc($querySubKategoriCashflow)) {
                                $subKategoriCashflowData[$row['id_subkategori_cashflow']] = $row;
                            }

                            // Inisialisasi nomor unik
                            $nomorGroup = 1;
                            ?>

                            <!-- Buat tabel HTML -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Rekening</th>
                                        <th>Jumlah</th> <!-- Kolom Jumlah yang ditambahkan -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td><strong>Pendapatan<strong></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    // Inisialisasi nomor unik
                                    $nomorGroup = 1;

                                    foreach ($groupCashflowData as $groupId => $group) {
                                        echo '<tr>';
                                        echo '<td>' . $nomorGroup . '</td>';
                                        echo '<td><strong>' . $group['groop'] . '</strong></td>';
                                        echo '<td></td>';
                                        echo '</tr>';
                                    
                                        $nomorSubKategori = 1;
                                        foreach ($subKategoriCashflowData as $subKategoriId => $subKategori) {
                                            if ($subKategori['id_group_cashflow'] == $groupId) {
                                                // Query SQL untuk mengambil nilai jumlah dari transaksi_masuk_cashflow
                                                $idSubkategoriCashflow = $subKategoriId;
                                                $queryJumlah = mysqli_query($conn, "SELECT SUM(jumlah) AS total_jumlah FROM transaksi_masuk_cashflow WHERE id_tahun_ajar='$idTahunAjar' AND bulan='$bulanLalu' AND id_subkategori_cashflow = $idSubkategoriCashflow");
                                                $jumlahData = mysqli_fetch_assoc($queryJumlah);
                                                $totalJumlah = $jumlahData['total_jumlah'];
                                    
                                                // Periksa apakah total jumlah tidak sama dengan nol sebelum menampilkan baris sub kategori
                                                if ($totalJumlah != 0) {
                                                    echo '<tr>';
                                                    echo '<td>' . $nomorGroup . '.' . $nomorSubKategori . '</td>';
                                                    echo '<td>' . $subKategori['nama_sub_kategori'] . '</td>';
                                                    echo '<td>' . "Rp " . number_format($totalJumlah, 0, ',', '.') . '</td>';
                                                    echo '</tr>';
                                                    $nomorSubKategori++;
                                                }
                                            }
                                        }
                                    
                                        $nomorGroup++;
                                    }  
                                    
                                    // Menghitung jumlah penerimaan 
                                    $queryMasukBulan = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_cashflow WHERE id_tahun_ajar='$idTahunAjar' AND bulan = '$bulanLalu'");
                                    $queryKeluarBulan = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_cashflow WHERE id_tahun_ajar='$idTahunAjar' AND bulan = '$bulanLalu'");

                                    $totalMasukBulan = 0;
                                    $totalKeluarBulan = 0;

                                    if ($rowMasuk = mysqli_fetch_assoc($queryMasukBulan)) {
                                        $totalMasukBulan = $rowMasuk['total_masuk'];
                                    }

                                    if ($rowKeluar = mysqli_fetch_assoc($queryKeluarBulan)) {
                                        $totalKeluarBulan = $rowKeluar['total_keluar'];
                                    }

                                    $laba = $totalMasukBulan - $totalKeluarBulan;


                                    echo '<tr><td></td><td></td><td></td></tr>';

                                    echo '<tr>';
                                    echo '<td></td>';
                                    echo '<td><strong>Jumlah Penerimaan</strong></td>';
                                    echo '<td><strong>' . "Rp " . number_format($totalMasukBulan, 0, ',', '.') . '</strong></td>';
                                    echo '</tr>';
                                    
                                    ?>
                                </tbody>
                            </table><br>
                            
                        </div>

                        <!-- Tabel Pengeluaran -->
                        <div style="flex-basis: 48%;">
                            <?php
                            // Ambil data dari tabel group_cashflow
                            $queryGroupCashflow2 = mysqli_query($conn, "SELECT * FROM group_cashflow WHERE jenis='Pengeluaran'");
                            $groupCashflowData2 = array();

                            while ($row = mysqli_fetch_assoc($queryGroupCashflow2)) {
                                $groupCashflowData2[$row['id_group_cashflow']] = $row;
                            }

                            // Ambil data dari tabel sub_kategori_cashflow
                            $querySubKategoriCashflow2 = mysqli_query($conn, "SELECT * FROM sub_kategori_cashflow");
                            $subKategoriCashflowData2 = array();

                            while ($row = mysqli_fetch_assoc($querySubKategoriCashflow2)) {
                                $subKategoriCashflowData2[$row['id_subkategori_cashflow']] = $row;
                            }

                            // Inisialisasi nomor unik
                            $nomorGroup = 1;
                            ?>

                            <!-- Buat tabel HTML -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nomor</th>
                                        <th>Rekening</th>
                                        <th>Jumlah</th> <!-- Kolom Jumlah yang ditambahkan -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td><strong>Pengeluaran<strong></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    // Inisialisasi nomor unik
                                    $nomorGroup = 1;

                                    foreach ($groupCashflowData2 as $groupId2 => $group2) {
                                        echo '<tr>';
                                        echo '<td>' . $nomorGroup . '</td>';
                                        echo '<td><strong>' . $group2['groop'] . '</strong></td>';
                                        echo '<td></td>';
                                        echo '</tr>';
                                    
                                        $nomorSubKategori = 1;
                                        foreach ($subKategoriCashflowData2 as $subKategoriId2 => $subKategori2) {
                                            if ($subKategori2['id_group_cashflow'] == $groupId2) {
                                                // Query SQL untuk mengambil nilai jumlah dari transaksi_keluar_cashflow
                                                $idSubkategoriCashflow2= $subKategoriId2;
                                                $queryJumlah2 = mysqli_query($conn, "SELECT SUM(jumlah) AS total_jumlah FROM transaksi_keluar_cashflow WHERE id_tahun_ajar='$idTahunAjar' AND bulan='$bulanLalu' AND id_subkategori_cashflow = $idSubkategoriCashflow2");
                                                $jumlahData2 = mysqli_fetch_assoc($queryJumlah2);
                                                $totalJumlah2 = $jumlahData2['total_jumlah'];
                                    
                                                // Periksa apakah total jumlah tidak sama dengan nol sebelum menampilkan baris sub kategori
                                                if ($totalJumlah2 != 0) {
                                                    echo '<tr>';
                                                    echo '<td>' . $nomorGroup . '.' . $nomorSubKategori . '</td>';
                                                    echo '<td>' . $subKategori2['nama_sub_kategori'] . '</td>';
                                                    echo '<td>' . "Rp " . number_format($totalJumlah2, 0, ',', '.') . '</td>';
                                                    echo '</tr>';
                                                    $nomorSubKategori++;
                                                }
                                            }
                                        }
                                    
                                        $nomorGroup++;
                                    }  
                                    
                                    // Menghitung saldo 
                                    $queryMasuk = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_cashflow");
                                    $queryKeluar = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_cashflow");
                                    
                                    $totalMasuk = 0;
                                    $totalKeluar = 0;

                                    if ($rowMasuk = mysqli_fetch_assoc($queryMasuk)) {
                                        $totalMasuk = $rowMasuk['total_masuk'];
                                    }

                                    if ($rowKeluar = mysqli_fetch_assoc($queryKeluar)) {
                                        $totalKeluar = $rowKeluar['total_keluar'];
                                    }

                                    $saldo = $totalMasuk - $totalKeluar;
                                    $saldoBulanLalu = $saldo - $laba;


                                    echo '<tr><td></td><td></td><td></td></tr>';

                                    echo '<tr>';
                                    echo '<td></td>';
                                    echo '<td><strong>Jumlah Pengeluaran</strong></td>';
                                    echo '<td><strong>' . "Rp " . number_format($totalKeluarBulan, 0, ',', '.') . '</strong></td>';
                                    echo '</tr>';
                                    
                                    ?>
                                </tbody>
                            </table><br>
                            
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <!-- Tabel Rekap Pendapatan -->
                        <div style="flex-basis: 48%;">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="width: 20%">I</td>
                                        <td style="width: 30%">Jumlah Pendapatan bulan ini</td>
                                        <td style="width: 30%"><strong><?= "Rp " . number_format($totalMasukBulan, 0, ',', '.'); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%"></td>
                                        <td style="width: 30%">Jumlah Pengeluaran bulan ini</td>
                                        <td style="width: 30%"><strong><?= "Rp " . number_format($totalKeluarBulan, 0, ',', '.'); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%"></td>
                                        <td style="width: 30%">Laba / Rugi bulan ini</td>
                                        <td style="width: 30%"><strong><?= "Rp " . number_format($laba, 0, ',', '.'); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tabel Rekap Pengeluaran -->
                        <div style="flex-basis: 48%;">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="width: 20%">II</td>
                                        <td style="width: 30%">Saldo Kas bulan lalu</td>
                                        <td style="width: 30%"><strong><?= "Rp " . number_format($saldoBulanLalu, 0, ',', '.'); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%"></td>
                                        <td style="width: 30%">Laba / Rugi bulan ini</td>
                                        <td style="width: 30%"><strong><?= "Rp " . number_format($laba, 0, ',', '.'); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 20%"></td>
                                        <td style="width: 30%">Saldo kas bulan ini</td>
                                        <td style="width: 30%"><strong><?= "Rp " . number_format($saldo, 0, ',', '.'); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

    
                


            
        </div>

                


  


        </main>
    </body>
</html>