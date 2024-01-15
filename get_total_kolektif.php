<?php
include 'config.php';
// Koneksi ke database
$conn = mysqli_connect("localhost:3306","root","","sdk");

// Dapatkan nilai "siswa" dan "kategori" dari parameter GET
$kelas = $_GET['kelas'];
$kategori = $_GET['subKategori'];
$jumlahKolektif = 0;

// Lakukan query SQL untuk mengambil nilai "nominal" dari tabel sesuai dengan parameter
$querySiswa = "SELECT id_siswa FROM siswa WHERE id_kelas = '$kelas'";
$siswa = mysqli_query($conn, $querySiswa);

$siswaArray = []; // Buat array untuk menyimpan data siswa
            
while ($row = mysqli_fetch_assoc($siswa)) {
    $siswaArray[] = $row['id_siswa']; // Tambahkan id_siswa ke dalam array
}

// for ($i = 0; $i < count($siswaArray); $i++) {
//     $idSiswa = $siswaArray[$i];
//     $queryGetPenetapan = "SELECT nominal FROM penetapan WHERE id_sub_kategori = '$kategori' AND id_siswa = '$idSiswa'";
//     $getPenetapan = mysqli_query($conn, $queryGetPenetapan);
//     $rowPenetapan = mysqli_fetch_assoc($getPenetapan);
//     $penetapan = floatval($rowPenetapan['nominal']);
//     $jumlahKolektif += $penetapan;
// }

for ($i = 0; $i < count($siswaArray); $i++) {
    $idSiswa = $siswaArray[$i];

    if ($kategori == 5) {
        $queryGetPenetapan = "SELECT nominal FROM penetapan WHERE id_sub_kategori IN ('5', '6', '7', '8') AND id_siswa = '$idSiswa'";
    } else {
        $queryGetPenetapan = "SELECT nominal FROM penetapan WHERE id_sub_kategori = '$kategori' AND id_siswa = '$idSiswa'";
    }

    $getPenetapan = mysqli_query($conn, $queryGetPenetapan);
    $totalNominal = 0;

    while ($rowPenetapan = mysqli_fetch_assoc($getPenetapan)) {
        $penetapan = floatval($rowPenetapan['nominal']);
        $totalNominal += $penetapan;
    }

    $jumlahKolektif += $totalNominal;
}

// Mengembalikan data dalam format JSON tanpa tanda kutip ganda
echo json_encode($jumlahKolektif);
?>
