<?php 
// Parameter tahun ajar dan bulan dari halaman aplikasi
$tahunAjar = "2023/2024"; // Ini akan menjadi tahun_ajar Anda
$bulan = "Mei"; // Ini akan menjadi bulan yang digunakan

// Pisahkan tahun awal dan tahun akhir dari parameter tahun ajar
list($tahunAwal, $tahunAkhir) = explode('/', $tahunAjar);

// Konversi nama bulan ke angka
if ($bulan == 'Januari') {
    $bulanNum = 1;
} elseif ($bulan == 'Februari') {
    $bulanNum = 2;
} elseif ($bulan == 'Maret') {
    $bulanNum = 3;
} elseif ($bulan == 'April') {
    $bulanNum = 4;
} elseif ($bulan == 'Mei') {
    $bulanNum = 5;
} elseif ($bulan == 'Juni') {
    $bulanNum = 6;
} elseif ($bulan == 'Juli') {
    $bulanNum = 7;
} elseif ($bulan == 'Agustus') {
    $bulanNum = 8;
} elseif ($bulan == 'September') {
    $bulanNum = 9;
} elseif ($bulan == 'Oktober') {
    $bulanNum = 10;
} elseif ($bulan == 'November') {
    $bulanNum = 11;
} elseif ($bulan == 'Desember') {
    $bulanNum = 12;
} else {
    $bulanNum = 'Bulan Tidak valid';
}

// Daftar bulan-bulan yang menggunakan tahun awal
$bulanTahunAwal = range(7, 12); // Menggunakan angka bulan

// Logika untuk menentukan tahun yang akan digunakan berdasarkan bulan
if (in_array($bulanNum, $bulanTahunAwal)) {
    $tahunYangDigunakan = $tahunAwal;
} else {
    $tahunYangDigunakan = $tahunAkhir;
}

// Mencari tanggal akhir dalam bulan sesuai tahun yang ditentukan
$tanggalAkhir = date('Y-m-t', strtotime("$tahunYangDigunakan-$bulanNum-01"));

echo "Tahun Ajar: $tahunAjar<br>";
echo "Bulan: $bulan<br>";
echo "Tanggal Akhir: $tanggalAkhir";


