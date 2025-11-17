<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">📝 Manajemen Tanya Jawab</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Q&A</li>
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
                        <a href="<?= url('/admin/qa/create') ?>" class="btn btn-primary">
                            <i class="uil-plus"></i> Tambah Q&A Baru
                        </a>
                    </div>
                    <div>
                        <form action="<?= url('/admin/qa') ?>" method="GET" class="d-flex gap-2">
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
                            
                            <input type="search" name="search" class="form-control" placeholder="Cari pertanyaan..." value="<?= htmlspecialchars($search) ?>">
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

<!-- Q&A Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Daftar Tanya Jawab (<?= count($qa_list) ?>)</h5>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Pertanyaan</th>
                                <th>Kategori</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Views</th>
                                <th>Tanggal</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($qa_list)): ?>
                                <?php foreach ($qa_list as $qa): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($qa['question']) ?></div>
                                            <small class="text-muted"><?= truncate(strip_tags($qa['answer']), 80) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">
                                                <?= htmlspecialchars($qa['category_name']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($qa['is_published']): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="uil-eye"></i> <?= $qa['view_count'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?= date('d M Y', strtotime($qa['created_at'])) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="uil-ellipsis-h"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/qa/detail/' . $qa['id']) ?>" target="_blank">
                                                            <i class="uil-eye me-2"></i> View Public
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?= url('/admin/qa/edit/' . $qa['id']) ?>">
                                                            <i class="uil-edit me-2"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="<?= url('/admin/qa/delete/' . $qa['id']) ?>"
                                                           onclick="return confirm('Hapus Q&A ini?')">
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
                                    <td colspan="6" class="text-center py-4">
                                        <i class="uil-question-circle font-size-48 text-muted"></i>
                                        <p class="text-muted mt-2">Belum ada Q&A</p>
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
