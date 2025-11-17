<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">👥 Manajemen Role & Permissions</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="<?= url('/admin/roles/create') ?>" class="btn btn-primary">
                            <i class="uil-plus"></i> Tambah Role Baru
                        </a>
                    </div>
                </div>
                
                <h5 class="card-title mb-4">Daftar Role (<?= count($roles) ?>)</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Role Name</th>
                                <th>Description</th>
                                <th class="text-center">Users</th>
                                <th class="text-center">Permissions</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($roles)): ?>
                                <?php foreach ($roles as $role): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">
                                                <?php
                                                $badges = [
                                                    'superadmin' => 'danger',
                                                    'admin' => 'warning',
                                                    'admin_konten' => 'info',
                                                    'admin_sertifikat' => 'primary',
                                                    'user' => 'secondary'
                                                ];
                                                $badge = $badges[$role['name']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $badge ?>">
                                                    <?= htmlspecialchars($role['name']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= htmlspecialchars($role['description'] ?? '-') ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= $role['user_count'] ?? 0 ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php 
                                            $permissions = json_decode($role['permissions'] ?? '{}', true);
                                            $permCount = is_array($permissions) ? count(array_filter($permissions)) : 0;
                                            ?>
                                            <span class="badge bg-success"><?= $permCount ?> permissions</span>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($role['name'] === 'superadmin'): ?>
                                                <span class="badge bg-light text-dark">System Role</span>
                                            <?php else: ?>
                                                <a href="<?= url('/admin/roles/edit/' . $role['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="uil-edit"></i> Edit
                                                </a>
                                                <?php if ($role['user_count'] == 0): ?>
                                                    <a href="<?= url('/admin/roles/delete/' . $role['id']) ?>" 
                                                       class="btn btn-sm btn-outline-danger"
                                                       onclick="return confirm('Hapus role ini?')">
                                                        <i class="uil-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="uil-users-alt font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">Belum ada role</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
