<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📁 Manajemen Kategori</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
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
                        <a href="<?= url('/admin/categories/create') ?>" class="btn btn-primary">
                            <i class="uil-plus"></i> Tambah Kategori
                        </a>
                    </div>
                    <div>
                        <form action="<?= url('/admin/categories') ?>" method="GET" class="d-flex gap-2">
                            <select name="type" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Tipe</option>
                                <option value="qa" <?= $type_filter == 'qa' ? 'selected' : '' ?>>Q&A</option>
                                <option value="fatwa" <?= $type_filter == 'fatwa' ? 'selected' : '' ?>>Fatwa</option>
                                <option value="material" <?= $type_filter == 'material' ? 'selected' : '' ?>>Material</option>
                                <option value="book" <?= $type_filter == 'book' ? 'selected' : '' ?>>Book</option>
                                <option value="forum" <?= $type_filter == 'forum' ? 'selected' : '' ?>>Forum</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Daftar Kategori (<?= count($categories) ?>)</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Tipe</th>
                                <th>Slug</th>
                                <th class="text-center">Items</th>
                                <th class="text-center">Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td><?= $cat['id'] ?></td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($cat['name']) ?></div>
                                            <?php if (!empty($cat['description'])): ?>
                                                <small class="text-muted"><?= truncate($cat['description'], 60) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $colors = ['qa' => 'primary', 'fatwa' => 'danger', 'material' => 'info', 'book' => 'success', 'forum' => 'warning'];
                                            $color = $colors[$cat['type']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?>">
                                                <?= ucfirst($cat['type']) ?>
                                            </span>
                                        </td>
                                        <td><code><?= $cat['slug'] ?></code></td>
                                        <td class="text-center">
                                            <span class="badge bg-info"><?= $cat['item_count'] ?? 0 ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($cat['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="uil-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/categories/edit/' . $cat['id']) ?>">
                                                            <i class="uil-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="<?= url('/admin/categories/delete/' . $cat['id']) ?>"
                                                           onclick="return confirm('Hapus kategori ini? Items di dalamnya tidak akan terhapus.')">
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
                                        <i class="uil-folder-open font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">Belum ada kategori</p>
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
