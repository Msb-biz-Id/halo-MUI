<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📤 Upload Media</h4>
            <a href="<?= url('/admin/media') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="uil-arrow-left"></i> Back to Library
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="uil-cloud-upload"></i> Upload Files</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/admin/media/upload') ?>" method="POST" enctype="multipart/form-data" id="uploadForm">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Select Files <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="files[]" id="fileInput" multiple accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx" required>
                        <small class="text-muted">
                            Supported: Images (JPG, PNG, GIF), Videos (MP4, WEBM), Documents (PDF, DOC, XLS)<br>
                            Max size: 10MB per file
                        </small>
                    </div>
                    
                    <div id="preview" class="row g-3 mb-4"></div>
                    
                    <div class="alert alert-info">
                        <i class="uil-info-circle"></i>
                        <strong>Tips:</strong>
                        <ul class="mb-0">
                            <li>Use descriptive filenames</li>
                            <li>Optimize images before uploading</li>
                            <li>Maximum 10 files per upload</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="uil-upload"></i> Upload Files
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    
    Array.from(e.target.files).forEach(file => {
        const col = document.createElement('div');
        col.className = 'col-md-4';
        
        const card = document.createElement('div');
        card.className = 'card';
        
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.className = 'card-img-top';
            img.src = URL.createObjectURL(file);
            img.style.height = '150px';
            img.style.objectFit = 'cover';
            card.appendChild(img);
        } else {
            const placeholder = document.createElement('div');
            placeholder.className = 'card-img-top bg-light d-flex align-items-center justify-content-center';
            placeholder.style.height = '150px';
            placeholder.innerHTML = '<i class="uil-file-alt font-size-48 text-muted"></i>';
            card.appendChild(placeholder);
        }
        
        const body = document.createElement('div');
        body.className = 'card-body';
        body.innerHTML = `
            <h6 class="card-title small">${file.name}</h6>
            <p class="card-text"><small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small></p>
        `;
        
        card.appendChild(body);
        col.appendChild(card);
        preview.appendChild(col);
    });
});
</script>
