<?php
// admin/detail.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../koneksi.php";

$koneksi = $koneksi ?? null;
if (!($koneksi instanceof mysqli)) {
    die("Koneksi database tidak tersedia.");
}

$id_pengajuan = (int)($_GET['id'] ?? 0);
$query = "SELECT pengajuan.*, berkas.*
          FROM pengajuan
          LEFT JOIN berkas ON berkas.pengajuan_id = pengajuan.id
          WHERE pengajuan.id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_pengajuan);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$data) {
    header("Location: dashboard.php");
    exit;
}

$dokumen_pbb = [
    'file_ktp' => 'Fotokopi KTP / NPWP Badan',
    'file_spop_slop' => 'Blangko SPOP / SLOP',
    'file_surat_pernyataan' => 'Surat Pernyataan Bermaterai',
    'file_legalisir_tanah' => 'Sertifikat / PPAT / SKUMHAT / IMTN',
    'file_foto_lokasi' => 'Foto Lokasi Tanah dan Bangunan',
    'file_titik_koordinat' => 'Titik Koordinat Google Maps',
    'file_surat_kuasa' => 'Surat Kuasa Pengurusan',
    'file_sppdt_pembanding' => 'SPPDT-P2 Tetangga',
    'file_akta_ahli_waris' => 'Akta Kematian / Ahli Waris / KK',
    'file_surat_beda_nama' => 'Surat Keterangan Beda Nama'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan #<?php echo (int) $data['id']; ?> - Bapenda</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=11">
</head>
<body class="detail-page">
    <button class="mobile-menu-button" type="button" data-menu-toggle><span></span><span></span><span></span></button>
    <div class="sidebar-backdrop" data-menu-close></div>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-mark">B</span>
            <strong>Admin Portal</strong>
            <span>Bapenda Samarinda</span>
        </div>
        <nav>
            <a href="dashboard.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="14" y="3" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="3" y="14" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="14" y="14" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/></svg>
                </span>Dashboard
            </a>
            <a class="active" href="data_pengajuan.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>Data Pengajuan
            </a>
            <a href="kelola_admin.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M8.5 3a4 4 0 100 8 4 4 0 000-8zM20 8v6M23 11h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>Tambah Admin
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a href="#"><span class="nav-icon"><svg width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 8h.01M12 12v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></span>Bantuan</a>
            <a class="sidebar-logout" href="logout.php"><span class="nav-icon"><svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Keluar</a>
        </div>
    </aside>

    <main class="detail-main">
        <header class="dashboard-top">
            <div>
                <h1>Detail Pengajuan</h1>
                <p>ID #<?php echo str_pad((string) $data['id'], 5, '0', STR_PAD_LEFT); ?></p>
            </div>
            <div class="admin-profile" title="<?php echo htmlspecialchars($_SESSION['admin_username']); ?>">
                <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?></div>
                <div class="admin-profile-info">
                    <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
                    <span>Administrator</span>
                </div>
                <span class="admin-profile-dot" aria-hidden="true"></span>
            </div>
        </header>

        <div class="detail-content">
            <a class="detail-back" href="data_pengajuan.php">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali ke Data Pengajuan
            </a>

            <div class="detail-card reveal-card detail-card-wide">
                <div class="detail-card-header">
                    <h2>Informasi Pemohon</h2>
                    <span class="status <?php echo htmlspecialchars($data['status']); ?>"><?php echo htmlspecialchars($data['status']); ?></span>
                </div>

                <div class="detail-field">
                    <label>Nama Wajib Pajak</label>
                    <span><?php echo htmlspecialchars($data['nama']); ?></span>
                </div>
                <div class="detail-field">
                    <label>NIK / NPWP</label>
                    <span><?php echo htmlspecialchars($data['nik']); ?></span>
                </div>
                <div class="detail-field">
                    <label>Alamat Wajib Pajak</label>
                    <span><?php echo nl2br(htmlspecialchars($data['alamat'])); ?></span>
                </div>
                <div class="detail-field">
                    <label>Alamat Objek Pajak</label>
                    <span><?php echo nl2br(htmlspecialchars($data['alamat_objek_pajak'] ?? '-')); ?></span>
                </div>
                <div class="detail-field">
                    <label>Kelurahan</label>
                    <span><?php echo htmlspecialchars($data['kelurahan'] ?? '-'); ?></span>
                </div>
                <div class="detail-field">
                    <label>Kecamatan</label>
                    <span><?php echo htmlspecialchars($data['kecamatan'] ?? '-'); ?></span>
                </div>
                <div class="detail-field">
                    <label>Kota</label>
                    <span><?php echo htmlspecialchars($data['kota'] ?? 'Samarinda'); ?></span>
                </div>
                <div class="detail-field">
                    <label>Telepon / HP / E-Mail</label>
                    <span><?php echo htmlspecialchars($data['no_hp']); ?></span>
                </div>
                <div class="detail-field">
                    <label>Jenis Surat</label>
                    <span><?php echo htmlspecialchars($data['jenis_surat']); ?></span>
                </div>
                <div class="detail-field">
                    <label>Tanggal Pengajuan</label>
                    <span><?php echo date('d F Y', strtotime($data['tanggal'])); ?></span>
                </div>

                <div class="detail-card-header document-header-block">
                    <h2>Dokumen yang Diunggah</h2>
                    <span class="detail-mini-text">Semua file tersimpan di folder uploads</span>
                </div>

                <?php foreach ($dokumen_pbb as $kolom => $label): ?>
                    <div class="detail-field">
                        <label><?php echo htmlspecialchars($label); ?></label>
                        <?php if (!empty($data[$kolom])): ?>
                            <a href="../uploads/<?php echo htmlspecialchars($data[$kolom]); ?>" target="_blank">Lihat Dokumen</a>
                        <?php else: ?>
                            <span class="detail-empty">Tidak dilampirkan</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <form action="update_status.php" method="POST" class="status-form">
                    <input type="hidden" name="id" value="<?php echo (int) $data['id']; ?>">
                    <label>Ubah Status Pengajuan
                        <select name="status" required>
                            <option value="pending" <?php echo $data['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="diproses" <?php echo $data['status'] === 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                            <option value="selesai" <?php echo $data['status'] === 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    </label>
                    <button type="submit">Simpan Status</button>
                </form>
            </div>
        </div>

        <footer class="dashboard-footer">
            <div class="footer-brand">
                <strong>BAPENDA SAMARINDA</strong>
                <span>© 2024 Badan Pendapatan Daerah Kota Samarinda.</span>
            </div>
            <div class="footer-links">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat &amp; Ketentuan</a>
                <a href="#">Kontak Kami</a>
            </div>
        </footer>
    </main>

    <script src="../assets/js/script.js"></script>
</body>
</html>


