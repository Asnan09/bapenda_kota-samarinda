<?php
// admin/dashboard.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../koneksi.php";

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

    $query = "SELECT id, nama, nik, jenis_surat, status, tanggal FROM pengajuan WHERE id LIKE ? OR nama LIKE ? OR nik LIKE ? OR jenis_surat LIKE ? ORDER BY tanggal DESC LIMIT ? OFFSET ?";
    $stmt  = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssssii", $pencarian, $pencarian, $pencarian, $pencarian, $per_page, $offset);
} else {
    $total_data_tampil = $total_pengajuan;
    $query = "SELECT id, nama, nik, jenis_surat, status, tanggal FROM pengajuan ORDER BY tanggal DESC LIMIT ? OFFSET ?";
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
    <title>Dashboard Admin — Bapenda Samarinda</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
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
            <span>Bapenda Samarinda</span>
        </div>
        <nav>
            <a class="active" href="dashboard.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="2" fill="currentColor"/><rect x="14" y="3" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="3" y="14" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="14" y="14" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/></svg>
                </span>
                Dashboard
            </a>
            <a href="dashboard.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                Data Pengajuan
            </a>
            <a href="../index.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 8v4l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                Pajak Daerah
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a href="#">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 8h.01M12 12v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                Bantuan
            </a>
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
                <h1>Ringkasan Data</h1>
                <p>Dashboard Administrasi Terpadu</p>
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
            </div>
        </header>

        <div class="content-body">

            <!-- Stats -->
            <section class="stats-grid">
                <article class="stat-card reveal-card">
                    <div class="stat-info">
                        <p>Total Pengajuan</p>
                        <strong><?php echo number_format($total_pengajuan); ?></strong>
                        <span class="stat-meta up">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24"><path d="M5 12l5 5L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Data real dari database
                        </span>
                    </div>
                    <div class="stat-icon-box blue">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke="#2563eb" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </div>
                </article>

                <article class="stat-card attention reveal-card">
                    <div class="stat-info">
                        <p>Menunggu Proses</p>
                        <strong><?php echo number_format($total_pending); ?></strong>
                        <span class="stat-meta warn">Butuh Atensi</span>
                    </div>
                    <div class="stat-icon-box orange">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M8 6h8M8 10h5M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z" stroke="#ea580c" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </div>
                </article>

                <article class="stat-card success reveal-card">
                    <div class="stat-info">
                        <p>Selesai Verifikasi</p>
                        <strong><?php echo number_format($total_selesai); ?></strong>
                        <span class="stat-meta info">
                            <?php echo $total_pengajuan > 0 ? round($total_selesai / $total_pengajuan * 100, 1) : 0; ?>% selesai
                        </span>
                    </div>
                    <div class="stat-icon-box gray">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke="#64748b" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="#64748b" stroke-width="1.8"/></svg>
                    </div>
                </article>
            </section>

            <!-- Table Panel -->
            <section class="table-panel reveal-card">
                <div class="table-heading">
                    <h2>Daftar Pengajuan Terbaru</h2>
                    <button class="filter-button" type="button">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M3 6h18M7 12h10M11 18h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        Filter Data
                    </button>
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
                                    <!-- View -->
                                    <a class="icon-action" href="detail.php?id=<?php echo $row['id']; ?>" title="Lihat detail">
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                                    </a>
                                    <!-- Edit / Refresh -->
                                    <a class="icon-action" href="detail.php?id=<?php echo $row['id']; ?>" title="Ubah status"
                                       <?php if ($row['status'] === 'selesai') echo 'style="opacity:.35;pointer-events:none;"'; ?>>
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M4 4v5h5M20 20v-5h-5M4 9a9 9 0 0114.9-3.4M20 15A9 9 0 015.1 18.4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                                    </a>
                                    <!-- Approve -->
                                    <a class="icon-action approve" href="detail.php?id=<?php echo $row['id']; ?>" title="Selesaikan"
                                       <?php if ($row['status'] === 'selesai') echo 'style="opacity:.35;pointer-events:none;"'; ?>>
                                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
                        Menampilkan <?php echo $total_data_tampil > 0 ? $offset + 1 : 0; ?>–<?php echo min($offset + $per_page, $total_data_tampil); ?>
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
                <strong>BAPENDA SAMARINDA</strong>
                <span>© 2024 Badan Pendapatan Daerah Kota Samarinda. Hak Cipta Dilindungi.</span>
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
