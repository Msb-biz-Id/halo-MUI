<div class="container my-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="fw-bold mb-2"><i class="fas fa-book-reader text-primary"></i> Materi Moderasi & Toleransi</h2>
            <p class="text-muted">Konten edukatif tentang moderasi beragama dan toleransi</p>
        </div>
    </div>
    
    <!-- Categories -->
    <?php if (!empty($categories)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-folder"></i> Kategori Materi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach ($categories as $category): ?>
                                <div class="col-md-4">
                                    <a href="<?= url('/material/category/' . $category['id']) ?>" 
                                       class="btn btn-outline-primary w-100 text-start">
                                        <?= htmlspecialchars($category['name']) ?>
                                        <span class="badge bg-primary float-end"><?= $category['material_count'] ?? 0 ?></span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Filter by Type -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="<?= url('/material') ?>" class="btn btn-outline-secondary <?= !isset($_GET['type']) ? 'active' : '' ?>">
                    <i class="fas fa-th"></i> Semua
                </a>
                <a href="<?= url('/material?type=article') ?>" class="btn btn-outline-secondary <?= $_GET['type'] ?? '' == 'article' ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i> Article
                </a>
                <a href="<?= url('/material?type=video') ?>" class="btn btn-outline-secondary <?= $_GET['type'] ?? '' == 'video' ? 'active' : '' ?>">
                    <i class="fas fa-video"></i> Video
                </a>
                <a href="<?= url('/material?type=infographic') ?>" class="btn btn-outline-secondary <?= $_GET['type'] ?? '' == 'infographic' ? 'active' : '' ?>">
                    <i class="fas fa-image"></i> Infographic
                </a>
            </div>
        </div>
    </div>
    
    <!-- Materials Grid -->
    <div class="row g-4">
        <?php if (!empty($materials)): ?>
            <?php foreach ($materials as $material): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm hover-card">
                        <?php if (!empty($material['thumbnail'])): ?>
                            <img src="<?= asset($material['thumbnail']) ?>" class="card-img-top" alt="<?= htmlspecialchars($material['title']) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                                 style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-book-reader fa-4x text-white opacity-50"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary-subtle text-primary">
                                    <?= htmlspecialchars($material['category_name']) ?>
                                </span>
                                <?php
                                $typeIcons = ['article' => 'file-alt', 'video' => 'video', 'infographic' => 'image'];
                                $typeColors = ['article' => 'info', 'video' => 'danger', 'infographic' => 'warning'];
                                ?>
                                <span class="badge bg-<?= $typeColors[$material['type']] ?? 'secondary' ?>">
                                    <i class="fas fa-<?= $typeIcons[$material['type']] ?? 'file' ?>"></i>
                                </span>
                            </div>
                            
                            <h5 class="card-title">
                                <a href="<?= url('/material/detail/' . $material['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= htmlspecialchars($material['title']) ?>
                                </a>
                            </h5>
                            
                            <p class="card-text text-muted small">
                                <?= truncate(strip_tags($material['description'] ?? $material['content']), 100) ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-eye"></i> <?= $material['view_count'] ?? 0 ?> views
                                </small>
                                <a href="<?= url('/material/detail/' . $material['slug']) ?>" class="btn btn-sm btn-outline-primary">
                                    Baca Selengkapnya →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada materi</h5>
                </div>
            </div>
        <?php endif; ?>
    </div>
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
