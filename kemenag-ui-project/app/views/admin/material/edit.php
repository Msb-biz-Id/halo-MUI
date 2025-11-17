<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Edit Materi</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/material') ?>">Material</a></li>
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
                <h5 class="mb-0 text-dark"><i class="uil-edit"></i> Edit Materi</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/material/edit/' . $material['id']) ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">Judul Materi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" 
                               value="<?= htmlspecialchars($material['title']) ?>" required>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= $material['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label fw-bold">Tipe Konten <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="article" <?= $material['type'] == 'article' ? 'selected' : '' ?>>Article</option>
                                <option value="video" <?= $material['type'] == 'video' ? 'selected' : '' ?>>Video</option>
                                <option value="infographic" <?= $material['type'] == 'infographic' ? 'selected' : '' ?>>Infographic</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Deskripsi Singkat</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($material['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="form-label fw-bold">Konten Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="12" required><?= htmlspecialchars($material['content']) ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <?php if (!empty($material['thumbnail'])): ?>
                            <div class="mb-2">
                                <img src="<?= asset($material['thumbnail']) ?>" alt="Current thumbnail" style="max-width: 200px; height: auto;">
                                <p class="small text-muted">Current thumbnail</p>
                            </div>
                        <?php endif; ?>
                        <label for="thumbnail" class="form-label fw-bold">Update Thumbnail/Gambar</label>
                        <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                    </div>
                    
                    <div class="mb-4">
                        <label for="video_url" class="form-label fw-bold">URL Video</label>
                        <input type="url" class="form-control" id="video_url" name="video_url" 
                               value="<?= htmlspecialchars($material['video_url'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-4">
                        <label for="tags" class="form-label fw-bold">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="<?= htmlspecialchars($material['tags'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" 
                                   <?= $material['is_published'] ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="is_published">
                                Published
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Info:</strong> Views: <?= $material['view_count'] ?? 0 ?> | 
                        Created: <?= date('d M Y H:i', strtotime($material['created_at'])) ?>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Update Materi
                        </button>
                        <a href="<?= url('/admin/material') ?>" class="btn btn-secondary btn-lg">
                            <i class="uil-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
