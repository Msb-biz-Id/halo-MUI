<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">✏️ Edit Translation</h5>
            </div>
            <div class="card-body">
                <form action="<?= url('/admin/translations/edit/' . $translation['id']) ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Language</label>
                        <input type="text" class="form-control" value="<?= strtoupper($translation['language']) ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Translation Key</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($translation['translation_key']) ?>" readonly>
                    </div>
                    
                    <div class="mb-4">
                        <label for="translation_value" class="form-label fw-bold">Translation Value <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="translation_value" name="translation_value" 
                                  rows="5" required><?= htmlspecialchars($translation['translation_value']) ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="uil-check"></i> Update Translation
                        </button>
                        <a href="<?= url('/admin/translations?language=' . $translation['language']) ?>" 
                           class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
