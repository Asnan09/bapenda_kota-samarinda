<?php
// layanan.php
// Menampilkan daftar layanan surat yang tersedia untuk masyarakat.
$daftar_layanan = [
    "Surat Keterangan Pajak Daerah",
    "Surat Keterangan NJOP",
    "Surat Validasi Pajak Daerah",
    "Surat Permohonan Informasi Pajak",
    "Surat Layanan Administrasi Bapenda"
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Layanan</title>
    <link rel="stylesheet" href="assets/css/style.css?v=7">
</head>
<body>
    <header class="navbar">
        <div class="brand">BAPENDA SAMARINDA</div>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="layanan.php" class="active">Layanan</a>
            <a href="cek_status.php">Cek Status</a>
            <a href="index.php#lokasi">Lokasi</a>
            <a class="nav-button" href="admin/login.php">Login Admin</a>
        </nav>
    </header>

    <main class="container">
        <div class="section-title">
            <p class="label">Katalog Layanan</p>
            <h1>Daftar Layanan Digital</h1>
            <p>Pilih layanan administrasi perpajakan daerah yang Anda butuhkan.</p>
        </div>
        <div class="service-grid">
            <?php foreach ($daftar_layanan as $layanan): ?>
                <article class="card">
                    <span class="icon-box">DOC</span>
                    <h3><?php echo htmlspecialchars($layanan); ?></h3>
                    <p>Pengajuan dapat dilakukan secara online melalui formulir yang tersedia.</p>
                    <a href="form_pengajuan.php">Ajukan</a>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
    <footer class="site-footer">
        <div>
            <h3>BAPENDA SAMARINDA</h3>
            <p>Layanan surat online yang terhubung dengan database bapenda.</p>
        </div>
    </footer>
    <script src="assets/js/script.js?v=2"></script>
</body>
</html>

