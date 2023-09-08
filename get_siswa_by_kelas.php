<?php
// Koneksi ke database
$conn = mysqli_connect("localhost:3306","root","","sdk");

// Ambil nilai kelas yang dikirimkan melalui parameter GET
$idKelas = $_GET['kelas'];

// Query untuk mengambil data siswa berdasarkan kelas
$query = "SELECT id_siswa, nama FROM siswa WHERE id_kelas = $idKelas";
$result = mysqli_query($conn, $query);

$dataSiswa = array();
while ($row = mysqli_fetch_assoc($result)) {
    $dataSiswa[] = $row;
}

// Mengembalikan data dalam format JSON
echo json_encode($dataSiswa);
?>
