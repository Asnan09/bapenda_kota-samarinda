<?php
// proses_pengajuan.php
// Menyimpan pengajuan Pendaftaran Objek Pajak Baru PBB-P2 beserta 10 berkas pendukung.
include "koneksi.php";

date_default_timezone_set('Asia/Makassar');

function redirect_dengan_error($pesan)
{
    $_SESSION['old_input'] = $_POST;
    header("Location: form_pengajuan.php?error=" . urlencode($pesan));
    exit;
}

function upload_berkas($nama_input, $label_file, $wajib = true)
{
    if (!isset($_FILES[$nama_input]) || $_FILES[$nama_input]['error'] === UPLOAD_ERR_NO_FILE) {
        if ($wajib) {
            return [false, "File $label_file wajib diunggah."];
        }
        return [true, null];
    }

    if ($_FILES[$nama_input]['error'] !== UPLOAD_ERR_OK) {
        return [false, "Terjadi kendala saat mengunggah $label_file."];
    }

    $folder_upload = __DIR__ . "/uploads/";
    if (!is_dir($folder_upload)) {
        mkdir($folder_upload, 0777, true);
    }

    $nama_file_asli = $_FILES[$nama_input]['name'];
    $ukuran_file = (int) $_FILES[$nama_input]['size'];
    $tmp_file = $_FILES[$nama_input]['tmp_name'];
    $ekstensi = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));
    $ekstensi_diizinkan = ["jpg", "jpeg", "png", "pdf"];
    $maksimal_ukuran = 2 * 1024 * 1024;

    if (!in_array($ekstensi, $ekstensi_diizinkan, true)) {
        return [false, "Format $label_file harus JPG, PNG, atau PDF."];
    }

    if ($ukuran_file > $maksimal_ukuran) {
        return [false, "Ukuran $label_file maksimal 2MB."];
    }

    $nama_file_baru = $nama_input . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ekstensi;
    $tujuan_upload = $folder_upload . $nama_file_baru;

    if (!move_uploaded_file($tmp_file, $tujuan_upload)) {
        return [false, "Gagal memindahkan $label_file ke folder uploads."];
    }

    return [true, $nama_file_baru];
}

function hapus_file_tersimpan($daftar_file)
{
    foreach ($daftar_file as $file) {
        $lokasi = __DIR__ . "/uploads/" . $file;
        if ($file && file_exists($lokasi)) {
            unlink($lokasi);
        }
    }
}

$nama = trim($_POST['nama'] ?? '');
$nik = trim($_POST['nik'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$alamat_objek_pajak = trim($_POST['alamat_objek_pajak'] ?? '');
$kelurahan = trim($_POST['kelurahan'] ?? '');
$kecamatan = trim($_POST['kecamatan'] ?? '');
$kota = trim($_POST['kota'] ?? 'Samarinda');
$no_hp = trim($_POST['no_hp'] ?? '');
$jenis_surat = trim($_POST['jenis_surat'] ?? 'Pendaftaran Objek Pajak Baru PBB-P2');

if ($nama === '' || $nik === '' || $alamat === '' || $alamat_objek_pajak === '' || $kelurahan === '' || $kecamatan === '' || $kota === '' || $no_hp === '') {
    redirect_dengan_error('Semua data wajib pajak harus diisi lengkap.');
}

$nik_normal = preg_replace('/[^0-9]/', '', $nik);
if (strlen($nik_normal) < 15 || strlen($nik_normal) > 16) {
    redirect_dengan_error('NIK / NPWP harus berisi 15 sampai 16 digit angka.');
}

$dokumen = [
    'file_ktp' => ['label' => 'Fotokopi KTP / NPWP Badan', 'required' => true],
    'file_spop_slop' => ['label' => 'Blangko SPOP / SLOP', 'required' => true],
    'file_surat_pernyataan' => ['label' => 'Surat Pernyataan Bermaterai', 'required' => true],
    'file_legalisir_tanah' => ['label' => 'Sertifikat / PPAT / SKUMHAT / IMTN', 'required' => true],
    'file_foto_lokasi' => ['label' => 'Foto Lokasi Tanah dan Bangunan', 'required' => true],
    'file_titik_koordinat' => ['label' => 'Titik Koordinat Google Maps', 'required' => false],
    'file_surat_kuasa' => ['label' => 'Surat Kuasa Pengurusan', 'required' => false],
    'file_sppdt_pembanding' => ['label' => 'SPPDT-P2 Tetangga', 'required' => false],
    'file_akta_ahli_waris' => ['label' => 'Akta Kematian / Ahli Waris / KK', 'required' => false],
    'file_surat_beda_nama' => ['label' => 'Surat Keterangan Beda Nama', 'required' => false]
];

$berkas_tersimpan = [];
foreach ($dokumen as $nama_input => $info_file) {
    [$berhasil, $hasil_upload] = upload_berkas($nama_input, $info_file['label'], $info_file['required']);
    if (!$berhasil) {
        hapus_file_tersimpan(array_values($berkas_tersimpan));
        redirect_dengan_error($hasil_upload);
    }
    $berkas_tersimpan[$nama_input] = $hasil_upload;
}

$status_awal = 'pending';
$waktu_pengajuan = new DateTime('now', new DateTimeZone('Asia/Makassar'));
$tahun_pengajuan = (int) $waktu_pengajuan->format('Y');
$bulan_pengajuan = (int) $waktu_pengajuan->format('m');
$tanggal_pengajuan = (int) $waktu_pengajuan->format('d');

$query_pengajuan = "INSERT INTO pengajuan (
    nama, nik, alamat, alamat_objek_pajak, kelurahan, kecamatan, kota,
    no_hp, jenis_surat, status, tahun_pengajuan, bulan_pengajuan, tanggal_pengajuan
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_pengajuan = mysqli_prepare($koneksi, $query_pengajuan);
mysqli_stmt_bind_param(
    $stmt_pengajuan,
    "ssssssssssiii",
    $nama,
    $nik_normal,
    $alamat,
    $alamat_objek_pajak,
    $kelurahan,
    $kecamatan,
    $kota,
    $no_hp,
    $jenis_surat,
    $status_awal,
    $tahun_pengajuan,
    $bulan_pengajuan,
    $tanggal_pengajuan
);

if (!mysqli_stmt_execute($stmt_pengajuan)) {
    hapus_file_tersimpan(array_values($berkas_tersimpan));
    redirect_dengan_error('Gagal menyimpan data pengajuan ke database.');
}

$pengajuan_id = mysqli_insert_id($koneksi);
$file_pendukung_ringkas = $berkas_tersimpan['file_spop_slop'] ?? null;

$query_berkas = "INSERT INTO berkas (
    pengajuan_id, file_ktp, file_pendukung, file_spop_slop, file_surat_pernyataan,
    file_legalisir_tanah, file_foto_lokasi, file_titik_koordinat, file_surat_kuasa,
    file_sppdt_pembanding, file_akta_ahli_waris, file_surat_beda_nama
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_berkas = mysqli_prepare($koneksi, $query_berkas);
mysqli_stmt_bind_param(
    $stmt_berkas,
    "isssssssssss",
    $pengajuan_id,
    $berkas_tersimpan['file_ktp'],
    $file_pendukung_ringkas,
    $berkas_tersimpan['file_spop_slop'],
    $berkas_tersimpan['file_surat_pernyataan'],
    $berkas_tersimpan['file_legalisir_tanah'],
    $berkas_tersimpan['file_foto_lokasi'],
    $berkas_tersimpan['file_titik_koordinat'],
    $berkas_tersimpan['file_surat_kuasa'],
    $berkas_tersimpan['file_sppdt_pembanding'],
    $berkas_tersimpan['file_akta_ahli_waris'],
    $berkas_tersimpan['file_surat_beda_nama']
);

if (!mysqli_stmt_execute($stmt_berkas)) {
    mysqli_query($koneksi, "DELETE FROM pengajuan WHERE id = " . (int) $pengajuan_id);
    hapus_file_tersimpan(array_values($berkas_tersimpan));
    redirect_dengan_error('Pengajuan tersimpan, tetapi data berkas gagal dicatat.');
}

unset($_SESSION['old_input']);
header('Location: cek_status.php?sukses=' . $pengajuan_id);
exit;
?>
