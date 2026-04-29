<?php
// form_pengajuan.php
// Form untuk mengirim pengajuan dan mengunggah berkas pendukung.
$jenis_surat = [
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
    <title>Form Pengajuan</title>
    <link rel="stylesheet" href="assets/css/style.css?v=7">
</head>
<body>
    <header class="navbar">
        <div class="brand">BAPENDA SAMARINDA</div>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="layanan.php">Layanan</a>
            <a href="cek_status.php">Cek Status</a>
            <a href="index.php#lokasi">Lokasi</a>
            <a class="nav-button" href="admin/login.php">Login Admin</a>
        </nav>
    </header>

    <main class="container">
        <p class="label">Formulir Elektronik</p>
        <h1>Pengajuan Surat Keterangan</h1>
        <p class="page-subtitle">Silakan lengkapi formulir di bawah ini dengan data yang valid untuk memproses pengajuan administrasi perpajakan daerah Anda.</p>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <div class="submission-layout">
            <form class="submission-form" action="proses_pengajuan.php" method="POST" enctype="multipart/form-data" id="formPengajuan">
                <section class="form-panel">
                    <h2><span class="icon-box">ID</span> Identitas Pemohon</h2>
                    <div class="form-grid">
                        <label>Nama Lengkap
                            <input type="text" name="nama" placeholder="Sesuai KTP" required>
                        </label>

                        <label>NIK
                            <input type="text" name="nik" maxlength="16" pattern="[0-9]{16}" placeholder="16 Digit Nomor Induk Kependudukan" required>
                        </label>
                    </div>

                    <label>Alamat Domisili
                        <textarea name="alamat" rows="4" placeholder="Jl. Gajah Mada No..." required></textarea>
                    </label>

                    <div class="form-grid">
                        <label>Nomor Telepon
                            <input type="text" name="no_hp" placeholder="+62 812xxxx" required>
                        </label>

                        <label>Jenis Surat
                            <select name="jenis_surat" required>
                                <option value="">Pilih Jenis Pengajuan</option>
                                <?php foreach ($jenis_surat as $surat): ?>
                                    <option value="<?php echo htmlspecialchars($surat); ?>"><?php echo htmlspecialchars($surat); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>
                </section>

                <section class="form-panel">
                    <h2><span class="icon-box">UP</span> Unggah Dokumen</h2>
                    <div class="form-grid">
                        <label>Foto KTP (JPEG/PNG/PDF)
                            <span class="upload-box">Klik untuk pilih file atau tarik ke sini<br><small>Maksimal 2MB</small></span>
                            <input type="file" name="file_ktp" accept=".jpg,.jpeg,.png,.pdf" required>
                        </label>

                        <label>Dokumen Pendukung (PDF/JPG/PNG)
                            <span class="upload-box">Klik untuk pilih file atau tarik ke sini<br><small>Maksimal 2MB</small></span>
                            <input type="file" name="file_pendukung" accept=".jpg,.jpeg,.png,.pdf" required>
                        </label>
                    </div>
                </section>

                <button class="btn primary submit-wide" type="submit">Submit Pengajuan</button>
            </form>

            <aside class="info-column">
                <section class="important-card">
                    <h2>Informasi Penting</h2>
                    <p>Pastikan NIK valid dan terdaftar di Dukcapil.</p>
                    <p>Proses verifikasi memakan waktu 3-5 hari kerja.</p>
                    <p>Notifikasi akan dikirim melalui SMS/WhatsApp.</p>
                </section>
                <section class="help-card">
                    <h3>Butuh Bantuan?</h3>
                    <p><strong>Telepon</strong><br>(0541) 735511</p>
                    <p><strong>Kantor</strong><br>Jl. Dahlia No. 1, Samarinda</p>
                </section>
            </aside>
        </div>
    </main>
    <footer class="site-footer">
        <div>
            <h3>BAPENDA SAMARINDA</h3>
            <p>Mewujudkan pelayanan perpajakan daerah yang modern dan transparan.</p>
        </div>
    </footer>
    <script src="assets/js/script.js?v=2"></script>
    <script src="assets/js/form.js"></script>
</body>
</html>

