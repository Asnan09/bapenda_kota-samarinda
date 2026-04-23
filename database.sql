-- database.sql
-- Database untuk project Layanan Surat Online Bapenda Samarinda.
-- Akun admin contoh: username = admin, password = admin123

CREATE DATABASE IF NOT EXISTS bapenda;
USE bapenda;

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS pengajuan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    nik VARCHAR(16) NOT NULL,
    alamat TEXT NOT NULL,
    no_hp VARCHAR(30) NOT NULL,
    jenis_surat VARCHAR(150) NOT NULL,
    status ENUM('pending', 'diproses', 'selesai') NOT NULL DEFAULT 'pending',
    tanggal TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS berkas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT NOT NULL,
    file_ktp VARCHAR(255) NOT NULL,
    file_pendukung VARCHAR(255) NOT NULL,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id) ON DELETE CASCADE
);

INSERT INTO admin (username, password)
SELECT 'admin', '$2y$10$/SYGLqaruoUHW52DHx7D7ORImFSPkHDD2im1g5oFsRZlQn6YCDoNG'
WHERE NOT EXISTS (SELECT 1 FROM admin WHERE username = 'admin');

