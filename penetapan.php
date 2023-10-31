<?php
require 'function.php';
require 'cek.php';
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Halaman Transaksi Siswa</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include 'navbar.php'; ?>
        <div id="layoutSidenav">
            <?php include 'sidebar.php'; ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h2 class="mt-4">Daftar Penetapan</h2>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Transaksi Siswa / Penetapan</li>                            
                        </ol>                 
                        <br>
                        <div class="container-fluid px-4">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPenetapan">
                                        Tambah Entry
                                    </button><br><br>
                                    <button type="button" class="btn btn-warning" name="tblEdit" data-bs-toggle="modal" data-bs-target="#modalEditPenetapan">
                                        Edit
                                    </button><br><br>
                                    <button type="button" class="btn btn-danger" name="tblHapus" data-bs-toggle="modal" data-bs-target="#modalHapusPenetapan">
                                        Hapus
                                    </button>
                                </div>
                                <div class="col-md-8">
                                    <?php
                                    if (isset($_SESSION['flash_message'])) {
                                        $message_class = isset($_SESSION['flash_message_class']) ? $_SESSION['flash_message_class'] : 'alert-success';
                                        echo '<div class="alert ' . $message_class . ' text-center">' . $_SESSION['flash_message'] . '</div>';
                                        unset($_SESSION['flash_message']); // Hapus pesan flash setelah ditampilkan
                                    }                                    
                                    ?>
                                </div>
                            </div>
                        </div>                    
                        <br>
                        <?php 
                            for ($kelas = 1; $kelas <= 6; $kelas++) {
                                echo '<div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-table me-1"></i>
                                        Daftar Penetapan kelas ' . $kelas . '
                                    </div>
                                    <div class="card-body">';                               

                                $dataPenetapan = mysqli_query($conn, "SELECT 
                                penetapan.*,
                                s.nama AS nama_siswa,
                                sks.id_kategori,
                                sks.nama_sub_kategori,
                                k.nama_kategori
                                FROM 
                                    penetapan
                                LEFT JOIN 
                                    siswa s ON penetapan.id_siswa = s.id_siswa 
                                LEFT JOIN 
                                    sub_kategori_siswa sks ON penetapan.id_sub_kategori = sks.id_sub_kategori
                                LEFT JOIN
                                    kategori k ON sks.id_kategori = k.id_kategori
                                WHERE s.status = 'aktif' AND
                                s.id_kelas = $kelas
                                GROUP BY 
                                    s.nama, sks.id_sub_kategori
                                ORDER BY
                                    s.nama
                                "); 

                                if ($dataPenetapan->num_rows > 0) {    

                                    echo '<table id="datatablesSimple1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>';                                

                                    // Mengambil sub kategori unik dan membuat kolom HTML
                                    $sub_kategori = array();
                                    while ($row = $dataPenetapan->fetch_assoc()) {
                                        $sub_kategori[$row["id_sub_kategori"]] = $row["nama_sub_kategori"];
                                    }

                                    foreach ($sub_kategori as $id_sub_kategori => $nama_sub_kategori) {
                                        echo "<th>" . $nama_sub_kategori . "</th>";                                            
                                    }                                

                                    echo "</tr></thead><tbody>";

                                    $nomor_baris = 1;
                                    $current_nama_siswa = "";
                                    $current_data = array();
                                    $first_iteration = true;

                                    foreach ($dataPenetapan as $row) {
                                        if ($row["nama_siswa"] !== $current_nama_siswa) {
                                            if (!$first_iteration) {                                            
                                                echo "<tr>";
                                                echo "<td>" . $nomor_baris++ . "</td>";
                                                echo "<td>" . $current_nama_siswa . "</td>";
                                                foreach ($sub_kategori as $id_sub_kategori => $nama_sub_kategori) {
                                                    if (isset($current_data[$id_sub_kategori])) {
                                                        echo "<td>" . $current_data[$id_sub_kategori] . "</td>";
                                                    } else {
                                                        echo "<td></td>"; // Jika tidak ada data untuk sub kategori ini
                                                    }
                                                }
                                                echo "</tr>";
                                            }
                                            $current_nama_siswa = $row["nama_siswa"];
                                            $current_data = array();
                                            $first_iteration = false;
                                        }
                                        $current_data[$row["id_sub_kategori"]] = $row["nominal"];
                                    }

                                    // Menampilkan data untuk siswa terakhir
                                    if (!$first_iteration) {
                                        echo "<tr><td>" . $nomor_baris++ . "</td><td>" . $current_nama_siswa . "</td>";
                                        foreach ($sub_kategori as $id_sub_kategori => $nama_sub_kategori) {
                                            if (isset($current_data[$id_sub_kategori])) {
                                                echo "<td>" . $current_data[$id_sub_kategori] . "</td>";
                                            } else {
                                                echo "<td></td>"; // Jika tidak ada data untuk sub kategori ini
                                            }
                                        }
                                        ?>
                                        </tr>                                   
                                    <?php
                                    }                                        
                                    echo "</tbody></table>";

                                    $queryPenetapanSpp = "SELECT
                                    SUM(CASE WHEN sks.id_sub_kategori = 5 THEN nominal ELSE 0 END) AS total_spp,
                                    SUM(CASE WHEN sks.id_sub_kategori = 6 THEN nominal ELSE 0 END) AS total_pramuka,
                                    SUM(CASE WHEN sks.id_sub_kategori = 7 THEN nominal ELSE 0 END) AS total_kegiatan,
                                    SUM(CASE WHEN sks.id_sub_kategori = 8 THEN nominal ELSE 0 END) AS total_komputer,
                                    SUM(CASE WHEN sks.id_sub_kategori = 9 THEN nominal ELSE 0 END) AS total_pts,
                                    SUM(CASE WHEN sks.id_sub_kategori = 10 THEN nominal ELSE 0 END) AS total_pas
                                    FROM penetapan
                                    LEFT JOIN siswa s ON penetapan.id_siswa = s.id_siswa
                                    LEFT JOIN sub_kategori_siswa sks ON penetapan.id_sub_kategori = sks.id_sub_kategori
                                    WHERE s.status = 'aktif' AND s.id_kelas = $kelas;";

                                    $dataPenetapanSpp = mysqli_query($conn, $queryPenetapanSpp);
                                    $rowData = mysqli_fetch_assoc($dataPenetapanSpp);
                                    $penetapanSpp = $rowData['total_spp'];
                                    $penetapanPramuka = $rowData['total_pramuka'];
                                    $penetapanKegiatan = $rowData['total_kegiatan'];
                                    $penetapanKomputer = $rowData['total_komputer'];
                                    $penetapanPts = $rowData['total_pts'];
                                    $penetapanPas = $rowData['total_pas'];

                                    echo 'Total Penetapan SPP kelas ' . $kelas . ' : Rp '  . number_format($penetapanSpp, 0, ',', '.');
                                    echo '<br>';
                                    echo 'Total Penetapan Pramuka kelas ' . $kelas . ' : Rp '  . number_format($penetapanPramuka, 0, ',', '.');
                                    echo '<br>';
                                    echo 'Total Penetapan Kegiatan kelas ' . $kelas . ' : Rp '  . number_format($penetapanKegiatan, 0, ',', '.');
                                    echo '<br>';
                                    echo 'Total Penetapan Komputer kelas ' . $kelas . ' : Rp '  . number_format($penetapanKomputer, 0, ',', '.');
                                    echo '<br>';
                                    echo 'Total Penetapan PTS kelas ' . $kelas . ' : Rp '  . number_format($penetapanPts, 0, ',', '.');
                                    echo '<br>';
                                    echo 'Total Penetapan PAS kelas ' . $kelas . ' : Rp '  . number_format($penetapanPas, 0, ',', '.');

                                } else {
                                    echo "Tidak ada data ditemukan";
                                }                                                          
                                echo '</div></div>';
                            } 
                        ?> 
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script>
        </script>
    </body>
    
        <!-- Modal Tambah Item Penetapan -->
    <div class="modal fade" id="modalTambahPenetapan">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Penetapan Pembayaran</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal Body -->
                <form method="post">
                    <div class="modal-body">                        
                        <div class="mb-3">
                            <label for="siswa">Siswa :</label>
                            <select class="form-select" name="siswa" id="siswa" aria-label="Siswa">
                                <option selected disabled>Pilih Siswa</option>
                                <option value="0">Semua</option>
                                <?php
                                // Ambil data kelas dari tabel kelas
                                $querySiswa = mysqli_query($conn, "SELECT id_siswa, nama FROM siswa ORDER BY nama");
                                while ($siswa = mysqli_fetch_assoc($querySiswa)) {
                                    echo '<option value="' . $siswa['id_siswa'] . '">' . $siswa['nama'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subKategori">Kategori :</label>
                            <select class="form-select" name="subKategori" id="subKategori" aria-label="subKategori">
                                <option selected disabled>Pilih Kategori</option>
                                <?php
                                // Ambil data kelas dari tabel kelas
                                $querySubKategori = mysqli_query($conn, "SELECT id_sub_kategori, nama_sub_kategori FROM sub_kategori_siswa");
                                while ($subKategori = mysqli_fetch_assoc($querySubKategori)) {
                                    echo '<option value="' . $subKategori['id_sub_kategori'] . '">' . $subKategori['nama_sub_kategori'] . '</option>';
                                }
                                ?>
                            </select>
                        </div> 
                        <div class="mb-3">
                            <label for="nominal">Nominal :</label>                        
                            <input type="number" name="nominal" id="nominal" class="form-control">
                        </div>                    
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" name="tambahPenetapan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal edit Item Penetapan -->
    <div class="modal fade" id="modalEditPenetapan">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Item Penetapan</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal Body -->
                <form method="post">
                    <div class="modal-body">                        
                        <div class="mb-3">
                            <label for="siswa">Siswa : </label>
                            <select class="form-select" name="siswa" id="siswa" aria-label="Siswa">
                                <option selected disabled>Pilih Siswa</option>
                                <option value="0">Semua</option>
                                <?php
                                // Ambil data kelas dari tabel kelas
                                $querySiswa = mysqli_query($conn, "SELECT id_siswa, nama FROM siswa ORDER BY nama");
                                while ($siswa = mysqli_fetch_assoc($querySiswa)) {
                                    echo '<option value="' . $siswa['id_siswa'] . '">' . $siswa['nama'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subKategori">Kategori :</label>
                            <select class="form-select" name="subKategori" id="subKategori" aria-label="subKategori">
                                <option selected disabled>Pilih Kategori</option>
                                <?php
                                // Ambil data kelas dari tabel kelas
                                $querySubKategori = mysqli_query($conn, "SELECT id_sub_kategori, nama_sub_kategori FROM sub_kategori_siswa");
                                while ($subKategori = mysqli_fetch_assoc($querySubKategori)) {
                                    echo '<option value="' . $subKategori['id_sub_kategori'] . '">' . $subKategori['nama_sub_kategori'] . '</option>';
                                }
                                ?>
                            </select>
                        </div> 
                        <div class="mb-3">
                            <label for="nominal">Nominal :</label>                        
                            <input type="number" name="nominal" id="nominal" class="form-control">
                        </div>                    
                    </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" name="editPenetapan">Simpan</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
                                    
    <!-- Modal Hapus Item Penetapan -->
    <div class="modal fade" id="modalHapusPenetapan">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Item Penetapan</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Modal Body -->
                <form method="post">
                    <div class="modal-body">                        
                        <div class="mb-3">
                            <label for="siswa">Siswa :</label>
                            <select class="form-select" name="siswa" id="siswa" aria-label="Siswa">
                                <option selected disabled>Pilih Siswa</option>
                                <option value="0">Semua</option>
                                <?php
                                // Ambil data kelas dari tabel kelas
                                $querySiswa = mysqli_query($conn, "SELECT id_siswa, nama FROM siswa ORDER BY nama");
                                while ($siswa = mysqli_fetch_assoc($querySiswa)) {
                                    echo '<option value="' . $siswa['id_siswa'] . '">' . $siswa['nama'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subKategori">Kategori :</label>
                            <select class="form-select" name="subKategori" id="subKategori" aria-label="subKategori">
                                <option selected disabled>Pilih Kategori</option>
                                <?php
                                // Ambil data kelas dari tabel kelas
                                $querySubKategori = mysqli_query($conn, "SELECT id_sub_kategori, nama_sub_kategori FROM sub_kategori_siswa");
                                while ($subKategori = mysqli_fetch_assoc($querySubKategori)) {
                                    echo '<option value="' . $subKategori['id_sub_kategori'] . '">' . $subKategori['nama_sub_kategori'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>                  
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" name="hapusPenetapan">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Temukan elemen "kelas" dan "siswa" pada Tambah Transaksi Tabungan
            var kelasDropdown = document.getElementById('kelas');
            var siswaDropdown = document.getElementById('siswa');

            // Temukan elemen "kelas" dan "siswa" pada Edit Transaksi Tabungan
            var kelasDropdown2 = document.getElementById('kelasEdit');
            var siswaDropdown2 = document.getElementById('siswaEdit');


            // Tambahkan event listener ketika nilai "kelas" berubah pada Tambah Transaksi Tabungan
            kelasDropdown.addEventListener('change', function() {
                var selectedKelas = kelasDropdown.value;

                // Gunakan AJAX untuk mengambil data siswa berdasarkan kelas
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_siswa_by_kelas.php?kelas=' + selectedKelas, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Parse data JSON yang diterima
                        var dataSiswa = JSON.parse(xhr.responseText);

                        // Bersihkan dropdown "siswa" dan tambahkan opsi-opsi baru
                        siswaDropdown.innerHTML = '<option selected disabled>Pilih Siswa</option>';
                        dataSiswa.forEach(function(siswa) {
                            siswaDropdown.innerHTML += '<option value="' + siswa.id_siswa + '">' + siswa.nama + '</option>';
                        });
                    }
                };
                xhr.send();
            });

            // Tambahkan event listener ketika nilai "kelas" berubah pada Edit Transaksi Tabungan
            kelasDropdown2.addEventListener('change', function() {
                var selectedKelas = kelasDropdown2.value;

                // Gunakan AJAX untuk mengambil data siswa berdasarkan kelas
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_siswa_by_kelas.php?kelas=' + selectedKelas, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Parse data JSON yang diterima
                        var dataSiswa = JSON.parse(xhr.responseText);

                        // Bersihkan dropdown "siswa" dan tambahkan opsi-opsi baru
                        siswaDropdown2.innerHTML = '<option selected disabled>Pilih Siswa</option>';
                        dataSiswa.forEach(function(siswa) {
                            siswaDropdown2.innerHTML += '<option value="' + siswa.id_siswa + '">' + siswa.nama + '</option>';
                        });
                    }
                };
                xhr.send();
            });
        });
    </script>

    
</html>
