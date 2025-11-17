<h2>Pengajuan Sertifikat Halal Diterima</h2>

<p>Halo <strong><?= htmlspecialchars($applicant_name) ?></strong>,</p>

<p>Terima kasih telah mengajukan sertifikat halal melalui Website Kemenag UI. Pengajuan Anda telah kami terima dan akan segera diproses.</p>

<div class="success-box">
    <h3 style="margin-top: 0;">✅ Pengajuan Berhasil Diterima</h3>
    <p style="margin: 0;">Nomor Tiket: <strong style="font-size: 18px; color: #006837;"><?= $ticket_number ?></strong></p>
</div>

<div class="info-box">
    <p><strong>Detail Pengajuan:</strong></p>
    <table>
        <tr>
            <td>Nomor Tiket</td>
            <td>: <?= $ticket_number ?></td>
        </tr>
        <tr>
            <td>Nama Perusahaan</td>
            <td>: <?= htmlspecialchars($company_name) ?></td>
        </tr>
        <tr>
            <td>Nama Produk</td>
            <td>: <?= htmlspecialchars($product_name) ?></td>
        </tr>
        <tr>
            <td>Kategori</td>
            <td>: <?= htmlspecialchars($product_category) ?></td>
        </tr>
        <tr>
            <td>Tanggal Pengajuan</td>
            <td>: <?= date('d F Y H:i') ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: <strong style="color: #ffc107;">PENDING</strong></td>
        </tr>
    </table>
</div>

<h3>📋 Langkah Selanjutnya:</h3>
<ol>
    <li><strong>Verifikasi Dokumen</strong> - Tim kami akan memverifikasi dokumen yang Anda upload (1-2 hari kerja)</li>
    <li><strong>Review Tim Ahli</strong> - Pengajuan akan direview oleh tim ahli kami (3-5 hari kerja)</li>
    <li><strong>Keputusan</strong> - Anda akan menerima notifikasi mengenai keputusan pengajuan</li>
    <li><strong>Sertifikat</strong> - Jika disetujui, sertifikat akan digenerate dan dapat didownload</li>
</ol>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?= APP_URL ?>/certificate/track/<?= $ticket_number ?>" class="btn">Lacak Status Pengajuan</a>
</div>

<div class="warning-box">
    <p><strong>💡 Catatan Penting:</strong></p>
    <ul style="margin: 0;">
        <li>Simpan nomor tiket <strong><?= $ticket_number ?></strong> untuk tracking</li>
        <li>Proses verifikasi memakan waktu 3-7 hari kerja</li>
        <li>Anda akan menerima email dan notifikasi untuk setiap perubahan status</li>
        <li>Pastikan data dan dokumen yang diupload sudah benar dan lengkap</li>
    </ul>
</div>

<p>Untuk melihat status pengajuan real-time, silakan login ke dashboard Anda atau klik tombol "Lacak Status" di atas.</p>

<p>Jika Anda memiliki pertanyaan, hubungi:</p>
<ul>
    <li>Email: <?= CONTACT_EMAIL ?></li>
    <li>Telepon: <?= CONTACT_PHONE ?></li>
    <li>WhatsApp: +62-xxx-xxxx-xxxx</li>
</ul>

<p>Terima kasih atas kepercayaan Anda,<br>
<strong>Tim Sertifikasi Halal<br>
Kementerian Agama RI</strong></p>
