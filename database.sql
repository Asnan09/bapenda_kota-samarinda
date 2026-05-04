CREATE DATABASE IF NOT EXISTS bapenda CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE bapenda;

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS pengajuan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    nik VARCHAR(32) NOT NULL,
    alamat TEXT NOT NULL,
    alamat_objek_pajak TEXT DEFAULT NULL,
    kelurahan VARCHAR(100) DEFAULT NULL,
    kecamatan VARCHAR(100) DEFAULT NULL,
    kota VARCHAR(100) DEFAULT 'Samarinda',
    no_hp VARCHAR(120) NOT NULL,
    jenis_surat VARCHAR(150) NOT NULL,
    status ENUM('pending','diproses','selesai') NOT NULL DEFAULT 'pending',
    tahun_pengajuan SMALLINT DEFAULT NULL,
    bulan_pengajuan TINYINT DEFAULT NULL,
    tanggal_pengajuan TINYINT DEFAULT NULL,
    tanggal TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS berkas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT NOT NULL,
    file_ktp VARCHAR(255) DEFAULT NULL,
    file_pendukung VARCHAR(255) DEFAULT NULL,
    file_spop_slop VARCHAR(255) DEFAULT NULL,
    file_surat_pernyataan VARCHAR(255) DEFAULT NULL,
    file_legalisir_tanah VARCHAR(255) DEFAULT NULL,
    file_foto_lokasi VARCHAR(255) DEFAULT NULL,
    file_titik_koordinat VARCHAR(255) DEFAULT NULL,
    file_surat_kuasa VARCHAR(255) DEFAULT NULL,
    file_sppdt_pembanding VARCHAR(255) DEFAULT NULL,
    file_akta_ahli_waris VARCHAR(255) DEFAULT NULL,
    file_surat_beda_nama VARCHAR(255) DEFAULT NULL,
    CONSTRAINT fk_berkas_pengajuan_sql FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO admin (username, password)
SELECT 'admin', '$2y$10$kl/jpdvW9X0Rn54dl81kluQ5A5nydvBcYtXxrw8fhl0UGiA5T7ePG'
WHERE NOT EXISTS (SELECT 1 FROM admin WHERE username = 'admin');
