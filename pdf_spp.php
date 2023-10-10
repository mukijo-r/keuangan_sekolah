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
$pdf = new MYPDF('portrait', PDF_UNIT, 'A4', true, 'UTF-8', false);


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
$pdf->SetMargins(15, 40, 15);
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
Laporan Keuangan SPP
Bulan $bulan
Tahun Ajar $tahunAjar


EOD;

$pdf->SetFont('times', '', 10);
$pdf->SetCellMargins(0, 1, 0, 0);
$html = '';

for ($kelas = 1; $kelas <= 6; $kelas++) {
    $html .= 'Daftar Penerimaan kelas ' . $kelas . ' :<br><br>';
    $html .= '<table border="0.75">';
    $html .= '<tr>';
    $html .= '<th rowspan="2" style="vertical-align: middle; width: 5%"> No.</th>';
    $html .= '<th rowspan="2" style="vertical-align: middle; width: 25%"> Nama Siswa</th>';
    $html .= '<th colspan="4" style="text-align: center; width: 70%"> Iuran SPP</th>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td> Penetapan</td>';
    $html .= '<td> Bulan ini</td>';
    $html .= '<td> Tunggakan</td>';
    $html .= '<td> Jumlah</td>';
    $html .= '</tr>';

    $queryPenerimaan = "SELECT
    s.nama AS nama_siswa,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'SPP' THEN tms.penetapan ELSE 0 END) AS penetapan_spp,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'SPP' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_spp,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'SPP' THEN tms.tunggakan ELSE 0 END) AS tunggakan_spp,
    SUM(CASE WHEN subkat.nama_sub_kategori = 'SPP' THEN tms.jumlah ELSE 0 END) AS jumlah_spp
    
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
    $totalPenetapanSpp = 0;
    $totalbulanIniSpp = 0;
    $totalTunggakanSpp = 0;
    $totalJumlahSpp = 0;

    $penerimaanEkstra = mysqli_query($conn, $queryPenerimaan);
    while($rowPenerimaan=mysqli_fetch_array($penerimaanEkstra)){
        $namaSiswa = $rowPenerimaan['nama_siswa'];                    
        $penetapanSpp = $rowPenerimaan['penetapan_spp'];
        $bulanIniSpp = $rowPenerimaan['bulan_ini_spp'];
        $tunggakanSpp = $rowPenerimaan['tunggakan_spp'];
        $jumlahSpp = $rowPenerimaan['jumlah_spp'];

    $html .= '<tr>';
    $html .= '<td> ' . $i++ . '</td>';
    $html .= '<td> ' . $namaSiswa . '</td>';
    $html .= '<td> Rp. ' . number_format($penetapanSpp, 0, ',', '.') . '</td>';
    $html .= '<td> Rp. ' . number_format($bulanIniSpp, 0, ',', '.') . '</td>';
    $html .= '<td> Rp. ' . number_format($tunggakanSpp, 0, ',', '.') . '</td>';
    $html .= '<td> Rp. ' . number_format($jumlahSpp, 0, ',', '.') . '</td>';
    $html .= '</tr>';

    // Tambahkan nilai ke total
    $totalPenetapanSpp += $penetapanSpp;
    $totalbulanIniSpp += $bulanIniSpp;
    $totalTunggakanSpp += $tunggakanSpp;
    $totalJumlahSpp += $jumlahSpp;
    }
    // Tampilkan baris total
    $html .= '<tr>';
    $html .= '<td colspan="2"> Total</td>';
    $html .= '<td><strong> Rp. ' . number_format($totalPenetapanSpp, 0, ',', '.') . '</strong></td>';
    $html .= '<td><strong> Rp. ' . number_format($totalbulanIniSpp, 0, ',', '.') . '</strong></td>';
    $html .= '<td><strong> Rp. ' . number_format($totalTunggakanSpp, 0, ',', '.') . '</strong></td>';
    $html .= '<td><strong> Rp. ' . number_format($totalJumlahSpp, 0, ',', '.') . '</strong></td>';
    $html .= '</tr>';                            
    $html .= '</table>'; 
    $html .= '<br><br>';
    }                       

// Tampilkan tabel untuk total kolom per kelas
$html .= 'Rekapitulasi Penerimaan Uang SPP :';
$html .= '<br><br>';
$html .= '<table border="0.75">';
$html .= '<tr>';
$html .= '<th rowspan="2"  style="vertical-align: middle;"> Kelas</th>';
$html .= '<th colspan="4" style="text-align: center;"> Iuran SPP</th>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td> Penetapan</td>';
$html .= '<td> Bulan ini</td>';
$html .= '<td> Tunggakan</td>';
$html .= '<td> Jumlah</td>';
$html .= '</tr>'; 

// Simpan total kolom per kelas
$penetapanSppKelas = 0;
$bulanIniSppKelas = 0;
$tunggakanSppKelas = 0;
$totalSppKelas = 0;

$finalPenetapanSpp = 0;
$finalBulanIniSpp = 0;
$finalTunggakanSpp = 0;
$finalJumlahSpp = 0;

// Loop untuk menghitung total kolom per kelas
for ($kelas = 1; $kelas <= 6; $kelas++) {

$queryTotal = "SELECT
SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'SPP' THEN tms.penetapan ELSE 0 END) AS penetapan_spp_kelas,
SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'SPP' THEN tms.bulan_ini ELSE 0 END) AS bulan_ini_spp_kelas,
SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'SPP' THEN tms.tunggakan ELSE 0 END) AS tunggakan_spp_kelas,
SUM(CASE WHEN s.id_kelas = $kelas AND subkat.nama_sub_kategori = 'SPP' THEN tms.jumlah ELSE 0 END) AS total_spp_kelas

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
$penetapanSppKelas =  $rowKelas['penetapan_spp_kelas'];
$bulanIniSppKelas =  $rowKelas['bulan_ini_spp_kelas'];
$tunggakanSppKelas =  $rowKelas['tunggakan_spp_kelas'];
$totalSppKelas = $rowKelas['total_spp_kelas'];

$html .= '<tr>';
$html .= '<td> Kelas ' . $kelas . '</td>';
$html .= '<td> Rp. ' . number_format($penetapanSppKelas, 0, ',', '.') . '</td>';
$html .= '<td> Rp. ' . number_format($bulanIniSppKelas, 0, ',', '.') . '</td>';
$html .= '<td> Rp. ' . number_format($tunggakanSppKelas, 0, ',', '.') . '</td>';
$html .= '<td> Rp. ' . number_format($totalSppKelas, 0, ',', '.') . '</td>';
$html .= '</tr>';

// Tambahkan nilai ke total
$finalPenetapanSpp += $penetapanSppKelas;
$finalBulanIniSpp += $bulanIniSppKelas;
$finalTunggakanSpp += $tunggakanSppKelas;
$finalJumlahSpp += $totalSppKelas;

}

$html .= '<tr>';
$html .= '<td><strong> Total</strong></td>';
$html .= '<td><strong> Rp. ' . number_format($finalPenetapanSpp, 0, ',', '.') . '</strong></td>';
$html .= '<td><strong> Rp. ' . number_format($finalBulanIniSpp, 0, ',', '.') . '</strong></td>';
$html .= '<td><strong> Rp. ' . number_format($finalTunggakanSpp, 0, ',', '.') . '</strong></td>';
$html .= '<td><strong> Rp. ' . number_format($finalJumlahSpp, 0, ',', '.') . '</strong></td>';
$html .= '</tr>';
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