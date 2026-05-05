<?php
// layanan.php
// Menampilkan daftar layanan surat yang tersedia untuk masyarakat.
$daftar_layanan = [
    "Pendaftaran Objek Pajak Baru PBB-P2",
    "Mutasi / Pembetulan Subjek/Objek Pajak PBB-P2",
    "Pemecahan Subjek/Objek (Objek Pajak Data Baru)",
    "Keberatan Subjek / Objek Pajak SPPDT PBB-P2",
    "Penghapusan Subjek/Objek Pajak SPPT PBB-P2",
    "Penggabungan Subjek/Objek Pajak SPPT PBB-P2"
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
        <div class="brand">SIAP-PBB</div>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="layanan.php" class="active">Layanan</a>
            <a href="cek_status.php">Cek Status</a>
            <a href="index.php#lokasi">Lokasi</a>
            <a class="nav-button" href="admin/login.php">Login Admin</a>
        </nav>
        <button class="menu-toggle" data-menu-toggle aria-label="Buka Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>

    <div class="sidebar-backdrop"></div>
    <aside class="sidebar">
        <button class="close-btn" data-menu-close aria-label="Tutup Menu">&times;</button>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="layanan.php" class="active">Layanan</a>
            <a href="cek_status.php">Cek Status</a>
            <a href="index.php#lokasi">Lokasi</a>
            <a class="nav-button" href="admin/login.php">Login Admin</a>
        </nav>
    </aside>

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
                    <a href="form_pengajuan.php?layanan=<?php echo urlencode($layanan); ?>">Ajukan</a>
                </article>
            <?php endforeach; ?>
        </div>
    </main>
    <footer class="site-footer">
        <div>
            <h3>SIAP-PBB</h3>
            <p>Layanan surat online yang terhubung dengan database bapenda.</p>
        </div>
    </footer>
    <script src="assets/js/script.js?v=2"></script>
</body>
</html>

