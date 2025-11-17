<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">⚖️ Manajemen Fatwa</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Fatwa</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="<?= url('/admin/fatwa/create') ?>" class="btn btn-danger">
                            <i class="uil-plus"></i> Tambah Fatwa Baru
                        </a>
                    </div>
                    <div>
                        <form action="<?= url('/admin/fatwa') ?>" method="GET" class="d-flex gap-2">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="1" <?= $status_filter === '1' ? 'selected' : '' ?>>Published</option>
                                <option value="0" <?= $status_filter === '0' ? 'selected' : '' ?>>Draft</option>
                            </select>
                            
                            <input type="search" name="search" class="form-control" placeholder="Cari fatwa..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="uil-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fatwa Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Daftar Fatwa (<?= count($fatwas) ?>)</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Judul Fatwa</th>
                                <th>Nomor</th>
                                <th>Kategori</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Views</th>
                                <th>Tanggal</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($fatwas)): ?>
                                <?php foreach ($fatwas as $fatwa): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($fatwa['title']) ?></div>
                                            <small class="text-muted"><?= truncate(strip_tags($fatwa['summary'] ?? $fatwa['content']), 80) ?></small>
                                        </td>
                                        <td>
                                            <?php if (!empty($fatwa['fatwa_number'])): ?>
                                                <span class="badge bg-danger"><?= $fatwa['fatwa_number'] ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger-subtle text-danger">
                                                <?= htmlspecialchars($fatwa['category_name']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($fatwa['is_published']): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="uil-eye"></i> <?= $fatwa['view_count'] ?? 0 ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?= date('d M Y', strtotime($fatwa['published_at'] ?? $fatwa['created_at'])) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="uil-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/fatwa/detail/' . $fatwa['slug']) ?>" target="_blank">
                                                            <i class="uil-eye me-2"></i> View Public
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/fatwa/edit/' . $fatwa['id']) ?>">
                                                            <i class="uil-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="<?= url('/admin/fatwa/delete/' . $fatwa['id']) ?>"
                                                           onclick="return confirm('Hapus fatwa ini?')">
                                                            <i class="uil-trash me-2"></i> Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="uil-balance-scale font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">Belum ada fatwa</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
