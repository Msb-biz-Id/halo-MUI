<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Edit Buku</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/books') ?>">Books</a></li>
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
                <h5 class="mb-0 text-dark"><i class="uil-edit"></i> Edit Buku</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/books/edit/' . $book['id']) ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold">Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" 
                               value="<?= htmlspecialchars($book['title']) ?>" required>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="author" class="form-label fw-bold">Penulis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="author" name="author" 
                                   value="<?= htmlspecialchars($book['author']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="publisher" class="form-label fw-bold">Penerbit</label>
                            <input type="text" class="form-control" id="publisher" name="publisher" 
                                   value="<?= htmlspecialchars($book['publisher'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="isbn" class="form-label fw-bold">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   value="<?= htmlspecialchars($book['isbn'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="year" class="form-label fw-bold">Tahun Terbit</label>
                            <input type="number" class="form-control" id="year" name="year" 
                                   value="<?= $book['year'] ?? '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="pages" class="form-label fw-bold">Jumlah Halaman</label>
                            <input type="number" class="form-control" id="pages" name="pages" 
                                   value="<?= $book['pages'] ?? '' ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= $book['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Deskripsi Buku <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?= htmlspecialchars($book['description']) ?></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <?php if (!empty($book['cover_image'])): ?>
                            <div class="mb-2">
                                <img src="<?= asset($book['cover_image']) ?>" alt="Cover" style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px;">
                                <p class="small text-muted">Current cover</p>
                            </div>
                        <?php endif; ?>
                        <label for="cover_image" class="form-label fw-bold">Update Cover Buku</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                    </div>
                    
                    <div class="mb-4">
                        <?php if (!empty($book['pdf_file'])): ?>
                            <div class="alert alert-info">
                                <i class="uil-file-check-alt"></i> PDF File: <?= basename($book['pdf_file']) ?>
                            </div>
                        <?php endif; ?>
                        <label for="pdf_file" class="form-label fw-bold">Update File PDF Buku</label>
                        <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept=".pdf">
                    </div>
                    
                    <div class="mb-4">
                        <label for="external_url" class="form-label fw-bold">URL Eksternal</label>
                        <input type="url" class="form-control" id="external_url" name="external_url" 
                               value="<?= htmlspecialchars($book['external_url'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-4">
                        <label for="tags" class="form-label fw-bold">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="<?= htmlspecialchars($book['tags'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_available" id="is_available" 
                                   <?= $book['is_available'] ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="is_available">
                                Available untuk Diunduh
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Info:</strong> Downloads: <?= $book['download_count'] ?? 0 ?> | 
                        Created: <?= date('d M Y H:i', strtotime($book['created_at'])) ?>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Update Buku
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
