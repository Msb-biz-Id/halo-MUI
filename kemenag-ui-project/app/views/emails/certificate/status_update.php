<h2>Update Status Sertifikat Halal</h2>

<p>Halo <strong><?= htmlspecialchars($applicant_name) ?></strong>,</p>

<p>Status pengajuan sertifikat halal Anda telah diperbarui.</p>

<div class="info-box">
    <p><strong>Detail Pengajuan:</strong></p>
    <table>
        <tr>
            <td>Nomor Tiket</td>
            <td>: <strong><?= $ticket_number ?></strong></td>
        </tr>
        <tr>
            <td>Nama Produk</td>
            <td>: <?= htmlspecialchars($product_name) ?></td>
        </tr>
        <tr>
            <td>Status Sebelumnya</td>
            <td>: <?= ucfirst($old_status ?? 'pending') ?></td>
        </tr>
        <tr>
            <td>Status Baru</td>
            <td>: <strong style="color: #006837;"><?= strtoupper($new_status) ?></strong></td>
        </tr>
        <tr>
            <td>Tanggal Update</td>
            <td>: <?= date('d F Y H:i') ?></td>
        </tr>
    </table>
</div>

<?php if ($new_status === 'in_review'): ?>
    <div class="info-box">
        <h3 style="margin-top: 0;">🔍 Sedang Ditinjau</h3>
        <p>Pengajuan Anda sedang ditinjau oleh tim ahli kami. Proses ini biasanya memakan waktu 3-5 hari kerja.</p>
        <p><?= $notes ?? 'Dokumen Anda sedang dalam proses verifikasi oleh tim kami.' ?></p>
    </div>
<?php elseif ($new_status === 'approved'): ?>
    <div class="success-box">
        <h3 style="margin-top: 0;">✅ Pengajuan Disetujui!</h3>
        <p><strong>Selamat! Pengajuan sertifikat halal Anda telah disetujui.</strong></p>
        <p><?= $notes ?? 'Sertifikat Anda sedang dalam proses penerbitan dan akan segera tersedia untuk diunduh.' ?></p>
    </div>
<?php elseif ($new_status === 'completed'): ?>
    <div class="success-box">
        <h3 style="margin-top: 0;">🎉 Sertifikat Siap!</h3>
        <p><strong>Sertifikat halal Anda sudah siap dan dapat diunduh!</strong></p>
        <p>Silakan login ke dashboard Anda untuk mengunduh sertifikat resmi.</p>
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="<?= APP_URL ?>/certificate/download/<?= $certificate_id ?>" class="btn">Download Sertifikat</a>
    </div>
<?php elseif ($new_status === 'rejected'): ?>
    <div class="warning-box">
        <h3 style="margin-top: 0;">❌ Pengajuan Ditolak</h3>
        <p>Mohon maaf, pengajuan sertifikat halal Anda ditolak dengan alasan berikut:</p>
        <p style="background: white; padding: 15px; border-radius: 5px; margin: 10px 0;">
            <strong>Alasan Penolakan:</strong><br>
            <?= nl2br(htmlspecialchars($rejection_reason ?? 'Dokumen tidak lengkap atau tidak memenuhi persyaratan.')) ?>
        </p>
        <p><strong>Anda dapat mengajukan kembali setelah memperbaiki kekurangan yang ada.</strong></p>
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="<?= APP_URL ?>/certificate/apply" class="btn">Ajukan Kembali</a>
    </div>
<?php endif; ?>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?= APP_URL ?>/certificate/track/<?= $ticket_number ?>" class="btn" style="background: #17a2b8;">
        Lihat Detail Status
    </a>
</div>

<p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi kami.</p>

<p>Terima kasih,<br>
<strong>Tim Sertifikasi Halal<br>
Kementerian Agama RI</strong></p>
