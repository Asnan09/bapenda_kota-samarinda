<?php
// admin/detail.php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include "../koneksi.php";

$id_pengajuan = (int)($_GET['id'] ?? 0);
$query = "SELECT pengajuan.*, berkas.file_ktp, berkas.file_pendukung
          FROM pengajuan LEFT JOIN berkas ON berkas.pengajuan_id = pengajuan.id
          WHERE pengajuan.id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_pengajuan);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$data) { header("Location: dashboard.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan #<?php echo $data['id']; ?> — Bapenda</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
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
            <a class="active" href="dashboard.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>Data Pengajuan
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
                <p>ID #<?php echo str_pad($data['id'], 5, '0', STR_PAD_LEFT); ?></p>
            </div>
            <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?></div>
        </header>

        <div class="detail-content">
            <a class="detail-back" href="dashboard.php">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali ke Dashboard
            </a>

            <div class="detail-card reveal-card">
                <div class="detail-card-header">
                    <h2>Informasi Pemohon</h2>
                    <span class="status <?php echo htmlspecialchars($data['status']); ?>"><?php echo htmlspecialchars($data['status']); ?></span>
                </div>

                <div class="detail-field">
                    <label>Nama Lengkap</label>
                    <span><?php echo htmlspecialchars($data['nama']); ?></span>
                </div>
                <div class="detail-field">
                    <label>NIK</label>
                    <span><?php echo htmlspecialchars($data['nik']); ?></span>
                </div>
                <div class="detail-field">
                    <label>Alamat</label>
                    <span><?php echo htmlspecialchars($data['alamat']); ?></span>
                </div>
                <div class="detail-field">
                    <label>No. Handphone</label>
                    <span><?php echo htmlspecialchars($data['no_hp']); ?></span>
                </div>
                <div class="detail-field">
                    <label>Jenis Surat</label>
                    <span><?php echo htmlspecialchars($data['jenis_surat']); ?></span>
                </div>
                <div class="detail-field">
                    <label>Tanggal Pengajuan</label>
                    <span><?php echo date("d F Y", strtotime($data['tanggal'])); ?></span>
                </div>
                <div class="detail-field">
                    <label>File KTP</label>
                    <?php if ($data['file_ktp']): ?>
                        <a href="../uploads/<?php echo htmlspecialchars($data['file_ktp']); ?>" target="_blank">Lihat File KTP →</a>
                    <?php else: ?><span style="color:var(--text-muted)">Tidak tersedia</span><?php endif; ?>
                </div>
                <div class="detail-field">
                    <label>File Pendukung</label>
                    <?php if ($data['file_pendukung']): ?>
                        <a href="../uploads/<?php echo htmlspecialchars($data['file_pendukung']); ?>" target="_blank">Lihat File Pendukung →</a>
                    <?php else: ?><span style="color:var(--text-muted)">Tidak tersedia</span><?php endif; ?>
                </div>

                <form action="update_status.php" method="POST" class="status-form">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    <label>Ubah Status Pengajuan
                        <select name="status" required>
                            <option value="pending"  <?php echo $data['status']==='pending'  ? 'selected' : ''; ?>>Pending</option>
                            <option value="diproses" <?php echo $data['status']==='diproses' ? 'selected' : ''; ?>>Diproses</option>
                            <option value="selesai"  <?php echo $data['status']==='selesai'  ? 'selected' : ''; ?>>Selesai</option>
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