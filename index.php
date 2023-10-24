<?php
require 'function.php';
require 'cek.php';
require 'config.php';

//session_start();

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
} else {
    // Pengguna tidak masuk. Lakukan sesuatu, seperti mengarahkan mereka kembali ke halaman login.
    header('location: login.php');
}

// Tambahkan pemeriksaan tambahan di sini, misalnya untuk nama pengguna sebelumnya
if (isset($_SESSION['previous_user'])) {
    $previousUsername = $_SESSION['previous_user'];
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - Manajemen Keuangan</title>
        <style>
            .with-background {
                background-image: url('assets/img/welcome2.jpg'); /* Ganti 'url-gambar-anda.jpg' dengan URL gambar yang ingin Anda gunakan */
                background-size: cover; /* Untuk mengatur gambar agar menutupi seluruh div */
                background-repeat: no-repeat; /* Agar gambar tidak diulang */
                background-position: center center; /* Agar gambar terpusat dalam div */
                /* opacity: 0.6; */
            }

            #clock {
            font-size: 5vmin; 
            text-align: right;
            margin-top: 0.5%;
            margin-right: 2%;
            color: white;
        }


        </style>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; ?>;
        <div id="layoutSidenav">
            <?php include 'sidebar.php'; ?>;
            <div id="layoutSidenav_content" class="with-background">
                <main>
                    <div class="container-fluid px-4" >
                    <br>
                    <figure class="bg-light p-4"
                            style="border-left: .35rem solid #fcdb5e; border-top: 1px solid #eee; border-right: 1px solid #eee; border-bottom: 1px solid #eee; opacity: 0.85;">
                            <blockquote class="blockquote pb-2">
                                <i><h1>
                                    Selamat datang <?= isset($previousUsername) ? $previousUsername : $username; ?>, Anda berada di tahun ajaran <u><?=$tahun_ajar;?></u>
                                </h1></i>
                                <h2><?=$bulanIni;?></h2>
                            </blockquote>
                    </figure>
                    </div>
                    <div id="clock"></div><br>
                    <div class="container-fluid px-4" >                        
                        <figure class="bg-light p-4"
                            style="border-left: .35rem solid #fcdb5e; border-top: 1px solid #eee; border-right: 1px solid #eee; border-bottom: 1px solid #eee; opacity: 0.85;">
                            <div class="row">                                
                                <div class="col-xl-3 col-md-6">
                                    <?php 
                                    $queryPemasukan = "SELECT SUM(total) AS grand_total
                                    FROM (
                                        SELECT SUM(jumlah) AS total FROM transaksi_masuk_siswa
                                        UNION ALL
                                        SELECT SUM(jumlah) FROM transaksi_masuk_nonsiswa
                                        UNION ALL
                                        SELECT SUM(jumlah) FROM transaksi_masuk_cashflow
                                    ) AS subquery;";

                                    $pemasukan = mysqli_query($conn, $queryPemasukan);
                                    $rowPemasukan = mysqli_fetch_assoc($pemasukan);
                                    $pemasukan = $rowPemasukan['grand_total'];                                    
                                    ?>
                                    <div class="card bg-success text-white mb-4">
                                        <div class="card-body">Total Pemasukan</div>
                                            <div class="card-footer d-flex align-items-center justify-content-between">
                                                <a class="small text-white stretched-link" href="#">View Details</a>
                                                <div class="collapse" id="pemasukanDetails">
                                                <h2 id="pemasukanValue">Rp. <?=$pemasukan;?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <?php 
                                    $queryPengeluaran = "SELECT SUM(total) AS grand_total
                                    FROM (
                                        SELECT SUM(jumlah) AS total FROM transaksi_keluar_siswa
                                        UNION ALL
                                        SELECT SUM(jumlah) FROM transaksi_keluar_nonsiswa
                                        UNION ALL
                                        SELECT SUM(jumlah) FROM transaksi_keluar_cashflow
                                    ) AS subquery;";

                                    $pengeluaran = mysqli_query($conn, $queryPengeluaran);
                                    $rowPengeluaran = mysqli_fetch_assoc($pengeluaran);
                                    $pengeluaran = $rowPengeluaran['grand_total'];
                                    
                                    ?>
                                    <div class="card bg-warning text-white mb-4">
                                        <div class="card-body">Total Pengeluaran</div>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <a class="small text-white stretched-link" href="#" data-toggle="collapse" data-target="#pengeluaranDetails">View Details</a>
                                            <div class="collapse" id="pengeluaranDetails">
                                                <h2 id="pengeluaranValue">Rp. <?=$pengeluaran;?></h2>
                                            </div>
                                        </div>
                                    </div>

                                </div>                                
                                <div class="col-xl-3 col-md-6">
                                    <?php 
                                    $saldo = $pemasukan - $pengeluaran;
                                    ?>
                                    <div class="card bg-info text-white mb-4">
                                        <div class="card-body">Saldo</div>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <a class="small text-white stretched-link" href="#" data-toggle="collapse" data-target="#saldoDetails">View Details</a>
                                            <div class="collapse" id="saldoDetails">
                                                <h2 id="saldoValue">Rp. <?=$saldo;?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </figure>
                    </div>
                    <div class="container-fluid px-4" > 
                        <figure class="bg-light p-4"
                            style="border-left: .35rem solid #fcdb5e; border-top: 1px solid #eee; border-right: 1px solid #eee; border-bottom: 1px solid #eee; opacity: 0.85;">
                        <div class="row">
                            <?php 
                                $queryDebet = "SELECT
                                'Cashflow' AS kategoriDebet,           
                                SUM(jumlah) AS debet FROM transaksi_masuk_cashflow WHERE bulan = '$bulanIni' AND id_tahun_ajar = $idTahunAjarDefault
                                UNION ALL
                                SELECT
                                    k.nama_kategori AS kategoriDebet,
                                    SUM(debet) AS debet
                                FROM kategori k
                                LEFT JOIN (
                                    SELECT tm.id_kategori, SUM(tm.jumlah) AS debet
                                    FROM transaksi_masuk_siswa tm
                                    JOIN tahun_ajar ta ON tm.id_tahun_ajar = ta.id_tahun_ajar
                                    WHERE ta.id_tahun_ajar = $idTahunAjarDefault AND tm.bulan = '$bulanIni'
                                    GROUP BY tm.id_kategori
                                    UNION ALL
                                    SELECT tn.id_kategori, SUM(tn.jumlah) AS debet
                                    FROM transaksi_masuk_nonsiswa tn
                                    JOIN tahun_ajar ta ON tn.id_tahun_ajar = ta.id_tahun_ajar
                                    WHERE ta.id_tahun_ajar = 21 AND tn.bulan = '$bulanIni'
                                    GROUP BY tn.id_kategori
                                    UNION ALL
                                    SELECT tbm.id_kategori, SUM(tbm.jumlah) AS debet
                                    FROM tabung_masuk tbm
                                    JOIN tahun_ajar ta ON tbm.id_tahun_ajar = ta.id_tahun_ajar
                                    WHERE ta.id_tahun_ajar = $idTahunAjarDefault AND tbm.bulan = '$bulanIni'
                                    GROUP BY tbm.id_kategori
                                ) AS debet
                                ON k.id_kategori = debet.id_kategori
                                WHERE k.id_kategori <> 1
                                GROUP BY k.id_kategori, k.nama_kategori;";
                            $debet = mysqli_query($conn, $queryDebet);

                            $dataDebet = array();
                            while ($rowDebet = mysqli_fetch_assoc($debet)) {
                                $dataDebet[] = $rowDebet;
                            }
                            
                            $queryKredit = "SELECT
                                'Cashflow' AS kategoriKredit,           
                                SUM(jumlah) AS kredit FROM transaksi_keluar_cashflow WHERE bulan = '$bulanIni' AND id_tahun_ajar = $idTahunAjarDefault
                                UNION ALL
                                SELECT
                                    k.nama_kategori AS kategoriKredit,
                                    SUM(kredit) AS kredit
                                FROM kategori k
                                LEFT JOIN (
                                    SELECT tks.id_kategori, SUM(tks.jumlah) AS kredit
                                    FROM transaksi_keluar_siswa tks
                                    JOIN tahun_ajar ta ON tks.id_tahun_ajar = ta.id_tahun_ajar
                                    WHERE ta.id_tahun_ajar = $idTahunAjarDefault AND tks.bulan = '$bulanIni'
                                    GROUP BY tks.id_kategori
                                    UNION ALL
                                    SELECT tkn.id_kategori, SUM(tkn.jumlah) AS kredit
                                    FROM transaksi_keluar_nonsiswa tkn
                                    JOIN tahun_ajar ta ON tkn.id_tahun_ajar = ta.id_tahun_ajar
                                    WHERE ta.id_tahun_ajar = $idTahunAjarDefault AND tkn.bulan = '$bulanIni'
                                    GROUP BY tkn.id_kategori
                                    UNION ALL
                                    SELECT tbk.id_kategori, SUM(tbk.jumlah) AS kredit
                                    FROM tabung_ambil tbk
                                    JOIN tahun_ajar ta ON tbk.id_tahun_ajar = ta.id_tahun_ajar
                                    WHERE ta.id_tahun_ajar = $idTahunAjarDefault AND tbk.bulan = '$bulanIni'
                                    GROUP BY tbk.id_kategori
                                ) AS kredit
                                ON k.id_kategori = kredit.id_kategori
                                WHERE k.id_kategori <> 1
                                GROUP BY k.id_kategori, k.nama_kategori;";
                            $kredit = mysqli_query($conn, $queryKredit);

                            $dataKredit = array();
                            while ($rowKredit = mysqli_fetch_assoc($kredit)) {
                                $dataKredit[] = $rowKredit;
                            }

                            $current_time = date('Y-m-d H:i:s');
                            


                            ?>
                            <div class="col-xl-6 col-md-5">
                                <canvas id="barChart"></canvas>
                            </div>
                            <div class="col-xl-6 col-md-5">
                                <canvas id="barChartKredit"></canvas>
                            </div>
                        </div>
                    </div>

                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>

        
        <script>
        function updateClock() {
            var now = new Date();
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            var formattedDate = now.toLocaleDateString(undefined, options);
            var time = now.toLocaleTimeString();

            var clockElement = document.getElementById('clock');
            clockElement.innerHTML = formattedDate + ' ' + time;
            }

            // Memanggil fungsi updateClock setiap detik
            setInterval(updateClock, 1000);

            // Memanggil updateClock pada saat halaman pertama kali dimuat
            updateClock();
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const pemasukanValue = document.getElementById("pemasukanValue");
                const pemasukanDetails = document.getElementById("pemasukanDetails");
                const totalPemasukan = document.getElementById("totalPemasukan");

                const viewDetailsLink = document.querySelector(".card-footer a");

                function toggleDetails() {
                    if (pemasukanDetails.classList.contains("show")) {
                        pemasukanDetails.classList.remove("show");
                        pemasukanValue.innerText = "Rp. <?=$pemasukan;?>";
                        viewDetailsLink.innerText = "View Details";
                    } else {
                        pemasukanDetails.classList.add("show");
                        pemasukanValue.innerText = "Rp. " + new Intl.NumberFormat('id-ID').format(<?=$pemasukan;?>);
                        viewDetailsLink.innerText = "Hide Details";
                    }
                }

                viewDetailsLink.addEventListener("click", function(event) {
                    event.preventDefault();
                    toggleDetails();
                });

                // Set default state
                toggleDetails();
            });            
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const pengeluaranValue = document.getElementById("pengeluaranValue");
                const pengeluaranDetails = document.getElementById("pengeluaranDetails");
                const totalPengeluaran = document.getElementById("totalPengeluaran");

                const viewDetailsLinkPengeluaran = document.querySelector(".card-footer a[data-target='#pengeluaranDetails']");

                function togglePengeluaranDetails() {
                    if (pengeluaranDetails.classList.contains("show")) {
                        pengeluaranDetails.classList.remove("show");
                        pengeluaranValue.innerText = "Rp. <?=$pengeluaran;?>";
                        viewDetailsLinkPengeluaran.innerText = "View Details";
                    } else {
                        pengeluaranDetails.classList.add("show");
                        pengeluaranValue.innerText = "Rp. " + new Intl.NumberFormat('id-ID').format(<?=$pengeluaran;?>);
                        viewDetailsLinkPengeluaran.innerText = "Hide Details";
                    }
                }

                viewDetailsLinkPengeluaran.addEventListener("click", function(event) {
                    event.preventDefault();
                    togglePengeluaranDetails();
                });

                // Set default state
                togglePengeluaranDetails();
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const saldoValue = document.getElementById("saldoValue");
                const saldoDetails = document.getElementById("saldoDetails");
                const totalSaldo = document.getElementById("totalSaldo");

                const viewDetailsLinkSaldo = document.querySelector(".card-footer a[data-target='#saldoDetails']");

                function toggleSaldoDetails() {
                    if (saldoDetails.classList.contains("show")) {
                        saldoDetails.classList.remove("show");
                        saldoValue.innerText = "Rp. <?=$saldo;?>";
                        viewDetailsLinkSaldo.innerText = "View Details";
                    } else {
                        saldoDetails.classList.add("show");
                        saldoValue.innerText = "Rp. " + new Intl.NumberFormat('id-ID').format(<?=$saldo;?>);
                        viewDetailsLinkSaldo.innerText = "Hide Details";
                    }
                }

                viewDetailsLinkSaldo.addEventListener("click", function(event) {
                    event.preventDefault();
                    toggleSaldoDetails();
                });

                // Set default state
                toggleSaldoDetails();
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var dataDebet = <?php echo json_encode($dataDebet); ?>;
                var labels = dataDebet.map(item => item.kategoriDebet); 
                var values = dataDebet.map(item => item.debet); 
                //console.log(values);
                var ctx = document.getElementById('barChart').getContext('2d');

                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Debet bulan ini',
                            data: values,
                            backgroundColor: 'rgba(25, 135, 84, 1)',
                            borderColor: 'rgba(25, 135, 84, 1)',
                            borderWidth: 1                        
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        return value / 1000 + 'k';
                                        //console.log(value);
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
                            },
                            datalabels: { 
                                anchor: 'end',
                                align: 'top',
                                offset: 4,
                                color: 'rgba(75, 192, 192, 1)',
                                font: { weight: 'bold' },
                                formatter: function(value) {
                                    return (value / 1000).toFixed(2) + 'k';
                                },
                                display: true,
                                data: values                                
                            }
                        }
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var dataKredit = <?php echo json_encode($dataKredit); ?>;
                var labelsKredit = dataKredit.map(item => item.kategoriKredit);
                var valuesKredit = dataKredit.map(item => item.kredit);

                var ctxKredit = document.getElementById('barChartKredit').getContext('2d');

                var chartKredit = new Chart(ctxKredit, {
                    type: 'bar',
                    data: {
                        labels: labelsKredit,
                        datasets: [{
                            label: 'Kredit bulan ini',
                            data: valuesKredit,
                            backgroundColor: 'rgba(255,204,0, 1)', // Warna latar belakang batang kredit
                            borderColor: 'rgba(255,204,0, 1)', // Warna tepi batang kredit
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        return value / 1000 + 'k';
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
                            }
                        }
                    }
                });
            });
        </script>


    </body>
</html>

