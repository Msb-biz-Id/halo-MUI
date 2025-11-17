<div class="container my-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="fw-bold mb-2"><i class="fas fa-book text-success"></i> Perpustakaan Digital</h2>
            <p class="text-muted">Koleksi buku keagamaan yang dapat diakses secara online</p>
        </div>
    </div>
    
    <!-- Search & Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="<?= url('/book') ?>" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="search" name="search" class="form-control" placeholder="Cari judul, penulis, atau penerbit..." value="<?= htmlspecialchars($search ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Books Grid -->
    <div class="row g-4">
        <?php if (!empty($books)): ?>
            <?php foreach ($books as $book): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <?php if (!empty($book['cover_image'])): ?>
                            <img src="<?= asset($book['cover_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>" style="height: 300px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                                 style="height: 300px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                <i class="fas fa-book-open fa-4x text-white opacity-50"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-success-subtle text-success mb-2 align-self-start">
                                <?= htmlspecialchars($book['category_name']) ?>
                            </span>
                            
                            <h6 class="card-title">
                                <a href="<?= url('/book/detail/' . $book['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= htmlspecialchars($book['title']) ?>
                                </a>
                            </h6>
                            
                            <p class="card-text small text-muted mb-2">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($book['author']) ?>
                            </p>
                            
                            <?php if (!empty($book['publisher'])): ?>
                                <p class="card-text small text-muted">
                                    <i class="fas fa-building"></i> <?= htmlspecialchars($book['publisher']) ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-download"></i> <?= $book['download_count'] ?? 0 ?>
                                    </small>
                                    <?php if (!empty($book['year'])): ?>
                                        <small class="text-muted"><?= $book['year'] ?></small>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= url('/book/detail/' . $book['slug']) ?>" class="btn btn-sm btn-success w-100">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-book fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada buku</h5>
                    <p class="text-muted">Coba ubah filter atau kata kunci pencarian</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Statistics -->
    <?php if (!empty($stats)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h3 class="fw-bold"><?= $stats['total_books'] ?? 0 ?></h3>
                                <p class="mb-0">Total Buku</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="fw-bold"><?= $stats['total_categories'] ?? 0 ?></h3>
                                <p class="mb-0">Kategori</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="fw-bold"><?= $stats['total_downloads'] ?? 0 ?></h3>
                                <p class="mb-0">Total Download</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2) !important;
}
</style>
