<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">🔔 Pengaturan Notifikasi</h4>
            <a href="<?= url('/profile') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="uil-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="uil-bell"></i> Kelola Preferensi Notifikasi Anda</h5>
            </div>
            
            <div class="card-body">
                <form action="<?= url('/profile/notification-settings') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- Email Notifications -->
                    <h6 class="fw-bold mb-3">📧 Notifikasi Email</h6>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="email_certificates" name="email_certificates" 
                               <?= ($settings['email_certificates'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="email_certificates">
                            <strong>Update Sertifikat Halal</strong>
                            <br><small class="text-muted">Notifikasi tentang status aplikasi sertifikat Anda</small>
                        </label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="email_forum" name="email_forum" 
                               <?= ($settings['email_forum'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="email_forum">
                            <strong>Balasan Forum</strong>
                            <br><small class="text-muted">Ketika ada yang membalas topik Anda di forum</small>
                        </label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="email_announcements" name="email_announcements" 
                               <?= ($settings['email_announcements'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="email_announcements">
                            <strong>Pengumuman</strong>
                            <br><small class="text-muted">Berita penting dan update sistem</small>
                        </label>
                    </div>
                    
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="email_newsletter" name="email_newsletter" 
                               <?= ($settings['email_newsletter'] ?? false) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="email_newsletter">
                            <strong>Newsletter</strong>
                            <br><small class="text-muted">Tips, artikel, dan konten edukatif mingguan</small>
                        </label>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Push Notifications -->
                    <h6 class="fw-bold mb-3">🔔 Notifikasi Web</h6>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="push_messages" name="push_messages" 
                               <?= ($settings['push_messages'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="push_messages">
                            <strong>Pesan Baru</strong>
                            <br><small class="text-muted">Notifikasi real-time untuk pesan internal</small>
                        </label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="push_activities" name="push_activities" 
                               <?= ($settings['push_activities'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="push_activities">
                            <strong>Aktivitas Akun</strong>
                            <br><small class="text-muted">Login baru, perubahan keamanan, dll</small>
                        </label>
                    </div>
                    
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="push_updates" name="push_updates" 
                               <?= ($settings['push_updates'] ?? false) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="push_updates">
                            <strong>Update Konten</strong>
                            <br><small class="text-muted">Konten baru: fatwa, Q&A, materi, dll</small>
                        </label>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Frequency -->
                    <h6 class="fw-bold mb-3">⏰ Frekuensi Email</h6>
                    
                    <div class="mb-4">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="email_frequency" id="freq_instant" value="instant" 
                                   <?= ($settings['email_frequency'] ?? 'instant') === 'instant' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="freq_instant">
                                <strong>Instant</strong> - Segera setelah ada notifikasi
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="email_frequency" id="freq_daily" value="daily" 
                                   <?= ($settings['email_frequency'] ?? 'instant') === 'daily' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="freq_daily">
                                <strong>Daily Digest</strong> - Rangkuman harian (1x sehari)
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="email_frequency" id="freq_weekly" value="weekly" 
                                   <?= ($settings['email_frequency'] ?? 'instant') === 'weekly' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="freq_weekly">
                                <strong>Weekly Digest</strong> - Rangkuman mingguan
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="uil-info-circle"></i> 
                        <strong>Catatan:</strong> Notifikasi keamanan penting (seperti login baru, perubahan password) 
                        akan selalu dikirim terlepas dari pengaturan ini.
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="uil-check"></i> Simpan Pengaturan
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="disableAll()">
                            <i class="uil-ban"></i> Nonaktifkan Semua
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function disableAll() {
    if (confirm('Nonaktifkan semua notifikasi? Anda mungkin melewatkan update penting.')) {
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    }
}
</script>
