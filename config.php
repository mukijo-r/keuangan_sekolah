<?php

$tahun_ajar = "2020/2021";
$tahunAjarLap = $tahun_ajar;
$idKategoriLap = 2;
$bulanLalu = strtotime('%B', strtotime(date('Y-m', strtotime('-1 month')))); 

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

?>