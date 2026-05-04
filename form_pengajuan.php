<?php
// form_pengajuan.php
// Form khusus pengajuan Pendaftaran Objek Pajak Baru PBB-P2.
date_default_timezone_set('Asia/Makassar');
$tanggal_otomatis = date('d F Y');
$jenis_surat = 'Pendaftaran Objek Pajak Baru PBB-P2';

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
        <p class="label">Formulir Elektronik PBB-P2</p>
        <h1>Pengajuan Pendaftaran Objek Pajak Baru PBB-P2</h1>
        <p class="page-subtitle">Silakan lengkapi data wajib pajak dan unggah seluruh berkas yang diperlukan. Tanggal pengajuan akan terisi otomatis mengikuti waktu saat masyarakat mengirim formulir.</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <div class="submission-layout pbb-layout">
            <form class="submission-form" action="proses_pengajuan.php" method="POST" enctype="multipart/form-data" id="formPengajuan">
                <input type="hidden" name="jenis_surat" value="<?php echo htmlspecialchars($jenis_surat); ?>">

                <section class="form-panel">
                    <h2><span class="icon-box">ID</span> Data Wajib Pajak</h2>
                    <div class="form-grid">
                        <label>Nama Wajib Pajak
                            <input type="text" name="nama" placeholder="Masukkan nama wajib pajak" required>
                        </label>

                        <label>NIK / NPWP
                            <input type="text" name="nik" inputmode="numeric" placeholder="15-16 digit NIK atau NPWP" required>
                        </label>
                    </div>

                    <div class="form-grid">
                        <label>Tanggal Pengajuan
                            <input type="text" name="tanggal_tampil" value="<?php echo htmlspecialchars($tanggal_otomatis); ?>" readonly>
                        </label>

                        <label>Kota
                            <input type="text" name="kota" value="Samarinda" readonly>
                        </label>
                    </div>

                    <label>Alamat Wajib Pajak
                        <textarea name="alamat" rows="4" placeholder="Masukkan alamat sesuai identitas wajib pajak" required></textarea>
                    </label>

                    <label>Alamat Objek Pajak
                        <textarea name="alamat_objek_pajak" rows="4" placeholder="Masukkan alamat lengkap objek pajak" required></textarea>
                    </label>

                    <div class="form-grid three-cols">
                        <label>Kelurahan
                            <input type="text" name="kelurahan" placeholder="Contoh: Air Putih" required>
                        </label>

                        <label>Kecamatan
                            <input type="text" name="kecamatan" placeholder="Contoh: Samarinda Ulu" required>
                        </label>

                        <label>Nomor Telepon / HP / E-Mail
                            <input type="text" name="no_hp" placeholder="08xxxx / email aktif" required>
                        </label>
                    </div>
                </section>

                <section class="form-panel">
                    <h2><span class="icon-box">UP</span> Unggah Dokumen Pendukung</h2>
                    <div class="upload-grid upload-grid-pbb">
                        <?php foreach ($dokumen_upload as $dokumen): ?>
                            <label class="upload-card-label"><?php echo htmlspecialchars($dokumen['title']); ?>
                                <span class="upload-box upload-box-rich">
                                    <strong>Pilih file</strong>
                                    <span><?php echo htmlspecialchars($dokumen['desc']); ?></span>
                                    <small>Maksimal 2MB • JPG / PNG / PDF</small>
                                    <em data-file-label>Belum ada file dipilih</em>
                                </span>
                                <input type="file" name="<?php echo htmlspecialchars($dokumen['name']); ?>" accept=".jpg,.jpeg,.png,.pdf" <?php echo in_array($dokumen['name'], ['file_titik_koordinat','file_surat_kuasa','file_sppdt_pembanding','file_akta_ahli_waris','file_surat_beda_nama'], true) ? '' : 'required'; ?>>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </section>

                <button class="btn primary submit-wide" type="submit">Kirim Pengajuan PBB-P2</button>
            </form>

            <aside class="info-column">
                <section class="important-card">
                    <h2>Informasi Penting</h2>
                    <p>Form ini khusus untuk <strong><?php echo htmlspecialchars($jenis_surat); ?></strong>.</p>
                    <p>Pastikan data kelurahan, kecamatan, dan alamat objek pajak ditulis lengkap agar verifikasi lebih cepat.</p>
                    <p>Berkas nomor 6 sampai 10 bersifat kondisional. Unggah jika memang sesuai kondisi objek pajak Anda.</p>
                </section>

                <section class="help-card checklist-card">
                    <h3>Checklist Berkas</h3>
                    <ol>
                        <li>KTP / NPWP Badan</li>
                        <li>Blangko SPOP / SLOP</li>
                        <li>Surat Pernyataan Bermaterai</li>
                        <li>Dokumen legalisir tanah</li>
                        <li>Foto lokasi tanah &amp; bangunan</li>
                        <li>Titik koordinat bila belum ada tikor</li>
                        <li>Surat kuasa bila diwakilkan</li>
                        <li>SPPDT-P2 pembanding bila ada</li>
                        <li>Akta kematian / ahli waris bila diperlukan</li>
                        <li>Surat beda nama dari kelurahan bila diperlukan</li>
                    </ol>
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
