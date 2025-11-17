<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Tambah Kategori Baru</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/categories') ?>">Categories</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="uil-folder-plus"></i> Form Kategori</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/categories/create') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="type" class="form-label fw-bold">Tipe Konten <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg" id="type" name="type" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="qa">Q&A (Tanya Jawab)</option>
                            <option value="fatwa">Fatwa</option>
                            <option value="material">Material (Materi Moderasi)</option>
                            <option value="book">Book (Perpustakaan)</option>
                            <option value="forum">Forum</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="name" class="form-label fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name" 
                               value="<?= old('name') ?>" required placeholder="Contoh: Halal & Haram">
                    </div>
                    
                    <div class="mb-4">
                        <label for="slug" class="form-label fw-bold">Slug <small class="text-muted">(Opsional, auto-generate)</small></label>
                        <input type="text" class="form-control" id="slug" name="slug" 
                               value="<?= old('slug') ?>" placeholder="halal-haram">
                        <small class="text-muted">URL-friendly version dari nama (otomatis dibuat jika kosong)</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?= old('description') ?></textarea>
                        <small class="text-muted">Penjelasan singkat tentang kategori ini</small>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                            <label class="form-check-label fw-bold" for="is_active">
                                Aktifkan Kategori
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Simpan Kategori
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
