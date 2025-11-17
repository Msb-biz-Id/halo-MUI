<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">🧪 Test Blacklist Checker</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/blacklist') ?>">Blacklist</a></li>
                    <li class="breadcrumb-item active">Test</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0 text-white"><i class="uil-flask"></i> Blacklist Detection Test Tool</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/blacklist/test') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="test_content" class="form-label fw-bold">Test Content</label>
                        <textarea class="form-control font-monospace" id="test_content" name="content" rows="6" 
                                  placeholder="Enter any text to test against blacklist...&#10;&#10;Example: Mari bermain slot online di situs judi terpercaya" 
                                  required><?= htmlspecialchars($test_content ?? '') ?></textarea>
                        <small class="text-muted">Enter forum post, comment, or any content to test</small>
                    </div>
                    
                    <button type="submit" class="btn btn-warning btn-lg px-5">
                        <i class="uil-search"></i> Check Content
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Results -->
        <?php if (isset($result)): ?>
            <div class="card border-<?= $result['has_blacklist'] ? 'danger' : 'success' ?>">
                <div class="card-header bg-<?= $result['has_blacklist'] ? 'danger' : 'success' ?> text-white">
                    <h5 class="mb-0">
                        <i class="uil-<?= $result['has_blacklist'] ? 'exclamation-triangle' : 'check-circle' ?>"></i>
                        Test Results
                    </h5>
                </div>
                
                <div class="card-body">
                    <?php if ($result['has_blacklist']): ?>
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">⚠️ BLACKLIST DETECTED!</h5>
                            <p>Found <strong><?= count($result['detected_words']) ?></strong> blacklisted word(s) in the content.</p>
                            <hr>
                            <p class="mb-0"><strong>Recommended Action:</strong> 
                                <span class="badge bg-dark font-size-14"><?= strtoupper($result['action']) ?></span>
                            </p>
                        </div>
                        
                        <h6 class="mt-4 mb-3">Detected Words:</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Word</th>
                                        <th>Type</th>
                                        <th>Severity</th>
                                        <th>Action</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result['detected_words'] as $detected): ?>
                                        <tr>
                                            <td><code class="text-danger"><?= htmlspecialchars($detected['word']) ?></code></td>
                                            <td><span class="badge bg-secondary"><?= $detected['type'] ?></span></td>
                                            <td>
                                                <?php
                                                $colors = ['low' => 'info', 'medium' => 'warning', 'high' => 'orange', 'critical' => 'danger'];
                                                ?>
                                                <span class="badge bg-<?= $colors[$detected['severity']] ?>">
                                                    <?= strtoupper($detected['severity']) ?>
                                                </span>
                                            </td>
                                            <td><span class="badge bg-dark"><?= $detected['action'] ?></span></td>
                                            <td><small><?= htmlspecialchars($detected['description'] ?? '-') ?></small></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-warning mt-4">
                            <h6><i class="uil-info-circle"></i> What This Means:</h6>
                            <ul class="mb-0">
                                <li><strong>flag:</strong> Content would be published but admin notified</li>
                                <li><strong>block:</strong> User cannot submit this content</li>
                                <li><strong>auto_reject:</strong> Content is immediately rejected with warning message</li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success mb-0">
                            <h5 class="alert-heading"><i class="uil-check-circle"></i> CLEAN!</h5>
                            <p class="mb-0">No blacklisted words detected in this content. It's safe to publish! ✅</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Test Samples -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="uil-lightbulb-alt"></i> Test Samples</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Click to test these samples:</p>
                
                <div class="accordion" id="sampleAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#sample1">
                                <span class="badge bg-danger me-2">SPAM</span> Sample 1: Gambling Content
                            </button>
                        </h2>
                        <div id="sample1" class="accordion-collapse collapse show" data-bs-parent="#sampleAccordion">
                            <div class="accordion-body">
                                <code class="d-block mb-2">Mari bermain slot online di situs judi terpercaya</code>
                                <button class="btn btn-sm btn-outline-primary" onclick="testSample('Mari bermain slot online di situs judi terpercaya')">
                                    Test This
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sample2">
                                <span class="badge bg-success me-2">CLEAN</span> Sample 2: Normal Content
                            </button>
                        </h2>
                        <div id="sample2" class="accordion-collapse collapse" data-bs-parent="#sampleAccordion">
                            <div class="accordion-body">
                                <code class="d-block mb-2">Bagaimana cara mendapatkan sertifikat halal untuk produk makanan?</code>
                                <button class="btn btn-sm btn-outline-primary" onclick="testSample('Bagaimana cara mendapatkan sertifikat halal untuk produk makanan?')">
                                    Test This
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function testSample(content) {
    document.getElementById('test_content').value = content;
    document.querySelector('form').submit();
}
</script>
