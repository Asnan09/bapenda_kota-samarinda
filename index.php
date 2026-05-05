<?php
// index.php
// Landing page sederhana untuk layanan surat online Bapenda Samarinda.
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Surat Online SIAP-PBB</title>
    <link rel="stylesheet" href="assets/css/style.css?v=7">
</head>

<body>
    <header class="navbar">
        <div class="brand">SIAP-PBB</div>
        <nav>
            <a href="index.php" class="active">Beranda</a>
            <a href="layanan.php">Layanan</a>
            <a href="cek_status.php">Cek Status</a>
            <a href="#lokasi">Lokasi</a>
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
            <a href="index.php" class="active">Beranda</a>
            <a href="layanan.php">Layanan</a>
            <a href="cek_status.php">Cek Status</a>
            <a href="#lokasi">Lokasi</a>
            <a class="nav-button" href="admin/login.php">Login Admin</a>
        </nav>
    </aside>

    <main class="hero">
        <section class="hero-content">
            <p class="label">Pelayanan Publik Modern</p>
            <h1>Layanan Surat Online Badan Pendapatan Daerah Kota Samarinda</h1>
            <p>Transformasi digital pelayanan pajak daerah. Ajukan berbagai kebutuhan administrasi perpajakan dan pantau
                status pengajuan dengan aman.</p>
        </section>
        <section class="hero-image" aria-label="Ilustrasi layanan digital">
            <div class="image-placeholder">SIAP-PBB</div>
        </section>
    </main>

    <section class="split-section">
        <div class="office-photo">Kantor SIAP-PBB</div>
        <div>
            <h2>Membayar Pajak Membangun Samarinda</h2>
            <p>Badan Pendapatan Daerah Kota Samarinda berkomitmen untuk meningkatkan pelayanan pajak daerah secara
                transparan, cepat, dan akuntabel.</p>
            <p>Melalui platform digital ini, masyarakat dapat mengirim pengajuan layanan tanpa proses yang berbelit.</p>
        </div>
    </section>

    <section class="container">
        <div class="section-title">
            <h2>Katalog Layanan Digital</h2>
            <p>Pilih jenis layanan surat yang Anda butuhkan.</p>
        </div>
        <div class="service-grid">
            <article class="card"><span class="icon-box">NEW</span>
                <h3>Pendaftaran Objek Baru</h3>
                <p>Layanan pendaftaran Objek Pajak Baru PBB-P2.</p><a href="form_pengajuan.php">Ajukan</a>
            </article>
            <article class="card"><span class="icon-box">MUT</span>
                <h3>Mutasi / Pembetulan</h3>
                <p>Permohonan Mutasi atau Pembetulan Subjek/Objek Pajak PBB-P2.</p><a
                    href="form_pengajuan.php">Ajukan</a>
            </article>
            <article class="card"><span class="icon-box">PCH</span>
                <h3>Pemecahan Objek</h3>
                <p>Layanan Pemecahan Subjek/Objek (Objek Pajak Data Baru).</p><a href="form_pengajuan.php">Ajukan</a>
            </article>
            <article class="card"><span class="icon-box">KBR</span>
                <h3>Keberatan Pajak</h3>
                <p>Permohonan Keberatan Subjek / Objek Pajak SPPDT PBB-P2.</p><a href="form_pengajuan.php">Ajukan</a>
            </article>
            <article class="card"><span class="icon-box">HPS</span>
                <h3>Penghapusan SPPT</h3>
                <p>Layanan Penghapusan Subjek/Objek Pajak SPPT PBB-P2.</p><a href="form_pengajuan.php">Ajukan</a>
            </article>
            <article class="card"><span class="icon-box">GAB</span>
                <h3>Penggabungan Objek</h3>
                <p>Permohonan Penggabungan Subjek/Objek Pajak SPPT PBB-P2.</p><a href="form_pengajuan.php">Ajukan</a>
            </article>
        </div>
    </section>

    <section class="map-section" id="lokasi">
        <iframe class="map-frame" title="Lokasi SIAP-PBB"
            src="https://www.google.com/maps?q=Bapenda%20Samarinda&output=embed" loading="lazy"
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
            <h3>SIAP-PBB</h3>
            <p>Sistem Informasi Akses Pajak PBB.</p>
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