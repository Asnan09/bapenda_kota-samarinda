<?php
// admin/kelola_admin.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../koneksi.php";

$admin_username = $_SESSION['admin_username'] ?? "admin";
$admin_initial = strtoupper(substr($admin_username, 0, 1));

$error = $_GET['error'] ?? "";
$success = $_GET['success'] ?? "";

// Ambil daftar admin
$query_admin = "SELECT id, username FROM admin ORDER BY id ASC";
$hasil_admin = mysqli_query($koneksi, $query_admin);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin — SIAP-PBB</title>
    <link rel="stylesheet" href="../assets/css/admin.css?v=10">
    <style>
        /* Modal Styles */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(15, 31, 61, 0.6); backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center; z-index: 1000;
            opacity: 0; visibility: hidden; transition: all 0.3s ease;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-box {
            background: #fff; width: 100%; max-width: 480px; border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: translateY(20px) scale(0.95); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden; display: flex; flex-direction: column;
        }
        .modal-overlay.active .modal-box { transform: none; }
        .modal-header {
            padding: 24px 32px; border-bottom: 1px solid #e2e8f3;
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-header h2 { font-size: 18px; font-weight: 800; color: #0f1f3d; }
        .modal-close {
            background: none; border: none; font-size: 24px; color: #6b7a99; cursor: pointer;
            width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px;
            transition: background 0.2s;
        }
        .modal-close:hover { background: #f1f5f9; color: #f43f5e; }
        .modal-body { padding: 32px; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-size: 13.5px; font-weight: 700; color: #1a3560; margin-bottom: 8px; }
        .input-group input {
            width: 100%; padding: 12px 16px; border: 2px solid #e2e8f3; border-radius: 10px;
            font-size: 14px; font-family: inherit; transition: all 0.2s; outline: none; background: #f8fafc;
        }
        .input-group input:focus { border-color: #3b82f6; background: #fff; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .btn-tambah {
            padding: 10px 20px; border-radius: 10px; background: #3b82f6; color: #fff; border: none;
            font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-tambah:hover { background: #2563eb; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
        .btn-submit {
            width: 100%; padding: 14px; border-radius: 10px; background: #10b981; color: #fff; border: none;
            font-size: 15px; font-weight: 800; cursor: pointer; transition: all 0.2s; margin-top: 10px;
        }
        .btn-submit:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
    </style>
</head>
<body class="dashboard-page">

    <button class="mobile-menu-button" type="button" data-menu-toggle>
        <span></span><span></span><span></span>
    </button>
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
            <a href="data_pengajuan.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>Data Pengajuan
            </a>
            <a class="active" href="kelola_admin.php">
                <span class="nav-icon">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M8.5 3a4 4 0 100 8 4 4 0 000-8zM20 8v6M23 11h-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>Kelola Admin
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a class="sidebar-logout" href="logout.php"><span class="nav-icon"><svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Keluar</a>
        </div>
    </aside>

    <main class="dashboard-main">
        <header class="dashboard-top">
            <div>
                <h1>Kelola Admin</h1>
                <p>Manajemen akun administrator sistem SIAP-PBB</p>
            </div>
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
            <?php if ($error): ?>
                <div class="alert error" style="padding: 12px 16px; background: #fef2f2; border: 1px solid #fecaca; color: #e11d48; border-radius: var(--radius-sm); margin-bottom: 24px; font-size: 13.5px; font-weight: 600;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert success" style="padding: 12px 16px; background: #dcfce7; border: 1px solid #bbf7d0; color: #16a34a; border-radius: var(--radius-sm); margin-bottom: 24px; font-size: 13.5px; font-weight: 600;">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <section class="table-panel reveal-card">
                <div class="table-heading" style="display: flex; align-items: center; justify-content: space-between; padding: 20px 24px;">
                    <h2>Daftar Akun Admin</h2>
                    <button type="button" class="btn-tambah" onclick="openModal()">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                        Tambah Admin Baru
                    </button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Username</th>
                            <th style="width: 150px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($hasil_admin)): ?>
                        <tr>
                            <td><strong>#<?php echo str_pad($row['id'], 3, "0", STR_PAD_LEFT); ?></strong></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['username']); ?></strong>
                                <?php if ($row['id'] === $_SESSION['admin_id']): ?>
                                    <span style="background: #e0e7ff; color: #4f46e5; padding: 2px 8px; border-radius: 50px; font-size: 10px; font-weight: 800; margin-left: 8px; vertical-align: middle;">ANDA</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($row['id'] !== $_SESSION['admin_id']): ?>
                                    <a href="hapus_admin.php?id=<?php echo $row['id']; ?>" class="icon-action" style="color: #ef4444; border-color: #fca5a5; background: #fef2f2; margin: 0 auto;" title="Hapus Admin" onclick="return confirm('Yakin ingin menghapus admin <?php echo htmlspecialchars($row['username']); ?>?');">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #94a3b8; font-size: 12px; font-weight: 600;">Tidak dapat dihapus</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </main>

    <!-- Modal Pop-Up Tambah Admin -->
    <div class="modal-overlay" id="addAdminModal">
        <div class="modal-box">
            <div class="modal-header">
                <h2>Tambah Administrator</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="proses_tambah_admin.php" method="POST">
                    <div class="input-group">
                        <label for="username">Username Baru</label>
                        <input type="text" id="username" name="username" placeholder="Masukkan username..." required autocomplete="off">
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password kuat..." required autocomplete="new-password">
                    </div>
                    <div class="input-group">
                        <label for="konfirmasi_password">Konfirmasi Password</label>
                        <input type="password" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ketik ulang password..." required autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn-submit">Simpan Admin</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
    <script>
        const modal = document.getElementById('addAdminModal');
        function openModal() { modal.classList.add('active'); }
        function closeModal() { modal.classList.remove('active'); }
        
        // Tutup modal jika klik di luar box
        modal.addEventListener('click', (e) => {
            if(e.target === modal) closeModal();
        });
    </script>
</body>
</html>
