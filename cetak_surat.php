<?php
require('fpdf.php');
include 'koneksi.php';

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

$jenis = $data['jenis_surat'];

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 15);

// Set Titles
$titles = [
    "Pendaftaran Objek Pajak Baru PBB-P2" => "PERMOHONAN OBJEK PAJAK BARU\nPBB-P2 TAHUN " . date('Y'),
    "Mutasi / Pembetulan Subjek/Objek Pajak PBB-P2" => "SURAT PERMOHONAN MUTASI / PEMBETULAN SUBJEK/OBJEK PAJAK\nPBB-P2 TAHUN " . date('Y'),
    "Pemecahan Subjek/Objek (Objek Pajak Data Baru)" => "PERMOHONAN PEMECAHAN SUBJEK/OBJEK ( OBJEK PAJAK DATA BARU )\nPBB-P2 TAHUN " . date('Y'),
    "Keberatan Subjek / Objek Pajak SPPDT PBB-P2" => "PERMOHONAN KEBERATAN SUBJEK / OBJEK PAJAK\nSPPDT PBB-P2 TAHUN " . date('Y'),
    "Penghapusan Subjek/Objek Pajak SPPT PBB-P2" => "PERMOHONAN PENGHAPUSAN SUBJEK/OBJEK PAJAK\nSPPT PBB-P2 TAHUN " . date('Y'),
    "Penggabungan Subjek/Objek Pajak SPPT PBB-P2" => "PERMOHONAN PENGGABUNGAN SUBJEK/OBJEK PAJAK\nSPPT PBB-P2 TAHUN " . date('Y')
];

$title_text = isset($titles[$jenis]) ? $titles[$jenis] : "PERMOHONAN OBJEK PAJAK BARU\nPBB-P2 TAHUN " . date('Y');

$pdf->SetFont('helvetica','B',11);
$lines = explode("\n", $title_text);
foreach($lines as $line) {
    $pdf->Cell(0, 5, $line, 0, 1, 'C');
}
$pdf->Ln(6);

$pdf->SetFont('helvetica','',9.5);

// Header (Rata Kanan)
$bulanEn = ['January','February','March','April','May','June','July','August','September','October','November','December'];
$bulanId = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$tanggal_pengajuan = str_replace($bulanEn, $bulanId, date('d F Y', strtotime($data['tanggal'])));

$pdf->Cell(0, 4, 'Samarinda, ' . $tanggal_pengajuan, 0, 1, 'R');
$pdf->Ln(4);

$pdf->Cell(110); $pdf->Cell(0, 4, 'Kepada,', 0, 1);
$pdf->Cell(110); $pdf->Cell(0, 4, 'Yth. Walikota Samarinda', 0, 1);
$pdf->Cell(110); $pdf->Cell(0, 4, 'Cq. Kepala Badan Pendapatan Daerah', 0, 1);
$pdf->Cell(110); $pdf->Cell(0, 4, 'Kota Samarinda', 0, 1);
$pdf->Cell(110); $pdf->Cell(0, 4, 'Di-', 0, 1);
$pdf->SetFont('helvetica','B',9.5);
$pdf->Cell(120); $pdf->Cell(0, 4, 'Samarinda', 0, 1);
$pdf->SetFont('helvetica','',9.5);
$pdf->Ln(5);

$pdf->Cell(0, 5, 'Dengan Hormat,', 0, 1);

// Helper function for forms
function printField($pdf, $label, $value, $w_label = 45) {
    $pdf->Cell($w_label, 5, $label, 0, 0);
    $pdf->Cell(3, 5, ':', 0, 0);
    $pdf->MultiCell(0, 5, $value);
}

// Variables
$w_label = 45;
$nama = $data['nama'];
$nik = $data['nik'];
$alamat = $data['alamat'];
$hp = $data['no_hp'];
$titik = '...................................................................';

// Generate Form Content based on Type
if ($jenis == "Mutasi / Pembetulan Subjek/Objek Pajak PBB-P2") {
    $pdf->MultiCell(0, 5, '          Bersama ini diberitahukan bahwa SPPDT/ SPPD PBB-P2 atas bumi dan / bangunan yang kami miliki / kuasai manfaatkan sebagai berikut :');
    printField($pdf, 'Nama Wajib Pajak', $nama);
    printField($pdf, 'NIK/NPWP', $nik);
    printField($pdf, 'NOP', $titik);
    printField($pdf, 'Alamat Wajib Pajak', $alamat);
    printField($pdf, 'Alamat Objek Pajak', $titik);
    printField($pdf, 'Luas Bumi', '.................................... M2');
    printField($pdf, 'Luas Bangunan', '.................................... M2');
    $pdf->Ln(2);
    $pdf->MultiCell(0, 5, '          Setelah kami teliti ternyata SPPDT/SPPD PBB-P2 tersebut di atas terdapat kesalahan atau perubahan sehubungan dengan hal tersebut dimohon agar dapat dibetulkan sesuai kondisi yang sebenarnya yaitu :');
    printField($pdf, 'Nama Wajib Pajak', $titik);
    printField($pdf, 'NIK/NPWP', $titik);
    printField($pdf, 'NOP', $titik);
    printField($pdf, 'Alamat Wajib Pajak', $titik);
    printField($pdf, 'Alamat Objek Pajak', $titik);
    printField($pdf, 'Luas Bumi', '.................................... M2');
    printField($pdf, 'Luas Bangunan', '.................................... M2');
    printField($pdf, 'Nomor Telepon/HP/E-Mail', $hp);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 5, '          Dengan ini mengajukan Permohonan Mutasi/Pembetulan subjek/objek pajak PBB-P2 Tahun ....... Dikarenakan hal-hal sebagai berikut : ........................................................................................');
    
    $syarat = [
        "Fotocopy tanda bukti identitas(KTP ) NPWP bagi Perusahaan / Badan",
        "Blanko SPOP/LSPOP",
        "Surat Pernyataan yang ditanda tangani diatas materai",
        "Fotocopy Sertifikat/PPAT/SKUMHAT/IMTN atau dokumen lain dipersamakan (legalisir)",
        "Foto lokasi tanah dan Foto bangunan (jika ada bangunan)",
        "Titik koordinat dari google maps (apabila surat tanah tidak ada tikor).",
        "Surat kuasa pengurusan apabila pengurusannya diwakilkan (tanda tangan diatas materai)",
        "Tidak memiliki tunggakan atau piutang PBB-P2",
        "Fotocopy SPPDT-P2 tetangga sebagai objek pembanding",
        "Apabila nama disurat tanah telah meninggal dunia wajib melampirkan akta kematian serta surat kuasa ahli waris dan ahli waris tanda dari kelurahan/notaris dan Foto copy kartu keluarga",
        "Keterangan kelurahan apabila surat tanah atau lokasi objek sudah berubah atau pindah kelurahan",
        "Apabila nama berbeda antara di KTP dan surat tanah harus melampirkan keterangan dari kelurahan"
    ];

} else if ($jenis == "Pemecahan Subjek/Objek (Objek Pajak Data Baru)") {
    $pdf->MultiCell(0, 5, '          Bersama ini diajukan Permohonan Pendaftaran Objek Pajak PBB-P2 baru atas bumi dan / bangunan yang kami miliki/ Kuasai/ Manfaatkan sebagai berikut:');
    printField($pdf, 'Nama Wajib Pajak', $nama);
    printField($pdf, 'NIK/NPWP', $nik);
    printField($pdf, 'NOP Induk SPPT PBB', $titik);
    printField($pdf, 'Dipecah Menjadi', '..........................Objek');
    printField($pdf, 'Alamat Wajib Pajak', $alamat);
    printField($pdf, 'Alamat Objek Pajak', $titik);
    printField($pdf, '', "Kelurahan : ......................................................................");
    printField($pdf, '', "Kecamatan : ......................................................................");
    printField($pdf, '', "Kota      : Samarinda");
    printField($pdf, 'Nomor Telepon/HP/E-Mail', $hp);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 5, '          Dengan ini mengajukan Permohonan pemecahan objek pajak PBB-P2 Tahun : ............................................ Dikarenakan hal-hal sebagai berikut : ........................................................................................');
    
    $syarat = [
        "Foto copy kartu tanda penduduk (KTP) NPWP bagi perusahaan dan badan",
        "Blangko SPOP/SLOP",
        "Surat Pernyataan yang ditanda tangani diatas materai",
        "Fotocopy Sertifikat/PPAT/SKUMHAT/IMTN atau dokumen lain dipersamakan (legalisir)",
        "Foto lokasi tanah dan Foto bangunan",
        "Titik Koordinat dari google maps (apabila surat tanah tidak ada tikor)",
        "Surat kuasa pengurusan apabila pengurusnya diwakilkan (apabila surat tanah tidak ada tikor)",
        "Tidak memiliki tunggakan atau piutang PBB-P2",
        "Fotocopy SPPDT-P2 tetangga sebagai objek pembanding (jika ada)",
        "Apabila nama disurat tanah telah meninggal dunia wajib melampirkan akta kematian serta surat kuasa ahli waris dan ahli waris dari kelurahan/notaris dan Foto copy kartu keluarga",
        "Keterangan kelurahan apabila surat tanah atau lokasi objek sudah berubah atau pindah kelurahan",
        "Apabila nama berbeda antara di KTP dan surat tanah harus melampirkan keterangan dari kelurahan"
    ];

} else if ($jenis == "Keberatan Subjek / Objek Pajak SPPDT PBB-P2") {
    $pdf->MultiCell(0, 5, 'Diberitahukan bahwa SPPDT (Surat Pemberitahuan Pajak Daerah Terhutang) PBB-P2 Tahun ............... sebagai berikut:');
    printField($pdf, 'Nama Wajib Pajak', $nama);
    printField($pdf, 'NIK/NPWP', $nik);
    printField($pdf, 'NOP', $titik);
    printField($pdf, 'Alamat Wajib Pajak', $alamat);
    printField($pdf, 'Alamat Objek Pajak', $titik);
    printField($pdf, 'Luas Bumi', '.................................... M2');
    printField($pdf, 'Luas Bangunan', '.................................... M2');
    $pdf->Ln(2);
    $pdf->MultiCell(0, 5, 'Setelah kami teliti ternyata SPPDT / SPPD PBB P-2 tersebut di atas terdapat kesalahan sehubungan dengan hal tersebut dimohon agar dapat dibetulkan sesuai kondisi yang sebenarnya yaitu :');
    printField($pdf, 'Nama Wajib Pajak', $titik);
    printField($pdf, 'NOP', $titik);
    printField($pdf, 'Alamat Wajib Pajak', $titik);
    printField($pdf, 'Alamat Objek Pajak', $titik);
    printField($pdf, 'Luas Bumi', '.................................... M2');
    printField($pdf, 'Luas Bangunan', '.................................... M2');
    printField($pdf, 'Nomor Telepon/HP/E-Mail', $hp);
    $pdf->Ln(2);
    $pdf->SetFont('helvetica','B',9.5);
    $pdf->Cell(0, 5, 'ALASAN PENGAJUAN PERMOHONAN', 0, 1, 'C');
    $pdf->SetFont('helvetica','',9.5);
    $pdf->MultiCell(0, 5, "........................................................................................................................................................................\n........................................................................................................................................................................");
    
    $syarat = [
        "Fotocopy tanda bukti identitas(KTP ) NPWP bagi Perusahaan / Badan",
        "Blangko SPOP/LSPOP",
        "Surat Pernyataan yang ditanda tangani diatas materai",
        "Fotocopy Sertifikat/PPAT/SKUMHAT/IMTN atau dokumen lain dipersamakan (legalisir)",
        "Foto lokasi tanah dan Foto bangunan",
        "Titik koordinat dari google maps (apabila surat tanah tidak ada tikor).",
        "Surat kuasa pengurusan apabila pengurusannya diwakilkan (tanda tangan diatas materai).",
        "Tidak memiliki tunggakan atau piutang PBB-P2",
        "Fotocopy SPPDT-P2 tetangga sebagai objek pembanding ( jika ada)",
        "Apabila nama disurat tanah telah meninggal dunia wajib melampirkan akta kematian serta surat kuasa ahli waris dan ahli waris tanda dari kelurahan/notaris dan Foto copy kartu keluarga",
        "Apabila nama berbeda antara di KTP dan surat tanah harus melampirkan keterangan dari kelurahan",
        "Keterangan kelurahan apabila surat tanah atau lokasi objek sudah berubah atau pindah kelurahan",
        "Fotocopy PBB-P2 sekitar ojek sebagai pembanding (jika ada)",
        "Persyaratan lain yang diperlukan berkaitan dengan permohonan"
    ];

} else if ($jenis == "Penghapusan Subjek/Objek Pajak SPPT PBB-P2" || $jenis == "Penggabungan Subjek/Objek Pajak SPPT PBB-P2") {
    $pdf->Cell(0, 5, '          Yang bertanda tangan dibawah ini:', 0, 1);
    printField($pdf, 'Nama', $nama);
    printField($pdf, 'Pekerjaan', '......................................................');
    printField($pdf, 'Alamat', $alamat);
    printField($pdf, 'Nomor Telepon/HP/E-Mail', $hp);
    $pdf->Ln(2);
    $act = ($jenis == "Penghapusan Subjek/Objek Pajak SPPT PBB-P2") ? "penghapusan" : "penggabungan";
    $pdf->MultiCell(0, 5, '          Dengan ini mengajukan permohonan '.$act.' Subjek/Objek SPPT PBB-P2, Adapun rincian SPPT PBB-P2 adalah sebagai berikut :');
    printField($pdf, 'Nama Wajib Pajak', $titik);
    printField($pdf, 'NIK/NPWP', $titik);
    printField($pdf, 'NOP', $titik);
    printField($pdf, 'Alamat Wajib Pajak', $titik);
    printField($pdf, 'Alamat Objek Pajak', $titik);
    printField($pdf, 'Luas Bumi', '.................................... M2');
    printField($pdf, 'Luas Bangunan', '.................................... M2');
    printField($pdf, 'Ketetapan Pajak Terhutang', $titik);
    $pdf->Ln(2);
    $pdf->SetFont('helvetica','B',9.5);
    $pdf->Cell(0, 5, 'ALASAN PENGAJUAN PERMOHONAN', 0, 1, 'C');
    $pdf->SetFont('helvetica','',9.5);
    $pdf->MultiCell(0, 5, "........................................................................................................................................................................\n........................................................................................................................................................................");

    if ($act == "penghapusan") {
        $syarat = [
            "Fotocopy Kartu Tanda Penduduk (KTP,) NPWP bagi Perusahaan / Badan",
            "Blanko SPOP/LSPOP",
            "Surat Pernyataan Tanggung Jawab Mutlak yang ditanda tangani diatas materai",
            "Fotocopy Sertifikat/PPAT/SKUMHAT/IMTN atau dokumen lain dipersamakan (legalisir)",
            "Foto lokasi tanah dan Foto bangunan",
            "Titik koordinat dari google maps (apabila surat tanah tidak ada tikor).",
            "Surat kuasa pengurusan apabila pengurusannya diwakilkan (tanda tangan diatas materai)",
            "Tidak memiliki tunggakan atau piutang SPPT PBB-P2 sampai dengan tahun berjalan (tanah yang sebenarnya)",
            "Asli SPPT PBB-P2 tahun berjalan yang dihapus",
            "Fotocopy PBB-P2 sekitar objek sebagai objek pembanding (jika ada)",
            "Persyaratan lain yang diperlukan berkaitan dengan permohonan"
        ];
    } else {
        $syarat = [
            "Fotocopy Kartu Tanda Penduduk (KTP), NPWP bagi Perusahaan / Badan",
            "Blanko SPOP/LSPOP",
            "Surat Pernyataan Tanggung Jawab Mutlak yang ditanda tangani diatas materai",
            "Fotocopy Sertifikat/PPAT/SKUMHAT/IMTN atau dokumen lain dipersamakan (legalisir)",
            "Foto lokasi tanah dan Foto bangunan",
            "Titik koordinat dari google maps (apabila surat tanah tidak ada tikor).",
            "Surat kuasa pengurusan apabila pengurusannya diwakilkan (tanda tangan diatas materai)",
            "Tidak memiliki tunggakan atau piutang SPPT PBB-P2",
            "Fotocopy SPPT PBB-P2 yang akan digabung",
            "Apabila nama disurat tanah telah meninggal dunia wajib melampirkan akta kematian serta surat kuasa ahli waris dan ahli waris dari kelurahan /notaris dan foto copy kartu keluarga",
            "Keterangan kelurahan apabila surat tanah atau lokasi objek sudah berubah/pindah kelurahan"
        ];
    }
} else {
    // Default / Pendaftaran Objek Pajak Baru PBB-P2
    $pdf->MultiCell(0, 5, '          Bersama ini diajukan Permohonan Pendaftaran Objek Pajak PBB-P2 baru atas bumi dan/ bangunan yang kami miliki/ Kuasai/ Manfaatkan sebagai berikut:');
    printField($pdf, 'Nama Wajib Pajak', $nama);
    printField($pdf, 'NIK/NPWP', $nik);
    printField($pdf, 'Alamat Wajib Pajak', $alamat);
    printField($pdf, 'Alamat Objek Pajak', $titik);
    printField($pdf, '', "Kelurahan : ......................................................................");
    printField($pdf, '', "Kecamatan : ......................................................................");
    printField($pdf, '', "Kota      : Samarinda");
    printField($pdf, 'Nomor Telepon/HP/E-Mail', $hp);
    
    $syarat = [
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
    ];
}

$pdf->Ln(2);
$pdf->MultiCell(0, 4.5, '          Adapun untuk pengajuan permohonan tersebut, saya bertanggung jawab penuh atas kebenaran data dan dokumen yang menjadi persyaratan sesuai lampiran yaitu :');

// Print Persyaratan
foreach ($syarat as $index => $s) {
    $pdf->Cell(8, 4.5, ($index + 1) . '.', 0, 0);
    $pdf->MultiCell(0, 4.5, $s);
}

$pdf->Ln(2);
$pdf->MultiCell(0, 4.5, 'Demikian permohonan ini disampaikan, atas perhatiannya diucapkan terimakasih');
$pdf->Ln(5);

// Tanda Tangan
$pdf->Cell(120);
$pdf->Cell(0, 4.5, 'Pemohon,', 0, 1, 'C');
$pdf->Ln(18);
$pdf->Cell(120);
$pdf->Cell(0, 4.5, $data['nama'], 0, 1, 'C');
$pdf->Cell(120);
$pdf->Cell(0, 1, '--------------------------------------------------', 0, 1, 'C');

$pdf->Output('I', 'Surat_Pengajuan_' . $data['nik'] . '.pdf');
?>
