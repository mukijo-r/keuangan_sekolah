<?php
// Include the main TCPDF library (search for installation path).
require_once('TCPDF-main/tcpdf.php');
$conn = mysqli_connect("localhost:3306","root","","sdk");

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo.jpg';
        $this->Image($image_file, 30, 12, 20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        // Tambahkan baris baru
        $this->SetCellPaddings(0, 0.2);
        $this->Ln(); // Ini akan membuat baris baru
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 6, 'YAYASAN KARMEL KEUSKUPAN MALANG', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(); 
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 6, 'SD KATOLIK BHAKTI ROGOJAMPI', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(); 
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 6, 'Jl. Ki. Hajar Dewantoro Tlp. (0333) 631698', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(); 
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 6, 'Rogojampi - Banyuwangi', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetLineWidth(0.3); // Atur ketebalan garis
        $this->Line(10, 37, 200, 37); // Koordinat untuk garis mendatar
        $this->Ln();

    }
}

// create new PDF document
$pdf = new MYPDF('landscape', PDF_UNIT, 'A4', true, 'UTF-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mukijo');
$pdf->SetTitle('Laporan Cash Flow');
$pdf->SetSubject('TCPDF');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 40, 10);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

$idTahunAjar = $_POST['idTahunAjar'];
$queryTahunAjar = mysqli_query($conn, "SELECT tahun_ajar FROM tahun_ajar WHERE id_tahun_ajar='$idTahunAjar'");
$rowTahunAjar = mysqli_fetch_assoc($queryTahunAjar);
$tahunAjar = $rowTahunAjar['tahun_ajar'];  

$bulanNum = $_POST['bulan'];
if ($bulanNum == 1) {
    $bulan = 'Januari';
} elseif ($bulanNum == 2) {
    $bulan = 'Februari';
} elseif ($bulanNum == 3) {
    $bulan = 'Maret';
} elseif ($bulanNum == 4) {
    $bulan = 'April';
} elseif ($bulanNum == 5) {
    $bulan = 'Mei';
} elseif ($bulanNum == 6) {
    $bulan = 'Juni';
} elseif ($bulanNum == 7) {
    $bulan = 'Juli';
} elseif ($bulanNum == 8) {
    $bulan = 'Agustus';
} elseif ($bulanNum == 9) {
    $bulan = 'September';
} elseif ($bulanNum == 10) {
    $bulan = 'Oktober';
} elseif ($bulanNum == 11) {
    $bulan = 'November';
} elseif ($bulanNum == 12) {
    $bulan = 'Desember';
}

$pdf->SetFont('helvetica', '', 10);
$txt = <<<EOD
Laporan Keuangan Ekstrakurikuler
Bulan $bulan
Tahun Ajar $tahunAjar


EOD;

$pdf->SetFont('times', '', 9);
$pdf->SetCellMargins(0, 1, 0, 0);
$html = '';
for ($kelas = 1; $kelas <= 6; $kelas++) {
    $html .= '
            Daftar Penerimaan kelas ' . $kelas . ' :<br><br>
            <table border="0.75">
                <tr>
                    <th rowspan="2" style="vertical-align: middle; width: 3%"> No.</th>
                    <th rowspan="2" style="vertical-align: middle; width: 13%"> Nama Siswa</th>
                    <th colspan="4" style="text-align: center; width: 28%">Iuran Kegiatan</th>
                    <th colspan="4" style="text-align: center; width: 28%">Iuran Pramuka</th>
                    <th colspan="4" style="text-align: center; width: 28%">Iuran Komputer</th>
                </tr>
                <tr>
                    <td> Penetapan</td>
                    <td> Bulan ini</td>
                    <td> Tunggakan</td>
                    <td> Jumlah</td>
                    <td> Penetapan</td>
                    <td> Bulan ini</td>
                    <td> Tunggakan</td>
                    <td> Jumlah</td>
                    <td> Penetapan</td>
                    <td> Bulan ini</td>
                    <td> Tunggakan</td>
                    <td> Jumlah</td>
                </tr>';
        

    $queryPenerimaan = "SELECT
    s.nama AS nama_siswa,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Kegiatan' THEN tms.penetapan ELSE 0 END) AS penetapan_kegiatan,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Pramuka' THEN tms.penetapan ELSE 0 END) AS penetapan_pramuka,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Komputer' THEN tms.penetapan ELSE 0 END) AS penetapan_komputer,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Kegiatan' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_kegiatan,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Pramuka' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_pramuka,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Komputer' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_komputer,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Kegiatan' THEN tms.tunggakan ELSE 0 END) AS tunggakan_kegiatan,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Pramuka' THEN tms.tunggakan ELSE 0 END) AS tunggakan_pramuka,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Komputer' THEN tms.tunggakan ELSE 0 END) AS tunggakan_komputer,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Kegiatan' THEN tms.jumlah ELSE 0 END) AS jumlah_kegiatan,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Pramuka' THEN tms.jumlah ELSE 0 END) AS jumlah_pramuka,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'Komputer' THEN tms.jumlah ELSE 0 END) AS jumlah_komputer
    FROM
        transaksi_masuk_siswa tms
    LEFT JOIN
        siswa s ON tms.id_siswa = s.id_siswa
    LEFT JOIN
        sub_kategori_siswa subkat ON tms.id_sub_kategori = subkat.id_sub_kategori
    WHERE
        tms.id_tahun_ajar = $idTahunAjar AND 
        MONTH(tms.tanggal) = $bulanNum AND
        s.id_kelas = $kelas
    GROUP BY
        s.nama;
    ";
    $i = 1;
    $totalPenetapanKegiatan = 0;
    $totalBulanIniKegiatan = 0;
    $totalTunggakanKegiatan = 0;
    $totalJumlahKegiatan = 0;

    $totalPenetapanPramuka = 0;
    $totalBulanIniPramuka = 0;
    $totalTunggakanPramuka = 0;
    $totalJumlahPramuka = 0;

    $totalPenetapanKomputer = 0;
    $totalBulanIniKomputer = 0;
    $totalTunggakanKomputer = 0;
    $totalJumlahKomputer = 0;

    $penerimaanEkstra = mysqli_query($conn, $queryPenerimaan);
    while($rowPenerimaan=mysqli_fetch_array($penerimaanEkstra)){
        $namaSiswa = $rowPenerimaan['nama_siswa'];                    
        $penetapanKegiatan = $rowPenerimaan['penetapan_kegiatan'];
        $bulanIniKegiatan = $rowPenerimaan['bulan_ini_kegiatan'];
        $tunggakanKegiatan = $rowPenerimaan['tunggakan_kegiatan'];
        $jumlahKegiatan = $rowPenerimaan['jumlah_kegiatan'];
        $penetapanPramuka = $rowPenerimaan['penetapan_pramuka'];
        $bulanIniPramuka = $rowPenerimaan['bulan_ini_pramuka'];
        $tunggakanPramuka = $rowPenerimaan['tunggakan_pramuka'];
        $jumlahPramuka = $rowPenerimaan['jumlah_pramuka'];
        $penetapanKomputer = $rowPenerimaan['penetapan_komputer'];
        $bulanIniKomputer = $rowPenerimaan['bulan_ini_komputer'];
        $tunggakanKomputer = $rowPenerimaan['tunggakan_komputer'];
        $jumlahKomputer = $rowPenerimaan['jumlah_komputer'];

        $html .= '<tr>
        <td> ' . $i . '</td>
        <td> ' . $namaSiswa . '</td>
        <td>Rp. ' . number_format($penetapanKegiatan, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniKegiatan, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanKegiatan, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($jumlahKegiatan, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($penetapanPramuka, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniPramuka, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanPramuka, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($jumlahPramuka, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($penetapanKomputer, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniKomputer, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanKomputer, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($jumlahKomputer, 0, ',', '.') . '</td>
        </tr>';

        // Tambahkan nilai ke total
        $totalPenetapanKegiatan += $penetapanKegiatan;
        $totalBulanIniKegiatan += $bulanIniKegiatan;
        $totalTunggakanKegiatan += $tunggakanKegiatan;
        $totalJumlahKegiatan += $jumlahKegiatan;

        $totalPenetapanPramuka += $penetapanPramuka;
        $totalBulanIniPramuka += $bulanIniPramuka;
        $totalTunggakanPramuka += $tunggakanPramuka;
        $totalJumlahPramuka += $jumlahPramuka;

        $totalPenetapanKomputer += $penetapanKomputer;
        $totalBulanIniKomputer += $bulanIniKomputer;
        $totalTunggakanKomputer += $tunggakanKomputer;
        $totalJumlahKomputer += $jumlahKomputer;
    }
        // Tampilkan baris total
    $html .= '<tr>
    <td colspan="2"><strong>Total</strong></td>
    <td><strong>Rp. ' . number_format($totalPenetapanKegiatan, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalBulanIniKegiatan, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalTunggakanKegiatan, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalJumlahKegiatan, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalPenetapanPramuka, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalBulanIniPramuka, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalTunggakanPramuka, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalJumlahPramuka, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalPenetapanKomputer, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalBulanIniKomputer, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalTunggakanKomputer, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalJumlahKomputer, 0, ',', '.') . '</strong></td>
    </tr>
    </table>
    <br><br><br>';                     
}
// Tampilkan tabel untuk total kolom per kelas
$html .= '
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Rekapitulasi Penerimaan Uang Kegiatan, Pramuka, Komputer
        </div>
        <div class="card-body">
            <table border="0.75">
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">Kelas</th>
                    <th colspan="4" style="text-align: center;">Iuran Kegiatan</th>
                    <th colspan="4" style="text-align: center;">Iuran Pramuka</th>
                    <th colspan="4" style="text-align: center;">Iuran Komputer</th>
                </tr>
                <tr>
                    <td>Penetapan</td>
                    <td>Bulan ini</td>
                    <td>Tunggakan</td>
                    <td>Jumlah</td>
                    <td>Penetapan</td>
                    <td>Bulan ini</td>
                    <td>Tunggakan</td>
                    <td>Jumlah</td>
                    <td>Penetapan</td>
                    <td>Bulan ini</td>
                    <td>Tunggakan</td>
                    <td>Jumlah</td>
                </tr>';


// Simpan total kolom per kelas
$penetapanKegiatanKelas = 0;
$bulanIniKegiatanKelas = 0;
$tunggakanKegiatanKelas = 0;
$totalKegiatanKelas = 0;                        

$penetapanPramukaKelas = 0;
$bulanIniPramukaKelas = 0;
$tunggakanPramukaKelas = 0;
$totalPramukaKelas = 0; 

$penetapanKomputerKelas = 0;
$bulanIniKomputerKelas = 0;
$tunggakanKomputerKelas = 0; 
$totalKomputerKelas = 0;

$finalPenetapanKegiatan = 0;
$finalBulanIniKegiatan = 0;
$finalTunggakanKegiatan = 0;
$finalJumlahKegiatan = 0;

$finalPenetapanPramuka = 0;
$finalBulanIniPramuka = 0;
$finalTunggakanPramuka = 0;
$finalJumlahPramuka = 0;

$finalPenetapanKomputer = 0;
$finalBulanIniKomputer = 0;
$finalTunggakanKomputer = 0;
$finalJumlahKomputer = 0;

// Loop untuk menghitung total kolom per kelas
for ($kelas = 1; $kelas <= 6; $kelas++) {

    $queryTotal = "SELECT
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Kegiatan' THEN tms.penetapan ELSE 0 END) AS penetapan_kegiatan_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Pramuka' THEN tms.penetapan ELSE 0 END) AS penetapan_pramuka_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Komputer' THEN tms.penetapan ELSE 0 END) AS penetapan_komputer_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Kegiatan' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_kegiatan_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Pramuka' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_pramuka_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Komputer' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_komputer_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Kegiatan' THEN tms.tunggakan ELSE 0 END) AS tunggakan_kegiatan_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Pramuka' THEN tms.tunggakan ELSE 0 END) AS tunggakan_pramuka_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Komputer' THEN tms.tunggakan ELSE 0 END) AS tunggakan_komputer_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Kegiatan' THEN tms.jumlah ELSE 0 END) AS total_kegiatan_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Pramuka' THEN tms.jumlah ELSE 0 END) AS total_pramuka_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'Komputer' THEN tms.jumlah ELSE 0 END) AS total_komputer_kelas

    FROM
        transaksi_masuk_siswa tms
    LEFT JOIN
        siswa s ON tms.id_siswa = s.id_siswa
    LEFT JOIN
        sub_kategori_siswa subkat ON tms.id_sub_kategori = subkat.id_sub_kategori
    WHERE
        tms.id_tahun_ajar = $idTahunAjar AND 
        MONTH(tms.tanggal) = $bulanNum";
        
    $resultTotal = mysqli_query($conn, $queryTotal);

    $rowKelas=mysqli_fetch_array($resultTotal);
    $penetapanKegiatanKelas =  $rowKelas['penetapan_kegiatan_kelas'];
    $bulanIniKegiatanKelas = $rowKelas['bulan_ini_kegiatan_kelas'];
    $tunggakanKegiatanKelas =  $rowKelas['tunggakan_kegiatan_kelas'];
    $totalKegiatanKelas = $rowKelas['total_kegiatan_kelas'];

    $penetapanPramukaKelas = $rowKelas['penetapan_pramuka_kelas'];
    $bulanIniPramukaKelas = $rowKelas['bulan_ini_pramuka_kelas'];
    $tunggakanPramukaKelas = $rowKelas['tunggakan_pramuka_kelas'];
    $totalPramukaKelas = $rowKelas['total_pramuka_kelas'];

    $penetapanKomputerKelas = $rowKelas['penetapan_komputer_kelas'];
    $bulanIniKomputerKelas = $rowKelas['bulan_ini_komputer_kelas'];
    $tunggakanKomputerKelas = $rowKelas['tunggakan_komputer_kelas'];
    $totalKomputerKelas = $rowKelas['total_komputer_kelas'];     

    $html .= '<tr>
        <td>Kelas ' . $kelas . '</td>
        <td>Rp. ' . number_format($penetapanKegiatanKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniKegiatanKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanKegiatanKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($totalKegiatanKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($penetapanPramukaKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniPramukaKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanPramukaKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($totalPramukaKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($penetapanKomputerKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniKomputerKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanKomputerKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($totalKomputerKelas, 0, ',', '.') . '</td>
    </tr>';


    // Tambahkan nilai ke total
    $finalPenetapanKegiatan += $penetapanKegiatanKelas;
    $finalBulanIniKegiatan += $bulanIniKegiatanKelas;
    $finalTunggakanKegiatan += $tunggakanKegiatanKelas;
    $finalJumlahKegiatan += $totalKegiatanKelas;

    $finalPenetapanPramuka += $penetapanPramukaKelas;
    $finalBulanIniPramuka += $bulanIniPramukaKelas;
    $finalTunggakanPramuka += $tunggakanPramukaKelas;
    $finalJumlahPramuka += $totalPramukaKelas;

    $finalPenetapanKomputer += $penetapanKomputerKelas;
    $finalBulanIniKomputer += $bulanIniKomputerKelas;
    $finalTunggakanKomputer += $tunggakanKomputerKelas;
    $finalJumlahKomputer += $totalKomputerKelas;
}

    $html .= '<tr>
        <td><strong> Total</strong></td>
        <td><strong>Rp. ' . number_format($finalPenetapanKegiatan, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalBulanIniKegiatan, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalTunggakanKegiatan, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalJumlahKegiatan, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalPenetapanPramuka, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalBulanIniPramuka, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalTunggakanPramuka, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalJumlahPramuka, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalPenetapanKomputer, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalBulanIniKomputer, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalTunggakanKomputer, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalJumlahKomputer, 0, ',', '.') . '</strong></td>
    </tr>
    </table>
    </div>
    </div><br>';       

$queryJabatan = mysqli_query($conn, "SELECT
MAX(CASE WHEN jabatan = 'Kepala Sekolah' THEN nama_lengkap END) AS kepala_sekolah,
MAX(CASE WHEN jabatan = 'Bendahara Sekolah' THEN nama_lengkap END) AS bendahara_sekolah,
MAX(CASE WHEN jabatan = 'Tenaga Administrasi Sekolah' THEN nama_lengkap END) AS pembuat_laporan,
MAX(CASE WHEN jabatan = 'Kepala Sekolah' THEN nip END) AS nip_kepala_sekolah,
MAX(CASE WHEN jabatan = 'Bendahara Sekolah' THEN nip END) AS nip_bendahara_sekolah,
MAX(CASE WHEN jabatan = 'Tenaga Administrasi Sekolah' THEN nip END) AS nip_pembuat_laporan
FROM guru;");

$rowJabatan = mysqli_fetch_assoc($queryJabatan);
$bendahara = $rowJabatan['bendahara_sekolah'];
$pembuatLaporan = $rowJabatan['pembuat_laporan'];
$kepalaSekolah = $rowJabatan['kepala_sekolah'];
$nipBendahara = $rowJabatan['nip_bendahara_sekolah'];
$nipPembuatLaporan = $rowJabatan['nip_pembuat_laporan'];
$nipKepalaSekolah = $rowJabatan['nip_kepala_sekolah'];

$html .= '<table>';
$html .= '<tr>';
$html .= '<td style="width: 10%"></td>';
$html .= '<td style="width: 60%">Bendahara Sekolah</td>';
$html .= '<td style="width: 30%">Pembuat Laporan</td>';
$html .= '</tr>';
$html .= '</table><br><br><br><br>';

$html .= '<table>';
$html .= '<tr>';
$html .= '<td style="width: 10%"></td>';
$html .= '<td style="width: 60%"><u>' . $bendahara . '</u></td>';
$html .= '<td style="width: 30%"><u>' . $pembuatLaporan . '</u></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="width: 10%"></td>';
$html .= '<td style="width: 60%">NIP. ' . $nipBendahara . '</td>';
$html .= '<td style="width: 30%">NIP. ' . $nipPembuatLaporan . '</td>';
$html .= '</tr>';
$html .= '</table><br><br>';

$html .= '<table>';
$html .= '<tr>';
$html .= '<td style="width: 10%"></td>';
$html .= '<td style="width: 55%">Kepala Sekolah</td>';
$html .= '</tr>';
$html .= '</table><br><br><br><br>';

$html .= '<table>';
$html .= '<tr>';
$html .= '<td style="width: 10%"></td>';
$html .= '<td style="width: 55%"><u>' . $kepalaSekolah . '</u></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="width: 10%"></td>';
$html .= '<td style="width: 55%">NIP. ' . $nipKepalaSekolah . '</td>';
$html .= '</tr>';
$html .= '</table><br>';

$html .= '<table>';
$html .= '<tr>';
$html .= '<td style="width: 55%"></td>';
$html .= '<td style="width: 45%">Telah diperiksa oleh Bagian Keuangan</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="width: 55%"></td>';
$html .= '<td style="width: 45%">Yayasan Karmel Malang </td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="width: 55%"></td>';
$html .= '<td style="width: 45%">Malang ________________________</td>';
$html .= '</tr>';
$html .= '</table><br><br>';

// print a block of text using Write()
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
$pdf->writeHTML($html, true, false, false, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('laporan_umum.pdf', 'I');

?>