<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">🔐 Setup Two-Factor Authentication</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="uil-info-circle"></i>
                    <strong>Why enable 2FA?</strong> Two-factor authentication adds an extra layer of security to your account.
                </div>
                
                <h6 class="mb-3">Step 1: Scan QR Code</h6>
                <p class="text-muted">Use an authenticator app (Google Authenticator, Authy, etc.) to scan this QR code:</p>
                
                <div class="text-center mb-4">
                    <img src="<?= $qr_code_url ?>" alt="QR Code" class="img-fluid border p-3 bg-white">
                </div>
                
                <h6 class="mb-3">Step 2: Enter Secret Key</h6>
                <p class="text-muted">Or manually enter this secret key in your authenticator app:</p>
                <div class="input-group mb-4">
                    <input type="text" class="form-control font-monospace" value="<?= htmlspecialchars($secret) ?>" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText('<?= $secret ?>')">
                        <i class="uil-copy"></i> Copy
                    </button>
                </div>
                
                <h6 class="mb-3">Step 3: Verify Code</h6>
                <p class="text-muted">Enter the 6-digit code from your authenticator app to verify:</p>
                
                <form action="<?= url('/admin/mfa/enable') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <input type="text" class="form-control form-control-lg text-center" name="code" 
                               placeholder="000000" maxlength="6" pattern="[0-9]{6}" required autofocus>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                            <i class="uil-check-circle"></i> Enable 2FA
                        </button>
                        <a href="<?= url('/admin/dashboard') ?>" class="btn btn-outline-secondary btn-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
