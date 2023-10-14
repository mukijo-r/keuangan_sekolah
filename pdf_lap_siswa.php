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
        $this->Ln(); // Ini akan membuat baris baru
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 6, 'SD KATOLIK BHAKTI ROGOJAMPI', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(); // Ini akan membuat baris baru
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 6, 'Jl. Ki. Hajar Dewantoro Tlp. (0333) 631698', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(); // Ini akan membuat baris baru
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 6, 'Rogojampi - Banyuwangi', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetLineWidth(0.3); // Atur ketebalan garis
        $this->Line(10, 37, 200, 37); // Koordinat untuk garis mendatar
        $this->Ln(); // Ini akan membuat baris baru

    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mukijo');
$pdf->SetTitle('Laporan Keuangan Umum');
$pdf->SetSubject('TCPDF');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(15, 45, 15);
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
$tahunAjar = $_POST['tahunAjar'];
$bulan = $_POST['bulan'];
$idSubKategori = $_POST['idSubKategori'];
$namaSubKategori = $_POST['namaSubKategori'];
$queryTransaksiSiswa = $_POST['queryTransaksiSiswa'];
$saldoBulanLalu = $_POST['saldoBulanLalu'];


$pdf->SetFont('helvetica', '', 10);
$txt = <<<EOD
Laporan Keuangan $namaSubKategori
Bulan $bulan
Tahun Ajar $tahunAjar


EOD;
$pdf->SetFont('times', '', 10);
$pdf->SetCellMargins(0, 1, 0, 0);
$html = '<table border="0.75">';
$html .= '<tr>
    <th style="width: 11%"><strong> Tanggal</strong></th>
    <th style="width: 27%"><strong> Uraian</strong></th>                                            
    <th style="width: 14%"><strong> Debet</strong></th>
    <th style="width: 14%"><strong> Kredit</strong></th>
    <th style="width: 14%"><strong> Saldo</strong></th>
    <th style="width: 20%"><strong> Keterangan</strong></th>
</tr>
<tr>
<td style="width: 11%"></td>
<td style="width: 27%"> Saldo bulan lalu</td>
<td style="width: 14%"> ' . (($saldoBulanLalu == 0) ? '' : "Rp " . number_format($saldoBulanLalu, 0, ',', '.')) . '</td>
<td style="width: 14%"></td>
<td style="width: 14%"> ' . (($saldoBulanLalu == 0) ? '' : "Rp " . number_format($saldoBulanLalu, 0, ',', '.')) . '</td>
<td style="width: 20%"></td>
</tr>';

$totalMasuk = 0;
$totalKeluar = 0;

$dataTransaksiSiswa = mysqli_query($conn, $queryTransaksiSiswa);

while($data=mysqli_fetch_array($dataTransaksiSiswa)){
    $tanggal =  $data['tanggal'];
    $tanggalMasuk = date("Y-m-d", strtotime($tanggal));
    $tanggalTampil = date("d-m-Y", strtotime($tanggal));
    $tanggalBayar = date("Y-m-d H:i:s", strtotime($tanggal));                                       
    $uraian = $data['uraian'];
    if ($uraian == '1' || $uraian == '2' ||$uraian == '3' ||$uraian == '4' ||$uraian == '5' ||$uraian == '6'){
        $uraianCetak = "Iuran kelas " . $uraian;
    } else {
        $uraianCetak = $uraian;
    }
    $pemasukan = $data['pemasukan'];
    $pengeluaran = $data['pengeluaran'];
    $keterangan = $data['keterangan'];

    // Menghitung saldo
    $queryMasuk = "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_siswa WHERE id_sub_kategori = '$idSubKategori' AND tanggal <= '$tanggalBayar'";
    $queryKeluar = "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_siswa WHERE id_sub_kategori = '$idSubKategori' AND tanggal <= '$tanggalBayar'";

    $masuk = mysqli_query($conn, $queryMasuk);
    $keluar = mysqli_query($conn, $queryKeluar);                                           

    if ($rowMasuk = mysqli_fetch_assoc($masuk)) {
        $totalMasuk = $rowMasuk['total_masuk'];
    }

    if ($rowKeluar = mysqli_fetch_assoc($keluar)) {
        $totalKeluar = $rowKeluar['total_keluar'];
    }

    $saldo = $totalMasuk - $totalKeluar;  
    
    $html .= '<tr>';
    $html .= '<td style="width: 11%"> ' . $tanggalTampil . '</td>';
    $html .= '<td style="width: 27%"> '. $uraianCetak . '</td>';
    $html .= '<td style="width: 14%"> ' . (($pemasukan == 0) ? '' : "Rp " . number_format($pemasukan, 0, ',', '.')) . '</td>';
    $html .= '<td style="width: 14%"> ' . (($pengeluaran == 0) ? '' : "Rp " . number_format($pengeluaran, 0, ',', '.')) . '</td>';
    $html .= '<td style="width: 14%"> ' . (($saldo == 0) ? '' : "Rp " . number_format($saldo, 0, ',', '.')) . '</td>';
    $html .= '<td style="width: 20%"> ' . $keterangan . '</td>';
    $html .= '</tr>';
}

$html .= '<tr>';
$html .= '<td colspan="2" style="text-align: center;">Total</td>';
$html .= '<td style="width: 14%"><strong> ' . (($totalMasuk == 0) ? '' : "Rp " . number_format($totalMasuk, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 14%"><strong> ' . (($totalKeluar == 0) ? '' : "Rp " . number_format($totalKeluar, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 14%"><strong> ' . (($saldo == 0) ? '' : "Rp " . number_format($saldo, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 20%"></td></tr>';
$html .= '</table><br><br><br>';

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