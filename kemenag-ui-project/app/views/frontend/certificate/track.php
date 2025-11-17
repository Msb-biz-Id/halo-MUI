<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0"><i class="fas fa-search"></i> Lacak Sertifikat Halal</h3>
                </div>
                
                <div class="card-body p-4">
                    <p class="text-muted mb-4">Masukkan nomor permohonan atau nomor sertifikat untuk melacak status sertifikat halal Anda.</p>
                    
                    <form action="<?= url('/certificate/track') ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="mb-4">
                            <label for="tracking_number" class="form-label fw-bold">Nomor Permohonan / Sertifikat</label>
                            <input type="text" class="form-control form-control-lg" id="tracking_number" 
                                   name="tracking_number" placeholder="Contoh: HALAL-2024-001" 
                                   value="<?= $tracking_number ?? '' ?>" required>
                            <small class="text-muted">Nomor yang Anda terima saat mengajukan permohonan</small>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-search"></i> Lacak Sekarang
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Result -->
            <?php if (isset($certificate)): ?>
                <?php if ($certificate): ?>
                    <div class="card mt-4 shadow-sm border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-certificate"></i> Detail Sertifikat</h5>
                        </div>
                        
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <strong>Nomor Permohonan:</strong><br>
                                    <span class="text-primary"><?= $certificate['application_number'] ?></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Status:</strong><br>
                                    <?php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'reviewing' => 'info',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    ];
                                    $statusColor = $statusColors[$certificate['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $statusColor ?> font-size-14">
                                        <?= strtoupper($certificate['status']) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <strong>Nama Perusahaan:</strong><br>
                                    <?= htmlspecialchars($certificate['company_name']) ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Nama Produk:</strong><br>
                                    <?= htmlspecialchars($certificate['product_name']) ?>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <strong>Tanggal Pengajuan:</strong><br>
                                    <?= date('d F Y', strtotime($certificate['application_date'])) ?>
                                </div>
                                <?php if ($certificate['status'] == 'approved' && !empty($certificate['certificate_number'])): ?>
                                    <div class="col-md-6 mb-3">
                                        <strong>Nomor Sertifikat:</strong><br>
                                        <span class="text-success fw-bold"><?= $certificate['certificate_number'] ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($certificate['notes'])): ?>
                                <hr>
                                <div class="alert alert-info">
                                    <strong><i class="fas fa-info-circle"></i> Catatan:</strong><br>
                                    <?= nl2br(htmlspecialchars($certificate['notes'])) ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($certificate['status'] == 'approved'): ?>
                                <div class="alert alert-success">
                                    <h6 class="alert-heading"><i class="fas fa-check-circle"></i> Sertifikat Disetujui!</h6>
                                    <p class="mb-0">Selamat! Sertifikat halal Anda telah disetujui.</p>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="<?= url('/certificate/download/' . $certificate['id']) ?>" class="btn btn-success">
                                        <i class="fas fa-download"></i> Download Sertifikat
                                    </a>
                                    <a href="<?= url('/certificate/verify/' . $certificate['certificate_number']) ?>" 
                                       class="btn btn-outline-primary" target="_blank">
                                        <i class="fas fa-qrcode"></i> Verifikasi Sertifikat
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mt-4">
                        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Tidak Ditemukan</h5>
                        <p class="mb-0">Nomor sertifikat yang Anda masukkan tidak ditemukan. Pastikan nomor sudah benar.</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- Help Card -->
            <div class="card mt-4 bg-light border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-question-circle text-info"></i> Bantuan</h6>
                    <ul class="small mb-0">
                        <li>Nomor permohonan dikirimkan via email setelah Anda mengajukan permohonan</li>
                        <li>Proses review biasanya memakan waktu 7-14 hari kerja</li>
                        <li>Anda akan menerima notifikasi email jika ada perubahan status</li>
                        <li>Jika ada pertanyaan, hubungi <a href="<?= url('/contact') ?>">Customer Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
