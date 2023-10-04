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
            font-size: 8vmin; /* Ukuran font dalam 20% dari lebar atau tinggi viewport (tergantung yang lebih kecil) */
            text-align: right;
            margin-top: 25%; /* Untuk menggeser elemen ke tengah halaman (opsional) */
        }


        </style>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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
                                Selamat datang, Anda berada di tahun ajaran <u><?=$tahun_ajar;?></u>
                            </h1></i>
                        </blockquote>
                    </figure>

                    </div>
                    <div id="clock"></div>
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
            clockElement.innerHTML = formattedDate + '<br>' + time;
            }

            // Memanggil fungsi updateClock setiap detik
            setInterval(updateClock, 1000);

            // Memanggil updateClock pada saat halaman pertama kali dimuat
            updateClock();
        </script>
    </body>
</html>

