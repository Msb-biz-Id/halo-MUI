<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📖 Manajemen Perpustakaan Digital</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Books</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="<?= url('/admin/books/create') ?>" class="btn btn-success">
                            <i class="uil-plus"></i> Tambah Buku Baru
                        </a>
                    </div>
                    <div>
                        <form action="<?= url('/admin/books') ?>" method="GET" class="d-flex gap-2">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <input type="search" name="search" class="form-control" placeholder="Cari buku, penulis, penerbit..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="uil-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Books Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Daftar Buku (<?= count($books) ?>)</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Cover</th>
                                <th>Buku</th>
                                <th>Penulis/Penerbit</th>
                                <th>Kategori</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Downloads</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($books)): ?>
                                <?php foreach ($books as $book): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($book['cover_image'])): ?>
                                                <img src="<?= asset($book['cover_image']) ?>" alt="Cover" style="width: 40px; height: 60px; object-fit: cover; border-radius: 3px;">
                                            <?php else: ?>
                                                <div style="width: 40px; height: 60px; background: #e9ecef; border-radius: 3px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="uil-book-open"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($book['title']) ?></div>
                                            <?php if (!empty($book['isbn'])): ?>
                                                <small class="text-muted">ISBN: <?= $book['isbn'] ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div><?= htmlspecialchars($book['author']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($book['publisher'] ?? '-') ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">
                                                <?= htmlspecialchars($book['category_name']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($book['is_available']): ?>
                                                <span class="badge bg-success">Available</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Unavailable</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="uil-download-alt"></i> <?= $book['download_count'] ?? 0 ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="uil-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/book/detail/' . $book['slug']) ?>" target="_blank">
                                                            <i class="uil-eye me-2"></i> View Public
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/books/edit/' . $book['id']) ?>">
                                                            <i class="uil-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="<?= url('/admin/books/delete/' . $book['id']) ?>"
                                                           onclick="return confirm('Hapus buku ini?')">
                                                            <i class="uil-trash me-2"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="uil-book font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">Belum ada buku</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
