<?php $layout = 'admin'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Redirect Manager</h4>
                <div>
                    <a href="<?= url('admin/redirect/import') ?>" class="btn btn-info me-2">
                        <i class="fas fa-upload"></i> Import CSV
                    </a>
                    <a href="<?= url('admin/redirect/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Redirect
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="redirectTable">
                <thead>
                    <tr>
                        <th>From URL</th>
                        <th>To URL</th>
                        <th>Type</th>
                        <th>Hits</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($redirects as $redirect): ?>
                    <tr>
                        <td><code>/<?= htmlspecialchars($redirect['from_url']) ?></code></td>
                        <td><code><?= htmlspecialchars($redirect['to_url']) ?></code></td>
                        <td><span class="badge bg-info"><?= $redirect['type'] ?></span></td>
                        <td><?= number_format($redirect['hits']) ?></td>
                        <td>
                            <span class="badge bg-<?= $redirect['status'] === 'active' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($redirect['status']) ?>
                            </span>
                        </td>
                        <td><?= date('Y-m-d H:i', strtotime($redirect['created_at'])) ?></td>
                        <td>
                            <button onclick="toggleStatus(<?= $redirect['id'] ?>)" 
                                    class="btn btn-sm btn-<?= $redirect['status'] === 'active' ? 'warning' : 'success' ?>">
                                <i class="fas fa-power-off"></i>
                            </button>
                            <a href="<?= url('admin/redirect/edit/' . $redirect['id']) ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteRedirect(<?= $redirect['id'] ?>)" 
                                    class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($pages > 1): ?>
            <nav>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleStatus(id) {
    fetch(`<?= url('admin/redirect/toggle/') ?>${id}`, {method: 'POST'})
        .then(() => location.reload());
}

function deleteRedirect(id) {
    if (confirm('Delete this redirect?')) {
        location.href = `<?= url('admin/redirect/delete/') ?>${id}`;
    }
}

$('#redirectTable').DataTable();
</script>
