<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= url('/') ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= url('/books') ?>">Books</a></li>
                            <li class="breadcrumb-item active"><?= htmlspecialchars($book['title']) ?></li>
                        </ol>
                    </nav>
                    
                    <h2 class="mb-3"><?= htmlspecialchars($book['title']) ?></h2>
                    <div class="mb-4">
                        <span class="badge bg-info"><?= htmlspecialchars($book['category_name']) ?></span>
                        <span class="text-muted ms-3">By <?= htmlspecialchars($book['author']) ?></span>
                        <span class="text-muted ms-3">•  <?= htmlspecialchars($book['publisher']) ?></span>
                    </div>
                    
                    <?php if (!empty($book['cover_image'])): ?>
                        <div class="text-center mb-4">
                            <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Book Cover" class="img-fluid" style="max-width: 300px;">
                        </div>
                    <?php endif; ?>
                    
                    <div class="prose">
                        <?= nl2br(htmlspecialchars($book['content'])) ?>
                    </div>
                    
                    <?php if (!empty($book['file_path'])): ?>
                        <div class="mt-4 text-center">
                            <a href="<?= htmlspecialchars($book['file_path']) ?>" class="btn btn-primary btn-lg" download>
                                <i class="uil-download-alt"></i> Download PDF
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
