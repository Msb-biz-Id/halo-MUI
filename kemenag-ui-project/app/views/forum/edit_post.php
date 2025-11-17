<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">✏️ Edit Post</h5>
            </div>
            <div class="card-body">
                <form action="<?= url('/forum/edit-post/' . $post['id']) ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <div class="mb-3">
                        <label for="content" class="form-label fw-bold">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="uil-check"></i> Update Post
                        </button>
                        <a href="<?= url('/forum/topic/' . $post['topic_id']) ?>" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
