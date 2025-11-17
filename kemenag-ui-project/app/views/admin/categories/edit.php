<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Edit Kategori</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/categories') ?>">Categories</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0 text-dark"><i class="uil-edit"></i> Edit Kategori</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/categories/edit/' . $category['id']) ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="type" class="form-label fw-bold">Tipe Konten <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg" id="type" name="type" required>
                            <option value="qa" <?= $category['type'] == 'qa' ? 'selected' : '' ?>>Q&A (Tanya Jawab)</option>
                            <option value="fatwa" <?= $category['type'] == 'fatwa' ? 'selected' : '' ?>>Fatwa</option>
                            <option value="material" <?= $category['type'] == 'material' ? 'selected' : '' ?>>Material (Materi Moderasi)</option>
                            <option value="book" <?= $category['type'] == 'book' ? 'selected' : '' ?>>Book (Perpustakaan)</option>
                            <option value="forum" <?= $category['type'] == 'forum' ? 'selected' : '' ?>>Forum</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" 
                               value="<?= htmlspecialchars($category['name']) ?>" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="slug" class="form-label fw-bold">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug" 
                               value="<?= htmlspecialchars($category['slug']) ?>">
                        <small class="text-muted">Current: <code><?= $category['slug'] ?></code></small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                   <?= $category['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="is_active">
                                Aktifkan Kategori
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Info:</strong> Items di kategori ini: <strong><?= $category['item_count'] ?? 0 ?></strong> | 
                        Created: <?= date('d M Y', strtotime($category['created_at'])) ?>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Update Kategori
                        </button>
                        <a href="<?= url('/admin/categories') ?>" class="btn btn-secondary btn-lg">
                            <i class="uil-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
