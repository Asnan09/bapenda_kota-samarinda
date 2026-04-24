<?php
// admin/proses_tambah_admin.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? "");
    $password = $_POST['password'] ?? "";
    $konfirmasi = $_POST['konfirmasi_password'] ?? "";

    if ($username === "" || $password === "" || $konfirmasi === "") {
        header("Location: kelola_admin.php?error=" . urlencode("Semua kolom wajib diisi."));
        exit;
    }

    if ($password !== $konfirmasi) {
        header("Location: kelola_admin.php?error=" . urlencode("Password dan Konfirmasi Password tidak cocok."));
        exit;
    }

    // Cek apakah username sudah ada
    $cek_query = "SELECT id FROM admin WHERE username = ?";
    $stmt_cek = mysqli_prepare($koneksi, $cek_query);
    mysqli_stmt_bind_param($stmt_cek, "s", $username);
    mysqli_stmt_execute($stmt_cek);
    $hasil_cek = mysqli_stmt_get_result($stmt_cek);

    if (mysqli_num_rows($hasil_cek) > 0) {
        header("Location: kelola_admin.php?error=" . urlencode("Username sudah digunakan. Silakan pilih username lain."));
        exit;
    }

    // Enkripsi password dan simpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $insert_query = "INSERT INTO admin (username, password) VALUES (?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $insert_query);
    mysqli_stmt_bind_param($stmt_insert, "ss", $username, $hashed_password);
    
    if (mysqli_stmt_execute($stmt_insert)) {
        header("Location: kelola_admin.php?success=" . urlencode("Admin baru '$username' berhasil ditambahkan!"));
        exit;
    } else {
        header("Location: kelola_admin.php?error=" . urlencode("Terjadi kesalahan sistem saat menyimpan data."));
        exit;
    }
} else {
    header("Location: kelola_admin.php");
    exit;
}
?>
