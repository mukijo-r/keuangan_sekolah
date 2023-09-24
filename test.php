<?php
require 'config.php';
$conn = mysqli_connect("localhost:3306","root","","sdk");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contoh Form PHP</title>
</head>
<body>

<div class="container-fluid px-1">
    <form method="post">    
        <div class="row row-cols-auto">                                
            <div class="col">

                <?php $queryKategori = mysqli_query($conn, "SELECT nama_kategori FROM kategori WHERE id_kategori='$idKategoriLap'");
                    $rowKategori = mysqli_fetch_assoc($queryKategori);
                    $namaKategori = $rowKategori['nama_kategori'];

                    $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar, tahun_ajar FROM tahun_ajar WHERE tahun_ajar = '$tahunAjarLap'");
                    $rowTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
                    $idTahunAjar = $rowTahunAjar['id_tahun_ajar'];
                    
                    
                    echo "tahun ajar : " . $tahunAjarLap . "<br>\n";
                    echo "bulan : " . $bulanLalu . "<br>\n";
                    echo "kategori " . $namaKategori . "<br><br>\n";
                    ?>
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="tahunAjar">Tahun Ajar</label>
                    </div>
                    <select id="tahunAjar" name="tahunAjar">
                        <option value="<?=$tahunAjarLap;?>"><?=$tahunAjarLap;?></option>
                        <?php
                            $queryTahunAjar = mysqli_query($conn, "SELECT id_tahun_ajar, tahun_ajar FROM tahun_ajar");
                            while ($rowTahunAjar = mysqli_fetch_assoc($queryTahunAjar)) {
                                echo '<option value="' . $rowTahunAjar['tahun_ajar'] . '">' . $rowTahunAjar['tahun_ajar'] . '</option>';
                            }
                            ?>
                    </select>
                </div>
                
            </div>                
            <div class="col">
                <?php
                // Mendapatkan daftar bulan dari database (menggunakan array asosiatif)
                $bulanOptions = array(                        
                    'Juli' => 'Juli',
                    'Agustus' => 'Agustus',
                    'September' => 'September',
                    'Oktober' => 'Oktober',
                    'November' => 'November',
                    'Desember' => 'Desember',
                    'Januari' => 'Januari',
                    'Februari' => 'Februari',
                    'Maret' => 'Maret',
                    'April' => 'April',
                    'Mei' => 'Mei',
                    'Juni' => 'Juni',
                );
                ?>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="bulan">Bulan</label>
                    </div>
                    <select class="custom-select" id="bulan" name="bulan">
                        <option value="<?=$bulanLalu;?>"><?=$bulanLalu;?></option>
                        <?php
                        foreach ($bulanOptions as $bulanValue => $bulanLabel) {
                            echo '<option value="' . $bulanValue . '">' . $bulanLabel . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="kategori">Kategori :</label>
                    <select class="form-select" name="kategori" id="kategori" aria-label="subKategori">
                        <option value="<?=$idKategoriLap;?>"><?=$namaKategori;?></option>
                        <?php
                        // Ambil data kelas dari tabel kelas
                        $queryKategori = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM kategori WHERE kelompok='umum'");
                        while ($kategori = mysqli_fetch_assoc($queryKategori)) {
                            echo '<option value="' . $kategori['id_kategori'] . '">' . $kategori['nama_kategori'] . '</option>';
                        }
                        ?>
                    </select>
                </div>


            </div>
            <div div class="col">
                <button type="submit" class="btn btn-primary" name="btnTampilLapUmum">
                    Tampilkan
                </button>
            </div>              
        </div>
    </form> 
    <br><br>
    <div class="container-fluid px-4">
        <div class="row">
    </div>

    <?php
    // Tampilkan Laporan Umum
    if(isset($_POST['btnTampilLapUmum'])){
        $tahunAjarLap = $_POST['tahunAjar'];
        $bulanLalu = $_POST['bulan'];
        $idKategoriLap = $_POST['kategori'];
    }
    ?>
                
</div>
            <br>
        <div class="card mb-4">
            <div class="row">
                <h5>Tahun ajar: <?=$tahunAjarLap;?></a> </h5>                  
                <h5>Bulan : <?=$bulanLalu;?></a> </h5>
                <h5>id Kategori: <?=$idKategoriLap;?></h5>

                <?php echo $tahunAjarLap . "\n";
                    echo $bulanLalu . "\n";
                    echo $namaKategori . "\n";
                ?>
                
            </div>
        </div>  
</body>
</html>

