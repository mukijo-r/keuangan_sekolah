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
            $addSiswa = mysqli_query($conn, "INSERT INTO siswa (nisn, nama, id_kelas, jk, tempat_lahir, tanggal_lahir, agama, alamat, `status`) VALUES ('$nisn', '$namaSiswa', $kelas, '$jk', '$kotaLahir', '$tanggalLahir', '$agama', '$alamat', 'aktif')");

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
        $status = $_POST['status'];
        if ($status == 'drop out') {
            $kelas = 404;
        }

        $tanggalLahir = date("Y-m-d", strtotime($tglLahir));

        try {
            // Coba jalankan query update
            $editSiswa = mysqli_query($conn, "UPDATE siswa 
            SET 
            nisn='$nisn', 
            nama='$namaSiswa', 
            id_kelas='$kelas', 
            jk='$jk', 
            tempat_lahir='$kotaLahir', 
            tanggal_lahir='$tanggalLahir', 
            agama='$agama', 
            alamat='$alamat', 
            `status`='$status' 
            WHERE 
            id_siswa='$ids'");

            if (!$editSiswa) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah benar-benar diperbarui dalam database
            $queryUpdate = "SELECT * FROM siswa 
            WHERE 
            id_siswa = '$ids' AND
            nisn='$nisn' AND
            nama='$namaSiswa' AND 
            id_kelas='$kelas' AND
            jk='$jk' AND 
            tempat_lahir='$kotaLahir' AND 
            tanggal_lahir='$tanggalLahir' AND
            agama='$agama' AND 
            alamat='$alamat' AND
            `status`='$status'
            ";
            $result = mysqli_query($conn, $queryUpdate);

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
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryUpdate . $e->getMessage();
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

    //Menaikkan siswa
    if (isset($_POST['naikkanSiswa'])) {
        try {
            // Jalankan query update
            $querySiswa = mysqli_query($conn, "SELECT id_siswa, nama, id_kelas FROM siswa WHERE id_kelas IN (1, 2, 3, 4, 5)");
    
            while ($row = mysqli_fetch_assoc($querySiswa)) {
                $idSiswa = $row['id_siswa'];
                $nama = $row['nama'];
                $idKelas = $row['id_kelas'];
                $idKelasBaru = $idKelas + 1;
                
                // Eksekusi query update di sini
                $naikkanSiswa = mysqli_query($conn, "UPDATE siswa SET id_kelas='$idKelasBaru' WHERE id_siswa='$idSiswa'");
                
                if (!$naikkanSiswa) {
                    throw new Exception("Naikkan siswa gagal");
                }
            }
    
            // Tambahkan kode Anda untuk memeriksa apakah data sudah diperbarui dengan benar
    
            $_SESSION['flash_message'] = 'Naikkan siswa berhasil';
            $_SESSION['flash_message_class'] = 'alert-success';
            header('location:siswa.php');
            exit;
        } catch (Exception $e) {
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger';
            header('location:siswa.php');
            exit;
        }
    }

    //Meluluskan siswa
    if (isset($_POST['luluskanSiswa'])) {
        try {
            // Jalankan query select
            $querySiswa = mysqli_query($conn, "SELECT id_siswa, nama, id_kelas FROM siswa WHERE id_kelas = 6");
    
            while ($row = mysqli_fetch_assoc($querySiswa)) {
                $idSiswa = $row['id_siswa'];
                $nama = $row['nama'];
                $idKelas = $row['id_kelas'];
                $idKelasBaru = 404;
                
                // Eksekusi query update di sini
                $luluskanSiswa = mysqli_query($conn, "UPDATE siswa SET `id_kelas`= $idKelasBaru, `status` = 'lulus' WHERE id_siswa='$idSiswa'");
                
                if (!$luluskanSiswa) {
                    throw new Exception("Luluskan siswa gagal");
                }
            }
    
            // Tambahkan kode Anda untuk memeriksa apakah data sudah diperbarui dengan benar
    
            $_SESSION['flash_message'] = 'Luluskan siswa berhasil, siswa masuk ke data alumni';
            $_SESSION['flash_message_class'] = 'alert-success';
            header('location:siswa.php');
            exit;
        } catch (Exception $e) {
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger';
            header('location:siswa.php');
            exit;
        }
    }
    
    // Tarik Siswa Lama
    if(isset($_POST['tarikSiswa'])){
        $ids = $_POST['idSiswa'];
        $idKelas = $_POST['idKelas'];
        $status = "aktif";       

        try {
            // Coba jalankan query update
            $quryEditSiswa = "UPDATE siswa 
            SET 
            id_kelas='$idKelas', 
            `status`='$status' 
            WHERE 
            id_siswa='$ids'";

            $editSiswa = mysqli_query($conn, $quryEditSiswa);

            if (!$editSiswa) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah benar-benar diperbarui dalam database
            $cekQuery = "SELECT * FROM siswa 
            WHERE 
            id_siswa = '$ids' AND
            id_kelas='$idKelas' AND
            `status`='$status'
            ";
            $result = mysqli_query($conn, $cekQuery);

            if ($result && mysqli_num_rows($result) == 1) {
                // Data sudah benar-benar diperbarui dalam database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tarik siswa lama berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:siswa.php');
                exit;
            } else {
                // Data tidak ada dalam database setelah edit, itu berarti gagal
                throw new Exception("Data siswa tidak ditemukan setelah ditarik");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $cekQuery . $e->getMessage();
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

    // Import Excel
    if (isset($_POST['importExcel'])) {
        // Membaca file Excel yang diunggah
        $inputFileName = $_FILES['formFile']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

        try {
        // Loop melalui baris-baris data Excel (mulai dari baris kedua karena baris pertama biasanya adalah header)
        foreach ($spreadsheet->getActiveSheet()->getRowIterator(1) as $row) {
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
            $status = $data[8];

            // Lakukan operasi INSERT ke tabel "siswa" dalam database
            $sql = "INSERT INTO siswa (nama, id_kelas, jk, nisn, tempat_lahir, tanggal_lahir, agama, alamat, `status`) VALUES ('$namaSiswa', '$idKelas', '$jk', '$nisn', '$tempatLahir', '$tanggalLahir', '$agama', '$alamat', '$status')";
            
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
    }
    }

    // Tabungan Masuk
    if(isset($_POST['tambahTransTabung'])){
        $tanggal = $_POST['tanggal'];
        $tanggalTabung = date("Y-m-d H:i", strtotime($tanggal));

        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: tabung.php');
            exit;
        }
        
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
        $bulan = $_POST['bulan'];;

        try {
            $queryInsertTabung = "INSERT INTO tabung_masuk (tanggal, id_tahun_ajar, id_kategori, bulan, id_siswa, jumlah, id_guru, keterangan) VALUES ('$tanggalTabung', '$idTahunAjar', '8', '$bulan','$idSiswa','$nominal','$guru','$keterangan')";
                
            $tabung = mysqli_query($conn, $queryInsertTabung);

            if (!$tabung) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $result = mysqli_query($conn, "SELECT * FROM tabung_masuk WHERE bulan = '$bulan' and id_siswa = $idSiswa and jumlah=$nominal");

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
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryInsertTabung . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            echo $queryInsertTabung;
            header('location:tabung.php');
            exit;
        }
    }

    // Edit Tabungan Masuk
    if(isset($_POST['editTransTabung'])){
        $bulan = $_POST['bulan'];
        
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
        $tanggalTabung = date("Y-m-d H:i", strtotime($tanggal));
        $idTbMasuk = $_POST['id_tb_masuk'];

        try {
            $queryUpdatetTabung = "UPDATE tabung_masuk SET bulan='$bulan', id_siswa='$idSiswa', jumlah='$nominal', id_guru='$idGuru', keterangan='$keterangan' WHERE id_tb_masuk='$idTbMasuk'";
            $updateTabung = mysqli_query($conn, $queryUpdatetTabung);

            if (!$updateTabung) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $result = mysqli_query($conn, "SELECT * FROM tabung_masuk WHERE bulan = '$bulan' and id_siswa = $idSiswa and jumlah=$nominal");

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
                $_SESSION['flash_message'] = 'Hapus data tabungan berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:tabung.php');
                exit;
            } else {
                // Data masih ada dalam database setelah dihapus, set pesan flash message gagal
                throw new Exception("Data tabungan masih ada dalam database setelah dihapus");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:tabung.php');
            exit;
        }
    }

    // Ambil Tabungan
    if(isset($_POST['ambilTab'])){
        $tanggal = $_POST['tanggal'];
        $tanggalAmbil = date("Y-m-d H:i", strtotime($tanggal));
        $idSiswa = $_POST['ids'];
        $ambilTab = $_POST['jumlahAmbil'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];
        
        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: tabung.php');
            exit;
        }

        try {
            // Coba jalankan query
            $queryambilTabungan = "INSERT INTO tabung_ambil
            (`tanggal`, `id_tahun_ajar`, `id_kategori`, `id_siswa`, `jumlah`, `id_guru`, `keterangan`) 
            VALUES ('$tanggalAmbil','$idTahunAjar', '8','$idSiswa','$ambilTab','$idGuru','$keterangan')";
            
            $ambilTabungan = mysqli_query($conn, $queryambilTabungan);

            if (!$ambilTabungan) {
                throw new Exception("Query ambil gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah ada dalam database setelah ditambahkan
            $result = mysqli_query($conn, "SELECT * FROM tabung_ambil WHERE id_siswa='$idSiswa' AND jumlah='$ambilTab'");

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah tidak ada dalam database, set pesan flash message berhasil
                $_SESSION['flash_message'] = 'Ambil Tabungan berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:tabung_list.php');
                exit;
            } else {
                // Data masih ada dalam database setelah dihapus, set pesan flash message gagal
                throw new Exception("Data tabungan masih ada dalam database setelah diambil");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryambilTabungan . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:tabung_list.php');
            exit;
        }
    }

    // Tambah Kategori Kas
    if(isset($_POST['tambahKategoriKas'])){
        $jenisKas = $_POST['jenisKas'];
        $kelompok = $_POST['kelompok'];
        $namaGuru = $_POST['guru'];
        $kode = $_POST['kode'];
        $keterangan = $_POST['keterangan'];

        try {
            $queryInsertKategori = "INSERT INTO `kategori`(`nama_kategori`, `kelompok`, `id_guru`, `kode`, `keterangan`) VALUES ('$jenisKas', '$kelompok', '$namaGuru', '$kode', '$keterangan')";
                
            $kategori = mysqli_query($conn, $queryInsertKategori);

            if (!$kategori) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $result = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori='$jenisKas'");

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah kategori berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:kategori_kas.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryInsertKategori . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            echo $queryInsertTabung;
            header('location:kategori_kas.php');
            exit;
        }
    }

    // Edit Kategori Kas
    if(isset($_POST['ubahKategoriKas'])){
        $idKas = $_POST['idk'];
        $jenisKas = $_POST['jenisKas'];
        $kelompok = $_POST['kelompok'];
        $idGuru = $_POST['guru'];
        $kode = $_POST['kode'];
        $keterangan = $_POST['keterangan'];

        try {
            $queryUpdateKategori = "UPDATE `kategori` SET `nama_kategori`='$jenisKas', `kelompok`='$kelompok', `id_guru`='$idGuru', `kode`='$kode', `keterangan`='$keterangan' WHERE `id_kategori`='$idKas'";
            
            $kategori = mysqli_query($conn, $queryUpdateKategori);

            if (!$kategori) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database

            $queryResult = "SELECT * FROM kategori WHERE nama_kategori='$jenisKas' AND `kelompok`='$kelompok' AND `id_guru`='$idGuru' AND `kode`='$kode' AND `keterangan`='$keterangan'";
            $result = mysqli_query($conn, $queryResult);

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Ubah kategori berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:kategori_kas.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak ditemukan setelah diubah");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryResult . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            echo $queryInsertTabung;
            header('location:kategori_kas.php');
            exit;
        }
    }

    // Hapus Kategori Kas
    if(isset($_POST['hapusKategoriKas'])){
        $idKas = $_POST['idk'];

        try {
            $queryHapusKategori = "DELETE from `kategori` WHERE `id_kategori`='$idKas'";          
            $kategori = mysqli_query($conn, $queryHapusKategori);

            if (!$kategori) {
                throw new Exception("Query hapus gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $result = mysqli_query($conn, "SELECT * FROM kategori WHERE `id_kategori`='$idKas'");

            if ($result && mysqli_num_rows($result) === 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Hapus kategori berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:kategori_kas.php');
                exit;
            } else {
                // Data masih ada dalam database, itu berarti gagal
                throw new Exception("Data masih ada setelah dihapus");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            echo $queryInsertTabung;
            header('location:kategori_kas.php');
            exit;
        }
    }

    // Tambah Item Penetapan
    if(isset($_POST['tambahPenetapan'])){    
        $siswa = $_POST['siswa'];
        $subKategori = $_POST['subKategori'];
        $nominal = $_POST['nominal'];

        try {
            if ($siswa == '0') {
                // Jika "Semua" dipilih, maka query insert akan diterapkan untuk semua siswa
                $querySiswa = mysqli_query($conn, "SELECT id_siswa FROM siswa");
                while ($row = mysqli_fetch_assoc($querySiswa)) {
                    $id_siswa = $row['id_siswa'];
                    $queryInsertPenetapan = "INSERT INTO `penetapan`(`id_siswa`, `id_sub_kategori`, `nominal`) 
                    VALUES ('$id_siswa','$subKategori','$nominal')";
                    // Eksekusi query di sini
                    $penetapan = mysqli_query($conn, $queryInsertPenetapan);
                }
            } else {
                // Jika siswa tertentu dipilih, maka query insert akan diterapkan hanya untuk siswa tersebut
                $queryInsertPenetapan = "INSERT INTO `penetapan`(`id_siswa`, `id_sub_kategori`, `nominal`) 
                VALUES ('$siswa','$subKategori','$nominal')";
                // Eksekusi query di sini
                $penetapan = mysqli_query($conn, $queryInsertPenetapan);
            }
            
            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $result = mysqli_query($conn, "SELECT * FROM penetapan WHERE `id_sub_kategori`='$subKategori' AND id_siswa=$siswa");      

            if (!$penetapan) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // if ($result && mysqli_num_rows($result) === 1) {
            if ($result) {    
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah penetapan berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:penetapan.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:penetapan.php');
            exit;
        }
    }

    // Edit Item Penetapan
    if(isset($_POST['editPenetapan'])){    
        $siswa = $_POST['siswa'];
        $subKategori = $_POST['subKategori'];
        $nominal = $_POST['nominal'];

        try {
            if ($siswa == '0') {
                // Jika "Semua" dipilih, maka query insert akan diterapkan untuk semua siswa
                $querySiswa = mysqli_query($conn, "SELECT id_siswa FROM siswa");
                while ($row = mysqli_fetch_assoc($querySiswa)) {
                    $id_siswa = $row['id_siswa'];
                    $queryUpdatePenetapan = "UPDATE `penetapan` SET `nominal`='$nominal' WHERE `id_sub_kategori`='$subKategori'"; 
                    $penetapan = mysqli_query($conn, $queryUpdatePenetapan);
                }
            } else {
                // Jika siswa tertentu dipilih, maka query insert akan diterapkan hanya untuk siswa tersebut
                $queryUpdatePenetapan = "UPDATE `penetapan` SET `nominal`='$nominal' WHERE `id_sub_kategori`='$subKategori' AND `id_siswa`='$siswa'";           
                $penetapan = mysqli_query($conn, $queryUpdatePenetapan);
            }        

            if (!$penetapan) {
                throw new Exception("Query Update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $result = mysqli_query($conn, "SELECT * FROM penetapan WHERE `id_sub_kategori`='$subKategori' AND `nominal`='$nominal'");

            if ($result && mysqli_num_rows($result) >= 1) {
                // Data sudah masuk ke database, pesan flash message berhasil
                $_SESSION['flash_message'] = 'Update kategori berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; 
                header('location:penetapan.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak ditemukan setelah diupdate");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:penetapan.php');
            exit;
        }
    }

    // Hapus Item Penetapan
    if(isset($_POST['hapusPenetapan'])){    
        $siswa = $_POST['siswa'];
        $subKategori = $_POST['subKategori'];
        $nominal = $_POST['nominal'];

        try {
            if ($siswa == '0') {
                // Jika "Semua" dipilih, maka query delete akan diterapkan untuk semua siswa
                $querySiswa = mysqli_query($conn, "SELECT id_siswa FROM siswa");
                while ($row = mysqli_fetch_assoc($querySiswa)) {
                    $id_siswa = $row['id_siswa'];
                    $queryHapusPenetapan = "DELETE FROM `penetapan` WHERE `id_sub_kategori`='$subKategori'"; 
                    $penetapan = mysqli_query($conn, $queryHapusPenetapan);
                }
            } else {
                // Jika siswa tertentu dipilih, maka query insert akan diterapkan hanya untuk siswa tersebut
                $queryHapusPenetapan = "DELETE FROM `penetapan` WHERE `id_sub_kategori`='$subKategori' AND `id_siswa`='$siswa'";           
                $penetapan = mysqli_query($conn, $queryHapusPenetapan);
            }        

            if (!$penetapan) {
                throw new Exception("Query Update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $result = mysqli_query($conn, "SELECT * FROM penetapan WHERE `id_sub_kategori`='$subKategori' AND `id_siswa`='$siswa' ");

            if ($result && mysqli_num_rows($result) === 0) {        
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Hapus kategori berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:penetapan.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data masih ada setelah dihapus");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:penetapan.php');
            exit;
        }
    }

    // Tambah Transaksi Masuk Siswa
    if(isset($_POST['tambahTransMasukSiswa'])){
        $tanggal = $_POST['tanggal'];
        $tanggalBayar = date("Y-m-d H:i:s", strtotime($tanggal));

        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: transaksi_masuk_siswa.php');
            exit;
        }
        
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
            header('location: transaksi_masuk_siswa.php');
            exit;
        }

        $idSiswa = $_POST['siswa'];
        $idSubKategori = $_POST['subKategori']; 
        // Menggunakan query untuk mendapatkan id_kategori berdasarkan id_subkategori yang dipilih
        $queryGetKategori = mysqli_query($conn, "SELECT id_kategori FROM sub_kategori_siswa WHERE id_sub_kategori = '$idSubKategori'");
        $rowKategori = mysqli_fetch_assoc($queryGetKategori);

        $id_kategori = $rowKategori['id_kategori'];
        $bulan = $_POST['bulan'];
        $penetapan = $_POST['nominal'];
        $bulanIni = $_POST['bulanIni'];
        $tunggakan = $_POST['tunggakan'];
        if ($tunggakan == ''){
            $tunggakan = 0;
        }
        $jumlah = $bulanIni + $tunggakan;
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];    

        try {
            $queryInsertTransSiswa = "INSERT INTO `transaksi_masuk_siswa`(`tanggal`, `id_tahun_ajar`, `id_siswa`, `id_kategori`, `id_sub_kategori`, `bulan`, `penetapan`, `bulan_ini`, `tunggakan`,`jumlah`, `id_guru`, `keterangan`) 
            VALUES 
            ('$tanggalBayar','$idTahunAjar','$idSiswa','$id_kategori','$idSubKategori','$bulan', '$penetapan', '$bulanIni', '$tunggakan', '$jumlah','$idGuru','$keterangan')";
                
            $insertTransSiswa = mysqli_query($conn, $queryInsertTransSiswa);

            if (!$insertTransSiswa) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $result = mysqli_query($conn, "SELECT * FROM `transaksi_masuk_siswa` WHERE bulan = '$bulan' and id_siswa = $idSiswa and jumlah=$jumlah");

            if ($result && mysqli_num_rows($result) > 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah transaksi siswa berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_masuk_siswa.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryInsertTransSiswa . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_masuk_siswa.php');
            exit;
        }
    }

    // Tambah Transaksi Masuk Siswa Kolektif
    if(isset($_POST['tambahTransSiswaKolektif'])){
        $tanggal = $_POST['tanggalKolektif'];
        $timestamp = strtotime($tanggal);
        $angkaBulan = date("m", $timestamp);

        if ($angkaBulan == '01') {
            $bulan = 'Januari';
        } elseif ($angkaBulan == '02') {
            $bulan = 'Februari';
        } elseif ($angkaBulan == '03') {
            $bulan = 'Maret';
        } elseif ($angkaBulan == '04') {
            $bulan = 'April';
        } elseif ($angkaBulan == '05') {
            $bulan = 'Mei';
        } elseif ($angkaBulan == '06') {
            $bulan = 'Juni';
        } elseif ($angkaBulan == '07') {
            $bulan = 'Juli';
        } elseif ($angkaBulan == '08') {
            $bulan = 'Agustus';
        } elseif ($angkaBulan == '09') {
            $bulan = 'September';
        } elseif ($angkaBulan == '10') {
            $bulan = 'Oktober';
        } elseif ($angkaBulan == '11') {
            $bulan = 'November';
        } elseif ($angkaBulan == '12') {
            $bulan = 'Desember';
        } else {
            $bulan = 'Bulan Tidak valid';
        }

        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: siswa.php');
            exit;
        }
        
        $kelas = $_POST['kelasKolektif'];
        // Menggunakan query untuk mendapatkan id_siswa berdasarkan nama_kelas yang dipilih
        $queryGetSiswa = mysqli_query($conn, "SELECT id_siswa FROM siswa WHERE id_kelas = '$kelas'");

        $idSubKategori = $_POST['subKategoriKolektif']; 
        // Menggunakan query untuk mendapatkan id_kategori berdasarkan id_subkategori yang dipilih
        $queryGetKategori = mysqli_query($conn, "SELECT id_kategori FROM sub_kategori_siswa WHERE id_sub_kategori = '$idSubKategori'");
        $rowKategori = mysqli_fetch_assoc($queryGetKategori);
        $idKategori = $rowKategori['id_kategori'];

        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];    

        try {
            if ($queryGetSiswa && mysqli_num_rows($queryGetSiswa) > 0) {
                $siswaArray = []; // Buat array untuk menyimpan data siswa
            
                while ($row = mysqli_fetch_assoc($queryGetSiswa)) {
                    $siswaArray[] = $row['id_siswa']; // Tambahkan id_siswa ke dalam array
                }               
            
                for ($i = 0; $i < count($siswaArray); $i++) {
                    $idSiswa = $siswaArray[$i];
                    $tanggalBayar = date("Y-m-d H:i:$i", strtotime($tanggal));
                    $queryGetPenetapan = "SELECT nominal FROM penetapan WHERE id_sub_kategori = '$idSubKategori' AND id_siswa = '$idSiswa'";
                    $getPenetapan = mysqli_query($conn, $queryGetPenetapan);
                    $rowPenetapan = mysqli_fetch_assoc($getPenetapan);
                    $penetapan = floatval($rowPenetapan['nominal']);
                    $bulanIni = $penetapan;
                    $tunggakan = 0;
                    $jumlah = $bulanIni + $tunggakan;

                    $queryInsertTransSiswa = "INSERT INTO `transaksi_masuk_siswa`(`tanggal`, `id_tahun_ajar`, `id_siswa`, `id_kategori`, `id_sub_kategori`, `bulan`, `penetapan`, `bulan_ini`, `tunggakan`, `jumlah`, `id_guru`, `keterangan`) 
                    VALUES 
                    ('$tanggalBayar','$idTahunAjar','$idSiswa','$idKategori','$idSubKategori','$bulan', '$penetapan', '$bulanIni', '$tunggakan', '$jumlah','$idGuru','$keterangan')";
            
                    $insertTransSiswa = mysqli_query($conn, $queryInsertTransSiswa);
                }
            }           

            if (!$insertTransSiswa) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $result = mysqli_query($conn, "SELECT * FROM `transaksi_masuk_siswa` WHERE bulan = '$bulan' and id_siswa = $idSiswa and jumlah=$jumlah");

            if ($result && mysqli_num_rows($result) > 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah transaksi siswa berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_masuk_siswa.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_masuk_siswa.php');
            exit;
        }
    }    

    // Edit Transaksi Masuk Siswa
    if(isset($_POST['editTransSiswa'])){
        $idTransaksiMasukSiswa = $_POST['id_tms_masuk'];
        $tanggal = $_POST['tanggal'];
        $tanggalBayar = date("Y-m-d H:i", strtotime($tanggal));
        

        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: transaksi_masuk_siswa.php');
            exit;
        }
        
        $kelas = $_POST['kelasEdit'];
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

        $siswa = $_POST['siswaEdit'];
        // Menggunakan query untuk mendapatkan id_siswa berdasarkan nama_siswa yang dipilih
        $queryGetSiswa = mysqli_query($conn, "SELECT id_siswa FROM siswa WHERE nama = '$siswa'");

        if ($queryGetSiswa) {
            $siswaData = mysqli_fetch_assoc($queryGetSiswa);
            $idSiswa = $siswaData['id_siswa'];
        } else {
            // siswa tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Siswa tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: transaksi_masuk_siswa.php');
            exit;
        }

        $idSubKategori = $_POST['subKategoriEdit'];
        $getIdKategori = mysqli_query($conn, "SELECT id_kategori FROM sub_kategori_siswa WHERE id_sub_kategori = '$idSubKategori'");
        $rowKategori = mysqli_fetch_assoc($getIdKategori);    
        $idKategori = $rowKategori['id_kategori'];

        $bulan = $_POST['bulanEdit'];
        $penetapan = $_POST['nominalEdit'];
        $bulanIni = $_POST['bulanIniEdit'];
        $tunggakan = $_POST['tunggakanEdit'];
        $jumlah = $_POST['jumlahEdit'];
        $guru = $_POST['guruEdit'];
        // Menggunakan query untuk mendapatkan id_guru berdasarkan nama_guru yang dipilih
        $queryGetGuru = mysqli_query($conn, "SELECT id_guru FROM guru WHERE nama_lengkap = '$guru'");

        if ($queryGetGuru && mysqli_num_rows($queryGetGuru) > 0) {
            $guruData = mysqli_fetch_assoc($queryGetGuru);
            $idGuru = $guruData['id_guru'];
        } else {
            // siswa tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Guru tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:transaksi_masuk_siswa.php');
            exit;
        }

        $keterangan = $_POST['keteranganEdit'];    

        try {
            $queryEditTransSiswa = "UPDATE `transaksi_masuk_siswa` 
            SET 
            `tanggal`='$tanggalBayar', 
            `id_tahun_ajar`='$idTahunAjar', 
            `id_siswa`='$idSiswa',        
            `id_sub_kategori`='$idSubKategori',
            `id_kategori`='$idKategori',  
            `bulan`='$bulan',
            `bulan_ini`='$bulanIni',
            `tunggakan`='$tunggakan', 
            `jumlah`='$jumlah', 
            `id_guru`='$idGuru', 
            `keterangan`='$keterangan'
            WHERE 
            `id_tms`= '$idTransaksiMasukSiswa'";      
                        
            $editTransSiswa = mysqli_query($conn, $queryEditTransSiswa);

            if (!$editTransSiswa) {
                throw new Exception("Query edit gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * 
            FROM `transaksi_masuk_siswa` 
            WHERE
            `tanggal`='$tanggalBayar' AND 
            `id_tahun_ajar`='$idTahunAjar' AND 
            `id_siswa`='$idSiswa' AND         
            `id_sub_kategori`='$idSubKategori' AND
            `id_kategori`='$idKategori' AND 
            `bulan`='$bulan' AND
            `bulan_ini`='$bulanIni' AND 
            `tunggakan`='$tunggakan' AND  
            `jumlah`='$jumlah' AND 
            `id_guru`='$idGuru' AND 
            `keterangan`='$keterangan'";

            $result = mysqli_query($conn, $queryCek);
            

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Edit transaksi siswa berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_masuk_siswa.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi tidak berubah setelah diedit");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryCek . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_masuk_siswa.php');
            exit;
        }
    }

    // Hapus Transaksi Masuk Siswa
    if(isset($_POST['hapusTransaksiSiswa'])){
        $idTransaksiMasukSiswa = $_POST['idTms'];   

        try {
            $queryHapusTransSiswa = "DELETE FROM `transaksi_masuk_siswa` WHERE `id_tms`= '$idTransaksiMasukSiswa'";                     
                        
            $hapusTransSiswa = mysqli_query($conn, $queryHapusTransSiswa);

            if (!$hapusTransSiswa) {
                throw new Exception("Query hapus gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `transaksi_masuk_siswa` 
            WHERE `id_tms`= '$idTransaksiMasukSiswa'";

            $result = mysqli_query($conn, $queryCek);
            

            if ($result && mysqli_num_rows($result) === 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Hapus transaksi siswa berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_masuk_siswa.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi belum terhapus");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryCek . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_masuk_siswa.php');
            exit;
        }
    }

    // Tambah Transaksi Keluar Siswa
    if(isset($_POST['tambahTransKeluarSiswa'])){
        $tanggal = $_POST['tanggal'];
        $tanggalBayar = date("Y-m-d H:i", strtotime($tanggal));
        $timestamp = strtotime($tanggal);
        $angkaBulan = date("m", $timestamp);

        if ($angkaBulan == '01') {
            $bulan = 'Januari';
        } elseif ($angkaBulan == '02') {
            $bulan = 'Februari';
        } elseif ($angkaBulan == '03') {
            $bulan = 'Maret';
        } elseif ($angkaBulan == '04') {
            $bulan = 'April';
        } elseif ($angkaBulan == '05') {
            $bulan = 'Mei';
        } elseif ($angkaBulan == '06') {
            $bulan = 'Juni';
        } elseif ($angkaBulan == '07') {
            $bulan = 'Juli';
        } elseif ($angkaBulan == '08') {
            $bulan = 'Agustus';
        } elseif ($angkaBulan == '09') {
            $bulan = 'September';
        } elseif ($angkaBulan == '10') {
            $bulan = 'Oktober';
        } elseif ($angkaBulan == '11') {
            $bulan = 'November';
        } elseif ($angkaBulan == '12') {
            $bulan = 'Desember';
        } else {
            $bulan = 'Bulan Tidak valid';
        }

        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: transaksi_keluar_siswa.php');
            exit;
        }
        
        $idSubKategori = $_POST['subKategori']; 
        // Menggunakan query untuk mendapatkan id_kategori berdasarkan id_subkategori yang dipilih
        $queryGetKategori = mysqli_query($conn, "SELECT id_kategori FROM sub_kategori_siswa WHERE id_sub_kategori = '$idSubKategori'");
        $rowKategori = mysqli_fetch_assoc($queryGetKategori);

        $id_kategori = $rowKategori['id_kategori'];
        //$bulan = $_POST['bulan'];
        $uraian = $_POST['uraian'];
        $jumlah = $_POST['jumlah'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];    

        try {
            $queryInsertTransSiswa = "INSERT INTO 
            `transaksi_keluar_siswa`(`tanggal`, `id_tahun_ajar`, `id_kategori`, `id_sub_kategori`, `bulan`, `uraian`, `jumlah`, `id_guru`, `keterangan`) 
            VALUES ('$tanggalBayar','$idTahunAjar','$id_kategori','$idSubKategori','$bulan','$uraian','$jumlah','$idGuru','$keterangan')";
                
            $insertTransSiswa = mysqli_query($conn, $queryInsertTransSiswa);

            if (!$insertTransSiswa) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $result = mysqli_query($conn, "SELECT * 
            FROM `transaksi_keluar_siswa` 
            WHERE `tanggal` = '$tanggalBayar'
            AND `id_tahun_ajar` = '$idTahunAjar'
            AND `id_kategori` = '$id_kategori'
            AND `id_sub_kategori` = '$idSubKategori'
            AND `bulan` = '$bulan'
            AND `uraian` = '$uraian'
            AND `jumlah` = '$jumlah'
            AND `id_guru` = '$idGuru'
            AND `keterangan` = '$keterangan'");

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah transaksi keluar berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_keluar_siswa.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_keluar_siswa.php');
            exit;
        }
    }

    // Edit Transaksi Keluar Siswa
    if(isset($_POST['editTransKeluarSiswa'])){
        $idTransaksiKeluarSiswa = $_POST['idTks'];
        $tanggal = $_POST['tanggal'];
        $tanggalBayar = date("Y-m-d H:i", strtotime($tanggal));

        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: transaksi_keluar_siswa.php');
            exit;
        }
        
        $idSubKategori = $_POST['subKategori']; 
        // Menggunakan query untuk mendapatkan id_kategori berdasarkan id_subkategori yang dipilih
        $queryGetKategori = mysqli_query($conn, "SELECT id_kategori FROM sub_kategori_siswa WHERE id_sub_kategori = '$idSubKategori'");
        $rowKategori = mysqli_fetch_assoc($queryGetKategori);
        $id_kategori = $rowKategori['id_kategori'];

        $bulan = $_POST['bulan'];
        $uraian = $_POST['uraian'];
        $jumlah = $_POST['jumlah'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];    

        try {
            $queryUpdateTransSiswa = "UPDATE `transaksi_keluar_siswa` 
            SET 
            `tanggal`='$tanggalBayar',
            `id_tahun_ajar`='$idTahunAjar',
            `id_kategori`='$id_kategori',
            `id_sub_kategori`='$idSubKategori',
            `bulan`='$bulan',
            `uraian`='$uraian',
            `jumlah`='$jumlah',
            `id_guru`='$idGuru',
            `keterangan`='$keterangan' 
            WHERE
            `id_tks`='$idTransaksiKeluarSiswa'
            ";
                
            $updateTransSiswa = mysqli_query($conn, $queryUpdateTransSiswa);

            if (!$updateTransSiswa) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * 
            FROM `transaksi_keluar_siswa` 
            WHERE `tanggal` = '$tanggalBayar' 
            AND `id_tahun_ajar` = '$idTahunAjar' 
            AND `id_kategori` = '$id_kategori' 
            AND `id_sub_kategori` = '$idSubKategori' 
            AND `bulan` = '$bulan' 
            AND `uraian` = '$uraian' 
            AND `jumlah` = '$jumlah' 
            AND `id_guru` = '$idGuru' 
            AND `keterangan` = '$keterangan'";

            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Edit transaksi keluar berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_keluar_siswa.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi tidak ditemukan setelah diubah");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryCek . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_keluar_siswa.php');
            exit;
        }
    }

    // Hapus Transaksi Keluar Siswa
    if(isset($_POST['hapusTransaksiKeluarSiswa'])){
        $idTransaksiKeluarSiswa = $_POST['idTks']; 

        try {
            $queryDeleteTransSiswa = "DELETE FROM `transaksi_keluar_siswa` 
            WHERE
            `id_tks`='$idTransaksiKeluarSiswa'";
                
            $deleteTransSiswa = mysqli_query($conn, $queryDeleteTransSiswa);

            if (!$deleteTransSiswa) {
                throw new Exception("Query hapus gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah terhapus dari database
            $queryCek = "SELECT * 
            FROM `transaksi_keluar_siswa` 
            WHERE `id_tks`='$idTransaksiKeluarSiswa'";

            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 0) {
                // Data sudah tidak ada di database, atur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Hapus transaksi keluar berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_keluar_siswa.php');
                exit;
            } else {
                // Data masih ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi masih ada setelah dihapus");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryCek . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_keluar_siswa.php');
            exit;
        }
    }

    // Tambah Transaksi Masuk Umum
    if(isset($_POST['tambahTransMasukUmum'])){
        $tanggal = $_POST['tanggal'];
        $tanggalBayar = date("Y-m-d H:i", strtotime($tanggal));
        $timestamp = strtotime($tanggal);
        $angkaBulan = date("m", $timestamp);

        if ($angkaBulan == '01') {
            $bulan = 'Januari';
        } elseif ($angkaBulan == '02') {
            $bulan = 'Februari';
        } elseif ($angkaBulan == '03') {
            $bulan = 'Maret';
        } elseif ($angkaBulan == '04') {
            $bulan = 'April';
        } elseif ($angkaBulan == '05') {
            $bulan = 'Mei';
        } elseif ($angkaBulan == '06') {
            $bulan = 'Juni';
        } elseif ($angkaBulan == '07') {
            $bulan = 'Juli';
        } elseif ($angkaBulan == '08') {
            $bulan = 'Agustus';
        } elseif ($angkaBulan == '09') {
            $bulan = 'September';
        } elseif ($angkaBulan == '10') {
            $bulan = 'Oktober';
        } elseif ($angkaBulan == '11') {
            $bulan = 'November';
        } elseif ($angkaBulan == '12') {
            $bulan = 'Desember';
        } else {
            $bulan = 'Bulan Tidak valid';
        }

        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: transaksi_keluar_siswa.php');
            exit;
        }        
        
        $idKategori = $_POST['kategori'];        
        $uraian = $_POST['uraian'];
        $jumlah = $_POST['jumlah'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];    

        try {
            $queryInsertTransUmum = "INSERT INTO 
            `transaksi_masuk_nonsiswa`(`tanggal`, `id_tahun_ajar`, `id_kategori`, `bulan`, `uraian`, `jumlah`, `id_guru`, `keterangan`) 
            VALUES ('$tanggalBayar','$idTahunAjar','$idKategori','$bulan','$uraian','$jumlah','$idGuru','$keterangan')";
                
            $insertTransUmum = mysqli_query($conn, $queryInsertTransUmum);

            if (!$insertTransUmum) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * 
            FROM `transaksi_masuk_nonsiswa` 
            WHERE `tanggal` = '$tanggalBayar'
            AND `id_tahun_ajar` = '$idTahunAjar'
            AND `id_kategori` = '$idKategori'
            AND `bulan` = '$bulan'
            AND `uraian` = '$uraian'
            AND `jumlah` = '$jumlah'
            AND `id_guru` = '$idGuru'
            AND `keterangan` = '$keterangan'";

            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah transaksi masuk berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_masuk_umum.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_masuk_umum.php');
            exit;
        }
    }

    // Edit Transaksi Masuk Umum
    if(isset($_POST['editTransMasukUmum'])){
        $idTransaksiMasukUmum = $_POST['idTmn'];
        $tanggal = $_POST['tanggal'];
        $tanggalBayar = date("Y-m-d H:i", strtotime($tanggal));

        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: transaksi_keluar_siswa.php');
            exit;
        }        
        
        $idKategori = $_POST['kategori'];
        $bulan = $_POST['bulan'];
        $uraian = $_POST['uraian'];
        $jumlah = $_POST['jumlah'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];    

        try {
            $queryUpdateTransUmum = "UPDATE `transaksi_masuk_nonsiswa` SET
            `tanggal`= '$tanggalBayar',
            `id_tahun_ajar`= '$idTahunAjar',
            `id_kategori`= '$idKategori',
            `bulan`= '$bulan',
            `uraian`= '$uraian',
            `jumlah`= '$jumlah',
            `id_guru`= '$idGuru',
            `keterangan`= '$keterangan'
            WHERE
            id_tmn = '$idTransaksiMasukUmum'";            
                
            $updateTransUmum = mysqli_query($conn, $queryUpdateTransUmum);

            if (!$updateTransUmum) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * 
            FROM `transaksi_masuk_nonsiswa` 
            WHERE `tanggal` = '$tanggalBayar'
            AND `id_tahun_ajar` = '$idTahunAjar'
            AND `id_kategori` = '$idKategori'
            AND `bulan` = '$bulan'
            AND `uraian` = '$uraian'
            AND `jumlah` = '$jumlah'
            AND `id_guru` = '$idGuru'
            AND `keterangan` = '$keterangan'";

            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Ubah pemasukan berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_masuk_umum.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi tidak ditemukan setelah diubah");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_masuk_umum.php');
            exit;
        }
    }

    // Hapus Transaksi Masuk Umum
    if(isset($_POST['hapusTransaksiMasukUmum'])){
        $idTransaksiMasukUmum = $_POST['idTmn'];  

        try {
            $queryDeleteTransUmum = "DELETE FROM `transaksi_masuk_nonsiswa`
            WHERE
            id_tmn = '$idTransaksiMasukUmum'";            
                
            $deleteTransUmum = mysqli_query($conn, $queryDeleteTransUmum);

            if (!$deleteTransUmum) {
                throw new Exception("Query delete gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * 
            FROM `transaksi_masuk_nonsiswa` 
            WHERE id_tmn = '$idTransaksiMasukUmum'";

            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Hapus transaksi masuk berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_masuk_umum.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi masih ada setelah dihapus");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryDeleteTransUmum . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_masuk_umum.php');
            exit;
        }
    }

    // Tambah Transaksi Keluar Umum
    if(isset($_POST['tambahTransKeluarUmum'])){
        $tanggal = $_POST['tanggal'];
        $tanggalBayar = date("Y-m-d H:i", strtotime($tanggal));
        $timestamp = strtotime($tanggal);
        $angkaBulan = date("m", $timestamp);

        if ($angkaBulan == '01') {
            $bulan = 'Januari';
        } elseif ($angkaBulan == '02') {
            $bulan = 'Februari';
        } elseif ($angkaBulan == '03') {
            $bulan = 'Maret';
        } elseif ($angkaBulan == '04') {
            $bulan = 'April';
        } elseif ($angkaBulan == '05') {
            $bulan = 'Mei';
        } elseif ($angkaBulan == '06') {
            $bulan = 'Juni';
        } elseif ($angkaBulan == '07') {
            $bulan = 'Juli';
        } elseif ($angkaBulan == '08') {
            $bulan = 'Agustus';
        } elseif ($angkaBulan == '09') {
            $bulan = 'September';
        } elseif ($angkaBulan == '10') {
            $bulan = 'Oktober';
        } elseif ($angkaBulan == '11') {
            $bulan = 'November';
        } elseif ($angkaBulan == '12') {
            $bulan = 'Desember';
        } else {
            $bulan = 'Bulan Tidak valid';
        }

        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: transaksi_keluar_siswa.php');
            exit;
        }        
        
        $idKategori = $_POST['kategori'];        
        $uraian = $_POST['uraian'];
        $jumlah = $_POST['jumlah'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];    

        try {
            $queryInsertTransUmum = "INSERT INTO 
            `transaksi_keluar_nonsiswa`(`tanggal`, `id_tahun_ajar`, `id_kategori`, `bulan`, `uraian`, `jumlah`, `id_guru`, `keterangan`) 
            VALUES ('$tanggalBayar','$idTahunAjar','$idKategori','$bulan','$uraian','$jumlah','$idGuru','$keterangan')";
                
            $insertTransUmum = mysqli_query($conn, $queryInsertTransUmum);

            if (!$insertTransUmum) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * 
            FROM `transaksi_keluar_nonsiswa` 
            WHERE `tanggal` = '$tanggalBayar'
            AND `id_tahun_ajar` = '$idTahunAjar'
            AND `id_kategori` = '$idKategori'
            AND `bulan` = '$bulan'
            AND `uraian` = '$uraian'
            AND `jumlah` = '$jumlah'
            AND `id_guru` = '$idGuru'
            AND `keterangan` = '$keterangan'";

            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah pengeluaran berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_keluar_umum.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryInsertTransUmum . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_keluar_umum.php');
            exit;
        }
    }

    // Edit Transaksi Keluar Umum
    if(isset($_POST['editTransKeluarUmum'])){
        $idTransaksiKeluarUmum = $_POST['idTkn'];
        $tanggal = $_POST['tanggal'];
        $tanggalBayar = date("Y-m-d H:i", strtotime($tanggal));

        // Menggunakan query untuk mendapatkan id_tahun_ajar berdasarkan tahun_ajar yang dipilih
        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        } else {
            // Kelas tidak ditemukan, tangani kesalahan di sini
            $_SESSION['flash_message'] = 'Tahun ajar tidak ditemukan.';
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location: transaksi_keluar_siswa.php');
            exit;
        }        
        
        $idKategori = $_POST['kategori'];
        $bulan = $_POST['bulan'];
        $uraian = $_POST['uraian'];
        $jumlah = $_POST['jumlah'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];    

        try {
            $queryUpdateTransUmum = "UPDATE `transaksi_keluar_nonsiswa` SET
            `tanggal`= '$tanggalBayar',
            `id_tahun_ajar`= '$idTahunAjar',
            `id_kategori`= '$idKategori',
            `bulan`= '$bulan',
            `uraian`= '$uraian',
            `jumlah`= '$jumlah',
            `id_guru`= '$idGuru',
            `keterangan`= '$keterangan'
            WHERE
            id_tkn = '$idTransaksiKeluarUmum'";            
                
            $updateTransUmum = mysqli_query($conn, $queryUpdateTransUmum);

            if (!$updateTransUmum) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * 
            FROM `transaksi_keluar_nonsiswa` 
            WHERE `tanggal` = '$tanggalBayar'
            AND `id_tahun_ajar` = '$idTahunAjar'
            AND `id_kategori` = '$idKategori'
            AND `bulan` = '$bulan'
            AND `uraian` = '$uraian'
            AND `jumlah` = '$jumlah'
            AND `id_guru` = '$idGuru'
            AND `keterangan` = '$keterangan'";

            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Ubah transaksi pengeluaran berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_keluar_umum.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data transaksi tidak ditemukan setelah diubah");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryUpdateTransUmum . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_keluar_umum.php');
            exit;
        }
    }

    // Hapus Transaksi Keluar Umum
    if(isset($_POST['hapusTransaksiKeluarUmum'])){
        $idTransaksiKeluarUmum = $_POST['idTkn'];  

        try {
            $queryDeleteTransUmum = "DELETE FROM `transaksi_keluar_nonsiswa`
            WHERE
            id_tkn = '$idTransaksiKeluarUmum'";            
                
            $deleteTransUmum = mysqli_query($conn, $queryDeleteTransUmum);

            if (!$deleteTransUmum) {
                throw new Exception("Query delete gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * 
            FROM `transaksi_keluar_nonsiswa` 
            WHERE id_tkn = '$idTransaksiKeluarUmum'";

            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Hapus pengeluaran berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_keluar_umum.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data pengeluaran masih ada setelah dihapus");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryCek . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal        
            header('location:transaksi_keluar_umum.php');
            exit;
        }
    } 

    // Tambah Group Cashflow
    if(isset($_POST['tambahGroupCashflow'])){
        $jenis = $_POST['jenis'];
        $group = $_POST['group'];
        $keterangan = $_POST['keterangan'];

        try {
            $queryInsertGroup = "INSERT INTO `group_cashflow`
            (`jenis`, `groop`, `keterangan`) 
            VALUES 
            ('$jenis','$group','$keterangan')";
                
            $insertGroup = mysqli_query($conn, $queryInsertGroup);

            if (!$insertGroup) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM group_cashflow WHERE groop='$group'";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) > 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah group berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:group_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:group_cashflow.php');
            exit;
        }
    }

    // Ubah Group Cashflow
    if(isset($_POST['ubahGroupCashflow'])){
        $idGroupCashflow = $_POST['idGroupCashflow'];
        $jenis = $_POST['jenis'];
        $group = $_POST['group'];
        $keterangan = $_POST['keterangan'];

        try {
            $queryUpdateGroup = "UPDATE `group_cashflow` 
            SET 
            `jenis`='$jenis',
            `groop`='$group',
            `keterangan`='$keterangan' 
            WHERE 
            `id_group_cashflow`='$idGroupCashflow'";
                
            $updateGroup = mysqli_query($conn, $queryUpdateGroup);

            if (!$updateGroup) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `group_cashflow` 
            WHERE 
            `jenis`='$jenis' AND
            `groop`='$group' AND
            `keterangan`='$keterangan'";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) > 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Update group berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:group_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak berubah setelah diupdate");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:group_cashflow.php');
            exit;
        }
    }

    // Hapus Group Cashflow
    if(isset($_POST['hapusGroupCashflow'])){
        $idGroupCashflow = $_POST['idGroupCashflow'];

        try {
            $queryDeleteGroup = "DELETE FROM `group_cashflow` WHERE `id_group_cashflow`='$idGroupCashflow'";
                
            $deleteGroup = mysqli_query($conn, $queryDeleteGroup);

            if (!$deleteGroup) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `group_cashflow` 
            WHERE 
            `id_group_cashflow`='$idGroupCashflow'";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Hapus group berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:group_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data masih ada setelah dihapus");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:group_cashflow.php');
            exit;
        }
    }

    // Tambah Sub Kategori Cashflow
    if(isset($_POST['tambahSubCashflow'])){
        $idGroupCashflow = $_POST['group'];
        $subKategori = $_POST['subKategori'];
        $keterangan = $_POST['keterangan'];

        try {
            $queryInsertSubCashflow = "INSERT INTO 
            `sub_kategori_cashflow`
            (`id_group_cashflow`, `nama_sub_kategori`,`keterangan`) 
            VALUES 
            ('$idGroupCashflow','$subKategori','$keterangan');";
                
            $insertSubCashflow = mysqli_query($conn, $queryInsertSubCashflow);

            if (!$insertSubCashflow) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `sub_kategori_cashflow` WHERE `nama_sub_kategori`='$subKategori'";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) > 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah sub kategori cash flow berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:sub_kategori_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryCek . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:sub_kategori_cashflow.php');
            exit;
        }
    }

    // Ubah Sub Kategori Cashflow
    if(isset($_POST['ubahSubCashflow'])){
        $idSubCashflow = $_POST['idSubSubCashflow'];
        $idGroupCashflow = $_POST['group'];
        $subKategori = $_POST['subKategori'];
        $keterangan = $_POST['keterangan'];

        try {
            $queryUpdateSubCashflow = "UPDATE 
            `sub_kategori_cashflow` 
            SET 
            `id_group_cashflow`='$idGroupCashflow',
            `nama_sub_kategori`='$subKategori',
            `keterangan`='$keterangan' 
            WHERE
            `id_subkategori_cashflow`= '$idSubCashflow'
            ";
                
            $updateSubCashflow = mysqli_query($conn, $queryUpdateSubCashflow);

            if (!$updateSubCashflow) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `sub_kategori_cashflow` 
            WHERE
            `id_subkategori_cashflow`= '$idSubCashflow' AND
            `id_group_cashflow`='$idGroupCashflow' AND
            `nama_sub_kategori`='$subKategori' AND
            `keterangan`='$keterangan'";

            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Update sub kategori cash flow berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:sub_kategori_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak berubah setelah diupdate");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:sub_kategori_cashflow.php');
            exit;
        }
    }

    // Hapus Sub Kategori Cashflow
    if(isset($_POST['hapusSubCashflow'])){
        $idSubCashflow = $_POST['idSubSubCashflow'];

        try {
            $queryDeleteSubCashflow = "DELETE FROM `sub_kategori_cashflow` 
            WHERE 
            `id_subkategori_cashflow`='$idSubCashflow';
            ";
                
            $deleteSubCashflow = mysqli_query($conn, $queryDeleteSubCashflow);

            if (!$deleteSubCashflow) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `sub_kategori_cashflow` 
            WHERE
            `id_subkategori_cashflow`= '$idSubCashflow';";

            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Hapus sub kategori cash flow berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:sub_kategori_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data masih ada setelah dihapus");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:sub_kategori_cashflow.php');
            exit;
        }
    }

    // Tambah Transaksi Masuk Cashflow
    if(isset($_POST['tambahTransMasukCashflow'])){
        $tanggal = $_POST['tanggal'];
        $timestamp = strtotime($tanggal);
        $angkaBulan = date("m", $timestamp);

        if ($angkaBulan == '01') {
            $bulan = 'Januari';
        } elseif ($angkaBulan == '02') {
            $bulan = 'Februari';
        } elseif ($angkaBulan == '03') {
            $bulan = 'Maret';
        } elseif ($angkaBulan == '04') {
            $bulan = 'April';
        } elseif ($angkaBulan == '05') {
            $bulan = 'Mei';
        } elseif ($angkaBulan == '06') {
            $bulan = 'Juni';
        } elseif ($angkaBulan == '07') {
            $bulan = 'Juli';
        } elseif ($angkaBulan == '08') {
            $bulan = 'Agustus';
        } elseif ($angkaBulan == '09') {
            $bulan = 'September';
        } elseif ($angkaBulan == '10') {
            $bulan = 'Oktober';
        } elseif ($angkaBulan == '11') {
            $bulan = 'November';
        } elseif ($angkaBulan == '12') {
            $bulan = 'Desember';
        } else {
            $bulan = 'Bulan Tidak valid';
        }

        $idGroupCashflow = $_POST['groop'];
        $subKategori = $_POST['subKategori'];
        $jumlah = $_POST['jumlah'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];

        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        }

        try {
            $queryInsertMasukCashflow = "INSERT INTO 
            `transaksi_masuk_cashflow`(`tanggal`, `id_tahun_ajar`, `id_group_cashflow`, `id_subkategori_cashflow`, `bulan`, `jumlah`, `id_guru`, `keterangan`) 
            VALUES ('$tanggal','$idTahunAjar','$idGroupCashflow' ,'$subKategori','$bulan','$jumlah','$idGuru','$keterangan')";
            
            $insertMasukCashflow = mysqli_query($conn, $queryInsertMasukCashflow);

            if (!$insertMasukCashflow) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `transaksi_masuk_cashflow` 
            WHERE
            `tanggal`='$tanggal' AND
            `id_tahun_ajar`='$idTahunAjar' AND
            `id_group_cashflow`='$idGroupCashflow' AND
            `id_subkategori_cashflow`='$subKategori' AND
            `bulan`='$bulan' AND
            `jumlah`='$jumlah' AND
            `id_guru`='$idGuru' AND
            `keterangan`='$keterangan'
             ";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah transaksi masuk Cash Flow berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_masuk_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryCek . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:transaksi_masuk_cashflow.php');
            exit;
        }
    }

    // Ubah Transaksi Masuk Cashflow
    if(isset($_POST['ubahTransMasukCashflow'])){
        $idCashflowMasuk = $_POST['idTmc'];
        $tanggal = $_POST['tanggal'];
        $idGroupCashflow = $_POST['groopMasuk'];
        $idSubKategori = $_POST['subKategoriMasuk'];
        $bulan = $_POST['bulan'];
        $jumlah = $_POST['jumlah'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];

        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        }

        try {
            $queryUpdateMasukCashflow = "UPDATE `transaksi_masuk_cashflow` 
            SET 
            `tanggal`='$tanggal',
            `id_tahun_ajar`='$idTahunAjar',
            `id_group_cashflow`='$idGroupCashflow',
            `id_subkategori_cashflow`='$idSubKategori',
            `bulan`='$bulan',
            `jumlah`='$jumlah',
            `id_guru`='$idGuru',
            `keterangan`='$keterangan' 
            WHERE
            id_tmc='$idCashflowMasuk'
            ";
            
            $updateMasukCashflow = mysqli_query($conn, $queryUpdateMasukCashflow);

            if (!$updateMasukCashflow) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `transaksi_masuk_cashflow` 
            WHERE
            `tanggal`='$tanggal' AND
            `id_tahun_ajar`='$idTahunAjar' AND
            `id_group_cashflow`='$idGroupCashflow' AND
            `id_subkategori_cashflow`='$idSubKategori' AND
            `bulan`='$bulan' AND
            `jumlah`='$jumlah' AND
            `id_guru`='$idGuru' AND
            `keterangan`='$keterangan'
                ";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) > 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Ubah transaksi masuk Cash Flow berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_masuk_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak berubah");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:transaksi_masuk_cashflow.php');
            exit;
        }
    }

    // Hapus Transaksi Masuk Cashflow
    if(isset($_POST['hapusTransaksiMasukCashflow'])){
        $idCashflowMasuk = $_POST['idTmc'];

        try {
            $queryDeleteMasukCashflow = "DELETE FROM `transaksi_masuk_cashflow` WHERE id_tmc='$idCashflowMasuk'";
            
            $deleteMasukCashflow = mysqli_query($conn, $queryDeleteMasukCashflow);

            if (!$deleteMasukCashflow) {
                throw new Exception("Query delete gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `transaksi_masuk_cashflow` WHERE id_tmc='$idCashflowMasuk'";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Hapus transaksi masuk Cash Flow berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_masuk_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data masih ada");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:transaksi_masuk_cashflow.php');
            exit;
        }
    }

    // Tambah Transaksi Keluar Cashflow
    if(isset($_POST['tambahTransKeluarCashflow'])){
        $tanggal = $_POST['tanggal'];
        $timestamp = strtotime($tanggal);
        $angkaBulan = date("m", $timestamp);

        if ($angkaBulan == '01') {
            $bulan = 'Januari';
        } elseif ($angkaBulan == '02') {
            $bulan = 'Februari';
        } elseif ($angkaBulan == '03') {
            $bulan = 'Maret';
        } elseif ($angkaBulan == '04') {
            $bulan = 'April';
        } elseif ($angkaBulan == '05') {
            $bulan = 'Mei';
        } elseif ($angkaBulan == '06') {
            $bulan = 'Juni';
        } elseif ($angkaBulan == '07') {
            $bulan = 'Juli';
        } elseif ($angkaBulan == '08') {
            $bulan = 'Agustus';
        } elseif ($angkaBulan == '09') {
            $bulan = 'September';
        } elseif ($angkaBulan == '10') {
            $bulan = 'Oktober';
        } elseif ($angkaBulan == '11') {
            $bulan = 'November';
        } elseif ($angkaBulan == '12') {
            $bulan = 'Desember';
        } else {
            $bulan = 'Bulan Tidak valid';
        }

        $idGroupCashflow = $_POST['groop'];
        $subKategori = $_POST['subKategori'];        
        $jumlah = $_POST['jumlah'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];

        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        }

        try {
            $queryInsertKeluarCashflow = "INSERT INTO 
            `transaksi_keluar_cashflow`(`tanggal`, `id_tahun_ajar`, `id_group_cashflow`, `id_subkategori_cashflow`, `bulan`, `jumlah`, `id_guru`, `keterangan`) 
            VALUES ('$tanggal','$idTahunAjar','$idGroupCashflow' ,'$subKategori','$bulan','$jumlah','$idGuru','$keterangan')";
            
            $insertKeluarCashflow = mysqli_query($conn, $queryInsertKeluarCashflow);

            if (!$insertKeluarCashflow) {
                throw new Exception("Query insert gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `transaksi_keluar_cashflow` 
            WHERE
            `tanggal`='$tanggal' AND
            `id_tahun_ajar`='$idTahunAjar' AND
            `id_group_cashflow`='$idGroupCashflow' AND
            `id_subkategori_cashflow`='$subKategori' AND
            `bulan`='$bulan' AND
            `jumlah`='$jumlah' AND
            `id_guru`='$idGuru' AND
            `keterangan`='$keterangan'
                ";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah transaksi keluar Cash Flow berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_keluar_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryCek . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:transaksi_keluar_cashflow.php');
            exit;
        }
    }

    // Ubah Transaksi Keluar Cashflow
    if(isset($_POST['ubahTransKeluarCashflow'])){
        $idCashflowKeluar = $_POST['idTkc'];
        $tanggal = $_POST['tanggal'];
        $tanggalBayar = date("Y-m-d H:i:s", strtotime($tanggal));
        $idGroupCashflow = $_POST['groopEdit'];
        $idSubKategori = $_POST['subKategoriEdit'];
        $bulan = $_POST['bulan'];
        $jumlah = $_POST['jumlah'];
        $idGuru = $_POST['guru'];
        $keterangan = $_POST['keterangan'];

        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        }

        try {
            $queryUpdateKeluarCashflow = "UPDATE `transaksi_keluar_cashflow` 
            SET 
            `tanggal`='$tanggalBayar',
            `id_tahun_ajar`='$idTahunAjar',
            `id_group_cashflow`='$idGroupCashflow',
            `id_subkategori_cashflow`='$idSubKategori',
            `bulan`='$bulan',
            `jumlah`='$jumlah',
            `id_guru`='$idGuru',
            `keterangan`='$keterangan' 
            WHERE
            id_tkc='$idCashflowKeluar'
            ";
            
            $updateKeluarCashflow = mysqli_query($conn, $queryUpdateKeluarCashflow);

            if (!$updateKeluarCashflow) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah keluar ke database
            $queryCek = "SELECT * FROM `transaksi_keluar_cashflow` 
            WHERE
            `tanggal`='$tanggalBayar' AND
            `id_tahun_ajar`='$idTahunAjar' AND
            `id_group_cashflow`='$idGroupCashflow' AND
            `id_subkategori_cashflow`='$idSubKategori' AND
            `bulan`='$bulan' AND
            `jumlah`='$jumlah' AND
            `id_guru`='$idGuru' AND
            `keterangan`='$keterangan'
                ";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) > 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Ubah transaksi keluar Cash Flow berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_keluar_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak berubah");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $queryUpdateKeluarCashflow . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:transaksi_keluar_cashflow.php');
            exit;
        }
    }

    // Hapus Transaksi Keluar Cashflow
    if(isset($_POST['hapusTransaksiKeluarCashflow'])){
        $idCashflowKeluar = $_POST['idTkc'];

        try {
            $queryDeleteKeluarCashflow = "DELETE FROM `transaksi_keluar_cashflow` WHERE id_tkc='$idCashflowKeluar'";
            
            $deleteKeluarCashflow = mysqli_query($conn, $queryDeleteKeluarCashflow);

            if (!$deleteKeluarCashflow) {
                throw new Exception("Query delete gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `transaksi_keluar_cashflow` WHERE id_tkc='$idCashflowKeluar'";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) === 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Hapus transaksi keluar Cash Flow berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_keluar_cashflow.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data masih ada");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:transaksi_keluar_cashflow.php');
            exit;
        }
    }

    // Tambah Pinjaman
    if(isset($_POST['pinjamKas'])){
        $tanggal = $_POST['tanggal'];
        $idKategori = $_POST['kategori'];
        $jumlah = $_POST['jumlah'];
        $keterangan = $_POST['keterangan'];

        try {
            $queryInsertPinjam = "INSERT INTO `pinjam`
            (`tanggal`, `id_kategori`, `jumlah`, `keterangan`) 
            VALUES 
            ('$tanggal','$idKategori','$jumlah','$keterangan')
            ";
            
            $insertPinjam = mysqli_query($conn, $queryInsertPinjam);

            if (!$insertPinjam) {
                throw new Exception("Query update gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `pinjam` 
            WHERE
            `tanggal`='$tanggal' AND
            `id_kategori`='$idKategori' AND
            `jumlah`='$jumlah' AND
            `keterangan`='$keterangan'
                ";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result && mysqli_num_rows($result) > 0) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah pinjaman berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:pinjam_kas.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Tambah pinjaman gagal");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:pinjam_kas.php');
            exit;
        }
    }

    // Kembalikan Pinjaman
    if(isset($_POST['kembalikanKas'])){
        $tanggal = $_POST['tanggal'];
        $idKategori = $_POST['kategori'];
        $jumlah = $_POST['jumlah'];
        $keterangan = $_POST['keterangan'];

        try {
            $queryInsertKembali = "INSERT INTO `pinjam`
            (`tanggal`, `id_kategori`, `jumlah`, `keterangan`) 
            VALUES 
            ('$tanggal','$idKategori','-$jumlah','$keterangan')
            ";
            
            $insertKembali = mysqli_query($conn, $queryInsertKembali);

            if (!$insertKembali) {
                throw new Exception("Query gagal"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCek = "SELECT * FROM `pinjam` 
            WHERE
            `tanggal`='$tanggal' AND
            `id_kategori`='$idKategori' AND
            `jumlah`='$jumlah' AND
            `keterangan`='$keterangan'
                ";
            
            $result = mysqli_query($conn, $queryCek);

            if ($result) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Pengembalian pinjaman berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:pinjam_kas.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Pengembalian pinjaman gagal, periksa data");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:pinjam_kas.php');
            exit;
        }
    }

    // Tarik Dana ke Cashflow
    if(isset($_POST['tarik2Cashflow'])){
        $tanggal = $_POST['tanggal'];
        $opsi = $_POST['idSubKategoriSiswa'];
        $jumlah = $_POST['jumlahTarik'];

        $idGroupCashflow = 3;        
        $bulan = $_POST['bulan'];
        
        $idGuru = $_POST['guru'];

        $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahun_ajar'");

        if ($queryTahunAjar && mysqli_num_rows($queryTahunAjar) > 0) {
            $dataTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
            $idTahunAjar = $dataTahunAjar['id_tahun_ajar'];
        }

        if ($opsi == 5){
            $idSubKategoriCf = 6;
            $idKategoriTks = 1;
            $idSubKategoriTks = 5;
            $uraian = "Tarik SPP ke Cash Flow";
            $jumlahTarik = $jumlah;
            $jumlahKeluar = 0.05 * $jumlah;
            $insertTkc = mysqli_query($conn, "INSERT INTO 
            `transaksi_keluar_cashflow`
            (`tanggal`, `id_tahun_ajar`, `id_group_cashflow`, `id_subkategori_cashflow`, `bulan`, `jumlah`, `id_guru`, `keterangan`) 
            VALUES 
            ('$tanggal','$idTahunAjar',6,23,'$bulan','$jumlahKeluar','$idGuru','Dari transaksi keluar siswa')"); 

        } elseif ($opsi == 9){
            $idSubKategoriCf = 7;
            $idKategoriTks = 10;
            $idSubKategoriTks = 9;
            $jumlahTarik = 0.2 * $jumlah;
            $uraian = "20% PTS ke Cash Flow";
        } elseif ($opsi == 10){
            $idSubKategoriCf = 7;
            $idKategoriTks = 10;
            $idSubKategoriTks = 10;
            $jumlahTarik = 0.2 * $jumlah;
            $uraian = "20% PAS ke Cash Flow";
        } elseif ($opsi == 11) {
            $idSubKategoriCf = 8;
            $idKategoriTks = 10;
            $idSubKategoriTks = 11;
            $jumlahTarik = 0.2 * $jumlah;
            $uraian = "20% US ke Cash Flow";
        }  elseif ($opsi == 8) {
            $idSubKategoriCf = 13;
            $idKategoriTks = 4;
            $idSubKategoriTks = 8;
            $jumlahTarik = 0.15 * $jumlah;
            $uraian = "15% Komputer ke Cash Flow";
        }      

        try {
            $queryInsertMasukCashflow = "INSERT INTO 
            `transaksi_masuk_cashflow`
            (`tanggal`, `id_tahun_ajar`, `id_group_cashflow`, `id_subkategori_cashflow`, `bulan`, `jumlah`, `id_guru`, `keterangan`) 
            VALUES 
            ('$tanggal','$idTahunAjar','$idGroupCashflow' ,'$idSubKategoriCf','$bulan','$jumlahTarik','$idGuru','Dari transaksi keluar siswa')";
            
            $insertMasukCashflow = mysqli_query($conn, $queryInsertMasukCashflow);

            $queryKeluarSiswa = "INSERT INTO 
            `transaksi_keluar_siswa`
            (`tanggal`, `id_tahun_ajar`, `id_kategori`, `id_sub_kategori`, `bulan`, `uraian`, `jumlah`, `id_guru`, `keterangan`) 
            VALUES 
            ('$tanggal','$idTahunAjar','$idKategoriTks','$idSubKategoriTks','$bulan','$uraian','$jumlahTarik','$idGuru','Ke CF');";

            $insertKeluarSiswa = mysqli_query($conn, $queryKeluarSiswa);


            if (!$insertMasukCashflow | !$insertKeluarSiswa) {
                throw new Exception("Query gagal, periksa data"); // Lempar exception jika query gagal
            }

            // Query SELECT untuk memeriksa apakah data sudah masuk ke database
            $queryCekCf = "SELECT * FROM `transaksi_masuk_cashflow` 
            WHERE
            `tanggal`='$tanggal' AND
            `id_tahun_ajar`='$idTahunAjar' AND
            `id_group_cashflow`='$idGroupCashflow' AND
            `id_subkategori_cashflow`='$idSubKategoriCf' AND
            `bulan`='$bulan' AND
            `jumlah`='$jumlahTarik' AND
            `id_guru`='$idGuru' AND
            `keterangan`='$keterangan'
             ";
            
            $resultCf = mysqli_query($conn, $queryCekCf);     

            $queryCekTks = "SELECT * FROM `transaksi_keluar_siswa` 
            WHERE
            `tanggal`='$tanggal' AND
            `id_tahun_ajar`='$idTahunAjar' AND
            `id_kategori`='$idKategoriTks' AND
            `id_sub_kategori`='$idSubKategoriTks' AND
            `bulan`='$bulan' AND
            `uraian`='$uraian' AND
            `jumlah`='$jumlahTarik' AND
            `id_guru`='$idGuru' AND
            `keterangan`= 'Ke CF, jangan diedit'
             ";
            
            $resultTks = mysqli_query($conn, $queryCekTks);

            
            if ($resultCf == 1 && $resultTks == 1) {
                // Data sudah masuk ke database, Anda dapat mengatur pesan flash message berhasil
                $_SESSION['flash_message'] = 'Tambah transaksi berhasil';
                $_SESSION['flash_message_class'] = 'alert-success'; // Berhasil
                header('location:transaksi_keluar_siswa.php');
                exit;
            } else {
                // Data tidak ada dalam database, itu berarti gagal
                throw new Exception("Data tidak ditemukan setelah ditambahkan");
            }
        } catch (Exception $e) {
            // Tangani exception jika terjadi kesalahan
            $_SESSION['flash_message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            $_SESSION['flash_message_class'] = 'alert-danger'; // Gagal
            header('location:transaksi_keluar_siswa.php');
            exit;
        }
    }

    


?>