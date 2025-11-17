<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Tambah Materi Baru</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/material') ?>">Material</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="uil-book-reader"></i> Form Materi Moderasi</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/material/create') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">Judul Materi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" 
                               value="<?= old('title') ?>" required>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label fw-bold">Tipe Konten <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="article" <?= old('type') == 'article' ? 'selected' : '' ?>>Article (Text)</option>
                                <option value="video" <?= old('type') == 'video' ? 'selected' : '' ?>>Video</option>
                                <option value="infographic" <?= old('type') == 'infographic' ? 'selected' : '' ?>>Infographic (Image)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Deskripsi Singkat</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?></textarea>
                        <small class="text-muted">Ringkasan singkat yang akan ditampilkan di daftar</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="form-label fw-bold">Konten Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="12" required><?= old('content') ?></textarea>
                        <small class="text-muted">Untuk video, masukkan embed code YouTube</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="thumbnail" class="form-label fw-bold">Thumbnail/Gambar</label>
                        <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF (Max 2MB)</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="video_url" class="form-label fw-bold">URL Video (YouTube/Vimeo)</label>
                        <input type="url" class="form-control" id="video_url" name="video_url" 
                               value="<?= old('video_url') ?>" placeholder="https://youtube.com/watch?v=...">
                        <small class="text-muted">Khusus untuk tipe Video</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="tags" class="form-label fw-bold">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="<?= old('tags') ?>" placeholder="moderasi, toleransi, keagamaan">
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" 
                                   <?= old('is_published', '1') ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="is_published">
                                Publish Sekarang
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Simpan Materi
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
