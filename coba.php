<?php
// Koneksi ke database (ganti dengan informasi koneksi yang sesuai)
$koneksi = new mysqli("localhost", "root", "", "test");

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query untuk mengambil data kategori
$query_kategori = "SELECT id, nama_kategori FROM kategori";
$result_kategori = $koneksi->query($query_kategori);

// Inisialisasi variabel untuk kode kategori
$kode_kategori = 1;

// Tampilkan data dalam tabel HTML
echo "<table border='1'>";
while ($row_kategori = $result_kategori->fetch_assoc()) {
    echo "<tr>";

    // Kolom pertama (kode kategori)
    echo "<td>" . $kode_kategori . "</td>";

    // Kolom kedua (kategori dalam huruf tebal)
    echo "<td><b>" . $row_kategori["nama_kategori"] . "</b></td>";

    echo "</tr>";

    // Query untuk mengambil subkategori yang terkait dengan kategori ini
    $query_subkategori = "SELECT id, nama_subkategori FROM subkategori WHERE id_kategori = " . $row_kategori["id"];
    $result_subkategori = $koneksi->query($query_subkategori);

    // Inisialisasi variabel untuk kode subkategori
    $kode_subkategori = 1;

    // Tampilkan data subkategori dalam tabel HTML
    while ($row_subkategori = $result_subkategori->fetch_assoc()) {
        echo "<tr>";

        // Kolom pertama (kode subkategori)
        echo "<td>" . $kode_kategori . "." . $kode_subkategori . "</td>";

        // Kolom kedua (subkategori dalam huruf biasa)
        echo "<td>" . $row_subkategori["nama_subkategori"] . "</td>";

        echo "</tr>";

        // Increment kode subkategori
        $kode_subkategori++;
    }

    // Increment kode kategori
    $kode_kategori++;
}

echo "</table>";

// Tutup koneksi
$koneksi->close();
?>


