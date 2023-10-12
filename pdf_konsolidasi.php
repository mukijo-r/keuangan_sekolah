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
$tahunAjar = $_POST['tahunAjar'];
$bulan = $_POST['bulan'];
$tanggalAkhir2 = $_POST['tanggalAkhir2'];
$saldoAwalCashflow = $_POST['saldoAwalCashflow'];
$debetCashflow = $_POST['debetCashflow'];
$kreditCashflow = $_POST['kreditCashflow'];
$saldoAkhirCashflow = $_POST['saldoAkhirCashflow'];
$queryKasYs = $_POST['queryKasYs'];
$queryKasS = $_POST['queryKasS'];
$tunaiKasCf = $_POST['tunaiKasCf'];
$jumlahPinjamCf = $_POST['jumlahPinjamCf'];
$keteranganCf = $_POST['keteranganCf'];
$keteranganPinjamCf = $_POST['keteranganPinjamCf'];
$namaGuru = $_POST['namaGuru'];
$queryPemegang = $_POST['queryPemegang'];

$pdf->SetFont('helvetica', '', 10);
$txt = <<<EOD
Laporan Keuangan Konsolidasi
Bulan $bulan
Tahun Ajar $tahunAjar

EOD;

$pdf->SetFont('times', '', 10);
$pdf->SetCellMargins(0, 1, 0, 0);

$html = '<table border="0.75">
        <tr>
            <th style="width: 5%"><strong>  No.</strong></th>    
            <th style="width: 21%"><strong>  Macam Keuangan</strong></th>
            <th style="width: 14%"><strong>  Saldo Awal</strong></th>
            <th style="width: 14%"><strong>  Masuk</strong></th>
            <th style="width: 14%"><strong>  Keluar</strong></th>
            <th style="width: 14%"><strong>  Saldo Akhir</strong></th>
            <th style="width: 18%"><strong>  Keterangan</strong></th>
        </tr>';
$html .= '<tr>';
$html .= '<td style="width: 5%">  1</td>';
$html .= '<td style="width: 21%">  Cash Flow</td>';
$html .= '<td style="width: 14%">  ' . (($saldoAwalCashflow == 0) ? '' : "Rp " . number_format($saldoAwalCashflow, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($debetCashflow == 0) ? '' : "Rp " . number_format($debetCashflow, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($kreditCashflow == 0) ? '' : "Rp " . number_format($kreditCashflow, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($saldoAkhirCashflow == 0) ? '' : "Rp " . number_format($saldoAkhirCashflow, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 18%">  ' . $keteranganCf . '</td>';
$html .= '</tr>';

$dataKasYs = mysqli_query($conn, $queryKasYs);
$i = 2;
$totalSaldoAwalYs = 0;
$totalDebetYs = 0;
$totalKreditYs = 0;
$totalSaldoAkhirYs = 0;
while($data=mysqli_fetch_array($dataKasYs)){
    $idKategori = $data['id_kategori'];

    if ($idKategori == 1) {
        continue;
    }

    $kategori = $data['nama_kategori'];
    $debetYs = $data['total_masuk'];
    $kreditYs = $data['total_keluar'];
    //query saldo ys
    $querySaldoYs = "SELECT
    k.id_kategori,
    k.nama_kategori,
    (SUM(COALESCE(masuk.total_masuk, 0)) - SUM(COALESCE(keluar.total_keluar, 0))) AS saldo
    FROM kategori k
    LEFT JOIN
        (
            SELECT
                tm.id_kategori,
                SUM(tm.jumlah) AS total_masuk
            FROM transaksi_masuk_siswa tm
            JOIN tahun_ajar ta ON tm.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tm.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
            UNION ALL
            SELECT
                tn.id_kategori,
                SUM(tn.jumlah) AS total_masuk
            FROM transaksi_masuk_nonsiswa tn
            JOIN tahun_ajar ta ON tn.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tn.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
            UNION ALL
            SELECT
                tbm.id_kategori,
                SUM(tbm.jumlah) AS total_masuk
            FROM tabung_masuk tbm
            JOIN tahun_ajar ta ON tbm.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tbm.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
        ) AS masuk
    ON k.id_kategori = masuk.id_kategori
    LEFT JOIN
        (
            SELECT
                tks.id_kategori,
                SUM(tks.jumlah) AS total_keluar
            FROM transaksi_keluar_siswa tks
            JOIN tahun_ajar ta ON tks.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tks.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
            UNION ALL
            SELECT
                tkn.id_kategori,
                SUM(tkn.jumlah) AS total_keluar
            FROM transaksi_keluar_nonsiswa tkn
            JOIN tahun_ajar ta ON tkn.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tkn.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
            UNION ALL
            SELECT
                tba.id_kategori,
                SUM(tba.jumlah) AS total_keluar
            FROM tabung_ambil tba
            JOIN tahun_ajar ta ON tba.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tba.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
        ) AS keluar
    ON k.id_kategori = keluar.id_kategori
    WHERE
        k.kode = 'ys'
    ";
    $saldoYs = mysqli_query($conn, $querySaldoYs);
    if ($rowSaldoYs = mysqli_fetch_assoc($saldoYs)) {
        $saldoAkhirYs = $rowSaldoYs['saldo'];
    }

    $selisihDebetKreditYs = $debetYs - $kreditYs;
    $saldoAwalYs = $saldoAkhirYs - $selisihDebetKreditYs;

    $queryGetKeteranganKasYs = "SELECT `keterangan` FROM `kategori` WHERE `id_kategori` = $idKategori";
    $queryKeteranganYs = mysqli_query($conn, $queryGetKeteranganKasYs);
    if ($rowKeteranganYs = mysqli_fetch_assoc($queryKeteranganYs)) {
        $keteranganYs = $rowKeteranganYs['keterangan'];
    } 

$html .= '<tr>';
$html .= '<td style="width: 5%">  ' . $i++ . '</td>';
$html .= '<td style="width: 21%">  ' . $kategori . '</td>';
$html .= '<td style="width: 14%">  ' . (($saldoAwalYs == 0) ? '' : "Rp " . number_format($saldoAwalYs, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($debetYs == 0) ? '' : "Rp " . number_format($debetYs, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($kreditYs == 0) ? '' : "Rp " . number_format($kreditYs, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($saldoAkhirYs == 0) ? '' : "Rp " . number_format($saldoAkhirYs, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 18%">  ' . $keteranganYs . '</td>';
$html .= '</tr>';



    $totalSaldoAwalYs += $saldoAwalYs;
    $totalDebetYs += $debetYs;
    $totalKreditYs += $kreditYs;
    $totalSaldoAkhirYs += $saldoAkhirYs;                                    
    }

$ysSaldoAwal = $saldoAwalCashflow + $totalSaldoAwalYs;
$ysDebet = $debetCashflow + $totalDebetYs;
$ysKredit = $kreditCashflow + $totalKreditYs;
$ysSaldo = $saldoAkhirCashflow + $totalSaldoAkhirYs;

$html .= '<tr>';
$html .= '<td style="width: 5%"></td>';
$html .= '<td style="width: 21%"></td>';
$html .= '<td style="width: 14%"><strong>  ' . (($ysSaldoAwal == 0) ? '' : "Rp " . number_format($ysSaldoAwal, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 14%"><strong>  ' . (($ysDebet == 0) ? '' : "Rp " . number_format($ysDebet, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 14%"><strong>  ' . (($ysKredit == 0) ? '' : "Rp " . number_format($ysKredit, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 14%"><strong>  ' . (($ysSaldo == 0) ? '' : "Rp " . number_format($ysSaldo, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 18%"></td>';
$html .= '</tr>';

$dataKasS = mysqli_query($conn, $queryKasS);
$i = 5;
$totalSaldoAwalS = 0;
$totalDebetS = 0;
$totalKreditS = 0;
$totalSaldoAkhirS = 0;
while($data=mysqli_fetch_array($dataKasS)){
    $idKategori = $data['id_kategori'];

    $kategori = $data['nama_kategori'];
    $debetS = $data['total_masuk'];
    $kreditS = $data['total_keluar'];
    
    // Menghitung saldo
    $querySaldoS = mysqli_query($conn, "SELECT
    k.id_kategori,
    k.nama_kategori,
    (SUM(COALESCE(masuk.total_masuk, 0)) - SUM(COALESCE(keluar.total_keluar, 0))) AS saldo
    FROM kategori k
    LEFT JOIN
        (
            SELECT
                tm.id_kategori,
                SUM(tm.jumlah) AS total_masuk
            FROM transaksi_masuk_siswa tm
            JOIN tahun_ajar ta ON tm.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tm.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
            UNION ALL
            SELECT
                tn.id_kategori,
                SUM(tn.jumlah) AS total_masuk
            FROM transaksi_masuk_nonsiswa tn
            JOIN tahun_ajar ta ON tn.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tn.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
            UNION ALL
            SELECT
                tbm.id_kategori,
                SUM(tbm.jumlah) AS total_masuk
            FROM tabung_masuk tbm
            JOIN tahun_ajar ta ON tbm.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tbm.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
        ) AS masuk
    ON k.id_kategori = masuk.id_kategori
    LEFT JOIN
        (
            SELECT
                tks.id_kategori,
                SUM(tks.jumlah) AS total_keluar
            FROM transaksi_keluar_siswa tks
            JOIN tahun_ajar ta ON tks.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tks.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
            UNION ALL
            SELECT
                tkn.id_kategori,
                SUM(tkn.jumlah) AS total_keluar
            FROM transaksi_keluar_nonsiswa tkn
            JOIN tahun_ajar ta ON tkn.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tkn.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
            UNION ALL
            SELECT
                tba.id_kategori,
                SUM(tba.jumlah) AS total_keluar
            FROM tabung_ambil tba
            JOIN tahun_ajar ta ON tba.id_tahun_ajar = ta.id_tahun_ajar
            WHERE
                tba.id_kategori = $idKategori
                AND tanggal <= '$tanggalAkhir2'
        ) AS keluar
    ON k.id_kategori = keluar.id_kategori
    WHERE
        k.kode = 's'
    ");

if ($rowSaldoS = mysqli_fetch_assoc($querySaldoS)) {
    $saldoAkhirS = $rowSaldoS['saldo'];
}

$selisihDebetKreditS = $debetS - $kreditS;
$saldoAwalS = $saldoAkhirS - $selisihDebetKreditS;

$queryGetKeteranganKasS = "SELECT `keterangan` FROM `kategori` WHERE `id_kategori` = $idKategori";
$queryKeteranganS = mysqli_query($conn, $queryGetKeteranganKasS);
if ($rowKeteranganS = mysqli_fetch_assoc($queryKeteranganS)) {
    $keteranganS = $rowKeteranganS['keterangan'];
} 

$html .= '<tr>';
$html .= '<td style="width: 5%">  ' . $i++ . '</td>';
$html .= '<td style="width: 21%">  ' . $kategori . '</td>';
$html .= '<td style="width: 14%">  ' . (($saldoAwalS == 0) ? '' : "Rp " . number_format($saldoAwalS, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($debetS == 0) ? '' : "Rp " . number_format($debetS, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($kreditS == 0) ? '' : "Rp " . number_format($kreditS, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($saldoAkhirS == 0) ? '' : "Rp " . number_format($saldoAkhirS, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 18%">  ' . $keteranganS . '</td>';
$html .= '</tr>';    

    $totalSaldoAwalS += $saldoAwalS;
    $totalDebetS += $debetS;
    $totalKreditS += $kreditS;
    $totalSaldoAkhirS += $saldoAkhirS;                                    
    }                                    
    $konsolidasiSaldoAwal = $ysSaldoAwal + $totalSaldoAwalS;
    $konsolidasiDebet = $ysDebet + $totalDebetS;
    $konsolidasiKredit = $ysKredit + $totalKreditS;
    $konsolidasiSaldo = $ysSaldo + $totalSaldoAkhirS;

$html .= '<tr>';
$html .= '<td style="width: 5%"></td>';
$html .= '<td style="width: 21%"></td>';
$html .= '<td style="width: 14%"><strong>  ' . (($konsolidasiSaldoAwal == 0) ? '' : "Rp " . number_format($konsolidasiSaldoAwal, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 14%"><strong>  ' . (($konsolidasiDebet == 0) ? '' : "Rp " . number_format($konsolidasiDebet, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 14%"><strong>  ' . (($konsolidasiKredit == 0) ? '' : "Rp " . number_format($konsolidasiKredit, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 14%"><strong>  ' . (($konsolidasiSaldo == 0) ? '' : "Rp " . number_format($konsolidasiSaldo, 0, ',', '.')) . '</strong></td>';
$html .= '<td style="width: 18%"></td>';
$html .= '</tr>';
$html .= '</table><br><br>';
$html .= '<table><tr><td style="width: 33%"></td><td>Daftar Pemegang Kas Keuangan Sekolah</td></tr></table><br>';

$html .= '<table border="0.75">
            <tr>
                <th style="width: 5%"><strong>  No.</strong></th>
                <th style="width: 19%"><strong>  Nama Pemegang Kas</strong></th>    
                <th style="width: 20%"><strong>  Macam Keuangan</strong></th>
                <th style="width: 14%"><strong>  Jumlah Total</strong></th>                                            
                <th style="width: 14%"><strong>  Tunai</strong></th>
                <th style="width: 14%"><strong>  Dipinjam</strong></th>
                <th style="width: 14%"><strong>  Keterangan</strong></th>
            </tr>';

$html .= '<tr>';
$html .= '<td style="width: 5%">1</td>';                                      
$html .= '<td style="width: 19%">  ' . $namaGuru . '</td>';
$html .= '<td style="width: 20%">  Cash Flow</td>'; 
$html .= '<td style="width: 14%">  ' . (($saldoAkhirCashflow == 0) ? '' : "Rp " . number_format($saldoAkhirCashflow, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($tunaiKasCf == 0) ? '' : "Rp " . number_format($tunaiKasCf, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . (($jumlahPinjamCf == 0) ? '' : "Rp " . number_format($jumlahPinjamCf, 0, ',', '.')) . '</td>';
$html .= '<td style="width: 14%">  ' . $keteranganPinjamCf . '</td>';
$html .= '</tr>';

$pemegangKas = mysqli_query($conn, $queryPemegang);
$i = 2;
$tunaiKas = 0;

while($data=mysqli_fetch_array($pemegangKas)){
    $idKategori = $data['id_kategori'];

    if ($idKategori == 1) {
        continue;
    }

    $kategori = $data['nama_kategori'];
    $nama = $data['nama_lengkap'];
    $kasMasuk = $data['total_masuk'];
    $kasKeluar = $data['total_keluar'];

    
    $queryPinjamKas = mysqli_query($conn, "SELECT 
    SUM(`jumlah`) AS total_jumlah, 
    (SELECT 
    `keterangan`
    FROM 
    `pinjam`
    WHERE 
    `tanggal` <= '$tanggalAkhir2' 
    AND `id_kategori` = $idKategori
    ORDER BY tanggal DESC limit 1) AS keterangan_terakhir 
    FROM 
    `pinjam` 
    WHERE 
    `tanggal` <= '$tanggalAkhir2' 
    AND `id_kategori` = $idKategori;");
    
    $rowPinjamKas = mysqli_fetch_assoc($queryPinjamKas);
    $jumlahPinjamKas = $rowPinjamKas['total_jumlah'];
    $keteranganPinjamKas = $rowPinjamKas['keterangan_terakhir'];                                  

    $jumlahKas = $kasMasuk - $kasKeluar;
    $tunaiKas = $jumlahKas - $jumlahPinjamKas;
    $html .= '<tr>';
    $html .= '<td>  ' . $i++ . '</td>';                                        
    $html .= '<td>  ' . $nama . '</td>';
    $html .= '<td>  ' . $kategori . '</td>';
    $html .= '<td style="width: 14%">  ' . (($jumlahKas == 0) ? '' : "Rp " . number_format($jumlahKas, 0, ',', '.')) . '</td>';
    $html .= '<td style="width: 14%">  ' . (($tunaiKas == 0) ? '' : "Rp " . number_format($tunaiKas, 0, ',', '.')) . '</td>';
    $html .= '<td style="width: 14%">  ' . (($jumlahPinjamKas == 0) ? '' : "Rp " . number_format($jumlahPinjamKas, 0, ',', '.')) . '</td>';
    $html .= '<td>  ' . $keteranganPinjamKas . '</td>';
    $html .= '</tr>'; 
}
$html .= '</table><br>';

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