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
        $this->Line(10, 37, 285, 37); // Koordinat untuk garis mendatar
        $this->Ln();

    }
}

// create new PDF document
$pdf = new MYPDF('landscape', PDF_UNIT, 'A4', true, 'UTF-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mukijo');
$pdf->SetTitle('Rekapan Ujian');
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
Laporan Keuangan Ujian
Bulan $bulan
Tahun Ajar $tahunAjar


EOD;

$pdf->SetFont('times', '', 9);
$pdf->SetCellMargins(0, 1, 0, 0);
$html = '';
for ($kelas = 1; $kelas <= 6; $kelas++) {

    $html .= '<table>';
    $html .= '<tr nobr="true"><td>';

    $html .= '
            Daftar Penerimaan kelas ' . $kelas . ' :<br><br>
            <table border="0.75">
                <tr>
                    <th rowspan="2" style="vertical-align: middle; width: 3%"> No.</th>
                    <th rowspan="2" style="vertical-align: middle; width: 13%"> Nama Siswa</th>
                    <th colspan="4" style="text-align: center; width: 28%">Iuran PTS</th>
                    <th colspan="4" style="text-align: center; width: 28%">Iuran PAS</th>
                    <th colspan="4" style="text-align: center; width: 28%">Iuran US</th>
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
    SUM(CASE WHEN subkat.nama_sub_kategori = 'PTS' THEN tms.penetapan ELSE 0 END) AS penetapan_pts,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'PAS' THEN tms.penetapan ELSE 0 END) AS penetapan_pas,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'US' THEN tms.penetapan ELSE 0 END) AS penetapan_us,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'PTS' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_pts,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'PAS' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_pas,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'US' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_us,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'PTS' THEN tms.tunggakan ELSE 0 END) AS tunggakan_pts,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'PAS' THEN tms.tunggakan ELSE 0 END) AS tunggakan_pas,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'US' THEN tms.tunggakan ELSE 0 END) AS tunggakan_us,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'PTS' THEN tms.jumlah ELSE 0 END) AS jumlah_pts,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'PAS' THEN tms.jumlah ELSE 0 END) AS jumlah_pas,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'US' THEN tms.jumlah ELSE 0 END) AS jumlah_us
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
    $totalPenetapanPts = 0;
    $totalBulanIniPts = 0;
    $totalTunggakanPts = 0;
    $totalJumlahPts = 0;

    $totalPenetapanPas = 0;
    $totalBulanIniPas = 0;
    $totalTunggakanPas = 0;
    $totalJumlahPas = 0;

    $totalPenetapanUs = 0;
    $totalBulanIniUs = 0;
    $totalTunggakanUs = 0;
    $totalJumlahUs = 0;

    $penerimaanEkstra = mysqli_query($conn, $queryPenerimaan);
    while($rowPenerimaan=mysqli_fetch_array($penerimaanEkstra)){
        $namaSiswa = $rowPenerimaan['nama_siswa'];                    
        $penetapanPts = $rowPenerimaan['penetapan_pts'];
        $bulanIniPts = $rowPenerimaan['bulan_ini_pts'];
        $tunggakanPts = $rowPenerimaan['tunggakan_pts'];
        $jumlahPts = $rowPenerimaan['jumlah_pts'];
        $penetapanPas = $rowPenerimaan['penetapan_pas'];
        $bulanIniPas = $rowPenerimaan['bulan_ini_pas'];
        $tunggakanPas = $rowPenerimaan['tunggakan_pas'];
        $jumlahPas = $rowPenerimaan['jumlah_pas'];
        $penetapanUs = $rowPenerimaan['penetapan_us'];
        $bulanIniUs = $rowPenerimaan['bulan_ini_us'];
        $tunggakanUs = $rowPenerimaan['tunggakan_us'];
        $jumlahUs = $rowPenerimaan['jumlah_us'];

        $html .= '<tr>
        <td> ' . $i . '</td>
        <td> ' . $namaSiswa . '</td>
        <td>Rp. ' . number_format($penetapanPts, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniPts, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanPts, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($jumlahPts, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($penetapanPas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniPas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanPas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($jumlahPas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($penetapanUs, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniUs, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanUs, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($jumlahUs, 0, ',', '.') . '</td>
        </tr>';

        // Tambahkan nilai ke total
        $totalPenetapanPts += $penetapanPts;
        $totalBulanIniPts += $bulanIniPts;
        $totalTunggakanPts += $tunggakanPts;
        $totalJumlahPts += $jumlahPts;

        $totalPenetapanPas += $penetapanPas;
        $totalBulanIniPas += $bulanIniPas;
        $totalTunggakanPas += $tunggakanPas;
        $totalJumlahPas += $jumlahPas;

        $totalPenetapanUs += $penetapanUs;
        $totalBulanIniUs += $bulanIniUs;
        $totalTunggakanUs += $tunggakanUs;
        $totalJumlahUs += $jumlahUs;
    }
        // Tampilkan baris total
    $html .= '<tr>
    <td colspan="2"><strong> Total</strong></td>
    <td><strong>Rp. ' . number_format($totalPenetapanPts, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalBulanIniPts, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalTunggakanPts, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalJumlahPts, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalPenetapanPas, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalBulanIniPas, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalTunggakanPas, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalJumlahPas, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalPenetapanUs, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalBulanIniUs, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalTunggakanUs, 0, ',', '.') . '</strong></td>
    <td><strong>Rp. ' . number_format($totalJumlahUs, 0, ',', '.') . '</strong></td>
    </tr>
    </table><br>';
    
    $html .= '</td></tr>'; 
    $html .= '</table>'; 
}
// Tampilkan tabel untuk total kolom per kelas

$html .= '<table>';
$html .= '<tr nobr="true"><td>';

$html .= '
    <br>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Rekapitulasi Penerimaan Uang PTS, PAS, US
        </div>
        <div class="card-body">
            <table border="0.75">
                <tr>
                    <th rowspan="2" style="vertical-align: middle;"> Kelas</th>
                    <th colspan="4" style="text-align: center;">Iuran PTS</th>
                    <th colspan="4" style="text-align: center;">Iuran PAS</th>
                    <th colspan="4" style="text-align: center;">Iuran US</th>
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


// Simpan total kolom per kelas
$penetapanPtsKelas = 0;
$bulanIniPtsKelas = 0;
$tunggakanPtsKelas = 0;
$totalPtsKelas = 0;                        

$penetapanPasKelas = 0;
$bulanIniPasKelas = 0;
$tunggakanPasKelas = 0;
$totalPasKelas = 0; 

$penetapanUsKelas = 0;
$bulanIniUsKelas = 0;
$tunggakanUsKelas = 0; 
$totalUsKelas = 0;

$finalPenetapanPts = 0;
$finalBulanIniPts = 0;
$finalTunggakanPts = 0;
$finalJumlahPts = 0;

$finalPenetapanPas = 0;
$finalBulanIniPas = 0;
$finalTunggakanPas = 0;
$finalJumlahPas = 0;

$finalPenetapanUs = 0;
$finalBulanIniUs = 0;
$finalTunggakanUs = 0;
$finalJumlahUs = 0;

// Loop untuk menghitung total kolom per kelas
for ($kelas = 1; $kelas <= 6; $kelas++) {

    $queryTotal = "SELECT
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PTS' THEN tms.penetapan ELSE 0 END) AS penetapan_pts_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PAS' THEN tms.penetapan ELSE 0 END) AS penetapan_pas_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'US' THEN tms.penetapan ELSE 0 END) AS penetapan_us_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PTS' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_pts_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PAS' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_pas_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'US' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_us_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PTS' THEN tms.tunggakan ELSE 0 END) AS tunggakan_pts_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PAS' THEN tms.tunggakan ELSE 0 END) AS tunggakan_pas_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'US' THEN tms.tunggakan ELSE 0 END) AS tunggakan_us_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PTS' THEN tms.jumlah ELSE 0 END) AS total_pts_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'PAS' THEN tms.jumlah ELSE 0 END) AS total_pas_kelas,
    SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'US' THEN tms.jumlah ELSE 0 END) AS total_us_kelas

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
    $penetapanPtsKelas =  $rowKelas['penetapan_pts_kelas'];
    $bulanIniPtsKelas = $rowKelas['bulan_ini_pts_kelas'];
    $tunggakanPtsKelas =  $rowKelas['tunggakan_pts_kelas'];
    $totalPtsKelas = $rowKelas['total_pts_kelas'];

    $penetapanPasKelas = $rowKelas['penetapan_pas_kelas'];
    $bulanIniPasKelas = $rowKelas['bulan_ini_pas_kelas'];
    $tunggakanPasKelas = $rowKelas['tunggakan_pas_kelas'];
    $totalPasKelas = $rowKelas['total_pas_kelas'];

    $penetapanUsKelas = $rowKelas['penetapan_us_kelas'];
    $bulanIniUsKelas = $rowKelas['bulan_ini_us_kelas'];
    $tunggakanUsKelas = $rowKelas['tunggakan_us_kelas'];
    $totalUsKelas = $rowKelas['total_us_kelas'];     

    $html .= '<tr>
        <td> Kelas ' . $kelas . '</td>
        <td>Rp. ' . number_format($penetapanPtsKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniPtsKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanPtsKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($totalPtsKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($penetapanPasKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniPasKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanPasKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($totalPasKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($penetapanUsKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($bulanIniUsKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($tunggakanUsKelas, 0, ',', '.') . '</td>
        <td>Rp. ' . number_format($totalUsKelas, 0, ',', '.') . '</td>
    </tr>';


    // Tambahkan nilai ke total
    $finalPenetapanPts += $penetapanPtsKelas;
    $finalBulanIniPts += $bulanIniPtsKelas;
    $finalTunggakanPts += $tunggakanPtsKelas;
    $finalJumlahPts += $totalPtsKelas;

    $finalPenetapanPas += $penetapanPasKelas;
    $finalBulanIniPas += $bulanIniPasKelas;
    $finalTunggakanPas += $tunggakanPasKelas;
    $finalJumlahPas += $totalPasKelas;

    $finalPenetapanUs += $penetapanUsKelas;
    $finalBulanIniUs += $bulanIniUsKelas;
    $finalTunggakanUs += $tunggakanUsKelas;
    $finalJumlahUs += $totalUsKelas;
}

    $html .= '<tr>
        <td><strong> Total</strong></td>
        <td><strong>Rp. ' . number_format($finalPenetapanPts, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalBulanIniPts, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalTunggakanPts, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalJumlahPts, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalPenetapanPas, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalBulanIniPas, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalTunggakanPas, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalJumlahPas, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalPenetapanUs, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalBulanIniUs, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalTunggakanUs, 0, ',', '.') . '</strong></td>
        <td><strong>Rp. ' . number_format($finalJumlahUs, 0, ',', '.') . '</strong></td>
    </tr>
    </table>
    </div>
    </div><br>';  
    
$html .= '</td></tr>'; 
$html .= '</table>'; 

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
$html .= '<tr nobr="true"><td>';

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
$html .= '</table><br>';

$html .= '</td></tr>'; 
$html .= '</table>';

// print a block of text using Write()
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
$pdf->writeHTML($html, true, false, false, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('laporan_umum.pdf', 'I');

?>