<!-- This is a redirect page - actual export handled by controller -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0">Export Audit Logs</h4>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="uil-export"></i> Export Options</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/audit-logs/export') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Date Range</label>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label small">From:</label>
                                <input type="date" class="form-control" name="date_from" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">To:</label>
                                <input type="date" class="form-control" name="date_to" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Export Format</label>
                        <select class="form-select" name="format">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel (XLSX)</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Filter Actions</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="actions[]" value="create" checked>
                            <label class="form-check-label">Create</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="actions[]" value="update" checked>
                            <label class="form-check-label">Update</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="actions[]" value="delete" checked>
                            <label class="form-check-label">Delete</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="actions[]" value="login" checked>
                            <label class="form-check-label">Login</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="actions[]" value="logout" checked>
                            <label class="form-check-label">Logout</label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="uil-download-alt"></i> Export Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
