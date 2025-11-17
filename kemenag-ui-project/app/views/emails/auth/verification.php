<h2>Verifikasi Email Anda</h2>

<p>Halo <strong><?= htmlspecialchars($user_name) ?></strong>,</p>

<p>Terima kasih telah mendaftar di Website Kemenag UI. Untuk menyelesaikan proses pendaftaran, silakan verifikasi email Anda dengan mengklik tombol di bawah ini:</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?= $verification_link ?>" class="btn">Verifikasi Email</a>
</div>

<div class="info-box">
    <p><strong>Informasi Akun:</strong></p>
    <table>
        <tr>
            <td>Username</td>
            <td>: <?= htmlspecialchars($username) ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td>: <?= htmlspecialchars($email) ?></td>
        </tr>
        <tr>
            <td>Tanggal Daftar</td>
            <td>: <?= date('d F Y H:i') ?></td>
        </tr>
    </table>
</div>

<p>Jika tombol di atas tidak berfungsi, copy dan paste link berikut ke browser Anda:</p>
<p style="background: #f8f9fa; padding: 10px; word-break: break-all; font-size: 12px;">
    <?= $verification_link ?>
</p>

<div class="warning-box">
    <p><strong>⚠️ Penting:</strong></p>
    <ul style="margin: 0;">
        <li>Link verifikasi ini berlaku selama <strong>24 jam</strong></li>
        <li>Jangan bagikan link ini kepada siapapun</li>
        <li>Jika Anda tidak merasa mendaftar, abaikan email ini</li>
    </ul>
</div>

<p>Jika Anda memiliki pertanyaan, silakan hubungi kami melalui:</p>
<ul>
    <li>Email: <?= CONTACT_EMAIL ?></li>
    <li>Telepon: <?= CONTACT_PHONE ?></li>
</ul>

<p>Terima kasih,<br>
<strong>Tim Kemenag UI</strong></p>
