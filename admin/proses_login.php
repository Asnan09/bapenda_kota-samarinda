<?php
// admin/proses_login.php
// Memeriksa username dan password admin menggunakan password_verify.
session_start();
include "../koneksi.php";

$username = trim($_POST['username'] ?? "");
$password = $_POST['password'] ?? "";

if ($username === "" || $password === "") {
    header("Location: login.php?error=" . urlencode("Username dan password wajib diisi."));
    exit;
}

$query = "SELECT id, username, password FROM admin WHERE username = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($hasil);

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    header("Location: dashboard.php");
    exit;
}

header("Location: login.php?error=" . urlencode("Username atau password salah."));
exit;
?>
