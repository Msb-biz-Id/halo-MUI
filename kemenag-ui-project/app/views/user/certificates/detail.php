<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Detail Sertifikat</h4>
            <a href="<?= url('/user/certificates') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="uil-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-<?= $certificate['status_color'] ?> text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <?= $certificate['certificate_number'] ?? 'Application #' . $certificate['id'] ?>
                    </h5>
                    <span class="badge bg-light text-dark fs-6">
                        <?= strtoupper($certificate['status']) ?>
                    </span>
                </div>
            </div>
            
            <div class="card-body">
                <h6 class="fw-bold mb-3">Company Information</h6>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Company Name:</div>
                    <div class="col-md-8"><?= htmlspecialchars($certificate['company_name']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Address:</div>
                    <div class="col-md-8"><?= htmlspecialchars($certificate['company_address']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">NPWP:</div>
                    <div class="col-md-8"><?= htmlspecialchars($certificate['company_npwp']) ?></div>
                </div>
                
                <hr class="my-4">
                
                <h6 class="fw-bold mb-3">Product Information</h6>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Product Name:</div>
                    <div class="col-md-8"><?= htmlspecialchars($certificate['product_name']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Description:</div>
                    <div class="col-md-8"><?= nl2br(htmlspecialchars($certificate['product_description'])) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Category:</div>
                    <div class="col-md-8">
                        <span class="badge bg-primary"><?= htmlspecialchars($certificate['category']) ?></span>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <h6 class="fw-bold mb-3">Supporting Documents</h6>
                <?php if (!empty($certificate['documents'])): ?>
                    <div class="list-group">
                        <?php foreach (json_decode($certificate['documents'], true) as $doc): ?>
                            <a href="<?= asset($doc['path']) ?>" class="list-group-item list-group-item-action" target="_blank">
                                <i class="uil-file-alt"></i> <?= htmlspecialchars($doc['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No documents uploaded</p>
                <?php endif; ?>
                
                <?php if ($certificate['status'] === 'approved'): ?>
                    <hr class="my-4">
                    <div class="alert alert-success">
                        <h6 class="fw-bold"><i class="uil-check-circle"></i> Certificate Approved!</h6>
                        <p class="mb-2">Approved by: <strong><?= htmlspecialchars($certificate['approved_by_name']) ?></strong></p>
                        <p class="mb-2">Approved on: <?= date('d F Y H:i', strtotime($certificate['approved_at'])) ?></p>
                        <?php if (!empty($certificate['certificate_file'])): ?>
                            <a href="<?= asset($certificate['certificate_file']) ?>" class="btn btn-success mt-3" download>
                                <i class="uil-download-alt"></i> Download Certificate
                            </a>
                        <?php endif; ?>
                    </div>
                <?php elseif ($certificate['status'] === 'rejected'): ?>
                    <hr class="my-4">
                    <div class="alert alert-danger">
                        <h6 class="fw-bold"><i class="uil-times-circle"></i> Application Rejected</h6>
                        <p class="mb-0"><strong>Reason:</strong> <?= nl2br(htmlspecialchars($certificate['rejection_reason'])) ?></p>
                        <a href="<?= url('/certificate/apply') ?>" class="btn btn-primary mt-3">
                            <i class="uil-redo"></i> Submit New Application
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">Application Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <i class="uil-check-circle text-success"></i>
                        <div>
                            <strong>Submitted</strong>
                            <br><small><?= date('d M Y H:i', strtotime($certificate['created_at'])) ?></small>
                        </div>
                    </div>
                    
                    <?php if ($certificate['status'] !== 'pending'): ?>
                        <div class="timeline-item">
                            <i class="uil-search text-info"></i>
                            <div>
                                <strong>Under Review</strong>
                                <br><small><?= date('d M Y', strtotime($certificate['updated_at'])) ?></small>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($certificate['status'] === 'approved'): ?>
                        <div class="timeline-item">
                            <i class="uil-check text-success"></i>
                            <div>
                                <strong>Approved</strong>
                                <br><small><?= date('d M Y H:i', strtotime($certificate['approved_at'])) ?></small>
                            </div>
                        </div>
                    <?php elseif ($certificate['status'] === 'rejected'): ?>
                        <div class="timeline-item">
                            <i class="uil-times text-danger"></i>
                            <div>
                                <strong>Rejected</strong>
                                <br><small><?= date('d M Y', strtotime($certificate['updated_at'])) ?></small>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Need Help?</h6>
                <p class="small text-muted mb-3">
                    Jika Anda memiliki pertanyaan tentang aplikasi sertifikat ini, hubungi kami.
                </p>
                <a href="<?= url('/contact') ?>" class="btn btn-sm btn-outline-primary w-100">
                    <i class="uil-envelope"></i> Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:before {
    content: '';
    position: absolute;
    left: -22px;
    top: 5px;
    width: 2px;
    height: 100%;
    background: #dee2e6;
}
.timeline-item:last-child:before {
    display: none;
}
.timeline-item i {
    position: absolute;
    left: -30px;
    top: 0;
    font-size: 20px;
}
</style>
