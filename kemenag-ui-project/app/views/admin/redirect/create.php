<?php $layout = 'admin'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Create Redirect</h4>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">From URL <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">/</span>
                        <input type="text" name="from_url" class="form-control" 
                               placeholder="old-page" required>
                    </div>
                    <small class="text-muted">The old URL path (without leading slash)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">To URL <span class="text-danger">*</span></label>
                    <input type="text" name="to_url" class="form-control" 
                           placeholder="/new-page or https://external.com" required>
                    <small class="text-muted">New URL (can be internal or external)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Redirect Type</label>
                    <select name="type" class="form-select">
                        <option value="301">301 - Permanent</option>
                        <option value="302">302 - Temporary</option>
                        <option value="307">307 - Temporary (Preserve Method)</option>
                        <option value="308">308 - Permanent (Preserve Method)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Redirect
                    </button>
                    <a href="<?= url('admin/redirect') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
