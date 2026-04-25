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
    <title>Login Admin - Bapenda Samarinda</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css?v=10">
</head>
<body class="login-page-new">

    <div class="login-container">
        <a href="../index.php" class="back-link">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            KEMBALI KE BERANDA
        </a>

        <main class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24"><path fill="var(--blue)" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h1>Admin<span class="text-blue">Portal</span></h1>
                <p>BAPENDA KOTA SAMARINDA</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert error" style="margin: 0 40px 16px; background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 8px; font-size: 12px; border: 1px solid #fecaca; text-align: center;"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <form class="login-form-new" action="proses_login.php" method="POST">
                <div class="input-group">
                    <label>NAMA PENGGUNA</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <input type="text" name="username" placeholder="admin" required autocomplete="username">
                    </div>
                </div>

                <div class="input-group">
                    <label>KATA SANDI</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <input type="password" name="password" id="passwordField" placeholder="********" required autocomplete="current-password">
                        <button type="button" class="toggle-password" id="togglePassword">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                    </div>
                </div>

                <button class="btn-login-new" type="submit">MASUK SEKARANG</button>
            </form>

            <div class="login-footer">
                @ 2024 SISTEM INFORMASI BAPENDA
            </div>
        </main>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordField');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            if(type === 'text') {
                this.innerHTML = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>';
            } else {
                this.innerHTML = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
            }
        });
    </script>
</body>
</html>

