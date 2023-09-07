<?php
require 'lib/excel-reader2.php';
session_start();
//Koneksi ke database

$conn = mysqli_connect("localhost:3306","root","","sdk");

// Tambah Siswa
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

    try {
        // Coba jalankan query insert
        $addSiswa = mysqli_query($conn, "INSERT INTO siswa (nisn, nama, id_kelas, jk, tempat_lahir, tanggal_lahir, agama, alamat) VALUES ('$nisn', '$namaSiswa', $kelas, '$jk', '$kotaLahir', '$tanggalLahir', '$agama', '$alamat')");

        if (!$addSiswa) {
            throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
        }

        // Query SELECT untuk memeriksa apakah data sudah masuk ke database
        $result = mysqli_query($conn, "SELECT * FROM siswa WHERE nisn = '$nisn'");

        if ($result && mysqli_num_rows($result) > 0) {
            // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
            $_SESSION['flash_message'] = 'Tambah data siswa berhasil';
            $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
            header('location:siswa.php');
            exit;
        } else {
            // Data tidak ada dalam database, itu berarti gagal
            throw new Exception("Data siswa tidak ditemukan setelah ditambahkan");
        }
    } catch (Exception $e) {
        // Tangani exception jika terjadi kesalahan
        $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location:siswa.php');
        exit;
    }
}


// Edit Siswa
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

    try {
        // Coba jalankan query update
        $editSiswa = mysqli_query($conn, "UPDATE siswa SET nisn='$nisn', nama='$namaSiswa', id_kelas='$kelas', jk='$jk', tempat_lahir='$kotaLahir', tanggal_lahir='$tanggalLahir', agama='$agama', alamat='$alamat' WHERE id_siswa='$ids'");

        if (!$editSiswa) {
            throw new Exception("Query update gagal"); // Lempar exception jika query gagal
        }

        // Query SELECT untuk memeriksa apakah data sudah benar-benar diperbarui dalam database
        $result = mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa = '$ids'");

        if ($result && mysqli_num_rows($result) > 0) {
            // Data sudah benar-benar diperbarui dalam database, Anda dapat mengatur pesan flash message berhasil
            $_SESSION['flash_message'] = 'Edit data siswa berhasil';
            $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
            header('location:siswa.php');
            exit;
        } else {
            // Data tidak ada dalam database setelah edit, itu berarti gagal
            throw new Exception("Data siswa tidak ditemukan setelah diedit");
        }
    } catch (Exception $e) {
        // Tangani exception jika terjadi kesalahan
        $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location:siswa.php');
        exit;
    }
}


// Hapus Siswa
if(isset($_POST['hapusSiswa'])){
    $ids = $_POST['ids'];

    try {
        // Coba jalankan query hapus
        $hapusSiswa = mysqli_query($conn, "DELETE FROM siswa WHERE id_siswa='$ids'");

        if (!$hapusSiswa) {
            throw new Exception("Query hapus gagal"); // Lempar exception jika query gagal
        }

        // Query SELECT untuk memeriksa apakah data sudah tidak ada dalam database setelah dihapus
        $result = mysqli_query($conn, "SELECT * FROM siswa WHERE id_siswa = '$ids'");

        if ($result && mysqli_num_rows($result) === 0) {
            // Data sudah tidak ada dalam database, Anda dapat mengatur pesan flash message berhasil
            $_SESSION['flash_message'] = 'Hapus data siswa berhasil';
            $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
            header('location:siswa.php');
            exit;
        } else {
            // Data masih ada dalam database setelah dihapus, itu berarti gagal
            throw new Exception("Data siswa masih ada dalam database setelah dihapus");
        }
    } catch (Exception $e) {
        // Tangani exception jika terjadi kesalahan
        $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location:siswa.php');
        exit;
    }
}


// Tambah Guru
if(isset($_POST['tambahGuru'])){
    $nip = $_POST['nip'];
    $namaGuru = $_POST['namaGuru'];
    $jk = $_POST['jk'];
    $jabatan = $_POST['jabatan'];

    try {
        // Coba jalankan query insert
        $addGuru = mysqli_query($conn, "INSERT INTO guru (nip, nama_lengkap, jk, jabatan) VALUES ('$nip', '$namaGuru', '$jk', '$jabatan')");

        if (!$addGuru) {
            throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
        }

        // Query SELECT untuk memeriksa apakah data sudah masuk ke database
        $result = mysqli_query($conn, "SELECT * FROM guru WHERE nip = '$nip'");

        if ($result && mysqli_num_rows($result) > 0) {
            // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
            $_SESSION['flash_message'] = 'Tambah data guru berhasil';
            $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
            header('location:guru.php');
            exit;
        } else {
            // Data tidak ada dalam database setelah insert, itu berarti gagal
            throw new Exception("Data guru tidak ditemukan setelah ditambahkan");
        }
    } catch (Exception $e) {
        // Tangani exception jika terjadi kesalahan
        $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location:guru.php');
        exit;
    }
}


//Edit Guru
if(isset($_POST['editGuru'])){
    $nip = $_POST['nip'];
    $namaGuru = $_POST['namaGuru'];
    $jk = $_POST['jk'];
    $jabatan= $_POST['jabatan'];
    $idg = $_POST['idg'];

    try {
        // Coba jalankan query update
        $editGuru = mysqli_query($conn, "UPDATE guru SET nip='$nip', nama_lengkap='$namaGuru', jk='$jk', jabatan='$jabatan' WHERE id_guru=$idg");

        if (!$editGuru) {
            throw new Exception("Query update gagal"); // Lempar exception jika query gagal
        }

        // Query SELECT untuk mengambil data yang baru saja diperbarui
        $result = mysqli_query($conn, "SELECT * FROM guru WHERE id_guru = $idg");

        if ($result && mysqli_num_rows($result) > 0) {
            // Data yang baru saja diperbarui ada dalam database, itu berarti edit berhasil
            $_SESSION['flash_message'] = 'Edit data guru berhasil';
            $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
            header('location:guru.php');
            exit;
        } else {
            // Data yang baru saja diperbarui tidak ada dalam database, itu berarti edit gagal
            $_SESSION['flash_message'] = 'Edit data guru gagal';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:guru.php');
            exit;
        }
    } catch (Exception $e) {
        // Tangani exception jika terjadi kesalahan
        $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location:guru.php');
        exit;
    }
}
   


// Hapus Guru
if(isset($_POST['hapusGuru'])){
    $idg = $_POST['idg'];

    try {
        // Coba jalankan query hapus
        $hapusGuru = mysqli_query($conn, "DELETE FROM guru WHERE id_guru='$idg'");

        if (!$hapusGuru) {
            throw new Exception("Query hapus gagal"); // Lempar exception jika query gagal
        }

        // Query SELECT untuk memeriksa apakah data sudah tidak ada dalam database setelah dihapus
        $result = mysqli_query($conn, "SELECT * FROM guru WHERE id_guru = '$idg'");

        if ($result && mysqli_num_rows($result) === 0) {
            // Data sudah tidak ada dalam database, Anda dapat mengatur pesan flash message berhasil
            $_SESSION['flash_message'] = 'Hapus data guru berhasil';
            $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
            header('location:guru.php');
            exit;
        } else {
            // Data masih ada dalam database setelah dihapus, itu berarti gagal
            throw new Exception("Data guru masih ada dalam database setelah dihapus");
        }
    } catch (Exception $e) {
        // Tangani exception jika terjadi kesalahan
        $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location:guru.php');
        exit;
    }
}

    
?>