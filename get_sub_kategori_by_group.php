<?php
include 'config.php';
// Koneksi ke database
$conn = mysqli_connect("localhost:3306","root","","sdk");

// Ambil nilai groop yang dikirimkan melalui parameter GET
$idGroupCashflow = $_GET['groop'];

// Query untuk mengambil data sub kategori berdasarkan groop
$query = "SELECT id_subkategori_cashflow, nama_sub_kategori FROM sub_kategori_cashflow WHERE id_group_cashflow =$idGroupCashflow";
$result = mysqli_query($conn, $query);

$subKategori = array();
while ($row = mysqli_fetch_assoc($result)) {
    $subKategori[] = $row;
}

// Mengembalikan data dalam format JSON
echo json_encode($subKategori);
?>
