<?php
// admin/update_status.php
// Memperbarui status pengajuan dari halaman detail.
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../koneksi.php";

$id_pengajuan = (int) ($_REQUEST['id'] ?? 0);
$status = $_REQUEST['status'] ?? "";
$keterangan = $_REQUEST['keterangan'] ?? null;
$status_diizinkan = ["pending", "diproses", "selesai", "ditolak"];

if ($id_pengajuan <= 0 || !in_array($status, $status_diizinkan)) {
    header("Location: dashboard.php");
    exit;
}

if ($keterangan !== null) {
    $query = "UPDATE pengajuan SET status = ?, keterangan = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $status, $keterangan, $id_pengajuan);
} else {
    $query = "UPDATE pengajuan SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $id_pengajuan);
}
mysqli_stmt_execute($stmt);

$redirect = $_SERVER['HTTP_REFERER'] ?? "dashboard.php";
header("Location: " . $redirect);
exit;
?>
