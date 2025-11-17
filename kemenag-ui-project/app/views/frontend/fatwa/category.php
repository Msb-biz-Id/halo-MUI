<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">📜 Fatwa - <?= htmlspecialchars($category['name']) ?></h2>
            <p class="lead text-muted"><?= htmlspecialchars($category['description']) ?></p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <?php if (!empty($fatwas)): ?>
                <?php foreach ($fatwas as $fatwa): ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <span class="badge bg-success mb-2"><?= htmlspecialchars($fatwa['category_name']) ?></span>
                            <h5 class="card-title">
                                <a href="<?= url('/fatwa/' . $fatwa['id']) ?>"><?= htmlspecialchars($fatwa['title']) ?></a>
                            </h5>
                            <p class="card-text text-muted"><?= nl2br(htmlspecialchars(substr($fatwa['content'], 0, 200))) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="uil-calendar-alt"></i> <?= date('d M Y', strtotime($fatwa['published_at'])) ?>
                                </small>
                                <a href="<?= url('/fatwa/' . $fatwa['id']) ?>" class="btn btn-sm btn-outline-success">Read More</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">No fatwas in this category yet.</div>
            <?php endif; ?>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">Categories</h6>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($all_categories as $cat): ?>
                        <a href="<?= url('/fatwa/category/' . $cat['id']) ?>" 
                           class="list-group-item list-group-item-action <?= $cat['id'] == $category['id'] ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
