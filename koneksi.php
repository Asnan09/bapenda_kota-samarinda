<?php
// koneksi.php
// Koneksi database MySQL untuk aplikasi Bapenda.

date_default_timezone_set('Asia/Makassar');

$host_database = "localhost";
$user_database = "root";
$password_database = "";
$nama_database = "bapenda";

$koneksi = mysqli_connect($host_database, $user_database, $password_database, $nama_database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8mb4");
@mysqli_query($koneksi, "SET time_zone = '+08:00'");

function jalankan_query_skema($koneksi, $query)
{
    if (!mysqli_query($koneksi, $query)) {
        die("Gagal menyiapkan struktur database: " . mysqli_error($koneksi));
    }
}

function kolom_ada($koneksi, $tabel, $kolom)
{
    $query = "SELECT COUNT(*) AS total
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = ?
        AND COLUMN_NAME = ?";

    $stmt = mysqli_prepare($koneksi, $query);

    if (!$stmt) {
        die("Gagal memeriksa struktur tabel `$tabel`: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmt, "ss", $tabel, $kolom);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);

    if (!$hasil) {
        mysqli_stmt_close($stmt);
        die("Gagal membaca struktur tabel `$tabel`: " . mysqli_error($koneksi));
    }

    $data = mysqli_fetch_assoc($hasil);
    mysqli_stmt_close($stmt);

    return (int)($data['total'] ?? 0) > 0;
}

$bootstrap_flag = __DIR__ . DIRECTORY_SEPARATOR . '.schema_pbb_ready';
if (!file_exists($bootstrap_flag)) {
    jalankan_query_skema($koneksi, "CREATE TABLE IF NOT EXISTS admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    jalankan_query_skema($koneksi, "CREATE TABLE IF NOT EXISTS pengajuan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(150) NOT NULL,
        nik VARCHAR(32) NOT NULL,
        alamat TEXT NOT NULL,
        no_hp VARCHAR(120) NOT NULL,
        jenis_surat VARCHAR(150) NOT NULL,
        status ENUM('pending','diproses','selesai') NOT NULL DEFAULT 'pending',
        tanggal TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    jalankan_query_skema($koneksi, "CREATE TABLE IF NOT EXISTS berkas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pengajuan_id INT NOT NULL,
        file_ktp VARCHAR(255) DEFAULT NULL,
        file_pendukung VARCHAR(255) DEFAULT NULL,
        CONSTRAINT fk_berkas_pengajuan FOREIGN KEY (pengajuan_id) REFERENCES pengajuan(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

    $kolom_pengajuan = [
        'alamat_objek_pajak' => "TEXT NULL AFTER alamat",
        'kelurahan' => "VARCHAR(100) NULL AFTER alamat_objek_pajak",
        'kecamatan' => "VARCHAR(100) NULL AFTER kelurahan",
        'kota' => "VARCHAR(100) NULL AFTER kecamatan",
        'tahun_pengajuan' => "SMALLINT NULL AFTER kota",
        'bulan_pengajuan' => "TINYINT NULL AFTER tahun_pengajuan",
        'tanggal_pengajuan' => "TINYINT NULL AFTER bulan_pengajuan"
    ];

    foreach ($kolom_pengajuan as $nama_kolom => $definisi) {
        if (!kolom_ada($koneksi, 'pengajuan', $nama_kolom)) {
            jalankan_query_skema($koneksi, "ALTER TABLE `pengajuan` ADD COLUMN `$nama_kolom` $definisi");
        }
    }

    $kolom_berkas = [
        'file_spop_slop' => "VARCHAR(255) NULL AFTER file_pendukung",
        'file_surat_pernyataan' => "VARCHAR(255) NULL AFTER file_spop_slop",
        'file_legalisir_tanah' => "VARCHAR(255) NULL AFTER file_surat_pernyataan",
        'file_foto_lokasi' => "VARCHAR(255) NULL AFTER file_legalisir_tanah",
        'file_titik_koordinat' => "VARCHAR(255) NULL AFTER file_foto_lokasi",
        'file_surat_kuasa' => "VARCHAR(255) NULL AFTER file_titik_koordinat",
        'file_sppdt_pembanding' => "VARCHAR(255) NULL AFTER file_surat_kuasa",
        'file_akta_ahli_waris' => "VARCHAR(255) NULL AFTER file_sppdt_pembanding",
        'file_surat_beda_nama' => "VARCHAR(255) NULL AFTER file_akta_ahli_waris"
    ];

    foreach ($kolom_berkas as $nama_kolom => $definisi) {
        if (!kolom_ada($koneksi, 'berkas', $nama_kolom)) {
            jalankan_query_skema($koneksi, "ALTER TABLE `berkas` ADD COLUMN `$nama_kolom` $definisi");
        }
    }

    $jumlah_admin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM admin"));
    if ((int)($jumlah_admin['total'] ?? 0) === 0) {
        $username_default = 'admin';
        $password_hash = '$2y$10$kl/jpdvW9X0Rn54dl81kluQ5A5nydvBcYtXxrw8fhl0UGiA5T7ePG';
        $stmt_admin = mysqli_prepare($koneksi, "INSERT INTO admin (username, password) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt_admin, "ss", $username_default, $password_hash);
        mysqli_stmt_execute($stmt_admin);
    }

    @file_put_contents($bootstrap_flag, date('c'));
}
?>
