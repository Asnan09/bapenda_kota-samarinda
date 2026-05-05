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

// Ambil filter bulan & tahun
$filter_bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$filter_tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

// Base query parts
$where_chart = "WHERE MONTH(tanggal) = $filter_bulan AND YEAR(tanggal) = $filter_tahun";

// Query ringkasan atas (semua data atau bisa difilter juga, di sini kita biarkan total keseluruhan agar konsisten)
$total_pengajuan = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengajuan"))['total'];
$total_pending   = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengajuan WHERE status = 'pending'"))['total'];
$total_diproses  = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengajuan WHERE status = 'diproses'"))['total'];
$total_selesai   = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pengajuan WHERE status = 'selesai'"))['total'];

// Data for Doughnut Chart (Filtered)
$chart_status = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT 
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'diproses' THEN 1 ELSE 0 END) as diproses,
    SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai
    FROM pengajuan $where_chart"));

// Data for Line Chart (Dates of the selected month)
$chart_dates = [];
$chart_counts = [];
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $filter_bulan, $filter_tahun);
for ($i = 1; $i <= $days_in_month; $i++) {
    $date = sprintf("%04d-%02d-%02d", $filter_tahun, $filter_bulan, $i);
    $chart_dates[] = $i . " " . date('M', mktime(0, 0, 0, $filter_bulan, 10)); // e.g. 1 Sep
    $query = "SELECT COUNT(*) AS total FROM pengajuan WHERE DATE(tanggal) = '$date'";
    $chart_counts[] = (int) mysqli_fetch_assoc(mysqli_query($koneksi, $query))['total'];
}

// Data for Bar Chart (Jenis Surat - Filtered)
$chart_jenis_labels = [];
$chart_jenis_data = [];
$q_jenis = mysqli_query($koneksi, "SELECT jenis_surat, COUNT(*) as total FROM pengajuan $where_chart GROUP BY jenis_surat");
while($r = mysqli_fetch_assoc($q_jenis)) {
    $chart_jenis_labels[] = $r['jenis_surat'];
    $chart_jenis_data[] = (int) $r['total'];
}

$bulan_array = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
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
            <a class="active" href="dashboard.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="2" fill="currentColor"/><rect x="14" y="3" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="3" y="14" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/><rect x="14" y="14" width="7" height="7" rx="2" fill="currentColor" opacity=".5"/></svg>
                </span>
                Dashboard
            </a>
            <a href="data_pengajuan.php">
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
                <h1>Ringkasan Data</h1>
                <p>Dashboard Administrasi Terpadu</p>
            </div>
            <div class="admin-profile" title="<?php echo htmlspecialchars($admin_username); ?>">
                <div class="admin-avatar"><?php echo htmlspecialchars($admin_initial); ?></div>
                <div class="admin-profile-info">
                    <strong><?php echo htmlspecialchars($admin_username); ?></strong>
                    <span>Administrator</span>
                </div>
                <span class="admin-profile-dot" aria-hidden="true"></span>
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

            <!-- Filter Waktu -->
            <form method="GET" action="dashboard.php" style="display: flex; gap: 12px; margin-bottom: 20px; align-items: center;" class="filter-form">
                <select name="bulan" class="modern-select">
                    <?php foreach($bulan_array as $m => $nama_bulan): ?>
                        <option value="<?php echo $m; ?>" <?php echo $m === $filter_bulan ? 'selected' : ''; ?>><?php echo $nama_bulan; ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="tahun" class="modern-select">
                    <?php 
                    $tahun_sekarang = date('Y');
                    for($t = $tahun_sekarang - 2; $t <= $tahun_sekarang + 1; $t++): ?>
                        <option value="<?php echo $t; ?>" <?php echo $t === $filter_tahun ? 'selected' : ''; ?>><?php echo $t; ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit" style="padding: 10px 20px; background: var(--blue); color: var(--white); border: none; border-radius: var(--radius-sm); font-weight: 600; font-size: 14px; cursor: pointer; transition: background 0.2s; height: 42px;">Filter Grafik</button>
            </form>

            <!-- Charts Section -->
            <section class="charts-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <!-- Doughnut Chart: Status Pengajuan -->
                <article class="chart-card reveal-card" style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow);">
                    <h3 style="font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 20px;">Persentase Status (<?php echo $bulan_array[$filter_bulan] . " " . $filter_tahun; ?>)</h3>
                    <div style="height: 250px; display: flex; justify-content: center;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </article>

                <!-- Line Chart: Tren Bulanan -->
                <article class="chart-card reveal-card" style="background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); animation-delay: 0.1s;">
                    <h3 style="font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 20px;">Tren Pengajuan (<?php echo $bulan_array[$filter_bulan] . " " . $filter_tahun; ?>)</h3>
                    <div style="height: 250px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </article>

                <!-- Bar Chart: Jenis Surat -->
                <article class="chart-card reveal-card" style="grid-column: 1 / -1; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); animation-delay: 0.2s;">
                    <h3 style="font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 20px;">Distribusi Jenis Surat</h3>
                    <div style="height: 300px;">
                        <canvas id="jenisChart"></canvas>
                    </div>
                </article>
            </section>

        </div><!-- /.content-body -->

        <!-- Footer -->
        <footer class="dashboard-footer">
            <div class="footer-brand">
                <strong>SIAP-PBB</strong>
                <span>© 2024 Badan Pendapatan Daerah Kota Samarinda. Hak Cipta Dilindungi.</span>
            </div>
            <div class="footer-links">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat &amp; Ketentuan</a>
                <a href="#">Kontak Kami</a>
            </div>
        </footer>

    </main><!-- /.dashboard-main -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/script.js?v=6"></script>
     <script>
        // Chart.js Configuration
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = "#6b7a99";
        
        // 1. Status Chart (Doughnut)
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Diproses', 'Selesai'],
                datasets: [{
                    data: [<?php echo (int)$chart_status['pending']; ?>, <?php echo (int)$chart_status['diproses']; ?>, <?php echo (int)$chart_status['selesai']; ?>],
                    backgroundColor: ['#64748b', '#f59e0b', '#10b981'], // Tailwind Slate, Amber, Emerald
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 1500, easing: 'easeOutQuart' },
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, boxWidth: 8 } }
                },
                cutout: '75%'
            }
        });

        // 2. Trend Chart (Line)
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_dates); ?>,
                datasets: [{
                    label: 'Pengajuan Masuk',
                    data: <?php echo json_encode($chart_counts); ?>,
                    borderColor: '#6366f1', // Tailwind Indigo
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 2000, easing: 'easeOutExpo' },
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#e2e8f3' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } }
                }
            }
        });

        // 3. Jenis Surat Chart (Bar)
        new Chart(document.getElementById('jenisChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chart_jenis_labels); ?>,
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: <?php echo json_encode($chart_jenis_data); ?>,
                    backgroundColor: '#8b5cf6', // Tailwind Violet
                    borderRadius: 6,
                    barThickness: 'flex',
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 1500, easing: 'easeOutBounce' },
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#e2e8f3' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>


