<?php
require_once 'vendor/autoload.php';
require 'config.php';

use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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


    if (isset($_POST['importExcel'])) {
        // Membaca file Excel yang diunggah
        $inputFileName = $_FILES['formFile']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

        try {
        // Loop melalui baris-baris data Excel (mulai dari baris kedua karena baris pertama biasanya adalah header)
        foreach ($spreadsheet->getActiveSheet()->getRowIterator(2) as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }

            // Ambil data dari kolom-kolom Excel
            $namaSiswa = $data[0];
            $idKelas = $data[1];
            $jk = $data[2];
            $nisn = $data[3];
            $tempatLahir = $data[4];
            $tanggalLahir = $data[5];
            $agama = $data[6];
            $alamat = $data[7];

            // Lakukan operasi INSERT ke tabel "siswa" dalam database
            $sql = "INSERT INTO siswa (nama, id_kelas, jk, nisn, tempat_lahir, tanggal_lahir, agama, alamat) VALUES ('$namaSiswa', '$idKelas', '$jk', '$nisn', '$tempatLahir', '$tanggalLahir', '$agama', '$alamat')";
            
            // // Eksekusi query INSERT
            if (!mysqli_query($conn, $sql)) {
                throw new Exception(mysqli_error($conn));
            }
        }

        // Tutup koneksi database
        mysqli_close($conn);

        // Set pesan flash
        $_SESSION['flash_message'] = 'Import Data Siswa Berhasil';
        $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
        header('location:siswa.php');
        exit;
    }
    catch (Exception $e) {
    // Tangani exception di sini
    $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
    $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
    header('location:siswa.php');
    exit;
}}

// Tabungan Masuk
if(isset($_POST['tambahTransTabung'])){
    $tanggal = $_POST['tanggal'];
    
    $kelas = $_POST['kelas'];
    // Menggunakan query untuk mendapatkan id_kelas berdasarkan nama_kelas yang dipilih
    $queryGetKelas = mysqli_query($conn, "SELECT id_kelas FROM kelas WHERE nama_kelas = '$kelas'");

    if ($queryGetKelas && mysqli_num_rows($queryGetKelas) > 0) {
        $kelasData = mysqli_fetch_assoc($queryGetKelas);
        $id_kelas = $kelasData['id_kelas'];
    } else {
        // Kelas tidak ditemukan, tangani kesalahan di sini
        $_SESSION['flash_message'] = 'Kelas tidak ditemukan.';
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location: tabung.php');
        exit;
    }

    $idSiswa = $_POST['id_siswa'];
    $nominal = $_POST['nominal'];
    $guru = $_POST['guru'];
    $keterangan = $_POST['keterangan'];
    $tanggal = date("Y-m-d", strtotime($tanggal));

    try {
        $queryInsertTabung = "INSERT INTO tabung_masuk (`tanggal`, `id_siswa`, `jumlah`, `id_guru`, `keterangan`) VALUES ('$tanggal','$idSiswa','$nominal','$guru','$keterangan')";

        $tabung = mysqli_query($conn, $queryInsertTabung);

        if (!$tabung) {
            throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
        }

        // Query SELECT untuk memeriksa apakah data sudah masuk ke database
        $result = mysqli_query($conn, "SELECT * FROM tabung_masuk WHERE tanggal = '$tanggal' and id_siswa = $idSiswa and jumlah=$nominal");

        if ($result && mysqli_num_rows($result) > 0) {
            // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
            $_SESSION['flash_message'] = 'Tambah tabungan siswa berhasil';
            $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
            header('location:tabung.php');
            exit;
        } else {
            // Data tidak ada dalam database, itu berarti gagal
            throw new Exception("Data transaksi tidak ditemukan setelah ditambahkan");
        }
    } catch (Exception $e) {
        // Tangani exception jika terjadi kesalahan
        $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        echo $queryInsert;
        header('location:tabung.php');
        exit;
    }
}

// Edit Tabungan Masuk
if(isset($_POST['editTransTabung'])){
    $tanggal = $_POST['tanggal'];
    
    $kelas = $_POST['kelas'];
    // Menggunakan query untuk mendapatkan id_kelas berdasarkan nama_kelas yang dipilih
    $queryGetKelas = mysqli_query($conn, "SELECT id_kelas FROM kelas WHERE nama_kelas = '$kelas'");

    if ($queryGetKelas && mysqli_num_rows($queryGetKelas) > 0) {
        $kelasData = mysqli_fetch_assoc($queryGetKelas);
        $id_kelas = $kelasData['id_kelas'];
    } else {
        // Kelas tidak ditemukan, tangani kesalahan di sini
        $_SESSION['flash_message'] = 'Kelas tidak ditemukan.';
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location: tabung.php');
        exit;
    }

    $siswa = $_POST['id_siswa'];
    // Menggunakan query untuk mendapatkan id_siswa berdasarkan nama_siswa yang dipilih
    $queryGetSiswa = mysqli_query($conn, "SELECT id_siswa FROM siswa WHERE nama = '$siswa'");

    if ($queryGetSiswa && mysqli_num_rows($queryGetSiswa) > 0) {
        $siswaData = mysqli_fetch_assoc($queryGetSiswa);
        $idSiswa = $siswaData['id_siswa'];
    } else {
        // siswa tidak ditemukan, tangani kesalahan di sini
        $_SESSION['flash_message'] = 'Siswa tidak ditemukan.';
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location: tabung.php');
        exit;
    }

    $nominal = $_POST['nominal'];
    $guru = $_POST['guru'];
    // Menggunakan query untuk mendapatkan id_guru berdasarkan nama_guru yang dipilih
    $queryGetGuru = mysqli_query($conn, "SELECT id_guru FROM guru WHERE nama_lengkap = '$guru'");

    if ($queryGetGuru && mysqli_num_rows($queryGetGuru) > 0) {
        $guruData = mysqli_fetch_assoc($queryGetGuru);
        $idGuru = $guruData['id_guru'];
    } else {
        // siswa tidak ditemukan, tangani kesalahan di sini
        $_SESSION['flash_message'] = 'Guru tidak ditemukan.';
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location: tabung.php');
        exit;
    }

    $keterangan = $_POST['keterangan'];
    $tanggal = date("Y-m-d", strtotime($tanggal));
    $idTbMasuk = $_POST['id_tb_masuk'];

    try {
        $queryUpdatetTabung = "UPDATE tabung_masuk SET tanggal='$tanggal', id_siswa='$idSiswa', jumlah='$nominal', id_guru='$idGuru', keterangan='$keterangan' WHERE id_tb_masuk='$idTbMasuk'";
        $updateTabung = mysqli_query($conn, $queryUpdatetTabung);

        if (!$updateTabung) {
            throw new Exception("Query update gagal"); // Lempar exception jika query gagal
        }

        // Query SELECT untuk memeriksa apakah data sudah masuk ke database
        $result = mysqli_query($conn, "SELECT * FROM tabung_masuk WHERE tanggal = '$tanggal' and id_siswa = $idSiswa and jumlah=$nominal");

        if ($result && mysqli_num_rows($result) > 0) {
            // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
            $_SESSION['flash_message'] = 'Edit tabungan siswa berhasil';
            $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
            header('location:tabung.php');
            exit;
        } else {
            // Data tidak ada dalam database, itu berarti gagal
            throw new Exception("Data transaksi tidak berubah setelah diubah");
        }
    } catch (Exception $e) {
        //Tangani exception jika terjadi kesalahan
        $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        echo $queryInsert;
        header('location:tabung.php');
        exit;
    }
}

// Hapus Transaksi Menabung
if(isset($_POST['hapusTransaksiMenabung'])){
    $idTbMasuk = $_POST['id_tb_masuk'];

    try {
        // Coba jalankan query hapus
        $hapusTransaksiMenabung = mysqli_query($conn, "DELETE FROM tabung_masuk WHERE id_tb_masuk='$idTbMasuk'");

        if (!$hapusTransaksiMenabung) {
            throw new Exception("Query hapus gagal"); // Lempar exception jika query gagal
        }

        // Query SELECT untuk memeriksa apakah data sudah tidak ada dalam database setelah dihapus
        $result = mysqli_query($conn, "SELECT * FROM tabung_masuk WHERE id_tb_masuk='$idTbMasuk'");

        if ($result && mysqli_num_rows($result) === 0) {
            // Data sudah tidak ada dalam database, set pesan flash message berhasil
            $_SESSION['flash_message'] = 'Hapus data guru berhasil';
            $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
            header('location:tabung.php');
            exit;
        } else {
            // Data masih ada dalam database setelah dihapus, set pesan flash message gagal
            throw new Exception("Data guru masih ada dalam database setelah dihapus");
        }
    } catch (Exception $e) {
        // Tangani exception jika terjadi kesalahan
        $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
        $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
        header('location:tabung.php');
        exit;
    }
}

// Ubah Tahun Ajar
if (isset($_POST['tambahTahunAjar'])) {
    $tahun_ajar = $_POST['tahunAjar'];
    
}



    //$insertTA = mysqli_query($conn, "INSERT INTO tahun_ajar (tahun_ajar) VALUES ('$tahun_ajar')");
    //$tahun_ajar = $tahun_ajar;



    
?>