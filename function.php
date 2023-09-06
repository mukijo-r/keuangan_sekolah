<?php
session_start();
//Koneksi ke database

$conn = mysqli_connect("localhost:3306","root","","sdk");

//Tambah Siswa
if(isset($_POST['tambahSiswa'])){
    $nisn = $_POST['nisn'];
    $namaSiswa = $_POST['namaSiswa'];
    $kelas = $_POST['kelas'];
    $jk = $_POST['jk'];
    $kotaLahir = $_POST['tempatLahir'];
    $tglLahir = $_POST['tanggalLahir'];
    $agama = $_POST['agama'];
    $alamat = $_POST['alamat'];

    $tanggalLahir = date("Y-m-d", strtotime($tglLahir));

    $addSiswa = mysqli_query($conn, "insert into siswa (nisn, nama, id_kelas, jk, tempat_lahir, tanggal_lahir, agama, alamat) values ($nisn, '$namaSiswa', $kelas, '$jk', '$kotaLahir', '$tanggalLahir', '$agama', '$alamat')");
    header('location:index.php');
}

//Edit Siswa
if(isset($_POST['editSiswa'])){
    $nisn = $_POST['nisn'];
    $namaSiswa = $_POST['namaSiswa'];
    $kelas = $_POST['kelas'];
    $jk = $_POST['jk'];
    $kotaLahir = $_POST['tempatLahir'];
    $tglLahir = $_POST['tanggalLahir'];
    $agama = $_POST['agama'];
    $alamat = $_POST['alamat'];

    $tanggalLahir = date("Y-m-d", strtotime($tglLahir));

    $addSiswa = mysqli_query($conn, "insert into siswa (nisn, nama, id_kelas, jk, tempat_lahir, tanggal_lahir, agama, alamat) values ($nisn, '$namaSiswa', $kelas, '$jk', '$kotaLahir', '$tanggalLahir', '$agama', '$alamat')");
    header('location:index.php');
}
    
?>