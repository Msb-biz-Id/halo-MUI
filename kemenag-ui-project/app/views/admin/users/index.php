<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">👥 Manajemen Pengguna</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <a href="<?= url('/admin/users/create') ?>" class="btn btn-primary">
                            <i class="uil-plus"></i> Tambah Pengguna
                        </a>
                    </div>
                    <div>
                        <form action="<?= url('/admin/users') ?>" method="GET" class="d-flex gap-2">
                            <select name="role" class="form-select" onchange="this.form.submit()">
                                <option value="">All Roles</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['id'] ?>" <?= $role_filter == $role['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($role['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <input type="search" name="search" class="form-control" placeholder="Search users..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="uil-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Users List (<?= count($users) ?>)</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Status</th>
                                <th>Registered</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, #006837, #004d28); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <span class="text-white fw-bold">
                                                        <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?= htmlspecialchars($user['full_name']) ?></div>
                                                    <?php if ($user['email_verified_at']): ?>
                                                        <small class="text-success"><i class="uil-check-circle"></i> Verified</small>
                                                    <?php else: ?>
                                                        <small class="text-warning"><i class="uil-exclamation-triangle"></i> Not Verified</small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><?= $user['email'] ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-primary-subtle text-primary">
                                                <?= htmlspecialchars($user['role_name']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($user['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?= date('d M Y', strtotime($user['created_at'])) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="uil-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/users/edit/' . $user['id']) ?>">
                                                            <i class="uil-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/users/reset-password/' . $user['id']) ?>" 
                                                           onclick="return confirm('Reset password for this user?')">
                                                            <i class="uil-key-skeleton me-2"></i> Reset Password
                                                        </a>
                                                    </li>
                                                    <?php if ($user['role_id'] != 1): // Can't delete superadmin ?>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="<?= url('/admin/users/delete/' . $user['id']) ?>"
                                                               onclick="return confirm('Delete this user permanently?')">
                                                                <i class="uil-trash me-2"></i> Delete
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="uil-users-alt font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">No users found</p>
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
