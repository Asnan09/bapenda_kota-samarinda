<?php
// detail_pengajuan.php (Public View)
include "koneksi.php";

$id_pengajuan = (int)($_GET['id'] ?? 0);
$query = "SELECT pengajuan.*, berkas.*
          FROM pengajuan
          LEFT JOIN berkas ON berkas.pengajuan_id = pengajuan.id
          WHERE pengajuan.id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_pengajuan);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    header("Location: cek_status.php");
    exit;
}

$dokumen_pbb = [
    'file_ktp' => 'Fotokopi KTP / NPWP Badan',
    'file_spop_slop' => 'Blangko SPOP / SLOP',
    'file_surat_pernyataan' => 'Surat Pernyataan Bermaterai',
    'file_legalisir_tanah' => 'Sertifikat / PPAT / SKUMHAT / IMTN',
    'file_foto_lokasi' => 'Foto Lokasi Tanah dan Bangunan',
    'file_titik_koordinat' => 'Titik Koordinat Google Maps',
    'file_surat_kuasa' => 'Surat Kuasa Pengurusan',
    'file_sppdt_pembanding' => 'SPPDT-P2 Tetangga',
    'file_akta_ahli_waris' => 'Akta Kematian / Ahli Waris / KK',
    'file_surat_beda_nama' => 'Surat Keterangan Beda Nama'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan #<?php echo str_pad((string) $data['id'], 5, '0', STR_PAD_LEFT); ?></title>
    <link rel="stylesheet" href="assets/css/style.css?v=12">
    <style>
        .detail-container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .detail-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f3; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); padding: 32px; display: grid; grid-template-columns: 1fr 1fr; gap: 0 24px; }
        .detail-header { grid-column: 1 / -1; display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #f0f2f7; padding-bottom: 20px; margin-bottom: 24px; }
        .detail-header h1 { font-size: 20px; font-weight: 800; color: #0f1f3d; }
        .detail-field { padding: 12px 0; border-bottom: 1px solid #f8fafc; }
        .detail-field label { display: block; font-size: 11px; font-weight: 700; color: #6b7a99; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .detail-field span { font-size: 14px; font-weight: 600; color: #1a2744; }
        .detail-status { display: inline-block; padding: 6px 16px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .detail-status.pending { background: #f1f5f9; color: #64748b; }
        .detail-status.diproses { background: #fff1e8; color: #ea580c; }
        .detail-status.selesai { background: #dcfce7; color: #16a34a; }
        .detail-status.ditolak { background: #fee2e2; color: #dc2626; }
        .document-grid { grid-column: 1 / -1; display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 30px; }
        .doc-item { background: #f9fafb; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; }
        .doc-item span { font-size: 13px; font-weight: 600; color: #334155; }
        .doc-item a { font-size: 12px; font-weight: 700; color: #2563eb; text-decoration: none; }
        .rejection-note { grid-column: 1 / -1; background: #fff1f1; border-left: 4px solid #dc2626; padding: 15px; border-radius: 8px; margin-top: 20px; }
        .rejection-note strong { display: block; color: #dc2626; font-size: 13px; margin-bottom: 5px; }
        .rejection-note p { font-size: 13.5px; color: #991b1b; margin: 0; line-height: 1.5; }
        @media (max-width: 768px) { .detail-card { grid-template-columns: 1fr; } .document-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body style="background: #f8fafc; font-family: 'Poppins', sans-serif;">

    <header class="navbar">
        <div class="brand">SIAP-PBB</div>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="layanan.php">Layanan</a>
            <a href="cek_status.php" class="active">Cek Status</a>
            <a class="nav-button" href="admin/login.php">Login Admin</a>
        </nav>
    </header>

    <div class="detail-container">
        <a href="cek_status.php" style="display: inline-flex; align-items: center; gap: 8px; color: #6b7a99; font-size: 13.5px; font-weight: 600; text-decoration: none; margin-bottom: 20px;">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Kembali ke Daftar Status
        </a>

        <div class="detail-card">
            <div class="detail-header">
                <div>
                    <h1>Detail Pengajuan Surat</h1>
                    <span style="font-size: 13px; color: #6b7a99;">Nomor Registrasi: #<?php echo str_pad((string) $data['id'], 5, '0', STR_PAD_LEFT); ?></span>
                </div>
                <div class="detail-status <?php echo htmlspecialchars($data['status']); ?>">
                    <?php echo htmlspecialchars($data['status']); ?>
                </div>
            </div>

            <?php if ($data['status'] === 'ditolak' && !empty($data['keterangan'])): ?>
                <div class="rejection-note">
                    <strong>Alasan Penolakan:</strong>
                    <p><?php echo nl2br(htmlspecialchars($data['keterangan'])); ?></p>
                </div>
            <?php endif; ?>

            <div class="detail-field">
                <label>Nama Pemohon</label>
                <span><?php echo htmlspecialchars($data['nama']); ?></span>
            </div>
            <div class="detail-field">
                <label>NIK / NPWP</label>
                <span><?php echo htmlspecialchars($data['nik']); ?></span>
            </div>
            <div class="detail-field">
                <label>Jenis Layanan</label>
                <span><?php echo htmlspecialchars($data['jenis_surat']); ?></span>
            </div>
            <div class="detail-field">
                <label>Tanggal Masuk</label>
                <span><?php echo date('d F Y', strtotime($data['tanggal'])); ?></span>
            </div>
            <div class="detail-field" style="grid-column: 1 / -1;">
                <label>Alamat Pemohon</label>
                <span><?php echo nl2br(htmlspecialchars($data['alamat'])); ?></span>
            </div>
            <div class="detail-field" style="grid-column: 1 / -1;">
                <label>Alamat Objek Pajak</label>
                <span><?php echo nl2br(htmlspecialchars($data['alamat_objek_pajak'] ?? '-')); ?></span>
            </div>
            <div class="detail-field">
                <label>Kelurahan</label>
                <span><?php echo htmlspecialchars($data['kelurahan'] ?? '-'); ?></span>
            </div>
            <div class="detail-field">
                <label>Kecamatan</label>
                <span><?php echo htmlspecialchars($data['kecamatan'] ?? '-'); ?></span>
            </div>

            <div class="document-grid">
                <div style="grid-column: 1 / -1; font-size: 14px; font-weight: 800; color: #0f1f3d; margin-top: 10px;">
                    BERKAS LAMPIRAN
                </div>
                <?php foreach ($dokumen_pbb as $kolom => $label): ?>
                    <?php if (!empty($data[$kolom])): ?>
                        <div class="doc-item">
                            <span><?php echo htmlspecialchars($label); ?></span>
                            <a href="uploads/<?php echo htmlspecialchars($data[$kolom]); ?>" target="_blank">LIHAT</a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
