<?php
$conn = mysqli_connect("localhost:3306","root","","sdk");

// Mengambil nilai tahun ajar dari database
$queryTahunAjarDefault = "SELECT
tad.*,
ta.tahun_ajar AS tahun_ajar
FROM tahun_ajar_default tad
LEFT JOIN tahun_ajar ta ON tad.id_tahun_ajar = ta.id_tahun_ajar;";

$result = mysqli_query($conn, $queryTahunAjarDefault);
$dataTahunAjar = mysqli_fetch_assoc($result);
$tahun_ajar = $dataTahunAjar['tahun_ajar'];
$idTahunAjarDefault = $dataTahunAjar['id_tahun_ajar'];
$tahunAjarLap = $tahun_ajar;

$idKategoriLap = 2;
$idSubKategoriLap = 5;
$bulanLalu = date('F', strtotime('last month'));
 

if ($bulanLalu == 'January') {
    $bulanLalu = 'Januari';
} elseif ($bulanLalu == 'February') {
    $bulanLalu = 'Februari';
} elseif ($bulanLalu == 'March') {
    $bulanLalu = 'Maret';
} elseif ($bulanLalu == 'April') {
    $bulanLalu = 'April';
} elseif ($bulanLalu == 'May') {
    $bulanLalu = 'Mei';
} elseif ($bulanLalu == 'June') {
    $bulanLalu = 'Juni';
} elseif ($bulanLalu == 'July') {
    $bulanLalu = 'Juli';
} elseif ($bulanLalu == 'August') {
    $bulanLalu = 'Agustus';
} elseif ($bulanLalu == 'September') {
    $bulanLalu = 'September';
} elseif ($bulanLalu == 'October') {
    $bulanLalu = 'Oktober';
} elseif ($bulanLalu == 'November') {
    $bulanLalu = 'November';
} elseif ($bulanLalu == 'December') {
    $bulanLalu = 'Desember';
} else {
    $bulanLalu = 'Bulan Tidak valid';
}

$bulanIni = date('F');
if ($bulanIni == 'January') {
    $bulanIni = 'Januari';
} elseif ($bulanIni == 'February') {
    $bulanIni = 'Februari';
} elseif ($bulanIni == 'March') {
    $bulanLalu = 'Maret';
} elseif ($bulanIni == 'April') {
    $bulanIni = 'April';
} elseif ($bulanIni == 'May') {
    $bulanIni = 'Mei';
} elseif ($bulanIni == 'June') {
    $bulanIni = 'Juni';
} elseif ($bulanIni == 'July') {
    $bulanIni = 'Juli';
} elseif ($bulanIni == 'August') {
    $bulanIni = 'Agustus';
} elseif ($bulanIni == 'September') {
    $bulanIni = 'September';
} elseif ($bulanIni == 'October') {
    $bulanIni = 'Oktober';
} elseif ($bulanIni == 'November') {
    $bulanIni = 'November';
} elseif ($bulanIni == 'December') {
    $bulanIni = 'Desember';
} else {
    $bulanIni = 'Bulan Tidak valid';
}

?>