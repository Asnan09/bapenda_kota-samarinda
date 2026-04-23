<?php
// proses_pengajuan.php
// Memproses data form, upload file, lalu menyimpan data ke database.
include "koneksi.php";

function redirect_dengan_error($pesan) {
    header("Location: form_pengajuan.php?error=" . urlencode($pesan));
    exit;
}

function upload_berkas($nama_input) {
    if (!isset($_FILES[$nama_input]) || $_FILES[$nama_input]['error'] !== UPLOAD_ERR_OK) {
        return [false, "File " . $nama_input . " wajib diunggah."];
    }

    $folder_upload = __DIR__ . "/uploads/";
    $nama_file_asli = $_FILES[$nama_input]['name'];
    $ukuran_file = $_FILES[$nama_input]['size'];
    $tmp_file = $_FILES[$nama_input]['tmp_name'];
    $ekstensi = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));
    $ekstensi_diizinkan = ["jpg", "jpeg", "png", "pdf"];
    $maksimal_ukuran = 2 * 1024 * 1024;

    if (!in_array($ekstensi, $ekstensi_diizinkan)) {
        return [false, "Format file harus JPG, PNG, atau PDF."];
    }

    if ($ukuran_file > $maksimal_ukuran) {
        return [false, "Ukuran file maksimal 2MB."];
    }

    $nama_file_baru = uniqid($nama_input . "_", true) . "." . $ekstensi;
    $tujuan_upload = $folder_upload . $nama_file_baru;

    if (!move_uploaded_file($tmp_file, $tujuan_upload)) {
        return [false, "Gagal mengunggah file."];
    }

    return [true, $nama_file_baru];
}

$nama = trim($_POST['nama'] ?? "");
$nik = trim($_POST['nik'] ?? "");
$alamat = trim($_POST['alamat'] ?? "");
$no_hp = trim($_POST['no_hp'] ?? "");
$jenis_surat = trim($_POST['jenis_surat'] ?? "");

if ($nama === "" || $nik === "" || $alamat === "" || $no_hp === "" || $jenis_surat === "") {
    redirect_dengan_error("Semua data wajib diisi.");
}

if (!preg_match('/^[0-9]{16}$/', $nik)) {
    redirect_dengan_error("NIK harus berisi 16 angka.");
}

list($ktp_berhasil, $file_ktp) = upload_berkas("file_ktp");
if (!$ktp_berhasil) {
    redirect_dengan_error($file_ktp);
}

list($pendukung_berhasil, $file_pendukung) = upload_berkas("file_pendukung");
if (!$pendukung_berhasil) {
    if (file_exists(__DIR__ . "/uploads/" . $file_ktp)) {
        unlink(__DIR__ . "/uploads/" . $file_ktp);
    }
    redirect_dengan_error($file_pendukung);
}

$status_awal = "pending";
$query_pengajuan = "INSERT INTO pengajuan (nama, nik, alamat, no_hp, jenis_surat, status) VALUES (?, ?, ?, ?, ?, ?)";
$stmt_pengajuan = mysqli_prepare($koneksi, $query_pengajuan);
mysqli_stmt_bind_param($stmt_pengajuan, "ssssss", $nama, $nik, $alamat, $no_hp, $jenis_surat, $status_awal);

if (!mysqli_stmt_execute($stmt_pengajuan)) {
    unlink(__DIR__ . "/uploads/" . $file_ktp);
    unlink(__DIR__ . "/uploads/" . $file_pendukung);
    redirect_dengan_error("Gagal menyimpan pengajuan.");
}

$pengajuan_id = mysqli_insert_id($koneksi);
$query_berkas = "INSERT INTO berkas (pengajuan_id, file_ktp, file_pendukung) VALUES (?, ?, ?)";
$stmt_berkas = mysqli_prepare($koneksi, $query_berkas);
mysqli_stmt_bind_param($stmt_berkas, "iss", $pengajuan_id, $file_ktp, $file_pendukung);

if (!mysqli_stmt_execute($stmt_berkas)) {
    redirect_dengan_error("Pengajuan tersimpan, tetapi berkas gagal disimpan.");
}

header("Location: cek_status.php?sukses=" . $pengajuan_id);
exit;
?>
