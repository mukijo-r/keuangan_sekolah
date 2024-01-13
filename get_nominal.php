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

if ($kategori == 5) {
    $queryNominalPramuka = "SELECT nominal FROM penetapan WHERE id_siswa = '$siswa' AND id_sub_kategori = '6'";
    $pramuka = mysqli_query($conn, $queryNominalPramuka);
    $dataNominalPramuka = mysqli_fetch_assoc($pramuka);
    $nominalPramuka = $dataNominalPramuka['nominal'];

    $queryNominalKegiatan = "SELECT nominal FROM penetapan WHERE id_siswa = '$siswa' AND id_sub_kategori = '7'";
    $kegiatan = mysqli_query($conn, $queryNominalKegiatan);
    $dataNominalKegiatan = mysqli_fetch_assoc($kegiatan);
    $nominalKegiatan = $dataNominalKegiatan['nominal'];

    $queryNominalKomputer = "SELECT nominal FROM penetapan WHERE id_siswa = '$siswa' AND id_sub_kategori = '8'";
    $komputer = mysqli_query($conn, $queryNominalKomputer);
    $dataNominalKomputer = mysqli_fetch_assoc($komputer);
    $nominalKomputer = $dataNominalKomputer['nominal'];

    $nominalEkstrakurikuler = $nominalPramuka + $nominalKegiatan + $nominalKomputer;

    $nominal = $nominal + $nominalEkstrakurikuler;
} else {
    $nominal = $nominal;
}

// Mengembalikan data dalam format JSON tanpa tanda kutip ganda
echo json_encode($nominal);
?>
