<!-- Welcome Section -->
<div class="row">
    <div class="col-12">
        <div class="stat-card bg-gradient" style="background: linear-gradient(135deg, #006837 0%, #004d28 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-white mb-2">Selamat Datang, <?= htmlspecialchars($user['full_name']) ?>!</h4>
                    <p class="mb-0 text-white-50">Role: <?= htmlspecialchars($user['role_name']) ?></p>
                </div>
                <div>
                    <i class="fas fa-user-circle fa-5x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Total Sertifikat</h6>
                    <h3 class="mb-0"><?= $stats['total_certificates'] ?? 0 ?></h3>
                </div>
                <div class="icon icon-primary">
                    <i class="fas fa-certificate"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Topik Forum</h6>
                    <h3 class="mb-0"><?= $stats['total_forum_topics'] ?? 0 ?></h3>
                </div>
                <div class="icon icon-success">
                    <i class="fas fa-comments"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Pesan</h6>
                    <h3 class="mb-0"><?= $stats['unread_messages'] ?? 0 ?></h3>
                </div>
                <div class="icon icon-warning">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">Notifikasi</h6>
                    <h3 class="mb-0"><?= $stats['unread_notifications'] ?? 0 ?></h3>
                </div>
                <div class="icon icon-info">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="stat-card">
            <h5 class="mb-3"><i class="fas fa-bolt text-warning"></i> Aksi Cepat</h5>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= url('/certificate/apply') ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Ajukan Sertifikat Halal
                </a>
                <a href="<?= url('/certificate/track') ?>" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i> Lacak Sertifikat
                </a>
                <a href="<?= url('/forum/create-topic') ?>" class="btn btn-success">
                    <i class="fas fa-comment-dots"></i> Buat Topik Forum
                </a>
                <a href="<?= url('/messages/new') ?>" class="btn btn-outline-success">
                    <i class="fas fa-paper-plane"></i> Kirim Pesan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Certificates -->
    <div class="col-md-6">
        <div class="stat-card">
            <h5 class="mb-3"><i class="fas fa-certificate text-primary"></i> Sertifikat Terbaru</h5>
            
            <?php if (!empty($recent_certificates)): ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($recent_certificates as $cert): ?>
                        <a href="<?= url('/certificate/detail/' . $cert['id']) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?= htmlspecialchars($cert['product_name']) ?></h6>
                                <small class="text-muted"><?= timeAgo($cert['submitted_at']) ?></small>
                            </div>
                            <p class="mb-1 small"><?= htmlspecialchars($cert['company_name']) ?></p>
                            <span class="badge bg-<?= $cert['status'] === 'completed' ? 'success' : ($cert['status'] === 'rejected' ? 'danger' : 'warning') ?>">
                                <?= ucfirst($cert['status']) ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-3">
                    <a href="<?= url('/dashboard/certificates') ?>" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-certificate fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada pengajuan sertifikat</p>
                    <a href="<?= url('/certificate/apply') ?>" class="btn btn-sm btn-primary">Ajukan Sekarang</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Forum Topics -->
    <div class="col-md-6">
        <div class="stat-card">
            <h5 class="mb-3"><i class="fas fa-comments text-success"></i> Topik Forum Saya</h5>
            
            <?php if (!empty($recent_forum_topics)): ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($recent_forum_topics as $topic): ?>
                        <a href="<?= url('/forum/topic/' . $topic['id']) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><?= htmlspecialchars($topic['title']) ?></h6>
                                <small class="text-muted"><?= timeAgo($topic['created_at']) ?></small>
                            </div>
                            <p class="mb-1 small text-muted">
                                <i class="fas fa-comments"></i> <?= $topic['reply_count'] ?? 0 ?> balasan • 
                                <i class="fas fa-eye"></i> <?= $topic['view_count'] ?? 0 ?> views
                            </p>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-3">
                    <a href="<?= url('/dashboard/forum-topics') ?>" class="btn btn-sm btn-outline-success">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada topik forum</p>
                    <a href="<?= url('/forum/create-topic') ?>" class="btn btn-sm btn-success">Buat Topik</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="stat-card">
            <h5 class="mb-3"><i class="fas fa-history text-info"></i> Aktivitas Terakhir</h5>
            
            <?php if (!empty($recent_activity)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Aktivitas</th>
                                <th>Detail</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_activity as $activity): ?>
                                <tr>
                                    <td>
                                        <i class="fas fa-<?= $activity['icon'] ?? 'circle' ?> text-<?= $activity['color'] ?? 'primary' ?>"></i>
                                        <?= htmlspecialchars($activity['action']) ?>
                                    </td>
                                    <td class="text-muted small"><?= htmlspecialchars($activity['description']) ?></td>
                                    <td class="text-muted small"><?= timeAgo($activity['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-3">Belum ada aktivitas</p>
            <?php endif; ?>
        </div>
    </div>
</div>
