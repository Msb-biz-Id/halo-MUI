<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">🔒 Privacy & Data</h4>
            <a href="<?= url('/profile') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="uil-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <!-- Privacy Settings -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="uil-lock"></i> Pengaturan Privacy</h5>
            </div>
            <div class="card-body">
                <form action="<?= url('/profile/privacy') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <h6 class="fw-bold mb-3">Visibilitas Profil</h6>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="show_email" name="show_email" 
                               <?= ($privacy['show_email'] ?? false) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="show_email">
                            Tampilkan email di profil publik
                        </label>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="show_phone" name="show_phone" 
                               <?= ($privacy['show_phone'] ?? false) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="show_phone">
                            Tampilkan nomor telepon di profil publik
                        </label>
                    </div>
                    
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="show_company" name="show_company" 
                               <?= ($privacy['show_company'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="show_company">
                            Tampilkan informasi perusahaan
                        </label>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="fw-bold mb-3">Aktivitas Forum</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Siapa yang bisa melihat topik forum Anda?</label>
                        <select class="form-select" name="forum_visibility">
                            <option value="public" <?= ($privacy['forum_visibility'] ?? 'public') === 'public' ? 'selected' : '' ?>>
                                Semua orang (Public)
                            </option>
                            <option value="members" <?= ($privacy['forum_visibility'] ?? 'public') === 'members' ? 'selected' : '' ?>>
                                Hanya member terdaftar
                            </option>
                            <option value="private" <?= ($privacy['forum_visibility'] ?? 'public') === 'private' ? 'selected' : '' ?>>
                                Hanya saya
                            </option>
                        </select>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h6 class="fw-bold mb-3">Data Analytics</h6>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="allow_analytics" name="allow_analytics" 
                               <?= ($privacy['allow_analytics'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="allow_analytics">
                            <strong>Izinkan Analytics</strong>
                            <br><small class="text-muted">Membantu kami meningkatkan layanan dengan data anonim</small>
                        </label>
                    </div>
                    
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="personalized_content" name="personalized_content" 
                               <?= ($privacy['personalized_content'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="personalized_content">
                            <strong>Konten Personalisasi</strong>
                            <br><small class="text-muted">Rekomendasi konten berdasarkan aktivitas Anda</small>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="uil-check"></i> Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Data Management -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0 text-dark"><i class="uil-database"></i> Manajemen Data</h5>
            </div>
            <div class="card-body">
                <h6 class="fw-bold mb-3">Download Data Anda</h6>
                <p class="text-muted">
                    Anda dapat mengunduh salinan data pribadi Anda dalam format JSON. 
                    Ini termasuk profil, riwayat aktivitas, dan konten yang Anda buat.
                </p>
                <button type="button" class="btn btn-outline-primary mb-4" onclick="requestDataDownload()">
                    <i class="uil-download-alt"></i> Request Data Export
                </button>
                
                <hr class="my-4">
                
                <h6 class="fw-bold mb-3 text-danger">Danger Zone</h6>
                
                <div class="alert alert-danger">
                    <h6 class="fw-bold"><i class="uil-exclamation-triangle"></i> Hapus Akun</h6>
                    <p class="mb-2">
                        Menghapus akun akan menghapus PERMANEN semua data Anda termasuk:
                    </p>
                    <ul class="mb-3">
                        <li>Profil dan informasi personal</li>
                        <li>Aplikasi sertifikat halal</li>
                        <li>Topik dan postingan forum</li>
                        <li>Riwayat aktivitas</li>
                    </ul>
                    <p class="mb-0 small text-muted">
                        <strong>Catatan:</strong> Proses ini tidak dapat dibatalkan!
                    </p>
                </div>
                
                <button type="button" class="btn btn-danger" onclick="confirmDeleteAccount()">
                    <i class="uil-trash-alt"></i> Hapus Akun Saya
                </button>
            </div>
        </div>
    </div>
    
    <!-- Privacy Info -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm mb-4 bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="uil-info-circle"></i> Tentang Privacy Anda
                </h6>
                <p class="small text-muted mb-0">
                    Kami berkomitmen melindungi privacy Anda. Data Anda diamankan dengan enkripsi 
                    dan tidak akan dibagikan ke pihak ketiga tanpa izin Anda.
                </p>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Data Yang Kami Kumpulkan</h6>
                <ul class="small text-muted mb-0">
                    <li>Informasi profil (nama, email, telepon)</li>
                    <li>Aktivitas forum dan konten</li>
                    <li>Aplikasi sertifikat halal</li>
                    <li>Log keamanan dan akses</li>
                    <li>Preferensi pengaturan</li>
                </ul>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Hak Anda</h6>
                <ul class="small text-muted mb-0">
                    <li>Akses ke data Anda</li>
                    <li>Koreksi data yang tidak akurat</li>
                    <li>Hapus data Anda</li>
                    <li>Export data Anda</li>
                    <li>Withdraw consent</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function requestDataDownload() {
    if (confirm('Request data export? Kami akan mengirim link download ke email Anda dalam 24 jam.')) {
        // Submit request
        alert('Request Anda telah diterima. Cek email Anda dalam 24 jam.');
    }
}

function confirmDeleteAccount() {
    if (confirm('PERINGATAN! Ini akan menghapus akun Anda PERMANEN. Lanjutkan?')) {
        if (confirm('Yakin 100%? Proses ini TIDAK BISA dibatalkan!')) {
            window.location.href = '<?= url('/profile/delete-account') ?>';
        }
    }
}
</script>
