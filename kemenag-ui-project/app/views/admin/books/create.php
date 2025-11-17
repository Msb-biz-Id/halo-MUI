<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Tambah Buku Baru</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/books') ?>">Books</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="uil-book-open"></i> Form Buku Baru</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/books/create') ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" 
                               value="<?= old('title') ?>" required>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="author" class="form-label fw-bold">Penulis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="author" name="author" 
                                   value="<?= old('author') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="publisher" class="form-label fw-bold">Penerbit</label>
                            <input type="text" class="form-control" id="publisher" name="publisher" 
                                   value="<?= old('publisher') ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="isbn" class="form-label fw-bold">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   value="<?= old('isbn') ?>" placeholder="978-...">
                        </div>
                        <div class="col-md-4">
                            <label for="year" class="form-label fw-bold">Tahun Terbit</label>
                            <input type="number" class="form-control" id="year" name="year" 
                                   value="<?= old('year') ?>" min="1900" max="<?= date('Y') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="pages" class="form-label fw-bold">Jumlah Halaman</label>
                            <input type="number" class="form-control" id="pages" name="pages" 
                                   value="<?= old('pages') ?>" min="1">
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
                        <label for="description" class="form-label fw-bold">Deskripsi Buku <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?= old('description') ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="cover_image" class="form-label fw-bold">Cover Buku</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG (Max 2MB) - Rekomendasi ukuran: 300x450px</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="pdf_file" class="form-label fw-bold">File PDF Buku</label>
                        <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept=".pdf">
                        <small class="text-muted">Format: PDF (Max 50MB)</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="external_url" class="form-label fw-bold">URL Eksternal</label>
                        <input type="url" class="form-control" id="external_url" name="external_url" 
                               value="<?= old('external_url') ?>" placeholder="https://...">
                        <small class="text-muted">Link ke sumber eksternal jika tidak upload PDF</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="tags" class="form-label fw-bold">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="<?= old('tags') ?>" placeholder="islam, fiqih, hadist">
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_available" id="is_available" 
                                   <?= old('is_available', '1') ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="is_available">
                                Available untuk Diunduh
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="uil-check"></i> Simpan Buku
                        </button>
                        <a href="<?= url('/admin/books') ?>" class="btn btn-secondary btn-lg">
                            <i class="uil-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
