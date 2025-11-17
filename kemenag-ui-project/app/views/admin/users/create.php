<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Tambah Pengguna Baru</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/users') ?>">Users</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="uil-user-plus"></i> Form Tambah Pengguna</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/users/create') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= old('username') ?>" required>
                            <small class="text-muted">Unik, tanpa spasi</small>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= old('email') ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?= old('full_name') ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="role_id" name="role_id" required>
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>" <?= old('role_id') == $role['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role['name']) ?> - <?= htmlspecialchars($role['description']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <small class="text-muted">Jika tidak dicentang, user tidak bisa login</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="uil-info-circle"></i> Informasi</h6>
                        <ul class="mb-0 small">
                            <li>User yang dibuat oleh admin otomatis terverifikasi email</li>
                            <li>User akan menerima email dengan kredensial login</li>
                            <li>Password harus minimal 8 karakter</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="uil-check"></i> Simpan
                        </button>
                        <a href="<?= url('/admin/users') ?>" class="btn btn-secondary">
                            <i class="uil-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
