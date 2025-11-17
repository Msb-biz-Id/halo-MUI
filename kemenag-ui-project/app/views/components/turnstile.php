<!-- Cloudflare Turnstile Widget Component -->
<?php if (turnstile_enabled()): ?>
<div class="mb-3">
    <div class="cf-turnstile" 
         data-sitekey="<?= env('TURNSTILE_SITE_KEY') ?>" 
         data-theme="<?= $_GET['theme'] ?? 'light' ?>"
         data-size="normal"></div>
    <?php if ($error = turnstile_error()): ?>
    <div class="text-danger mt-2">
        <small><?= htmlspecialchars($error) ?></small>
    </div>
    <?php endif; ?>
</div>

<!-- Turnstile Script -->
<?php if (!defined('TURNSTILE_SCRIPT_LOADED')): ?>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<?php define('TURNSTILE_SCRIPT_LOADED', true); ?>
<?php endif; ?>
<?php endif; ?>
