<?php
include 'config.php';
// Koneksi ke database
$conn = mysqli_connect("localhost:3306","root","","sdk");

// Ambil nilai kelas yang dikirimkan melalui parameter GET
$idKategori = $_GET['kategori'];

// Query untuk mengambil data siswa berdasarkan kelas
$queryKategori = "SELECT id_sub_kategori, nama_sub_kategori FROM sub_kategori_siswa WHERE id_kategori = $idKategori";
$result = mysqli_query($conn, $queryKategori);

$dataSubKategori = array();
while ($row = mysqli_fetch_assoc($result)) {
    $dataSubKategori[] = $row;
}

// Mengembalikan data dalam format JSON
echo json_encode($dataSubKategori);
?>
