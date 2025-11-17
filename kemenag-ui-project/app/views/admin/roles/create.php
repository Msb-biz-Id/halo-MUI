<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Tambah Role Baru</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/roles') ?>">Roles</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="uil-user-plus"></i> Form Role & Permissions</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/roles/create') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold">Nama Role <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                   value="<?= old('name') ?>" required placeholder="admin_forum">
                            <small class="text-muted">Gunakan lowercase, underscore (contoh: admin_forum)</small>
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label fw-bold">Deskripsi</label>
                            <input type="text" class="form-control" id="description" name="description" 
                                   value="<?= old('description') ?>" placeholder="Admin yang mengelola forum">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="fw-bold mb-3">Pilih Permissions:</h6>
                    
                    <?php
                    $permissionGroups = [
                        'User Management' => ['user_management', 'user_view', 'user_create', 'user_edit', 'user_delete'],
                        'Certificate Management' => ['certificate_management', 'certificate_view', 'certificate_approve', 'certificate_reject'],
                        'Content Management' => ['content_management', 'qa_manage', 'fatwa_manage', 'material_manage', 'book_manage'],
                        'Forum Management' => ['forum_management', 'forum_moderation', 'forum_approve', 'blacklist_manage'],
                        'Settings & Logs' => ['settings_manage', 'audit_logs', 'media_manage', 'role_manage']
                    ];
                    ?>
                    
                    <?php foreach ($permissionGroups as $group => $permissions): ?>
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="group_<?= str_replace(' ', '_', $group) ?>" 
                                           onchange="toggleGroup(this, '<?= str_replace(' ', '_', $group) ?>')">
                                    <label class="form-check-label fw-bold" for="group_<?= str_replace(' ', '_', $group) ?>">
                                        <?= $group ?>
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($permissions as $perm): ?>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input group_<?= str_replace(' ', '_', $group) ?>" 
                                                       type="checkbox" name="permissions[<?= $perm ?>]" 
                                                       id="perm_<?= $perm ?>" value="1">
                                                <label class="form-check-label" for="perm_<?= $perm ?>">
                                                    <?= ucwords(str_replace('_', ' ', $perm)) ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Simpan Role
                        </button>
                        <a href="<?= url('/admin/roles') ?>" class="btn btn-secondary btn-lg">
                            <i class="uil-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleGroup(checkbox, groupClass) {
    const checkboxes = document.querySelectorAll('.' + groupClass);
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}
</script>
