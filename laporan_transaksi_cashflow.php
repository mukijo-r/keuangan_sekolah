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
                                        <button type="submit" class="btn btn-primary" name="btnTampilLapUmum" id="btnTampilLapUmum">
                                            Tampilkan
                                        </button>
                                    </div>            
                                </div>
                            </form> 
                        </div>
                    </div> 
                    <div class="container-fluid px-4">
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
                <th>Group / Sub Kategori</th>
                <th>Jumlah</th> <!-- Kolom Jumlah yang ditambahkan -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Inisialisasi nomor unik
            $nomorGroup = 1;

            foreach ($groupCashflowData as $groupId => $group) {
                echo '<tr>';
                echo '<td>' . $nomorGroup . '</td>';
                echo '<td><strong>' . $group['groop'] . '</strong></td>'; // Cetak 'groop' dengan tebal
                echo '<td></td>'; // Kolom Jumlah pada group tetap kosong
                echo '</tr>';

                $nomorSubKategori = 1;
                foreach ($subKategoriCashflowData as $subKategoriId => $subKategori) {
                    if ($subKategori['id_group_cashflow'] == $groupId) {
                        echo '<tr>';
                        echo '<td>' . $nomorGroup . '.' . $nomorSubKategori . '</td>';
                        echo '<td>' . $subKategori['nama_sub_kategori'] . '</td>';

                        // Query SQL untuk mengambil nilai jumlah dari transaksi_masuk_siswa
                        $idSubkategoriCashflow = $subKategoriId;
                        $queryJumlah = mysqli_query($conn, "SELECT SUM(jumlah) AS total_jumlah FROM transaksi_masuk_cashflow WHERE id_subkategori_cashflow = $idSubkategoriCashflow");
                        $jumlahData = mysqli_fetch_assoc($queryJumlah);
                        $totalJumlah = $jumlahData['total_jumlah'];
                        ?>
                        <td><?="Rp " . number_format($totalJumlah, 0, ',', '.');?></td>
                        <?php
                        echo '</tr>';                        
                        $nomorSubKategori++;
                    }
                }

                $nomorGroup++;
            }
            ?>
        </tbody>
    </table>
</div>

                


  


        </main>
    </body>
</html>