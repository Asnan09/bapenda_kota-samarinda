<?php
// admin/update_status.php
// Memperbarui status pengajuan dari halaman detail.
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../koneksi.php";

$id_pengajuan = (int) ($_POST['id'] ?? 0);
$status = $_POST['status'] ?? "";
$status_diizinkan = ["pending", "diproses", "selesai"];

if ($id_pengajuan <= 0 || !in_array($status, $status_diizinkan)) {
    header("Location: dashboard.php");
    exit;
}

$query = "UPDATE pengajuan SET status = ? WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "si", $status, $id_pengajuan);
mysqli_stmt_execute($stmt);

header("Location: detail.php?id=" . $id_pengajuan);
exit;
?>
