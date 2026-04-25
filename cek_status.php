<?php
// cek_status.php
// Halaman untuk masyarakat mengecek status pengajuan berdasarkan ID.
include "koneksi.php";

$data_pengajuan = null;
$pesan_error = "";

if (isset($_GET['id'])) {
    $id_pengajuan = (int) $_GET['id'];
    $query = "SELECT id, nama, jenis_surat, status, tanggal FROM pengajuan WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_pengajuan);
    mysqli_stmt_execute($stmt);
    $hasil = mysqli_stmt_get_result($stmt);
    $data_pengajuan = mysqli_fetch_assoc($hasil);

    if (!$data_pengajuan) {
        $pesan_error = "Data pengajuan tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pengajuan</title>
    <link rel="stylesheet" href="assets/css/style.css?v=4">
</head>
<body>
    <header class="navbar">
        <div class="brand">Bapenda Samarinda</div>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="layanan.php">Layanan</a>
            <a href="index.php#lokasi">Lokasi</a>
            <a class="nav-button" href="admin/login.php">Login Admin</a>
        </nav>
    </header>

    <main class="container narrow">
        <h1>Cek Status Pengajuan</h1>

        <?php if (isset($_GET['sukses'])): ?>
            <div class="alert success">
                Pengajuan berhasil dikirim. ID Pengajuan Anda: <strong><?php echo htmlspecialchars($_GET['sukses']); ?></strong>
            </div>
        <?php endif; ?>

        <form class="form-card" action="cek_status.php" method="GET">
            <label>ID Pengajuan
                <input type="number" name="id" placeholder="Contoh: 1" required>
            </label>
            <button class="btn primary" type="submit">Cek Status</button>
        </form>

        <?php if ($pesan_error): ?>
            <div class="alert error"><?php echo htmlspecialchars($pesan_error); ?></div>
        <?php endif; ?>

        <?php if ($data_pengajuan): ?>
            <section class="result-card">
                <h2>Status: <?php echo htmlspecialchars(ucfirst($data_pengajuan['status'])); ?></h2>
                <p><strong>ID:</strong> <?php echo htmlspecialchars($data_pengajuan['id']); ?></p>
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($data_pengajuan['nama']); ?></p>
                <p><strong>Jenis Surat:</strong> <?php echo htmlspecialchars($data_pengajuan['jenis_surat']); ?></p>
                <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($data_pengajuan['tanggal']); ?></p>
            </section>
        <?php endif; ?>
    </main>
    <script src="assets/js/script.js?v=2"></script>
</body>
</html>
