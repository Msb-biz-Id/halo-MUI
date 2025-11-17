<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">➕ Add New Translation</h5>
            </div>
            <div class="card-body">
                <form action="<?= url('/admin/translations/create') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-3">
                        <label for="language" class="form-label fw-bold">Language <span class="text-danger">*</span></label>
                        <select class="form-select" id="language" name="language" required>
                            <option value="id">Indonesian</option>
                            <option value="en">English</option>
                            <option value="ar">Arabic</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="translation_key" class="form-label fw-bold">Translation Key <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="translation_key" name="translation_key" 
                               placeholder="e.g., menu.home" required>
                        <small class="text-muted">Use dot notation for nested keys</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="translation_value" class="form-label fw-bold">Translation Value <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="translation_value" name="translation_value" 
                                  rows="3" required placeholder="Enter translated text"></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="uil-check"></i> Create Translation
                        </button>
                        <a href="<?= url('/admin/translations') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
