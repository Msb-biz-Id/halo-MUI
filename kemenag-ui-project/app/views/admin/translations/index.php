<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">🌐 Manage Translations</h4>
            <a href="<?= url('/admin/translations/create') ?>" class="btn btn-primary">
                <i class="uil-plus"></i> Add Translation
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="<?= url('/admin/translations') ?>" method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <select class="form-select" name="language" onchange="this.form.submit()">
                            <option value="id" <?= $current_language === 'id' ? 'selected' : '' ?>>Indonesian</option>
                            <option value="en" <?= $current_language === 'en' ? 'selected' : '' ?>>English</option>
                            <option value="ar" <?= $current_language === 'ar' ? 'selected' : '' ?>>Arabic</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search translations..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Translation</th>
                                <th>Language</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($translations)): ?>
                                <?php foreach ($translations as $trans): ?>
                                    <tr>
                                        <td><code><?= htmlspecialchars($trans['translation_key']) ?></code></td>
                                        <td><?= htmlspecialchars($trans['translation_value']) ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?= strtoupper($trans['language']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="<?= url('/admin/translations/edit/' . $trans['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="uil-edit"></i> Edit
                                            </a>
                                            <form action="<?= url('/admin/translations/delete/' . $trans['id']) ?>" 
                                                  method="POST" class="d-inline" onsubmit="return confirm('Delete this translation?')">
                                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="uil-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="uil-language font-size-48 text-muted"></i>
                                        <p class="text-muted mt-3">No translations found.</p>
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
