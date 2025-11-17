<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Tambah Tanya Jawab Baru</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/qa') ?>">Q&A</a></li>
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
                <h5 class="mb-0"><i class="uil-question-circle"></i> Form Tanya Jawab</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/qa/create') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="question" class="form-label fw-bold">Pertanyaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="question" name="question" 
                               value="<?= old('question') ?>" placeholder="Contoh: Bagaimana cara mendapatkan sertifikat halal?" required>
                    </div>
                    
                    <div class="mb-4">
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
                    
                    <div class="mb-4">
                        <label for="answer" class="form-label fw-bold">Jawaban <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="answer" name="answer" rows="10" 
                                  placeholder="Tulis jawaban lengkap di sini..." required><?= old('answer') ?></textarea>
                        <small class="text-muted">Minimal 100 karakter</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="references" class="form-label fw-bold">Referensi</label>
                        <textarea class="form-control" id="references" name="references" rows="4" 
                                  placeholder="Contoh: Al-Quran Surah ..., Hadist ..., dll"><?= old('references') ?></textarea>
                        <small class="text-muted">Sumber hukum atau referensi (opsional)</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="tags" class="form-label fw-bold">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="<?= old('tags') ?>" placeholder="halal, sertifikat, makanan (pisahkan dengan koma)">
                        <small class="text-muted">Untuk memudahkan pencarian</small>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" 
                                   <?= old('is_published', '1') ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="is_published">
                                Publish Sekarang
                            </label>
                        </div>
                        <small class="text-muted">Jika tidak dicentang, akan disimpan sebagai draft</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Simpan Q&A
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
