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
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                'Cashflow' AS kategori,           
                                SUM(jumlah)/1000 AS debet FROM transaksi_masuk_cashflow WHERE bulan = 'September' AND id_tahun_ajar = 21
                                UNION ALL
                                SELECT
                                    k.nama_kategori AS kategori,
                                    SUM(debet)/1000 AS debet
                                FROM kategori k
                                LEFT JOIN (
                                    SELECT tm.id_kategori, SUM(tm.jumlah) AS debet
                                    FROM transaksi_masuk_siswa tm
                                    JOIN tahun_ajar ta ON tm.id_tahun_ajar = ta.id_tahun_ajar
                                    WHERE ta.id_tahun_ajar = 21 AND tm.bulan = 'September'
                                    GROUP BY tm.id_kategori
                                    UNION ALL
                                    SELECT tn.id_kategori, SUM(tn.jumlah) AS debet
                                    FROM transaksi_masuk_nonsiswa tn
                                    JOIN tahun_ajar ta ON tn.id_tahun_ajar = ta.id_tahun_ajar
                                    WHERE ta.id_tahun_ajar = 21 AND tn.bulan = 'September'
                                    GROUP BY tn.id_kategori
                                    UNION ALL
                                    SELECT tbm.id_kategori, SUM(tbm.jumlah) AS debet
                                    FROM tabung_masuk tbm
                                    JOIN tahun_ajar ta ON tbm.id_tahun_ajar = ta.id_tahun_ajar
                                    WHERE ta.id_tahun_ajar = 21 AND tbm.bulan = 'September'
                                    GROUP BY tbm.id_kategori
                                ) AS debet
                                ON k.id_kategori = debet.id_kategori
                                WHERE k.id_kategori <> 1
                                GROUP BY k.id_kategori, k.nama_kategori;";
                            $debet = mysqli_query($conn, $queryDebet);

                            $data = array();
                            while ($rowDebet = mysqli_fetch_assoc($debet)) {
                                $data[] = $rowDebet;
                            }
                            $jsonData = json_encode($data);                           


                            ?>
                            <div class="col-xl-6 col-md-6">
                                <canvas id="barChart"></canvas>
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
        <script async defer src="https://dailyverses.net/get/verse.js?language=niv"></script>
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
            var data = <?php echo json_encode($data); ?>;
            var labels = data.map(item => item.kategori); 
            var values = data.map(item => item.debet); 

            var ctx = document.getElementById('barChart').getContext('2d');

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Debet Bulan ini (dalam ribuan)',
                        data: values,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: [{
                            beginAtZero: true,
                            ticks: {
                                function(value, index, values) {
                                    return value / 1000 + 'k';
                                }
                            }
                        }]
                    }
                }
            });
        });


        </script>



    </body>
</html>

