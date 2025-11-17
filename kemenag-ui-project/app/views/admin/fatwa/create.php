<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Tambah Fatwa Baru</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/fatwa') ?>">Fatwa</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="uil-balance-scale"></i> Form Fatwa Baru</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/fatwa/create') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="title" class="form-label fw-bold">Judul Fatwa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" 
                                   value="<?= old('title') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="fatwa_number" class="form-label fw-bold">Nomor Fatwa</label>
                            <input type="text" class="form-control" id="fatwa_number" name="fatwa_number" 
                                   value="<?= old('fatwa_number') ?>" placeholder="Contoh: 01/2024">
                            <small class="text-muted">Opsional</small>
                        </div>
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
                        <label for="summary" class="form-label fw-bold">Ringkasan</label>
                        <textarea class="form-control" id="summary" name="summary" rows="3" 
                                  placeholder="Ringkasan singkat fatwa..."><?= old('summary') ?></textarea>
                        <small class="text-muted">Ringkasan akan ditampilkan di daftar fatwa</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="form-label fw-bold">Isi Fatwa <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="12" 
                                  placeholder="Tulis isi fatwa lengkap di sini..." required><?= old('content') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="legal_basis" class="form-label fw-bold">Dasar Hukum</label>
                        <textarea class="form-control" id="legal_basis" name="legal_basis" rows="5" 
                                  placeholder="Dasar hukum yang digunakan..."><?= old('legal_basis') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="references" class="form-label fw-bold">Referensi</label>
                        <textarea class="form-control" id="references" name="references" rows="4" 
                                  placeholder="Referensi dan sumber..."><?= old('references') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="tags" class="form-label fw-bold">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="<?= old('tags') ?>" placeholder="hukum, ibadah, muamalah (pisahkan dengan koma)">
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
                        <button type="submit" class="btn btn-danger btn-lg px-5">
                            <i class="uil-check"></i> Simpan Fatwa
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
