<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Edit Tanya Jawab</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/qa') ?>">Q&A</a></li>
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
                <h5 class="mb-0 text-dark"><i class="uil-edit"></i> Edit Q&A</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/qa/edit/' . $qa['id']) ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="question" class="form-label fw-bold">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="question" name="question" 
                               value="<?= htmlspecialchars($qa['question']) ?>" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= $qa['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="answer" class="form-label fw-bold">Jawaban <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="answer" name="answer" rows="10" required><?= htmlspecialchars($qa['answer']) ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="references" class="form-label fw-bold">Referensi</label>
                        <textarea class="form-control" id="references" name="references" rows="4"><?= htmlspecialchars($qa['references'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="tags" class="form-label fw-bold">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="<?= htmlspecialchars($qa['tags'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" 
                                   <?= $qa['is_published'] ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="is_published">
                                Published
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Info:</strong> Views: <?= $qa['view_count'] ?> | 
                        Created: <?= date('d M Y H:i', strtotime($qa['created_at'])) ?>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Update Q&A
                        </button>
                        <a href="<?= url('/admin/qa') ?>" class="btn btn-secondary btn-lg">
                            <i class="uil-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
