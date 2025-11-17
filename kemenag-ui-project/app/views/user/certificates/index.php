<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📜 Sertifikat Halal Saya</h4>
            <a href="<?= url('/certificate/apply') ?>" class="btn btn-primary">
                <i class="uil-plus"></i> Ajukan Sertifikat Baru
            </a>
        </div>
    </div>
</div>

<div class="row">
    <?php if (!empty($certificates)): ?>
        <?php foreach ($certificates as $cert): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-<?= $cert['status_color'] ?> text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><?= $cert['certificate_number'] ?? 'Pending' ?></h6>
                            <span class="badge bg-light text-dark"><?= strtoupper($cert['status']) ?></span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h6 class="fw-bold mb-2"><?= htmlspecialchars($cert['company_name']) ?></h6>
                        <p class="text-muted small mb-2"><?= htmlspecialchars($cert['product_name']) ?></p>
                        
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="uil-calendar-alt"></i> Submitted: <?= date('d M Y', strtotime($cert['created_at'])) ?>
                            </small>
                        </div>
                        
                        <?php if ($cert['status'] === 'approved'): ?>
                            <div class="alert alert-success py-2">
                                <small>
                                    <i class="uil-check-circle"></i> 
                                    Approved on <?= date('d M Y', strtotime($cert['approved_at'])) ?>
                                </small>
                            </div>
                        <?php elseif ($cert['status'] === 'rejected'): ?>
                            <div class="alert alert-danger py-2">
                                <small>
                                    <i class="uil-times-circle"></i> 
                                    Rejected: <?= htmlspecialchars($cert['rejection_reason']) ?>
                                </small>
                            </div>
                        <?php elseif ($cert['status'] === 'pending'): ?>
                            <div class="alert alert-warning py-2">
                                <small>
                                    <i class="uil-clock"></i> Waiting for admin review
                                </small>
                            </div>
                        <?php elseif ($cert['status'] === 'in_review'): ?>
                            <div class="alert alert-info py-2">
                                <small>
                                    <i class="uil-search"></i> Currently under review
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <a href="<?= url('/user/certificates/detail/' . $cert['id']) ?>" class="btn btn-sm btn-outline-primary me-2">
                            <i class="uil-eye"></i> View Detail
                        </a>
                        
                        <?php if ($cert['status'] === 'approved' && !empty($cert['certificate_file'])): ?>
                            <a href="<?= asset($cert['certificate_file']) ?>" class="btn btn-sm btn-success" download>
                                <i class="uil-download-alt"></i> Download
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="uil-certificate font-size-48 text-muted mb-3"></i>
                    <h5 class="text-muted">Belum Ada Sertifikat</h5>
                    <p class="text-muted">Anda belum mengajukan sertifikat halal.</p>
                    <a href="<?= url('/certificate/apply') ?>" class="btn btn-primary mt-3">
                        <i class="uil-plus"></i> Ajukan Sekarang
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
