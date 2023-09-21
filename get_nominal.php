<?php
include 'config.php';
// Koneksi ke database
$conn = mysqli_connect("localhost:3306","root","","sdk");

// Dapatkan nilai "siswa" dan "kategori" dari parameter GET
$siswa = $_GET['siswa'];
$kategori = $_GET['subKategori'];

// Lakukan query SQL untuk mengambil nilai "nominal" dari tabel sesuai dengan parameter
$query = "SELECT nominal FROM penetapan WHERE id_siswa = '$siswa' AND id_sub_kategori = '$kategori'";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Ambil nilai "nominal" dari hasil query dan konversi ke tipe data numerik
$row = mysqli_fetch_assoc($result);
$nominal = floatval($row['nominal']);

// Mengembalikan data dalam format JSON tanpa tanda kutip ganda
echo json_encode($nominal);
?>
