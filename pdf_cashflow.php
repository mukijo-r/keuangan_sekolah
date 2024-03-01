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
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);


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
$tanggalAkhir2 = $_POST['tanggalAkhir2'];



$pdf->SetFont('helvetica', '', 10);
$txt = <<<EOD
Laporan Cash Flow 
Bulan $bulan
Tahun Ajar $tahunAjar


EOD;

$pdf->SetFont('times', '', 10);
$pdf->SetCellMargins(0, 1, 0, 0);

$html = '<table>';
$html .= '<tr>';
//Kolom Pendapatan
$html .= '<td style="width: 50%">';
$queryGroupCashflow = mysqli_query($conn, "SELECT * FROM group_cashflow WHERE jenis='Pendapatan'");
$groupCashflowData = array();

while ($row = mysqli_fetch_assoc($queryGroupCashflow)) {
    $groupCashflowData[$row['id_group_cashflow']] = $row;
}

// Ambil data dari tabel sub_kategori_cashflow
$querySubKategoriCashflow = mysqli_query($conn, "SELECT * FROM sub_kategori_cashflow");
$subKategoriCashflowData = array();

while ($row = mysqli_fetch_assoc($querySubKategoriCashflow)) {
    $subKategoriCashflowData[$row['id_subkategori_cashflow']] = $row;
}

$html .= '<table border="0.75">
            <tr style="line-height: 30px;">
                <th style="width: 15%"><strong>  Nomor</strong></th>
                <th style="width: 55%"><strong>  Rekening</strong></th>
                <th style="width: 29%"><strong>  Jumlah</strong></th>
            </tr>
            <tr style="line-height: 20px;">
                <td></td>
                <td><strong>  Pendapatan</strong></td>
                <td></td>
            </tr>';
        $nomorGroup = 1;

foreach ($groupCashflowData as $groupId => $group) {
    $html .= '<tr style="line-height: 20px;">';
    $html .= '<td><strong>  ' . $nomorGroup . '</strong></td>';
    $html .= '<td><strong>  ' . $group['groop'] . '</strong></td>';
    $html .= '<td></td>';
    $html .= '</tr>';

    $nomorSubKategori = 1;
    foreach ($subKategoriCashflowData as $subKategoriId => $subKategori) {
        if ($subKategori['id_group_cashflow'] == $groupId) {
            // Query SQL untuk mengambil nilai jumlah dari transaksi_masuk_cashflow
            $idSubkategoriCashflow = $subKategoriId;
            $queryJumlah = mysqli_query($conn, "SELECT SUM(jumlah) AS total_jumlah FROM transaksi_masuk_cashflow WHERE id_tahun_ajar='$idTahunAjar' AND bulan='$bulan' AND id_subkategori_cashflow = $idSubkategoriCashflow");
            $jumlahData = mysqli_fetch_assoc($queryJumlah);
            $totalJumlah = $jumlahData['total_jumlah'];

            // Periksa apakah total jumlah tidak sama dengan nol sebelum menampilkan baris sub kategori
            if ($totalJumlah != 0) {
                $html .= '<tr style="line-height: 20px;">';
                $html .= '<td>  ' . $nomorGroup . '.' . $nomorSubKategori . '</td>';
                $html .= '<td>  ' . $subKategori['nama_sub_kategori'] . '</td>';
                $html .= '<td>  ' . "Rp " . number_format($totalJumlah, 0, ',', '.') . '</td>';
                $html .= '</tr>';
                $nomorSubKategori++;
            }
        }
    }

    $nomorGroup++;
}

// Menghitung jumlah penerimaan 
$queryMasukBulan = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_cashflow WHERE id_tahun_ajar='$idTahunAjar' AND bulan = '$bulan'");
$queryKeluarBulan = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_cashflow WHERE id_tahun_ajar='$idTahunAjar' AND bulan = '$bulan'");

$totalMasukBulan = 0;
$totalKeluarBulan = 0;

if ($rowMasuk = mysqli_fetch_assoc($queryMasukBulan)) {
    $totalMasukBulan = $rowMasuk['total_masuk'];
}

if ($rowKeluar = mysqli_fetch_assoc($queryKeluarBulan)) {
    $totalKeluarBulan = $rowKeluar['total_keluar'];
}

$laba = $totalMasukBulan - $totalKeluarBulan;

$html .= '<tr style="line-height: 20px;"><td></td><td></td><td></td></tr>';

$html .= '<tr style="line-height: 20px;">';
$html .= '<td></td>';
$html .= '<td><strong>  Jumlah Penerimaan</strong></td>';
$html .= '<td><strong>' . "Rp " . number_format($totalMasukBulan, 0, ',', '.') . '</strong></td>';
$html .= '</tr>';

                                           
$html .= '</table><br>';

$html .= '</td>';

//Kolom Pengeluaran
$html .= '<td style="width: 50%">';
$queryGroupCashflow2 = mysqli_query($conn, "SELECT * FROM group_cashflow WHERE jenis='Pengeluaran'");
$groupCashflowData2 = array();

while ($row = mysqli_fetch_assoc($queryGroupCashflow2)) {
    $groupCashflowData2[$row['id_group_cashflow']] = $row;
}

// Ambil data dari tabel sub_kategori_cashflow
$querySubKategoriCashflow2 = mysqli_query($conn, "SELECT * FROM sub_kategori_cashflow");
$subKategoriCashflowData2 = array();

while ($row = mysqli_fetch_assoc($querySubKategoriCashflow2)) {
    $subKategoriCashflowData2[$row['id_subkategori_cashflow']] = $row;
}

// Inisialisasi nomor unik
$nomorGroup = 1;

$html .= '<table border="0.75">
            <tr style="line-height: 30px;">
                <th style="width: 15%"><strong>  Nomor</strong></th>
                <th style="width: 55%"><strong>  Rekening</strong></th>
                <th style="width: 29%"><strong>  Jumlah</strong></th>
            </tr>
            <tr style="line-height: 20px;">
                <td></td>
                <td><strong>  Pendapatan</strong></td>
                <td></td>
            </tr>';

// Menambahkan data ke dalam tabel
$nomorGroup = 1;

foreach ($groupCashflowData2 as $groupId2 => $group2) {
    $html .= '<tr style="line-height: 20px;">';
    $html .= '<td><strong>  ' . $nomorGroup . '</strong></td>';
    $html .= '<td><strong>  ' . $group2['groop'] . '</strong></td>';
    $html .= '<td></td>';
    $html .= '</tr>';

    $nomorSubKategori = 1;
    foreach ($subKategoriCashflowData2 as $subKategoriId2 => $subKategori2) {
        if ($subKategori2['id_group_cashflow'] == $groupId2) {
            $idSubkategoriCashflow2 = $subKategoriId2;
            $queryJumlah2 = mysqli_query($conn, "SELECT SUM(jumlah) AS total_jumlah FROM transaksi_keluar_cashflow WHERE id_tahun_ajar='$idTahunAjar' AND bulan='$bulan' AND id_subkategori_cashflow = $idSubkategoriCashflow2");
            $jumlahData2 = mysqli_fetch_assoc($queryJumlah2);
            $totalJumlah2 = $jumlahData2['total_jumlah'];

            if ($totalJumlah2 != 0) {
                $html .= '<tr style="line-height: 20px;">';
                $html .= '<td>  ' . $nomorGroup . '.' . $nomorSubKategori . '</td>';
                $html .= '<td>  ' . $subKategori2['nama_sub_kategori'] . '</td>';
                $html .= '<td>  ' . "Rp " . number_format($totalJumlah2, 0, ',', '.') . '</td>';
                $html .= '</tr>';
                $nomorSubKategori++;
            }
        }
    }

    $nomorGroup++;
}

// Menghitung saldo
$queryMasuk = mysqli_query($conn, "SELECT SUM(jumlah) AS total_masuk FROM transaksi_masuk_cashflow WHERE tanggal <= '$tanggalAkhir2'");
$queryKeluar = mysqli_query($conn, "SELECT SUM(jumlah) AS total_keluar FROM transaksi_keluar_cashflow WHERE tanggal <= '$tanggalAkhir2'");

$totalMasuk = 0;
$totalKeluar = 0;

if ($rowMasuk = mysqli_fetch_assoc($queryMasuk)) {
    $totalMasuk = $rowMasuk['total_masuk'];
}

if ($rowKeluar = mysqli_fetch_assoc($queryKeluar)) {
    $totalKeluar = $rowKeluar['total_keluar'];
}

$saldo = $totalMasuk - $totalKeluar;
$saldoBulanLalu = $saldo - $laba;

$html .= '<tr style="line-height: 20px;"><td></td><td></td><td></td></tr>';
$html .= '<tr style="line-height: 20px;">';
$html .= '<td></td>';
$html .= '<td><strong>  Jumlah Pengeluaran</strong></td>';
$html .= '<td><strong>  ' . "  Rp " . number_format($totalKeluarBulan, 0, ',', '.') . '</strong></td>';
$html .= '</tr>';

$html .= '</table>';


$html .= '</td>';
$html .= '</tr>';
$html .= '</table><br><br>';

$html .= '<table><tr><td>';
$html .= '<table>';
$html .= '<tr>';
$html .= '<td style="width: 15%">  I</td>';
$html .= '<td style="width: 55%">  Jumlah Pendapatan bulan ini</td>';
$html .= '<td style="width: 29%"><strong>' . "  Rp " . number_format($totalMasukBulan, 0, ',', '.') . '</strong></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="width: 15%"></td>';
$html .= '<td style="width: 55%">  Jumlah Pengeluaran bulan ini</td>';
$html .= '<td style="width: 29%"><strong>' . "  Rp " . number_format($totalKeluarBulan, 0, ',', '.') . '</strong></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="width: 15%"></td>';
$html .= '<td style="width: 55%">  Laba / Rugi bulan ini</td>';
$html .= '<td style="width: 29%"><strong>' . "  Rp " . number_format($laba, 0, ',', '.') . '</strong></td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</td>';
$html .= '<td>';
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<td style="width: 15%">  II</td>';
    $html .= '<td style="width: 55%">  Saldo Kas bulan lalu</td>';
    $html .= '<td style="width: 29%"><strong>' . "  Rp " . number_format($saldoBulanLalu, 0, ',', '.') . '</strong></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 15%"></td>';
    $html .= '<td style="width: 55%">  Laba / Rugi bulan ini</td>';
    $html .= '<td style="width: 29%"><strong>' . "  Rp " . number_format($laba, 0, ',', '.') . '</strong></td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="width: 15%"></td>';
    $html .= '<td style="width: 55%">  Saldo kas bulan ini</td>';
    $html .= '<td style="width: 29%"><strong>' . "  Rp " . number_format($saldo, 0, ',', '.') . '</strong></td>';
    $html .= '</tr>';
    $html .= '</table>';
$html .= '</td>';
$html .= '</tr>';
$html .= '</table><br><br>';



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
$html .= '</table><br><br>';

$html .= '</td></tr>'; 
$html .= '</table>';



// print a block of text using Write()
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);
$pdf->writeHTML($html, true, false, false, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('laporan_umum.pdf', 'I');

?>