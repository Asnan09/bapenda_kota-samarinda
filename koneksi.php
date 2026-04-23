<?php
// koneksi.php
// File ini dipakai semua halaman untuk terhubung ke database MySQL.
$host_database = "localhost";
$user_database = "root";
$password_database = "";
$nama_database = "bapenda";

$koneksi = mysqli_connect($host_database, $user_database, $password_database, $nama_database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
