<?php
require 'config.php';
// Koneksi ke database
$conn = mysqli_connect("localhost:3306","root","","sdk");

    $idSubKategoriSiswa = $_GET["idSubKategoriSiswa"];
    $bulan = $_GET["bulan"];

    // Buat kueri SQL untuk mengambil jumlah dari database
    $query = "SELECT SUM(jumlah) FROM transaksi_masuk_siswa WHERE id_tahun_ajar = $idTahunAjarDefault AND id_sub_kategori = $idSubKategoriSiswa AND bulan = '$bulan'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        // Ambil nilai jumlah dari hasil kueri
        $row = mysqli_fetch_assoc($result);
        $jumlah = intval($row["SUM(jumlah)"]);
        echo $jumlah; // Mengembalikan nilai jumlah ke JavaScript
    } else {
        echo $query; // Mengembalikan 0 jika terjadi kesalahan
    }

?>
