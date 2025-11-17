<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Add Blacklisted Word</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= url('/admin/blacklist') ?>">Blacklist</a></li>
                    <li class="breadcrumb-item active">Add Word</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="uil-plus-circle"></i> Add New Blacklisted Word</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/blacklist/create') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="word" class="form-label fw-bold">Word/Phrase <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="word" name="word" 
                               value="<?= old('word') ?>" placeholder="e.g., slot, judi online" required>
                        <small class="text-muted">Enter the word or phrase to blacklist (case insensitive)</small>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="type" class="form-label fw-bold">Match Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="partial" <?= old('type') == 'partial' ? 'selected' : '' ?>>Partial (Contains)</option>
                                <option value="exact" <?= old('type') == 'exact' ? 'selected' : '' ?>>Exact Match</option>
                                <option value="regex" <?= old('type') == 'regex' ? 'selected' : '' ?>>Regex Pattern</option>
                            </select>
                            <small class="text-muted">
                                <strong>Partial:</strong> Matches if content contains the word<br>
                                <strong>Exact:</strong> Matches only exact word<br>
                                <strong>Regex:</strong> Uses regex pattern matching
                            </small>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="severity" class="form-label fw-bold">Severity <span class="text-danger">*</span></label>
                            <select class="form-select" id="severity" name="severity" required>
                                <option value="low" <?= old('severity') == 'low' ? 'selected' : '' ?>>Low</option>
                                <option value="medium" <?= old('severity') == 'medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="high" <?= old('severity') == 'high' ? 'selected' : '' ?>>High</option>
                                <option value="critical" <?= old('severity') == 'critical' ? 'selected' : '' ?>>Critical</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="action" class="form-label fw-bold">Action <span class="text-danger">*</span></label>
                        <select class="form-select" id="action" name="action" required>
                            <option value="flag" <?= old('action') == 'flag' ? 'selected' : '' ?>>Flag (Notify admin only)</option>
                            <option value="block" <?= old('action') == 'block' ? 'selected' : '' ?>>Block (Prevent submission)</option>
                            <option value="auto_reject" <?= old('action') == 'auto_reject' ? 'selected' : '' ?>>Auto Reject (Immediately reject)</option>
                        </select>
                        <small class="text-muted">
                            <strong>Flag:</strong> Content is allowed but admin is notified<br>
                            <strong>Block:</strong> User cannot submit content<br>
                            <strong>Auto Reject:</strong> Content is automatically rejected with warning
                        </small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Why is this word blacklisted? (e.g., Gambling spam)"><?= old('description') ?></textarea>
                        <small class="text-muted">Optional: Add context for other moderators</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="uil-info-circle"></i> Tips:</h6>
                        <ul class="mb-0 small">
                            <li>Use <strong>partial</strong> matching for common spam words (e.g., "slot" will match "slot online")</li>
                            <li>Use <strong>exact</strong> for words that shouldn't match variations</li>
                            <li>Use <strong>critical + auto_reject</strong> for serious violations (gambling, scams)</li>
                            <li>Blacklist checking is <strong>case insensitive</strong> by default</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Add Word
                        </button>
                        <a href="<?= url('/admin/blacklist') ?>" class="btn btn-secondary btn-lg">
                            <i class="uil-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Quick Add Suggestions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="uil-lightbulb-alt"></i> Common Spam Words</h6>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-2">Quick suggestions (click to add):</p>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-danger" style="cursor: pointer;" onclick="fillWord('slot')">slot</span>
                    <span class="badge bg-danger" style="cursor: pointer;" onclick="fillWord('judi')">judi</span>
                    <span class="badge bg-danger" style="cursor: pointer;" onclick="fillWord('togel')">togel</span>
                    <span class="badge bg-danger" style="cursor: pointer;" onclick="fillWord('casino')">casino</span>
                    <span class="badge bg-danger" style="cursor: pointer;" onclick="fillWord('betting')">betting</span>
                    <span class="badge bg-danger" style="cursor: pointer;" onclick="fillWord('poker')">poker</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillWord(word) {
    document.getElementById('word').value = word;
    document.getElementById('type').value = 'partial';
    document.getElementById('severity').value = 'critical';
    document.getElementById('action').value = 'auto_reject';
    document.getElementById('description').value = 'Gambling/spam keyword';
}
</script>
