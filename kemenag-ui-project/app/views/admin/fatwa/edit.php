<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Edit Fatwa</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/fatwa') ?>">Fatwa</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0 text-dark"><i class="uil-edit"></i> Edit Fatwa</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/fatwa/edit/' . $fatwa['id']) ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="title" class="form-label fw-bold">Judul Fatwa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" 
                                   value="<?= htmlspecialchars($fatwa['title']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="fatwa_number" class="form-label fw-bold">Nomor Fatwa</label>
                            <input type="text" class="form-control" id="fatwa_number" name="fatwa_number" 
                                   value="<?= htmlspecialchars($fatwa['fatwa_number'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= $fatwa['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="summary" class="form-label fw-bold">Ringkasan</label>
                        <textarea class="form-control" id="summary" name="summary" rows="3"><?= htmlspecialchars($fatwa['summary'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="form-label fw-bold">Isi Fatwa <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="12" required><?= htmlspecialchars($fatwa['content']) ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="legal_basis" class="form-label fw-bold">Dasar Hukum</label>
                        <textarea class="form-control" id="legal_basis" name="legal_basis" rows="5"><?= htmlspecialchars($fatwa['legal_basis'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="references" class="form-label fw-bold">Referensi</label>
                        <textarea class="form-control" id="references" name="references" rows="4"><?= htmlspecialchars($fatwa['references'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="tags" class="form-label fw-bold">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="<?= htmlspecialchars($fatwa['tags'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" 
                                   <?= $fatwa['is_published'] ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="is_published">
                                Published
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Info:</strong> Views: <?= $fatwa['view_count'] ?? 0 ?> | 
                        Slug: <?= $fatwa['slug'] ?> | 
                        Created: <?= date('d M Y H:i', strtotime($fatwa['created_at'])) ?>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Update Fatwa
                        </button>
                        <a href="<?= url('/admin/fatwa') ?>" class="btn btn-secondary btn-lg">
                            <i class="uil-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
