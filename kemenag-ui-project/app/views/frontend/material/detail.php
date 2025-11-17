<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-lg">
                <?php if (!empty($material['thumbnail'])): ?>
                    <img src="<?= asset($material['thumbnail']) ?>" class="card-img-top" alt="<?= htmlspecialchars($material['title']) ?>" style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>
                
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary-subtle text-primary me-2">
                                <i class="fas fa-tag"></i> <?= htmlspecialchars($material['category_name']) ?>
                            </span>
                            <?php
                            $typeIcons = ['article' => 'file-alt', 'video' => 'video', 'infographic' => 'image'];
                            $typeColors = ['article' => 'info', 'video' => 'danger', 'infographic' => 'warning'];
                            ?>
                            <span class="badge bg-<?= $typeColors[$material['type']] ?>">
                                <i class="fas fa-<?= $typeIcons[$material['type']] ?>"></i> <?= ucfirst($material['type']) ?>
                            </span>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($material['created_at'])) ?>
                            <span class="mx-2">•</span>
                            <i class="fas fa-eye"></i> <?= $material['view_count'] ?? 0 ?> views
                        </small>
                    </div>
                    
                    <h2 class="fw-bold mb-3"><?= htmlspecialchars($material['title']) ?></h2>
                    
                    <?php if (!empty($material['description'])): ?>
                        <div class="alert alert-info">
                            <?= nl2br(htmlspecialchars($material['description'])) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($material['type'] == 'video' && !empty($material['video_url'])): ?>
                        <div class="ratio ratio-16x9 mb-4">
                            <?php
                            // Convert YouTube URL to embed
                            $videoUrl = $material['video_url'];
                            if (strpos($videoUrl, 'youtube.com/watch') !== false) {
                                parse_str(parse_url($videoUrl, PHP_URL_QUERY), $params);
                                $videoId = $params['v'] ?? '';
                                $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                            } elseif (strpos($videoUrl, 'youtu.be') !== false) {
                                $videoId = basename(parse_url($videoUrl, PHP_URL_PATH));
                                $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                            } else {
                                $embedUrl = $videoUrl;
                            }
                            ?>
                            <iframe src="<?= $embedUrl ?>" allowfullscreen></iframe>
                        </div>
                    <?php endif; ?>
                    
                    <div class="material-content">
                        <?= nl2br(htmlspecialchars($material['content'])) ?>
                    </div>
                    
                    <?php if (!empty($material['tags'])): ?>
                        <hr class="my-4">
                        <div>
                            <strong><i class="fas fa-tags"></i> Tags:</strong>
                            <?php
                            $tags = explode(',', $material['tags']);
                            foreach ($tags as $tag):
                            ?>
                                <span class="badge bg-secondary-subtle text-secondary me-1"><?= trim($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-share-alt"></i> Share
                            </button>
                        </div>
                        <a href="<?= url('/material') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Material Info -->
            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Detail Materi</h6>
                    <p class="mb-2"><strong>Tipe:</strong> <?= ucfirst($material['type']) ?></p>
                    <p class="mb-2"><strong>Kategori:</strong> <?= htmlspecialchars($material['category_name']) ?></p>
                    <p class="mb-2"><strong>Tanggal:</strong> <?= date('d F Y', strtotime($material['created_at'])) ?></p>
                    <p class="mb-0"><strong>Views:</strong> <?= $material['view_count'] ?? 0 ?></p>
                </div>
            </div>
            
            <!-- Related Materials -->
            <?php if (!empty($related)): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-link"></i> Materi Terkait</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($related as $rel): ?>
                            <a href="<?= url('/material/detail/' . $rel['slug']) ?>" class="list-group-item list-group-item-action">
                                <h6 class="mb-1 small"><?= htmlspecialchars($rel['title']) ?></h6>
                                <span class="badge bg-<?= $typeColors[$rel['type']] ?? 'secondary' ?> small">
                                    <i class="fas fa-<?= $typeIcons[$rel['type']] ?? 'file' ?>"></i>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Category Link -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Kategori</h6>
                    <a href="<?= url('/material/category/' . $material['category_id']) ?>" class="btn btn-outline-primary w-100">
                        <i class="fas fa-folder"></i> Lihat semua di <?= htmlspecialchars($material['category_name']) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.material-content {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #333;
}
</style>
