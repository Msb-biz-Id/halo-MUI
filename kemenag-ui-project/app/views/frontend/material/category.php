<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">📚 <?= htmlspecialchars($category['name']) ?></h2>
            <p class="lead text-muted"><?= htmlspecialchars($category['description']) ?></p>
        </div>
    </div>
    
    <div class="row">
        <?php if (!empty($materials)): ?>
            <?php foreach ($materials as $material): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm hover-lift">
                        <?php if (!empty($material['image'])): ?>
                            <img src="<?= htmlspecialchars($material['image']) ?>" class="card-img-top" alt="Material">
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="badge bg-primary mb-2"><?= htmlspecialchars($material['category_name']) ?></span>
                            <h5 class="card-title">
                                <a href="<?= url('/material/' . $material['id']) ?>"><?= htmlspecialchars($material['title']) ?></a>
                            </h5>
                            <p class="card-text text-muted"><?= nl2br(htmlspecialchars(substr($material['content'], 0, 150))) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?= date('d M Y', strtotime($material['published_at'])) ?></small>
                                <a href="<?= url('/material/' . $material['id']) ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">No materials in this category yet.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
