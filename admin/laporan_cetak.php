<?php
// admin/laporan_cetak.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
include "../koneksi.php";

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    // Cetak per data
    $query = "SELECT * FROM pengajuan WHERE id = $id";
    $title = "Detail Pengajuan #$id";
} else {
    // Cetak semua (laporan global)
    $query = "SELECT * FROM pengajuan ORDER BY tanggal DESC";
    $title = "Laporan Keseluruhan Pengajuan";
}

$hasil = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; font-size: 13px; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .status { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .status.pending { background: #eee; }
        .status.diproses { background: #fff3cd; color: #856404; }
        .status.selesai { background: #d4edda; color: #155724; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>Badan Pendapatan Daerah Kota Samarinda</h1>
        <p>Jl. Dahlia No. 02, Bugis, Kec. Samarinda Kota, Kota Samarinda, Kalimantan Timur</p>
        <h2 style="margin-top: 20px; font-size: 18px;"><?php echo $title; ?></h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemohon</th>
                <th>NIK</th>
                <th>Jenis Surat</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while($row = mysqli_fetch_assoc($hasil)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['nik']); ?></td>
                <td><?php echo htmlspecialchars($row['jenis_surat']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                <td><span class="status <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Samarinda, <?php echo date('d F Y'); ?></p>
        <br><br><br>
        <p><strong>Administrator Bapenda</strong></p>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
    </div>
</body>
</html>
