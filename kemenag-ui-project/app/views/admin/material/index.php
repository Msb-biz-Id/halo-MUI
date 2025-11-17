<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📚 Manajemen Materi Moderasi</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Material</li>
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
                        <a href="<?= url('/admin/material/create') ?>" class="btn btn-primary">
                            <i class="uil-plus"></i> Tambah Materi Baru
                        </a>
                    </div>
                    <div>
                        <form action="<?= url('/admin/material') ?>" method="GET" class="d-flex gap-2">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <select name="type" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Tipe</option>
                                <option value="article" <?= $type_filter == 'article' ? 'selected' : '' ?>>Article</option>
                                <option value="video" <?= $type_filter == 'video' ? 'selected' : '' ?>>Video</option>
                                <option value="infographic" <?= $type_filter == 'infographic' ? 'selected' : '' ?>>Infographic</option>
                            </select>
                            
                            <input type="search" name="search" class="form-control" placeholder="Cari materi..." value="<?= htmlspecialchars($search) ?>">
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

<!-- Materials Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Daftar Materi (<?= count($materials) ?>)</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th class="text-center">Tipe</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Views</th>
                                <th>Tanggal</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($materials)): ?>
                                <?php foreach ($materials as $material): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($material['title']) ?></div>
                                            <small class="text-muted"><?= truncate(strip_tags($material['description'] ?? ''), 80) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">
                                                <?= htmlspecialchars($material['category_name']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $typeIcons = ['article' => 'uil-file-alt', 'video' => 'uil-video', 'infographic' => 'uil-image'];
                                            $typeColors = ['article' => 'info', 'video' => 'danger', 'infographic' => 'warning'];
                                            $icon = $typeIcons[$material['type']] ?? 'uil-file';
                                            $color = $typeColors[$material['type']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?>">
                                                <i class="<?= $icon ?>"></i> <?= ucfirst($material['type']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($material['is_published']): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="uil-eye"></i> <?= $material['view_count'] ?? 0 ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?= date('d M Y', strtotime($material['created_at'])) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="uil-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/material/detail/' . $material['slug']) ?>" target="_blank">
                                                            <i class="uil-eye me-2"></i> View Public
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/material/edit/' . $material['id']) ?>">
                                                            <i class="uil-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="<?= url('/admin/material/delete/' . $material['id']) ?>"
                                                           onclick="return confirm('Hapus materi ini?')">
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
                                        <i class="uil-book-reader font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">Belum ada materi</p>
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
