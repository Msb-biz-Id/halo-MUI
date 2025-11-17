<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Certificate Detail: <?= $certificate['ticket_number'] ?></h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/certificates') ?>">Certificates</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Certificate Info -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="uil-file-alt"></i> Certificate Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Ticket Number:</strong><br>
                        <span class="text-primary fs-5"><?= $certificate['ticket_number'] ?></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong><br>
                        <?php
                        $statusColors = ['pending' => 'warning', 'in_review' => 'info', 'approved' => 'success', 'rejected' => 'danger', 'completed' => 'primary'];
                        $statusColor = $statusColors[$certificate['status']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $statusColor ?> font-size-14">
                            <?= ucfirst(str_replace('_', ' ', $certificate['status'])) ?>
                        </span>
                    </div>
                </div>
                
                <hr>
                
                <h6 class="fw-bold mb-3">Company & Product Details</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Company Name:</strong><br>
                        <?= htmlspecialchars($certificate['company_name']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Product Name:</strong><br>
                        <?= htmlspecialchars($certificate['product_name']) ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Product Description:</strong><br>
                        <?= nl2br(htmlspecialchars($certificate['product_description'])) ?>
                    </div>
                </div>
                
                <hr>
                
                <h6 class="fw-bold mb-3">Applicant Information</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Name:</strong><br>
                        <?= htmlspecialchars($certificate['applicant_name']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong><br>
                        <?= $certificate['applicant_email'] ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Phone:</strong><br>
                        <?= $certificate['applicant_phone'] ?? '-' ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Priority:</strong><br>
                        <?php
                        $priorityColors = ['low' => 'secondary', 'normal' => 'info', 'high' => 'warning'];
                        $priorityColor = $priorityColors[$certificate['priority']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $priorityColor ?>">
                            <?= ucfirst($certificate['priority']) ?>
                        </span>
                    </div>
                </div>
                
                <hr>
                
                <h6 class="fw-bold mb-3">Documents</h6>
                <?php if (!empty($certificate['documents_array'])): ?>
                    <div class="list-group">
                        <?php foreach ($certificate['documents_array'] as $doc): ?>
                            <a href="<?= asset($doc['path']) ?>" class="list-group-item list-group-item-action" target="_blank">
                                <i class="uil-file-download me-2"></i> <?= $doc['name'] ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No documents uploaded</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Status History -->
        <?php if (!empty($history)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="uil-history"></i> Status History</h5>
                </div>
                <div class="card-body">
                    <div class="timeline-vertical">
                        <?php foreach ($history as $item): ?>
                            <div class="timeline-item mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <?php
                                        $historyColors = ['pending' => 'warning', 'in_review' => 'info', 'approved' => 'success', 'rejected' => 'danger', 'completed' => 'primary'];
                                        $historyColor = $historyColors[$item['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $historyColor ?>-subtle text-<?= $historyColor ?> rounded-circle p-2">
                                            <i class="uil-check"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong><?= ucfirst(str_replace('_', ' ', $item['status'])) ?></strong>
                                        <small class="text-muted d-block"><?= date('d M Y H:i', strtotime($item['created_at'])) ?></small>
                                        <p class="mb-0 small">By: <?= htmlspecialchars($item['changed_by_name']) ?></p>
                                        <?php if (!empty($item['notes'])): ?>
                                            <p class="mb-0 mt-1"><em><?= htmlspecialchars($item['notes']) ?></em></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Sidebar Actions -->
    <div class="col-lg-4">
        <!-- Review Actions -->
        <?php if ($certificate['status'] == 'pending' || $certificate['status'] == 'in_review'): ?>
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-dark"><i class="uil-clipboard-alt"></i> Review Actions</h5>
                </div>
                <div class="card-body">
                    <form action="<?= url('/admin/certificates/approve/' . $certificate['id']) ?>" method="POST" class="mb-2">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <textarea name="notes" class="form-control mb-2" rows="2" placeholder="Approval notes (optional)"></textarea>
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this certificate?')">
                            <i class="uil-check"></i> Approve Certificate
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="uil-times"></i> Reject Certificate
                    </button>
                </div>
            </div>
        <?php elseif ($certificate['status'] == 'approved'): ?>
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="uil-check-circle"></i> Generate Certificate</h5>
                </div>
                <div class="card-body">
                    <p>Certificate has been approved. Generate PDF certificate now.</p>
                    <form action="<?= url('/admin/certificates/generate/' . $certificate['id']) ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="uil-file-download"></i> Generate PDF
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Assignment -->
        <?php if ($certificate['status'] == 'pending'): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="uil-user-plus"></i> Assign Admin</h5>
                </div>
                <div class="card-body">
                    <form action="<?= url('/admin/certificates/assign/' . $certificate['id']) ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <select name="admin_id" class="form-select mb-2" required>
                            <option value="">-- Select Admin --</option>
                            <?php foreach ($admins as $admin): ?>
                                <option value="<?= $admin['id'] ?>" <?= ($certificate['assigned_to'] == $admin['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($admin['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="uil-check"></i> Assign
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="uil-info-circle"></i> Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Submitted:</span>
                    <strong><?= date('d M Y H:i', strtotime($certificate['submitted_at'])) ?></strong>
                </div>
                <?php if (!empty($certificate['assigned_admin_name'])): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Assigned To:</span>
                        <strong><?= htmlspecialchars($certificate['assigned_admin_name']) ?></strong>
                    </div>
                <?php endif; ?>
                <?php if (!empty($certificate['reviewer_name'])): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Reviewed By:</span>
                        <strong><?= htmlspecialchars($certificate['reviewer_name']) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Reviewed At:</span>
                        <strong><?= date('d M Y H:i', strtotime($certificate['reviewed_at'])) ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= url('/admin/certificates/reject/' . $certificate['id']) ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <p><strong>Ticket:</strong> <?= $certificate['ticket_number'] ?></p>
                    <p><strong>Company:</strong> <?= htmlspecialchars($certificate['company_name']) ?></p>
                    
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reason" rows="4" required placeholder="Please provide detailed reason for rejection..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Certificate</button>
                </div>
            </form>
        </div>
    </div>
</div>
