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
    <title>Detail Pengajuan #<?php echo (int) $data['id']; ?> - SIAP-PBB</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=11">
</head>
<body class="detail-page">
    <button class="mobile-menu-button" type="button" data-menu-toggle><span></span><span></span><span></span></button>
    <div class="sidebar-backdrop" data-menu-close></div>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-mark">B</span>
            <strong>Admin Portal</strong>
            <span>SIAP-PBB Samarinda</span>
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
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <?php 
                            $pesan_wa = "Halo " . $data['nama'] . ", ini dari layanan SIAP-PBB. Status pengajuan '" . $data['jenis_surat'] . "' Anda saat ini adalah: " . strtoupper($data['status']) . ".";
                            if($data['status'] == 'ditolak' && !empty($data['keterangan'])) $pesan_wa .= " Alasan: " . $data['keterangan'];
                            $pesan_wa .= " Silakan cek detailnya di web SIAP-PBB.";
                            $wa_link = "https://wa.me/" . preg_replace('/[^0-9]/', '', $data['no_hp'] ?? '') . "?text=" . urlencode($pesan_wa);
                        ?>
                        <a href="<?php echo $wa_link; ?>" target="_blank" style="display: flex; align-items: center; gap: 5px; padding: 6px 12px; background: #22c55e; color: #fff; border-radius: 6px; font-size: 12px; font-weight: 700; text-decoration: none;">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.432 5.631 1.433h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Notify WA
                        </a>
                        <span class="status <?php echo htmlspecialchars($data['status']); ?>"><?php echo htmlspecialchars($data['status']); ?></span>
                    </div>
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
                            <option value="ditolak" <?php echo $data['status'] === 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                    </label>
                    <label style="margin-top: 15px; display: block;">Catatan / Alasan Penolakan
                        <textarea name="keterangan" style="width: 100%; height: 80px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px; font-family: inherit;"><?php echo htmlspecialchars($data['keterangan'] ?? ''); ?></textarea>
                    </label>
                    <button type="submit">Simpan Status</button>
                </form>
            </div>
        </div>

        <footer class="dashboard-footer">
            <div class="footer-brand">
                <strong>SIAP-PBB</strong>
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


