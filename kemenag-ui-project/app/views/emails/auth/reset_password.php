<h2>Reset Password</h2>

<p>Halo <strong><?= htmlspecialchars($user_name) ?></strong>,</p>

<p>Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah untuk membuat password baru:</p>

<div style="text-align: center; margin: 30px 0;">
    <a href="<?= $reset_link ?>" class="btn">Reset Password</a>
</div>

<div class="info-box">
    <p><strong>Informasi Permintaan:</strong></p>
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
            <td>Waktu Request</td>
            <td>: <?= date('d F Y H:i') ?></td>
        </tr>
        <tr>
            <td>IP Address</td>
            <td>: <?= $ip_address ?? '-' ?></td>
        </tr>
    </table>
</div>

<p>Jika tombol di atas tidak berfungsi, copy dan paste link berikut ke browser Anda:</p>
<p style="background: #f8f9fa; padding: 10px; word-break: break-all; font-size: 12px;">
    <?= $reset_link ?>
</p>

<div class="warning-box">
    <p><strong>⚠️ Penting:</strong></p>
    <ul style="margin: 0;">
        <li>Link reset password ini berlaku selama <strong>1 jam</strong></li>
        <li>Jangan bagikan link ini kepada siapapun</li>
        <li>Jika Anda tidak melakukan permintaan ini, segera hubungi admin</li>
        <li>Password lama Anda masih berlaku sampai Anda membuat yang baru</li>
    </ul>
</div>

<p>Jika Anda tidak melakukan permintaan reset password, abaikan email ini dan password Anda tidak akan berubah.</p>

<p>Terima kasih,<br>
<strong>Tim Kemenag UI</strong></p>
