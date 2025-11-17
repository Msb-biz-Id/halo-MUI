<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h2 class="page-title">🔍 Search Forums</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <form action="<?= url('/forum/search') ?>" method="GET" class="mb-4">
            <div class="input-group input-group-lg">
                <input type="text" class="form-control" name="q" placeholder="Search topics..." 
                       value="<?= htmlspecialchars($query ?? '') ?>" required>
                <button type="submit" class="btn btn-primary px-4">Search</button>
            </div>
        </form>
        
        <?php if (isset($results)): ?>
            <h5 class="mb-3">Found <?= count($results) ?> result(s) for "<?= htmlspecialchars($query) ?>"</h5>
            
            <?php if (!empty($results)): ?>
                <?php foreach ($results as $topic): ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-2">
                                <a href="<?= url('/forum/topic/' . $topic['id']) ?>">
                                    <?= htmlspecialchars($topic['title']) ?>
                                </a>
                            </h5>
                            <p class="text-muted mb-2"><?= nl2br(htmlspecialchars(substr($topic['content'], 0, 200))) ?>...</p>
                            <small class="text-muted">
                                By <?= htmlspecialchars($topic['author_name']) ?> •
                                <?= timeAgo($topic['created_at']) ?>
                            </small>
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
