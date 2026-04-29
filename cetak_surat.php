<?php
require('fpdf.php');
include 'koneksi.php';

// Cek apakah ada ID pengajuan
if (!isset($_GET['id'])) {
    die("ID Pengajuan tidak ditemukan.");
}

$id = (int)$_GET['id'];
$query = "SELECT * FROM pengajuan WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data pengajuan tidak ditemukan.");
}

// Inisialisasi PDF tanpa menggunakan class Header override
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Margins (default 10mm)
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 15);

// Judul Surat (Hanya di halaman pertama)
$pdf->SetFont('helvetica','B',12);
$pdf->Cell(0, 6, 'PERMOHONAN OBJEK PAJAK BARU', 0, 1, 'C');
$pdf->Cell(0, 6, 'PBB-P2 TAHUN ' . date('Y'), 0, 1, 'C');
$pdf->Ln(8);

$pdf->SetFont('helvetica','',10);

// Tanggal dan Kepada Yth (Rata Kanan)
$tanggal_pengajuan = date('d F Y', strtotime($data['tanggal']));
$pdf->Cell(0, 5, 'Samarinda, ................................. ' . date('Y'), 0, 1, 'R');
$pdf->Ln(5);

$pdf->Cell(110);
$pdf->Cell(0, 4, 'Kepada,', 0, 1);
$pdf->Cell(110);
$pdf->Cell(0, 4, 'YTH. Walikota Samarinda', 0, 1);
$pdf->Cell(110);
$pdf->Cell(0, 4, 'Cq.Kepala Badan Pendapatan Daerah', 0, 1);
$pdf->Cell(110);
$pdf->Cell(0, 4, 'Kota Samarinda', 0, 1);
$pdf->Cell(110);
$pdf->Cell(0, 4, 'di-', 0, 1);
$pdf->SetFont('helvetica','B',10);
$pdf->Cell(120);
$pdf->Cell(0, 4, 'Samarinda', 0, 1);
$pdf->SetFont('helvetica','',10);
$pdf->Ln(6);

// Isi Surat
$pdf->Cell(0, 5, 'Dengan Hormat,', 0, 1);
$pdf->Ln(2);

$pdf->MultiCell(0, 5, '          Bersama ini diajukan Permohonan Pendaftaran Objek Pajak PBB-P2 baru atas bumi dan/ bangunan yang kami miliki/ Kuasai/ Manfaatkan sebagai berikut:');
$pdf->Ln(4);

// Formulir Data
$w_label = 45;
$pdf->Cell($w_label, 6, 'Nama Wajib Pajak', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(0, 6, $data['nama'], 0, 1);

$pdf->Cell($w_label, 6, 'NIK/NPWP', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(0, 6, $data['nik'], 0, 1);

$pdf->Cell($w_label, 6, 'Alamat Wajib Pajak', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->MultiCell(0, 6, $data['alamat']);

$pdf->Cell($w_label, 6, 'Alamat Objek Pajak', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(0, 6, '...........................................................................................', 0, 1);

$pdf->Cell($w_label, 6, '', 0, 0);
$pdf->Cell(5, 6, '', 0, 0);
$pdf->Cell(25, 6, 'Kelurahan', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(0, 6, '......................................................................', 0, 1);

$pdf->Cell($w_label, 6, '', 0, 0);
$pdf->Cell(5, 6, '', 0, 0);
$pdf->Cell(25, 6, 'Kecamatan', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(0, 6, '......................................................................', 0, 1);

$pdf->Cell($w_label, 6, '', 0, 0);
$pdf->Cell(5, 6, '', 0, 0);
$pdf->Cell(25, 6, 'Kota', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(0, 6, '......................................................................', 0, 1);

$pdf->Cell($w_label, 6, 'Nomor Telepon/HP/E-Mail', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
$pdf->Cell(0, 6, $data['no_hp'], 0, 1);

$pdf->Ln(4);

$pdf->MultiCell(0, 5, '        Adapun untuk pengajuan permohonan tersebut, saya bertanggung jawab penuh atas kebenaran data dan dokumen yang menjadikan persyaratan sesuai lampiran yaitu:');
$pdf->Ln(2);

// Persyaratan
$persyaratan = array(
    "Foto copy kartu tanda penduduk (KTP) NPWP bagi perusahaan dan badan",
    "Blangko SPOP/SLOP",
    "Surat Pernyataan yang ditanda tangani diatas materai",
    "Fotocopy Sertifikat/PPAT/SKUMHAT/IMTN atau dokumen lain dipersamakan (legalisir)",
    "Foto lokasi tanah dan Foto bangunan",
    "Titik Koordinat dari google maps (apabila surat tanah tidak ada tikor)",
    "Surat kuasa pengurusan apabila pengurusnya diwakilkan (apabila surat tanah tidak ada tikor)",
    "Fotocopy SPPDT-P2 tetangga sebagai objek pembanding (jika ada)",
    "Apabila nama disurat tanah telah meninggal dunia wajib melampirkan akta kematian serta surat kuasa ahli waris dan ahli waris dari kelurahan/notaris dan Foto copy kartu keluarga",
    "Apabila nama berbeda antara di KTP dan surat tanah harus melampirkan keterangan dari kelurahan"
);

foreach ($persyaratan as $index => $syarat) {
    // Agar text requirement rapi
    $pdf->Cell(8, 5, ($index + 1) . '.', 0, 0);
    $pdf->MultiCell(0, 5, $syarat);
}

$pdf->Ln(5);
$pdf->MultiCell(0, 5, 'Demikian permohonan ini disampaikan, atas perhatiannya diucapkan terimakasih');
$pdf->Ln(8);

// Tanda Tangan
$pdf->Cell(120);
$pdf->Cell(0, 5, 'Pemohon,', 0, 1, 'C');
$pdf->Ln(22);
$pdf->Cell(120);
$pdf->Cell(0, 5, $data['nama'], 0, 1, 'C');

// Output PDF
$pdf->Output('I', 'Surat_Pengajuan_' . $data['nik'] . '.pdf');
?>
