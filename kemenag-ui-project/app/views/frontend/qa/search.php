<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="text-center mb-4">🔍 Search Q&A</h2>
            
            <form action="<?= url('/qa/search') ?>" method="GET" class="mb-5">
                <div class="input-group input-group-lg shadow">
                    <input type="text" class="form-control" name="q" placeholder="Search questions..." 
                           value="<?= htmlspecialchars($query ?? '') ?>" required>
                    <button type="submit" class="btn btn-warning px-5">Search</button>
                </div>
            </form>
            
            <?php if (isset($results)): ?>
                <h4 class="mb-4">Found <?= count($results) ?> result(s) for "<?= htmlspecialchars($query) ?>"</h4>
                
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $qa): ?>
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <span class="badge bg-warning text-dark mb-2"><?= htmlspecialchars($qa['category_name']) ?></span>
                                <h5 class="card-title">
                                    <a href="<?= url('/qa/' . $qa['id']) ?>"><?= htmlspecialchars($qa['question']) ?></a>
                                </h5>
                                <p class="card-text text-muted"><?= nl2br(htmlspecialchars(substr($qa['answer'], 0, 200))) ?>...</p>
                                <a href="<?= url('/qa/' . $qa['id']) ?>" class="btn btn-sm btn-outline-warning">Read More</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        No results found. Try different keywords.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
