<?php
include 'config.php';
// Koneksi ke database
$conn = mysqli_connect("localhost:3306","root","","sdk");

// Dapatkan nilai "siswa" dan "kategori" dari parameter GET
$kategori = $_GET['subKategori'];

// Lakukan query SQL untuk mengambil nilai "nominal" dari tabel sesuai dengan parameter
$query = "SELECT DISTINCT nominal AS nominal_kolektif FROM penetapan WHERE id_sub_kategori = '$kategori'";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Ambil nilai "nominal" dari hasil query dan konversi ke tipe data numerik
$row = mysqli_fetch_assoc($result);
$nominalKolektif = floatval($row['nominal_kolektif']);

// Mengembalikan data dalam format JSON tanpa tanda kutip ganda
echo json_encode($nominalKolektif);
?>
