<?php
// admin/dashboard.php
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

$per_page = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $per_page;
$kata_kunci = trim($_GET['q'] ?? "");
$admin_username = $_SESSION['admin_username'] ?? "admin";
$admin_initial = strtoupper(substr($admin_username, 0, 1));

$total_pengajuan = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengajuan"))['total'];
$total_pending   = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengajuan WHERE status = 'pending'"))['total'];
$total_selesai   = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengajuan WHERE status = 'selesai'"))['total'];

if ($kata_kunci !== "") {
    $pencarian = "%" . $kata_kunci . "%";
    $query_total_filter = "SELECT COUNT(*) AS total FROM pengajuan WHERE id LIKE ? OR nama LIKE ? OR nik LIKE ? OR jenis_surat LIKE ?";
    $stmt_total = mysqli_prepare($koneksi, $query_total_filter);
    mysqli_stmt_bind_param($stmt_total, "ssss", $pencarian, $pencarian, $pencarian, $pencarian);
    mysqli_stmt_execute($stmt_total);
    $total_data_tampil = (int) mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_total))['total'];

    $query = "SELECT id, nama, nik, no_hp, jenis_surat, status, keterangan, tanggal FROM pengajuan WHERE id LIKE ? OR nama LIKE ? OR nik LIKE ? OR jenis_surat LIKE ? ORDER BY tanggal DESC LIMIT ? OFFSET ?";
    $stmt  = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssssii", $pencarian, $pencarian, $pencarian, $pencarian, $per_page, $offset);
} else {
    $total_data_tampil = $total_pengajuan;
    $query = "SELECT id, nama, nik, no_hp, jenis_surat, status, keterangan, tanggal FROM pengajuan ORDER BY tanggal DESC LIMIT ? OFFSET ?";
    $stmt  = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
}

$total_pages = max(1, (int) ceil($total_data_tampil / $per_page));
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — SIAP-PBB</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=10">
</head>
<body class="dashboard-page">

    <!-- Page Loader -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-mark">B</div>
        <p>Memuat dashboard...</p>
    </div>

    <!-- Mobile toggle -->
    <button class="mobile-menu-button" type="button" aria-label="Buka menu" data-menu-toggle>
        <span></span><span></span><span></span>
    </button>
    <div class="sidebar-backdrop" data-menu-close></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-mark">B</span>
            <strong>Admin Portal</strong>
            <span>SIAP-PBB Samarinda</span>
        </div>
        <nav>
            <a href="dashboard.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="2" fill="currentColor"/><rect x="14" y="3" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="3" y="14" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="14" y="14" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/></svg>
                </span>
                Dashboard
            </a>
            <a class="active" href="data_pengajuan.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                Data Pengajuan
            </a>
            <a href="kelola_admin.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M8.5 3a4 4 0 100 8 4 4 0 000-8zM20 8v6M23 11h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                Tambah Admin
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a class="sidebar-logout" href="logout.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                Keluar
            </a>
        </div>
    </aside>

    <!-- Main -->
    <main class="dashboard-main">

        <!-- Top Bar -->
        <header class="dashboard-top">
            <div>
                <h1>Data Pengajuan</h1>
                <p>Kelola Semua Data Pengajuan Surat</p>
            </div>
            <form class="search-box" method="GET" action="">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M20 20l-3-3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <input type="text" name="q" placeholder="Cari nomor pengajuan..." value="<?php echo htmlspecialchars($kata_kunci); ?>">
            </form>
            <div class="admin-profile" title="<?php echo htmlspecialchars($admin_username); ?>">
                <div class="admin-avatar"><?php echo htmlspecialchars($admin_initial); ?></div>
                <div>
                    <strong><?php echo htmlspecialchars($admin_username); ?></strong>
                    <span>Administrator</span>
                </div>
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" style="color: var(--text-muted); margin-left: 4px;"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
        </header>

        <div class="content-body">



            <!-- Table Panel -->
            <section class="table-panel reveal-card">
                <div class="table-heading">
                    <h2>Daftar Pengajuan Terbaru</h2>
                    <div style="display: flex; gap: 10px;">
                        <button class="filter-button" type="button">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M3 6h18M7 12h10M11 18h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            Filter Data
                        </button>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pemohon</th>
                            <th>Jenis Surat</th>
                            <th>Tanggal Masuk</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor = $offset + 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
                        <tr>
                            <td><?php echo str_pad((string)$nomor, 2, "0", STR_PAD_LEFT); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['nama']); ?></strong><br>
                                <small>NIK: <?php echo htmlspecialchars($row['nik']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($row['jenis_surat']); ?></td>
                            <td><?php echo date("d M Y", strtotime($row['tanggal'])); ?></td>
                            <td><span class="status <?php echo htmlspecialchars($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                            <td>
                                <div class="action-group">
                                    <!-- WhatsApp Notify -->
                                    <?php 
                                        $pesan_wa = "Halo " . $row['nama'] . ", ini dari layanan SIAP-PBB. Status pengajuan '" . $row['jenis_surat'] . "' Anda saat ini adalah: " . strtoupper($row['status']) . ".";
                                        if($row['status'] == 'ditolak' && !empty($row['keterangan'])) $pesan_wa .= " Alasan: " . $row['keterangan'];
                                        $pesan_wa .= " Silakan cek detailnya di web SIAP-PBB.";
                                        $wa_link = "https://wa.me/" . preg_replace('/[^0-9]/', '', $row['no_hp'] ?? '') . "?text=" . urlencode($pesan_wa);
                                    ?>
                                    <a class="icon-action" href="<?php echo $wa_link; ?>" target="_blank" title="Kirim Notifikasi WA" style="color: #22c55e; border-color: #bbf7d0; background: #f0fdf4;">
                                        <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.432 5.631 1.433h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    </a>
                                    <!-- View -->
                                    <a class="icon-action" href="detail.php?id=<?php echo $row['id']; ?>" title="Lihat detail">
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                                    </a>
                                    <!-- Download -->
                                    <a class="icon-action" href="laporan_cetak.php?id=<?php echo $row['id']; ?>" target="_blank" title="Download / Cetak PDF" style="color: #6366f1; border-color: #c7d2fe; background: #eef2ff;">
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M12 15V3m0 12l-4-4m4 4l4-4M2 17l.621 2.485A2 2 0 004.561 21h14.878a2 2 0 001.94-1.515L22 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    <!-- Process -->
                                    <a class="icon-action" href="update_status.php?id=<?php echo $row['id']; ?>&status=diproses" title="Proses Pengajuan"
                                       style="color: #0284c7; border-color: #bae6fd; background: #f0f9ff; <?php if ($row['status'] !== 'pending') echo 'opacity:.35;pointer-events:none;'; ?>"
                                       onclick="return confirm('Pindahkan status ke Diproses?')">
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    <!-- Approve -->
                                    <a class="icon-action approve" href="update_status.php?id=<?php echo $row['id']; ?>&status=selesai" title="Selesaikan"
                                       onclick="return confirm('Selesaikan pengajuan ini?')"
                                       <?php if ($row['status'] === 'selesai' || $row['status'] === 'ditolak') echo 'style="opacity:.35;pointer-events:none;"'; ?>>
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                    <!-- Reject -->
                                    <a class="icon-action" href="#" title="Tolak Pengajuan" 
                                       style="color: #ef4444; border-color: #fca5a5; background: #fef2f2; <?php if ($row['status'] === 'selesai' || $row['status'] === 'ditolak') echo 'opacity:.35;pointer-events:none;'; ?>"
                                       onclick="const msg = prompt('Alasan penolakan:'); if(msg) window.location.href='update_status.php?id=<?php echo $row['id']; ?>&status=ditolak&keterangan=' + encodeURIComponent(msg); return false;">
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php $nomor++; endwhile; ?>
                        <?php if (mysqli_num_rows($hasil) === 0): ?>
                        <tr>
                            <td colspan="6" class="empty-table">Belum ada data pengajuan dari masyarakat.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="table-footer">
                    <span>
                        Menampilkan <?php echo $total_data_tampil > 0 ? $offset + 1 : 0; ?>â€“<?php echo min($offset + $per_page, $total_data_tampil); ?>
                        dari <?php echo number_format($total_data_tampil); ?> data
                    </span>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a class="page-btn" href="?page=<?php echo $page - 1; ?>&q=<?php echo urlencode($kata_kunci); ?>">Sebelumnya</a>
                        <?php else: ?>
                            <button class="page-btn" disabled>Sebelumnya</button>
                        <?php endif; ?>

                        <?php if ($page < $total_pages): ?>
                            <a class="page-btn primary" href="?page=<?php echo $page + 1; ?>&q=<?php echo urlencode($kata_kunci); ?>">Berikutnya</a>
                        <?php else: ?>
                            <button class="page-btn primary" disabled>Berikutnya</button>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        </div><!-- /.content-body -->

        <!-- Footer -->
        <footer class="dashboard-footer">
            <div class="footer-brand">
                <strong>SIAP-PBB</strong>
                <span>Â© 2024 Badan Pendapatan Daerah Kota Samarinda. Hak Cipta Dilindungi.</span>
            </div>
            <div class="footer-links">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat &amp; Ketentuan</a>
                <a href="#">Kontak Kami</a>
            </div>
        </footer>

    </main><!-- /.dashboard-main -->

    <script src="../assets/js/script.js"></script>
</body>
</html>



