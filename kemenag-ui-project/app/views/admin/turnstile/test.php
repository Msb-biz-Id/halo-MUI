<?php $layout = 'admin'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Test Turnstile Verification</h4>
                <p class="text-muted">Test your Turnstile integration</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if (isset($tested) && $tested): ?>
            <!-- Result Card -->
            <div class="card mb-4">
                <div class="card-header bg-<?= $result['success'] ? 'success' : 'danger' ?> text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-<?= $result['success'] ? 'check-circle' : 'times-circle' ?>"></i>
                        Verification <?= $result['success'] ? 'Passed' : 'Failed' ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($result['success']): ?>
                        <p class="mb-0">✅ Turnstile verification successful! Your integration is working correctly.</p>
                    <?php else: ?>
                        <p class="mb-2">❌ Verification failed with error:</p>
                        <div class="alert alert-danger">
                            <strong><?= htmlspecialchars($result['error']) ?></strong>
                        </div>
                        <p class="mb-0"><small class="text-muted">Check your Turnstile configuration in .env file</small></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Test Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Test Form</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Complete the Turnstile Challenge:</label>
                            <?php include __DIR__ . '/../../components/turnstile.php'; ?>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Test Verification
                        </button>
                        <a href="<?= url('admin/turnstile') ?>" class="btn btn-secondary">
                            Back to Dashboard
                        </a>
                    </form>
                </div>
            </div>

            <!-- Instructions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">How to Test</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li>Complete the Turnstile challenge above</li>
                        <li>Click "Test Verification" button</li>
                        <li>Result will show if verification passed or failed</li>
                    </ol>

                    <p class="mt-3 mb-0"><strong>If test fails:</strong></p>
                    <ul>
                        <li>Check TURNSTILE_SITE_KEY in .env</li>
                        <li>Check TURNSTILE_SECRET_KEY in .env</li>
                        <li>Verify domain is whitelisted in Cloudflare Dashboard</li>
                        <li>Check server can reach challenges.cloudflare.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
