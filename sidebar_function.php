<?php
include 'config.php';

$conn = mysqli_connect("localhost:3306","root","","sdk");


//Tambah Tahun Ajar
if(isset($_POST['tambahTahunAjar'])){
    $tahun_ajar = $_POST['newTahunAjar'];
    $insertTA = mysqli_query($conn, "INSERT INTO tahun_ajar (tahun_ajar) VALUES ('$tahun_ajar')");
}

if(isset($_POST['ubahTahunAjar'])){
    $tahun_ajar = $_POST['tahunAjar'];
}


?>