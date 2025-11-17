<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📋 Application Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Application Number:</strong><br>
                            <code><?= htmlspecialchars($application['tracking_number']) ?></code>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <strong>Status:</strong><br>
                            <?php
                            $colors = [
                                'pending' => 'warning',
                                'in_review' => 'info',
                                'approved' => 'success',
                                'rejected' => 'danger'
                            ];
                            $color = $colors[$application['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $color ?> badge-lg"><?= strtoupper($application['status']) ?></span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="mb-3">Company Information</h6>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Company Name:</th>
                            <td><?= htmlspecialchars($application['company_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td><?= htmlspecialchars($application['company_address']) ?></td>
                        </tr>
                        <tr>
                            <th>Contact:</th>
                            <td><?= htmlspecialchars($application['contact_phone']) ?></td>
                        </tr>
                    </table>
                    
                    <h6 class="mb-3 mt-4">Product Information</h6>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Product Name:</th>
                            <td><?= htmlspecialchars($application['product_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td><?= htmlspecialchars($application['product_category']) ?></td>
                        </tr>
                        <tr>
                            <th>Brand:</th>
                            <td><?= htmlspecialchars($application['product_brand']) ?></td>
                        </tr>
                    </table>
                    
                    <h6 class="mb-3 mt-4">Application Timeline</h6>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Submitted:</th>
                            <td><?= date('d M Y H:i', strtotime($application['created_at'])) ?></td>
                        </tr>
                        <?php if ($application['status'] !== 'pending'): ?>
                        <tr>
                            <th>Last Updated:</th>
                            <td><?= date('d M Y H:i', strtotime($application['updated_at'])) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    
                    <?php if ($application['status'] == 'approved' && !empty($application['certificate_number'])): ?>
                        <div class="alert alert-success mt-4">
                            <h6><i class="uil-check-circle"></i> Certificate Approved!</h6>
                            <p class="mb-2">Certificate Number: <strong><?= htmlspecialchars($application['certificate_number']) ?></strong></p>
                            <a href="<?= url('/certificate/download/' . $application['id']) ?>" class="btn btn-success">
                                <i class="uil-download-alt"></i> Download Certificate
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($application['status'] == 'rejected' && !empty($application['rejection_reason'])): ?>
                        <div class="alert alert-danger mt-4">
                            <h6><i class="uil-times-circle"></i> Application Rejected</h6>
                            <p class="mb-0">Reason: <?= htmlspecialchars($application['rejection_reason']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
