<?php
// admin/logout.php
// Mengakhiri session admin lalu kembali ke halaman login.
session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
exit;
?>
