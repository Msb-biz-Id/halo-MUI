<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h2 class="text-center mb-4">🔍 Search</h2>
            
            <form action="<?= url('/search') ?>" method="GET" class="mb-5">
                <div class="input-group input-group-lg shadow">
                    <input type="text" class="form-control" name="q" placeholder="Search articles, fatwas, Q&A..." 
                           value="<?= htmlspecialchars($query ?? '') ?>" required>
                    <button type="submit" class="btn btn-primary px-5">Search</button>
                </div>
            </form>
            
            <?php if (isset($results)): ?>
                <h4 class="mb-4">Results for "<?= htmlspecialchars($query) ?>"</h4>
                
                <div class="row">
                    <?php if (!empty($results['materials'])): ?>
                        <div class="col-12 mb-4">
                            <h5>📚 Materials (<?= count($results['materials']) ?>)</h5>
                            <?php foreach ($results['materials'] as $item): ?>
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h6><a href="<?= url('/material/' . $item['id']) ?>"><?= htmlspecialchars($item['title']) ?></a></h6>
                                        <small class="text-muted"><?= nl2br(htmlspecialchars(substr($item['content'], 0, 150))) ?>...</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($results['fatwas'])): ?>
                        <div class="col-12 mb-4">
                            <h5>📜 Fatwas (<?= count($results['fatwas']) ?>)</h5>
                            <?php foreach ($results['fatwas'] as $item): ?>
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h6><a href="<?= url('/fatwa/' . $item['id']) ?>"><?= htmlspecialchars($item['title']) ?></a></h6>
                                        <small class="text-muted"><?= nl2br(htmlspecialchars(substr($item['content'], 0, 150))) ?>...</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($results['qas'])): ?>
                        <div class="col-12 mb-4">
                            <h5>❓ Q&A (<?= count($results['qas']) ?>)</h5>
                            <?php foreach ($results['qas'] as $item): ?>
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h6><a href="<?= url('/qa/' . $item['id']) ?>"><?= htmlspecialchars($item['question']) ?></a></h6>
                                        <small class="text-muted"><?= nl2br(htmlspecialchars(substr($item['answer'], 0, 150))) ?>...</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (empty($results['materials']) && empty($results['fatwas']) && empty($results['qas'])): ?>
                    <div class="alert alert-info">
                        <i class="uil-info-circle"></i> No results found. Try different keywords.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
