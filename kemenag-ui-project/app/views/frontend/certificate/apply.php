<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-file-medical"></i> Formulir Pengajuan Sertifikat Halal</h4>
                </div>
                
                <div class="card-body p-4">
                    <!-- Instructions -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Petunjuk Pengisian:</h6>
                        <ul class="mb-0 small">
                            <li>Pastikan semua data yang diisi adalah benar dan valid</li>
                            <li>Upload dokumen pendukung dalam format PDF atau gambar (max 5MB)</li>
                            <li>Proses verifikasi memakan waktu 3-7 hari kerja</li>
                            <li>Anda akan menerima notifikasi melalui email dan sistem</li>
                        </ul>
                    </div>
                    
                    <form action="<?= url('/certificate/apply') ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        
                        <h5 class="mb-3 text-primary"><i class="fas fa-building"></i> Informasi Perusahaan</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="company_name" 
                                       value="<?= old('company_name', user('company_name')) ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Perusahaan <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?= old('email', user('email')) ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" 
                                       value="<?= old('phone', user('phone')) ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Website Perusahaan</label>
                                <input type="url" class="form-control" name="website" 
                                       value="<?= old('website') ?>" placeholder="https://">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat Perusahaan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="company_address" rows="3" required><?= old('company_address') ?></textarea>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3 text-primary"><i class="fas fa-box"></i> Informasi Produk</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="product_name" 
                                       value="<?= old('product_name') ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori Produk <span class="text-danger">*</span></label>
                                <select class="form-select" name="product_category" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="makanan">Makanan</option>
                                    <option value="minuman">Minuman</option>
                                    <option value="kosmetik">Kosmetik</option>
                                    <option value="obat">Obat-obatan</option>
                                    <option value="suplemen">Suplemen</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Produk <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="product_description" rows="4" required><?= old('product_description') ?></textarea>
                            <small class="text-muted">Jelaskan komposisi, proses produksi, dan informasi penting lainnya</small>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3 text-primary"><i class="fas fa-file-upload"></i> Dokumen Pendukung</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Surat Permohonan <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="documents[]" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Format: PDF, JPG, PNG (Max 5MB)</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Sertifikat Perusahaan <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="documents[]" accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">SIUP, TDP, atau dokumen legalitas</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Daftar Bahan Baku <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="documents[]" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Dokumen Tambahan <small class="text-muted">(Opsional)</small></label>
                            <input type="file" class="form-control" name="documents[]" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                Saya menyatakan bahwa semua informasi yang diberikan adalah benar dan dapat dipertanggungjawabkan.
                                Saya memahami bahwa informasi yang salah dapat mengakibatkan penolakan permohonan.
                            </label>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane"></i> Kirim Permohonan
                            </button>
                            <a href="<?= url('/certificate') ?>" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// File size validation
document.querySelectorAll('input[type="file"]').forEach(function(input) {
    input.addEventListener('change', function() {
        if (this.files[0]) {
            const fileSize = this.files[0].size / 1024 / 1024; // in MB
            if (fileSize > 5) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                this.value = '';
            }
        }
    });
});
</script>
