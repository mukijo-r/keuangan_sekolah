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
    header('location:siswa.php');
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
    $ids = $_POST['ids'];

    $tanggalLahir = date("Y-m-d", strtotime($tglLahir));

    $editSiswa = mysqli_query($conn, "update siswa set nisn=$nisn, nama='$namaSiswa', id_kelas='$kelas', jk='$jk', tempat_lahir='$kotaLahir', tanggal_lahir='$tanggalLahir', agama='$agama', alamat='$alamat' where id_siswa=$ids");
    header('location:siswa.php');
}

//Hapus Siswa
if(isset($_POST['hapusSiswa'])){
    $ids = $_POST['ids'];

    $hapusSiswa = mysqli_query($conn, "delete from siswa where id_siswa=$ids");
    header('location:siswa.php');
}

//Edit Guru
if(isset($_POST['editGuru'])){
    $nip = $_POST['nip'];
    $namaGuru = $_POST['namaGuru'];
    $jk = $_POST['jk'];
    $jabatan= $_POST['jabatan'];
    $idg = $_POST['idg'];

    $editGuru = mysqli_query($conn, "pdate guru set nip='$nip', nama_lengkap='$namaGuru', jk='$jk', jabatan='$jabatan' where id_guru=$idg");
    if ($editGuru) {
        $_SESSION['flash_message'] = 'Edit data guru berhasil';
        $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
        header('location:guru.php');
        exit;
    } else {
        $_SESSION['flash_message'] = 'Edit data guru gagal';
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location:guru.php');
        exit;
    }    
}

//Hapus Guru
if(isset($_POST['hapusGuru'])){
    $idg = $_POST['idg'];

    $hapusGuru = mysqli_query($conn, "delete from guru where id_guru=$idg");
    header('location:guru.php');
}
    
?>