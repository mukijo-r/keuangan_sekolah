<?php
include 'config.php';
// Koneksi ke database
$conn = mysqli_connect("localhost:3306","root","","sdk");

// Dapatkan nilai "siswa" dan "kategori" dari parameter GET
$pengali = 0;
$siswa = $_GET['siswa'];
$kategori = $_GET['subKategori'];
$bulan = $_GET['bulan'];
$bulan = str_replace("'", '', $bulan);

if ($bulan == 'Juli') {
    $pengali = 0;
} elseif ($bulan == 'Agustus') {
    $pengali = 1;
} elseif ($bulan == 'September') {
    $pengali = 2;
} elseif ($bulan == 'Oktober') {
    $pengali = 3;
} elseif ($bulan == 'November') {
    $pengali = 4;
} elseif ($bulan == 'Desember') {
    $pengali = 5;
} elseif ($bulan == 'Januari') {
    $pengali = 6;
} elseif ($bulan == 'Februari') {
    $pengali = 7;
} elseif ($bulan == 'Maret') {
    $pengali = 8;
} elseif ($bulan == 'April') {
    $pengali = 9;
} elseif ($bulan == 'Mei') {
    $pengali = 10;
} elseif ($bulan == 'Juni') {
    $pengali = 11;
}


// Lakukan query SQL untuk mengambil nilai "nominal" dari tabel sesuai dengan parameter
$query = "SELECT nominal FROM penetapan WHERE id_siswa = '$siswa' AND id_sub_kategori = '$kategori'";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Ambil nilai "nominal" dari hasil query dan konversi ke tipe data numerik
$row = mysqli_fetch_assoc($result);
$nominal = floatval($row['nominal']);

//Lakukan query untuk mendapatkan jumlah yang sudah dibayar
$queryJumlah = "SELECT SUM(jumlah) AS sum_jumlah FROM transaksi_masuk_siswa WHERE id_siswa = '$siswa' AND id_sub_kategori = '$kategori'";
$resultJumlah = mysqli_query($conn, $queryJumlah);
if(!$result) {
    die("Error: " . mysqli_error($conn));
}
$rowJumlah = mysqli_fetch_assoc($resultJumlah);
$jumlah = floatval($rowJumlah['sum_jumlah']);

$totalNominal = $pengali * $nominal;

if ($totalNominal > $jumlah) {
    $tunggakan = $totalNominal - $jumlah;
} else {
    $tunggakan = 0;
}


// Mengembalikan data dalam format JSON tanpa tanda kutip ganda
//echo json_encode($tunggakan);
?>
