<?php
// admin/hapus_admin.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../koneksi.php";

$id_hapus = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_hapus > 0) {
    if ($id_hapus === (int)$_SESSION['admin_id']) {
        header("Location: kelola_admin.php?error=" . urlencode("Anda tidak dapat menghapus akun Anda sendiri."));
        exit;
    }

    $query = "DELETE FROM admin WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_hapus);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: kelola_admin.php?success=" . urlencode("Satu akun admin berhasil dihapus."));
        exit;
    } else {
        header("Location: kelola_admin.php?error=" . urlencode("Gagal menghapus akun admin."));
        exit;
    }
}

header("Location: kelola_admin.php");
exit;
?>
