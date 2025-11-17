<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📁 Media Library</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Media</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="uil-cloud-upload"></i> Upload Files
                        </button>
                    </div>
                    <div>
                        <select class="form-select" id="mediaType" onchange="filterMedia(this.value)">
                            <option value="">All Types</option>
                            <option value="image">Images</option>
                            <option value="document">Documents</option>
                            <option value="video">Videos</option>
                        </select>
                    </div>
                </div>
                
                <div class="row g-3">
                    <?php if (!empty($media)): ?>
                        <?php foreach ($media as $file): ?>
                            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                <div class="card h-100 border shadow-sm hover-card">
                                    <?php if (strpos($file['mime_type'], 'image') !== false): ?>
                                        <img src="<?= asset($file['path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($file['filename']) ?>" style="height: 150px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                            <i class="uil-file-alt font-size-48 text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body p-2">
                                        <h6 class="card-title small mb-1" title="<?= htmlspecialchars($file['filename']) ?>">
                                            <?= truncate($file['filename'], 20) ?>
                                        </h6>
                                        <p class="card-text">
                                            <small class="text-muted"><?= formatBytes($file['size']) ?></small>
                                        </p>
                                    </div>
                                    
                                    <div class="card-footer bg-light p-2">
                                        <div class="btn-group w-100" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyUrl('<?= asset($file['path']) ?>')">
                                                <i class="uil-copy"></i>
                                            </button>
                                            <a href="<?= asset($file['path']) ?>" class="btn btn-sm btn-outline-info" download>
                                                <i class="uil-download-alt"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteMedia(<?= $file['id'] ?>)">
                                                <i class="uil-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <i class="uil-image-slash font-size-48 text-muted"></i>
                            <p class="text-muted mt-2">No media files</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="uil-cloud-upload"></i> Upload Files</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('/admin/media/upload') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Files</label>
                        <input type="file" class="form-control" name="files[]" multiple accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx">
                        <small class="text-muted">Max 10MB per file. Multiple files allowed.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="uil-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('URL copied to clipboard!');
    });
}

function deleteMedia(id) {
    if (confirm('Delete this file?')) {
        window.location.href = '<?= url('/admin/media/delete/') ?>' + id;
    }
}

function filterMedia(type) {
    window.location.href = '<?= url('/admin/media') ?>?type=' + type;
}
</script>

<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}
</style>
