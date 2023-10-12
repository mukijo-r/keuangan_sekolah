<?php
include 'config.php';
// Koneksi ke database
$conn = mysqli_connect("localhost:3306","root","","sdk");

// Dapatkan nilai "siswa" dan "kategori" dari parameter GET
$kelas = $_GET['kelas'];
$kategori = $_GET['subKategori'];

// Lakukan query SQL untuk mengambil nilai "nominal" dari tabel sesuai dengan parameter
$querySiswa = "SELECT COUNT(nama) AS jumlah_siswa FROM siswa WHERE id_kelas = '$kelas'";
$siswa = mysqli_query($conn, $querySiswa);

$rowSiswa = mysqli_fetch_assoc($siswa);
$jumlahSiswa = floatval($rowSiswa['jumlah_siswa']);

// Lakukan query SQL untuk mengambil nilai "nominal" dari tabel sesuai dengan parameter
$query = "SELECT DISTINCT nominal AS nominal_kolektif FROM penetapan WHERE id_sub_kategori = '$kategori'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Ambil nilai "nominal" dari hasil query dan konversi ke tipe data numerik
$row = mysqli_fetch_assoc($result);
$nominalKolektif = floatval($row['nominal_kolektif']);


$jumlahKolektif = $jumlahSiswa * $nominalKolektif;

// Mengembalikan data dalam format JSON tanpa tanda kutip ganda
echo json_encode($jumlahKolektif);
?>
