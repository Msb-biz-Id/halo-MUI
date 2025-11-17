<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <i class="uil-shield-check font-size-48 text-primary"></i>
                        <h4 class="mt-3">Two-Factor Authentication</h4>
                        <p class="text-muted">Enter the 6-digit code from your authenticator app</p>
                    </div>
                    
                    <form action="<?= url('/auth/verify-mfa') ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="mb-4">
                            <label for="mfa_code" class="form-label">Authentication Code</label>
                            <input type="text" class="form-control form-control-lg text-center" id="mfa_code" 
                                   name="mfa_code" placeholder="000000" maxlength="6" pattern="[0-9]{6}" 
                                   required autofocus>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="uil-check-circle"></i> Verify
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="<?= url('/auth/logout') ?>" class="text-muted small">
                            <i class="uil-sign-out-alt"></i> Cancel & Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-submit when 6 digits entered
document.getElementById('mfa_code').addEventListener('input', function(e) {
    if (this.value.length === 6) {
        this.form.submit();
    }
});
</script>
