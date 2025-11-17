<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Buat Topik Forum Baru</h4>
                </div>
                
                <div class="card-body p-4">
                    
                    <!-- Important Notice -->
                    <div class="alert alert-warning mb-4">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Penting!</h6>
                        <ul class="mb-0 small">
                            <li><strong>Topik Anda akan direview oleh admin</strong> sebelum dipublikasikan</li>
                            <li>Gunakan bahasa yang sopan dan sesuai dengan aturan forum</li>
                            <li><strong>Dilarang</strong> menggunakan kata-kata: <span class="text-danger fw-bold">slot, judi, togel, casino, betting, spam, dll</span></li>
                            <li>Topik yang mengandung kata terlarang akan <strong>otomatis ditolak</strong></li>
                            <li>Anda akan menerima notifikasi setelah topik disetujui/ditolak</li>
                        </ul>
                    </div>
                    
                    <form action="<?= url('/forum/create-topic') ?>" method="POST" id="createTopicForm">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg" id="category_id" name="category_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">Pilih kategori yang sesuai dengan topik Anda</small>
                        </div>
                        
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">Judul Topik <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" 
                                   value="<?= old('title') ?>" placeholder="Contoh: Bagaimana cara mendapatkan sertifikat halal?" 
                                   required maxlength="255">
                            <small class="text-muted">Judul yang jelas dan deskriptif (max 255 karakter)</small>
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold">Konten Topik <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="10" 
                                      placeholder="Tulis pertanyaan atau diskusi Anda di sini..." 
                                      required><?= old('content') ?></textarea>
                            <small class="text-muted">Jelaskan topik Anda dengan detail (minimal 50 karakter)</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-shield-alt"></i> Sistem Moderasi Otomatis</h6>
                            <p class="mb-0 small">
                                Konten Anda akan <strong>otomatis dicek</strong> untuk kata-kata terlarang. 
                                Jika terdeteksi spam atau konten yang tidak pantas, topik akan <strong>otomatis ditolak</strong> 
                                dengan notifikasi peringatan yang jelas.
                            </p>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-paper-plane"></i> Buat Topik
                            </button>
                            <a href="<?= url('/forum') ?>" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Guidelines Card -->
            <div class="card mt-4 bg-light border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="fas fa-book-open text-primary"></i> Panduan Forum</h6>
                    <ul class="small mb-0">
                        <li>Gunakan judul yang jelas dan deskriptif</li>
                        <li>Tulis konten yang informatif dan bermanfaat</li>
                        <li>Hormati pengguna lain dan jaga etika berdiskusi</li>
                        <li>Jangan melakukan spam, promosi, atau posting yang tidak relevan</li>
                        <li>Kata-kata seperti <span class="text-danger fw-bold">slot, judi, togel, casino</span> akan otomatis ditolak</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter
document.getElementById('content').addEventListener('input', function() {
    const length = this.value.length;
    console.log('Character count:', length);
});

// Validate on submit
document.getElementById('createTopicForm').addEventListener('submit', function(e) {
    const content = document.getElementById('content').value;
    
    if (content.length < 50) {
        e.preventDefault();
        alert('Konten terlalu pendek! Minimal 50 karakter.');
        return false;
    }
});
</script>
