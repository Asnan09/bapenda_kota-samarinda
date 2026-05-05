<?php
// form_pengajuan.php
// Form khusus pengajuan Pendaftaran Objek Pajak Baru PBB-P2.
session_start();
date_default_timezone_set('Asia/Makassar');
$tanggal_otomatis = date('d F Y');
$jenis_surat = $_GET['layanan'] ?? 'Pendaftaran Objek Pajak Baru PBB-P2';
$old = $_SESSION['old_input'] ?? [];

$dokumen_upload = [
    ['name' => 'file_ktp', 'title' => '1. Fotokopi KTP / NPWP Badan', 'desc' => 'Wajib diunggah dalam format JPG, PNG, atau PDF.'],
    ['name' => 'file_spop_slop', 'title' => '2. Blangko SPOP / SLOP', 'desc' => 'Unggah blangko yang sudah diisi lengkap.'],
    ['name' => 'file_surat_pernyataan', 'title' => '3. Surat Pernyataan Bermaterai', 'desc' => 'Dokumen wajib yang sudah ditandatangani di atas materai.'],
    ['name' => 'file_legalisir_tanah', 'title' => '4. Sertifikat / PPAT / SKUMHAT / IMTN', 'desc' => 'Unggah dokumen legalisir atau dokumen setara yang dipersamakan.'],
    ['name' => 'file_foto_lokasi', 'title' => '5. Foto Lokasi Tanah dan Bangunan', 'desc' => 'Gabungkan foto lokasi menjadi satu file bila perlu.'],
    ['name' => 'file_titik_koordinat', 'title' => '6. Titik Koordinat Google Maps', 'desc' => 'Opsional, unggah bila surat tanah belum memiliki tikor.'],
    ['name' => 'file_surat_kuasa', 'title' => '7. Surat Kuasa Pengurusan', 'desc' => 'Opsional, unggah bila pengurusan diwakilkan.'],
    ['name' => 'file_sppdt_pembanding', 'title' => '8. SPPDT-P2 Tetangga', 'desc' => 'Opsional, sebagai objek pembanding bila tersedia.'],
    ['name' => 'file_akta_ahli_waris', 'title' => '9. Akta Kematian / Surat Ahli Waris / KK', 'desc' => 'Opsional, bila nama di surat tanah telah meninggal dunia.'],
    ['name' => 'file_surat_beda_nama', 'title' => '10. Surat Keterangan Beda Nama', 'desc' => 'Opsional, dari kelurahan bila nama di KTP berbeda dengan surat tanah.']
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengajuan PBB-P2</title>
    <link rel="stylesheet" href="assets/css/style.css?v=8">
</head>

<body>
    <div class="page-loader" id="pageLoader">
        <div class="loader-mark">B</div>
        <p>Menyiapkan formulir pengajuan...</p>
    </div>

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
        <header class="form-header">
            <p class="label">Layanan Elektronik</p>
            <h1><?php echo htmlspecialchars($jenis_surat); ?></h1>
            <p class="page-subtitle">Lengkapi formulir di bawah ini dengan data yang valid. Pastikan berkas yang
                diunggah terbaca dengan jelas untuk mempercepat proses verifikasi.</p>
        </header>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <div class="submission-layout pbb-layout">
            <form class="submission-form" action="proses_pengajuan.php" method="POST" enctype="multipart/form-data"
                id="formPengajuan">
                <input type="hidden" name="jenis_surat" value="<?php echo htmlspecialchars($jenis_surat); ?>">

                <section class="form-panel">
                    <div class="panel-header">
                        <span class="icon-box">01</span>
                        <h2>Data Diri Pemohon</h2>
                    </div>
                    <div class="form-grid">
                        <label>Nama Pemohon
                            <input type="text" name="nama" placeholder="Masukkan nama Pemohon"
                                value="<?php echo htmlspecialchars($old['nama'] ?? ''); ?>" required>
                        </label>

                        <label>NIK / NPWP
                            <input type="text" name="nik" inputmode="numeric" placeholder="15-16 digit NIK atau NPWP"
                                value="<?php echo htmlspecialchars($old['nik'] ?? ''); ?>" required>
                        </label>
                    </div>

                    <div class="form-grid">
                        <label>Tanggal Pengajuan
                            <input type="text" name="tanggal_tampil"
                                value="<?php echo htmlspecialchars($tanggal_otomatis); ?>" readonly>
                        </label>

                        <label>Kota
                            <input type="text" name="kota" value="Samarinda" readonly>
                        </label>
                    </div>

                    <label>Alamat Pemohon
                        <textarea name="alamat" rows="4" placeholder="Masukkan alamat sesuai identitas Pemohon"
                            required><?php echo htmlspecialchars($old['alamat'] ?? ''); ?></textarea>
                    </label>

                    <label>Alamat Objek Pajak
                        <textarea name="alamat_objek_pajak" rows="4" placeholder="Masukkan alamat lengkap objek pajak"
                            required><?php echo htmlspecialchars($old['alamat_objek_pajak'] ?? ''); ?></textarea>
                    </label>

                    <div class="form-grid three-cols">
                        <label>Kelurahan
                            <input type="text" name="kelurahan" placeholder="Contoh: Air Putih"
                                value="<?php echo htmlspecialchars($old['kelurahan'] ?? ''); ?>" required>
                        </label>

                        <label>Kecamatan
                            <input type="text" name="kecamatan" placeholder="Contoh: Samarinda Ulu"
                                value="<?php echo htmlspecialchars($old['kecamatan'] ?? ''); ?>" required>
                        </label>

                        <label>Nomor Telepon
                            <input type="text" name="no_hp" placeholder="08xxxxxxxx"
                                value="<?php echo htmlspecialchars($old['no_hp'] ?? ''); ?>" required>
                        </label>
                    </div>
                </section>

                <section class="form-panel">
                    <div class="panel-header">
                        <span class="icon-box">02</span>
                        <h2>Dokumen Pendukung</h2>
                    </div>
                    <div class="simple-upload-list">
                        <?php foreach ($dokumen_upload as $dokumen): ?>
                            <div class="upload-item">
                                <div class="upload-info">
                                    <strong><?php echo htmlspecialchars($dokumen['title']); ?></strong>
                                    <p><?php echo htmlspecialchars($dokumen['desc']); ?></p>
                                </div>
                                <div class="upload-control">
                                    <input type="file" name="<?php echo htmlspecialchars($dokumen['name']); ?>"
                                        accept=".jpg,.jpeg,.png,.pdf" <?php echo in_array($dokumen['name'], ['file_titik_koordinat', 'file_surat_kuasa', 'file_sppdt_pembanding', 'file_akta_ahli_waris', 'file_surat_beda_nama'], true) ? '' : 'required'; ?>>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <button class="btn primary submit-wide" type="submit">Kirim Pengajuan</button>
            </form>

            <aside class="info-column">
                <section class="important-card">
                    <h3>Panduan Pengisian</h3>
                    <p>Gunakan data sesuai KTP dan Sertifikat asli.</p>
                    <ul class="simple-list">
                        <li>Pastikan NIK/NPWP berjumlah 15-16 digit.</li>
                        <li>Alamat objek pajak harus detail (RT/RW/No Rumah).</li>
                        <li>Format file: JPG, PNG, atau PDF.</li>
                        <li>Ukuran maksimal file 2MB.</li>
                    </ul>
                </section>

                <section class="help-card">
                    <h3>Butuh Bantuan?</h3>
                    <p>Hubungi layanan bantuan kami jika Anda mengalami kesulitan:</p>
                    <div class="contact-item">
                        <strong>WhatsApp / Telp</strong>
                        <span>(0541) 735511</span>
                    </div>
                    <div class="contact-item">
                        <strong>Alamat Kantor</strong>
                        <span>Jl. Kesuma Bangsa No. 86, Samarinda</span>
                    </div>
                </section>
            </aside>
        </div>
    </main>

    <footer class="site-footer">
        <div>
            <h3>SIAP-PBB</h3>
            <p>Mewujudkan pelayanan perpajakan daerah yang modern dan transparan.</p>
        </div>
        <div class="footer-links">
            <a href="index.php">Beranda</a>
            <a href="layanan.php">Layanan</a>
            <a href="cek_status.php">Cek Status</a>
        </div>
    </footer>

    <script src="assets/js/script.js?v=2"></script>
    <script src="assets/js/form.js?v=2"></script>
</body>

</html>