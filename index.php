<?php
// index.php
// Landing page sederhana untuk layanan surat online Bapenda Samarinda.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Surat Online Bapenda Samarinda</title>
    <link rel="stylesheet" href="assets/css/style.css?v=4">
</head>
<body>
    <header class="navbar">
        <div class="brand">BAPENDA SAMARINDA</div>
        <nav>
            <a href="index.php" class="active">Beranda</a>
            <a href="layanan.php">Layanan</a>
            <a href="cek_status.php">Cek Status</a>
            <a href="#lokasi">Lokasi</a>
            <a class="nav-button" href="admin/login.php">Login Admin</a>
        </nav>
    </header>

    <main class="hero">
        <section class="hero-content">
            <p class="label">Pelayanan Publik Modern</p>
            <h1>Layanan Surat Online Badan Pendapatan Daerah Kota Samarinda</h1>
            <p>Transformasi digital pelayanan pajak daerah. Ajukan berbagai kebutuhan administrasi perpajakan dan pantau status pengajuan dengan aman.</p>
            <div class="actions">
                <a class="btn primary" href="form_pengajuan.php">Ajukan Surat Sekarang</a>
            </div>
        </section>
        <section class="hero-image" aria-label="Ilustrasi layanan digital">
            <div class="image-placeholder">BAPENDA<br>SAMARINDA</div>
        </section>
    </main>

    <section class="split-section">
        <div class="office-photo">Kantor Bapenda</div>
        <div>
            <h2>Membangun Samarinda Melalui Kemandirian Fiskal</h2>
            <p>Badan Pendapatan Daerah Kota Samarinda berkomitmen untuk meningkatkan pelayanan pajak daerah secara transparan, cepat, dan akuntabel.</p>
            <p>Melalui platform digital ini, masyarakat dapat mengirim pengajuan layanan tanpa proses yang berbelit.</p>
        </div>
    </section>

    <section class="container">
        <div class="section-title">
            <h2>Katalog Layanan Digital</h2>
            <p>Pilih jenis layanan surat yang Anda butuhkan.</p>
        </div>
        <div class="service-grid">
            <article class="card"><span class="icon-box">ID</span><h3>Laporan Pajak</h3><p>Pelaporan dan permintaan administrasi pajak daerah.</p><a href="form_pengajuan.php">Ajukan</a></article>
            <article class="card"><span class="icon-box">PIN</span><h3>Surat Domisili</h3><p>Keterangan domisili usaha atau tempat tinggal.</p><a href="form_pengajuan.php">Ajukan</a></article>
            <article class="card"><span class="icon-box">!</span><h3>Pengaduan</h3><p>Sampaikan kendala terkait layanan pajak daerah.</p><a href="form_pengajuan.php">Ajukan</a></article>
            <article class="card"><span class="icon-box">DOC</span><h3>Surat Keterangan Bebas</h3><p>Pengajuan surat keterangan bebas tunggakan pajak.</p><a href="form_pengajuan.php">Ajukan</a></article>
            <article class="card"><span class="icon-box">BPHTB</span><h3>Validasi BPHTB</h3><p>Layanan permohonan validasi administrasi BPHTB.</p><a href="form_pengajuan.php">Ajukan</a></article>
            <article class="card"><span class="icon-box">?</span><h3>Konsultasi Pajak</h3><p>Konsultasi dan informasi perpajakan daerah.</p><a href="form_pengajuan.php">Ajukan</a></article>
        </div>
    </section>

    <section class="map-section" id="lokasi">
        <iframe
            class="map-frame"
            title="Lokasi Bapenda Samarinda"
            src="https://www.google.com/maps?q=Bapenda%20Samarinda&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        <div class="location-card">
            <span class="label">Lokasi Kantor</span>
            <h2>Lokasi Kantor</h2>
            <p><strong>Alamat Utama</strong><br>Jl. Kesuma Bangsa No. 86, Samarinda</p>
            <p><strong>Jam Operasional</strong><br>Senin - Kamis: 08.00 - 15.30 WITA</p>
            <p><strong>Hubungi Kami</strong><br>(0541) 743901</p>
        </div>
    </section>

    <footer class="site-footer">
        <div>
            <h3>BAPENDA SAMARINDA</h3>
            <p>Portal layanan administrasi perpajakan daerah yang mudah dan transparan.</p>
        </div>
        <div class="footer-links">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Syarat & Ketentuan</a>
            <a href="#">Kontak Kami</a>
        </div>
    </footer>
    <script src="assets/js/script.js?v=2"></script>
</body>
</html>

