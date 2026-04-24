<?php
// admin/login.php
session_start();
if (isset($_SESSION['admin_id'])) { header("Location: dashboard.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Bapenda Samarinda</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="login-page">

    <header class="public-admin-nav">
        <strong>BAPENDA SAMARINDA</strong>
        <nav>
            <a href="../index.php">Beranda</a>
            <a href="../layanan.php">Layanan</a>
            <a href="../index.php#lokasi">Lokasi</a>
        </nav>
    </header>

    <main class="login-shell">
        <section class="login-box">
            <span class="secure-badge">?? Portal Keamanan</span>
            <h1>Login Admin</h1>
            <p>Silakan masuk menggunakan kredensial resmi Bapenda Samarinda.</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST">
                <label>Nama Pengguna
                    <input type="text" name="username" placeholder="admin" required autocomplete="username">
                </label>
                <label>Kata Sandi
                    <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                </label>
                <a class="forgot-link" href="#">Lupa kata sandi?</a>
                <button type="submit">Masuk ke Dashboard ?</button>
            </form>

            <div class="support-box">Butuh bantuan teknis?<br>Hubungi Divisi IT di ext. 402</div>
        </section>
    </main>

    <footer class="admin-footer">BAPENDA SAMARINDA &nbsp;©&nbsp; 2024 Badan Pendapatan Daerah Kota Samarinda.</footer>
    <script src="../assets/js/script.js"></script>
</body>
</html>
