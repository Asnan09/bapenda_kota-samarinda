<?php
// cek_status.php
// Halaman untuk masyarakat melihat daftar status pengajuan.
include "koneksi.php";

$data_pengajuan = [];
$query = "SELECT id, nik, nama, jenis_surat, status, tanggal FROM pengajuan ORDER BY tanggal DESC";
$hasil = mysqli_query($koneksi, $query);

if ($hasil) {
    while ($row = mysqli_fetch_assoc($hasil)) {
        $data_pengajuan[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pengajuan</title>
    <link rel="stylesheet" href="assets/css/style.css?v=7">
</head>
<body>
    <header class="navbar">
        <div class="brand">BAPENDA SAMARINDA</div>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="layanan.php">Layanan</a>
            <a href="cek_status.php" class="active">Cek Status</a>
            <a href="index.php#lokasi">Lokasi</a>
            <a class="nav-button" href="admin/login.php">Login Admin</a>
        </nav>
    </header>

    <main class="container narrow">
        <h1>Cek Status Pengajuan</h1>

        <?php if (isset($_GET['sukses'])): ?>
            <div class="alert success">
                Pengajuan berhasil dikirim. ID Pengajuan Anda: <strong><?php echo htmlspecialchars($_GET['sukses']); ?></strong>
            </div>
        <?php endif; ?>

                <div class="table-panel" style="margin-top: 40px; border-radius: 12px; border: 1px solid #e2e8f3; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); overflow: hidden;">
            <div class="table-heading" style="padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f3;">
                <h2 style="font-size: 16px; font-weight: 700; color: #0f1f3d; margin: 0;">Daftar Pengajuan Terbaru</h2>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; min-width: 860px; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr>
                            <th style="background: #f0f2f7; padding: 12px 16px; padding-left: 24px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; border-bottom: 1px solid #e2e8f3;">NO</th>
                            <th style="background: #f0f2f7; padding: 12px 16px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; border-bottom: 1px solid #e2e8f3;">NAMA PEMOHON</th>
                            <th style="background: #f0f2f7; padding: 12px 16px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; border-bottom: 1px solid #e2e8f3;">JENIS SURAT</th>
                            <th style="background: #f0f2f7; padding: 12px 16px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; border-bottom: 1px solid #e2e8f3;">TANGGAL MASUK</th>
                            <th style="background: #f0f2f7; padding: 12px 16px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; border-bottom: 1px solid #e2e8f3;">STATUS</th>
                            <th style="background: #f0f2f7; padding: 12px 16px; padding-right: 24px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; text-align: center; border-bottom: 1px solid #e2e8f3;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($data_pengajuan) > 0): ?>
                            <?php foreach ($data_pengajuan as $index => $pengajuan): ?>
                            <tr>
                                <td style="padding: 16px; padding-left: 24px; font-size: 13.5px; color: #6b7a99; font-weight: 600; border-bottom: 1px solid #e2e8f3;"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                <td style="padding: 16px; font-size: 13.5px; border-bottom: 1px solid #e2e8f3; line-height: 1.4;">
                                    <strong style="font-weight: 700; color: #0f1f3d;"><?php echo htmlspecialchars($pengajuan['nama']); ?></strong><br>
                                    <small style="font-size: 12px; color: #6b7a99;">NIK: <?php echo htmlspecialchars($pengajuan['nik']); ?></small>
                                </td>
                                <td style="padding: 16px; font-size: 13.5px; color: #1a2744; border-bottom: 1px solid #e2e8f3;"><?php echo htmlspecialchars($pengajuan['jenis_surat']); ?></td>
                                <td style="padding: 16px; font-size: 13.5px; color: #1a2744; border-bottom: 1px solid #e2e8f3;"><?php echo date("d M Y", strtotime($pengajuan['tanggal'])); ?></td>
                                <td style="padding: 16px; border-bottom: 1px solid #e2e8f3;">
                                    <?php 
                                        $status = strtolower($pengajuan['status']);
                                        if ($status == 'selesai') {
                                            echo '<span style="display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 11.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: #dcfce7; color: #16a34a;">SELESAI</span>';
                                        } elseif ($status == 'ditolak') {
                                            echo '<span style="display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 11.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: #fee2e2; color: #dc2626;">DITOLAK</span>';
                                        } else {
                                            echo '<span style="display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 11.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: #fff1e8; color: #ea580c;">DIPROSES</span>';
                                        }
                                    ?>
                                </td>
                                <td style="padding: 16px; padding-right: 24px; text-align: center; border-bottom: 1px solid #e2e8f3;">
                                    <?php if ($status == 'selesai'): ?>
                                        <a href="#" style="display: inline-flex; align-items: center; justify-content: center; background: #f0f2f7; border: 1px solid #e2e8f3; font-size: 15px; color: #6b7a99; text-decoration: none; width: 34px; height: 34px; border-radius: 8px; transition: all 0.2s;" title="Download Surat" onmouseover="this.style.background='#eff4ff'; this.style.color='#2563eb'; this.style.borderColor='#2563eb';" onmouseout="this.style.background='#f0f2f7'; this.style.color='#6b7a99'; this.style.borderColor='#e2e8f3';">
                                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                    <?php else: ?>
                                        <span style="display: inline-flex; align-items: center; justify-content: center; background: #f0f2f7; border: 1px solid #e2e8f3; font-size: 15px; color: #6b7a99; width: 34px; height: 34px; border-radius: 8px; opacity: 0.35; cursor: not-allowed;" title="Belum tersedia">
                                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="padding: 32px 16px; text-align: center; color: #6b7a99; font-size: 14px;">Belum ada data pengajuan surat.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 24px; background: #fff;">
                <span style="font-size: 12.5px; color: #6b7a99;">Menampilkan <?php echo count($data_pengajuan); ?> data pengajuan</span>
            </div>
        </div>

        <?php if (count($data_pengajuan) > 0): ?>
            <div class="table-panel" style="margin-top: 40px; border-radius: 12px; border: 1px solid #e2e8f3; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); overflow: hidden;">
                <div class="table-heading" style="padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f3;">
                    <h2 style="font-size: 16px; font-weight: 700; color: #0f1f3d; margin: 0;">Hasil Cek Status Pengajuan</h2>
                </div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; min-width: 860px; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr>
                                <th style="background: #f0f2f7; padding: 12px 16px; padding-left: 24px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; border-bottom: 1px solid #e2e8f3;">NO</th>
                                <th style="background: #f0f2f7; padding: 12px 16px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; border-bottom: 1px solid #e2e8f3;">NAMA PEMOHON</th>
                                <th style="background: #f0f2f7; padding: 12px 16px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; border-bottom: 1px solid #e2e8f3;">JENIS SURAT</th>
                                <th style="background: #f0f2f7; padding: 12px 16px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; border-bottom: 1px solid #e2e8f3;">TANGGAL MASUK</th>
                                <th style="background: #f0f2f7; padding: 12px 16px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; border-bottom: 1px solid #e2e8f3;">STATUS</th>
                                <th style="background: #f0f2f7; padding: 12px 16px; padding-right: 24px; font-size: 11px; font-weight: 700; letter-spacing: .07em; color: #6b7a99; text-transform: uppercase; text-align: center; border-bottom: 1px solid #e2e8f3;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_pengajuan as $index => $pengajuan): ?>
                            <tr>
                                <td style="padding: 16px; padding-left: 24px; font-size: 13.5px; color: #6b7a99; font-weight: 600; border-bottom: 1px solid #e2e8f3;"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></td>
                                <td style="padding: 16px; font-size: 13.5px; border-bottom: 1px solid #e2e8f3; line-height: 1.4;">
                                    <strong style="font-weight: 700; color: #0f1f3d;"><?php echo htmlspecialchars($pengajuan['nama']); ?></strong><br>
                                    <small style="font-size: 12px; color: #6b7a99;">NIK: <?php echo htmlspecialchars($pengajuan['nik']); ?></small>
                                </td>
                                <td style="padding: 16px; font-size: 13.5px; color: #1a2744; border-bottom: 1px solid #e2e8f3;"><?php echo htmlspecialchars($pengajuan['jenis_surat']); ?></td>
                                <td style="padding: 16px; font-size: 13.5px; color: #1a2744; border-bottom: 1px solid #e2e8f3;"><?php echo date("d M Y", strtotime($pengajuan['tanggal'])); ?></td>
                                <td style="padding: 16px; border-bottom: 1px solid #e2e8f3;">
                                    <?php 
                                        $status = strtolower($pengajuan['status']);
                                        if ($status == 'selesai') {
                                            echo '<span style="display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 11.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: #dcfce7; color: #16a34a;">SELESAI</span>';
                                        } elseif ($status == 'ditolak') {
                                            echo '<span style="display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 11.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: #fee2e2; color: #dc2626;">DITOLAK</span>';
                                        } else {
                                            echo '<span style="display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 11.5px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: #fff1e8; color: #ea580c;">DIPROSES</span>';
                                        }
                                    ?>
                                </td>
                                <td style="padding: 16px; padding-right: 24px; text-align: center; border-bottom: 1px solid #e2e8f3;">
                                    <?php if ($status == 'selesai'): ?>
                                        <a href="#" style="display: inline-flex; align-items: center; justify-content: center; background: #f0f2f7; border: 1px solid #e2e8f3; font-size: 15px; color: #6b7a99; text-decoration: none; width: 34px; height: 34px; border-radius: 8px; transition: all 0.2s;" title="Download Surat" onmouseover="this.style.background='#eff4ff'; this.style.color='#2563eb'; this.style.borderColor='#2563eb';" onmouseout="this.style.background='#f0f2f7'; this.style.color='#6b7a99'; this.style.borderColor='#e2e8f3';">
                                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                    <?php else: ?>
                                        <span style="display: inline-flex; align-items: center; justify-content: center; background: #f0f2f7; border: 1px solid #e2e8f3; font-size: 15px; color: #6b7a99; width: 34px; height: 34px; border-radius: 8px; opacity: 0.35; cursor: not-allowed;" title="Belum tersedia">
                                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 24px; background: #fff;">
                    <span style="font-size: 12.5px; color: #6b7a99;">Menampilkan <?php echo count($data_pengajuan); ?> data pengajuan</span>
                </div>
            </div>
        <?php endif; ?>
    </main>
    <script src="assets/js/script.js?v=2"></script>
</body>
</html>

