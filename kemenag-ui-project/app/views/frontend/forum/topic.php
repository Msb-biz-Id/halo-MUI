<div class="container my-5">
    <!-- Topic Header -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h2 class="fw-bold mb-2">
                        <?php if ($topic['is_pinned']): ?>
                            <i class="fas fa-thumbtack text-warning"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($topic['title']) ?>
                        <?php if ($topic['is_locked']): ?>
                            <i class="fas fa-lock text-danger"></i>
                        <?php endif; ?>
                    </h2>
                    <p class="text-muted mb-0">
                        <a href="<?= url('/forum/category/' . $topic['category_id']) ?>" class="badge bg-primary text-decoration-none">
                            <?= htmlspecialchars($topic['category_name']) ?>
                        </a>
                        <span class="mx-2">•</span>
                        <i class="fas fa-user"></i> <?= htmlspecialchars($topic['full_name'] ?? $topic['username']) ?>
                        <span class="mx-2">•</span>
                        <i class="fas fa-clock"></i> <?= timeAgo($topic['created_at']) ?>
                        <span class="mx-2">•</span>
                        <i class="fas fa-eye"></i> <?= $topic['view_count'] ?> views
                    </p>
                </div>
                
                <?php if (!$topic['is_approved']): ?>
                    <span class="badge bg-warning">Pending Approval</span>
                <?php endif; ?>
            </div>
            
            <div class="topic-content mt-4">
                <?= nl2br(htmlspecialchars($topic['content'])) ?>
            </div>
        </div>
    </div>
    
    <!-- Posts/Replies -->
    <h5 class="fw-bold mb-3">
        <i class="fas fa-comments"></i> Balasan (<?= count($posts) ?>)
    </h5>
    
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $index => $post): ?>
            <div class="card border-0 shadow-sm mb-3" id="post-<?= $post['id'] ?>">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; font-size: 20px;">
                                <?= strtoupper(substr($post['username'], 0, 1)) ?>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($post['full_name'] ?? $post['username']) ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> <?= timeAgo($post['created_at']) ?>
                                        <?php if ($post['updated_at'] > $post['created_at']): ?>
                                            <span class="text-warning">(diedit)</span>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                
                                <?php if (auth() && user('id') == $post['user_id']): ?>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="<?= url('/forum/edit-post/' . $post['id']) ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </a></li>
                                            <li><a class="dropdown-item text-danger" href="<?= url('/forum/delete-post/' . $post['id']) ?>" 
                                                   onclick="return confirm('Yakin ingin menghapus balasan ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a></li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="post-content">
                                <?= nl2br(htmlspecialchars($post['content'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Belum ada balasan. Jadilah yang pertama berkomentar!
        </div>
    <?php endif; ?>
    
    <!-- Reply Form -->
    <?php if (auth()): ?>
        <?php if (!$topic['is_locked']): ?>
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-reply"></i> Balas Topik Ini</h5>
                </div>
                <div class="card-body">
                    <form action="<?= url('/forum/reply/' . $topic['id']) ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="mb-3">
                            <label for="reply_content" class="form-label">Balasan Anda</label>
                            <textarea class="form-control" id="reply_content" name="content" rows="5" 
                                      placeholder="Tulis balasan Anda..." required></textarea>
                            <small class="text-muted">
                                ⚠️ Perhatian: Balasan yang mengandung kata terlarang akan otomatis ditolak
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Kirim Balasan
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-lock"></i> Topik ini telah dikunci. Anda tidak dapat menambahkan balasan.
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-sign-in-alt"></i> 
            <a href="<?= url('/login') ?>" class="alert-link fw-bold">Login</a> atau 
            <a href="<?= url('/register') ?>" class="alert-link fw-bold">Daftar</a> 
            untuk membalas topik ini
        </div>
    <?php endif; ?>
</div>
