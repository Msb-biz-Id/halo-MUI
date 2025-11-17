<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">➕ Bulk Add Blacklist Words</h4>
            <a href="<?= url('/admin/blacklist') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="uil-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="uil-plus-circle"></i> Add Multiple Words at Once</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/blacklist/bulk-add') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label for="words" class="form-label fw-bold">
                            Blacklist Words <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control font-monospace" id="words" name="words" rows="15" 
                                  required placeholder="Enter one word per line. Example:&#10;judi&#10;slot&#10;togel&#10;casino&#10;betting"><?= old('words') ?></textarea>
                        <small class="text-muted">One word per line. Duplicates will be skipped.</small>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="type" class="form-label fw-bold">Match Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="partial" selected>Partial Match (Contains)</option>
                                <option value="exact">Exact Match</option>
                                <option value="regex">Regex Pattern</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="severity" class="form-label fw-bold">Severity</label>
                            <select class="form-select" id="severity" name="severity">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="action" class="form-label fw-bold">Action</label>
                            <select class="form-select" id="action" name="action">
                                <option value="flag">Flag (Notify Only)</option>
                                <option value="block" selected>Block (Prevent Submit)</option>
                                <option value="auto_reject">Auto Reject</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Description (Optional)</label>
                        <input type="text" class="form-control" id="description" name="description" 
                               placeholder="E.g., Gambling-related terms">
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="uil-info-circle"></i>
                        <strong>Quick Add Templates:</strong>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-dark me-2" onclick="addTemplate('gambling')">Gambling</button>
                            <button type="button" class="btn btn-sm btn-outline-dark me-2" onclick="addTemplate('drugs')">Drugs</button>
                            <button type="button" class="btn btn-sm btn-outline-dark me-2" onclick="addTemplate('offensive')">Offensive</button>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger btn-lg px-5">
                            <i class="uil-plus-circle"></i> Add All Words
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('words').value=''">
                            <i class="uil-trash"></i> Clear
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const templates = {
    gambling: 'judi\nslot\ntogel\ncasino\nbetting\npoker\ntaruhan\njackpot\nroulette\nblackjack',
    drugs: 'narkoba\nganja\nkokain\nheroin\nshabu\namphetamine\nextacy',
    offensive: 'anjing\nbabi\ntai\nkontol\nmemek\ntolol\nbodoh'
};

function addTemplate(type) {
    const textarea = document.getElementById('words');
    const current = textarea.value.trim();
    const newWords = templates[type];
    
    if (current) {
        textarea.value = current + '\n' + newWords;
    } else {
        textarea.value = newWords;
    }
}
</script>
