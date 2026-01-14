<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Roles Management</h1>
            <p class="page-subtitle">Manage system roles and their permissions</p>
        </div>
        <a href="<?= base_url('roles/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Role
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-user-tag me-2"></i>All Roles
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($roles)): ?>
            <div class="text-center py-5">
                <i class="fas fa-user-tag fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No roles found</h5>
                <p class="text-muted">Create your first role to get started.</p>
                <a href="<?= base_url('roles/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Role
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Display Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roles as $role): ?>
                        <tr>
                            <td><?= $role['id'] ?></td>
                            <td>
                                <span class="fw-semibold"><?= esc($role['name']) ?></span>
                            </td>
                            <td><?= esc($role['display_name']) ?></td>
                            <td>
                                <span class="text-muted">
                                    <?= esc($role['description']) ? substr(esc($role['description']), 0, 50) . '...' : '-' ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($role['is_active']): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Active
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>Inactive
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y', strtotime($role['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('roles/' . $role['id']) ?>" 
                                       class="btn btn-sm btn-outline-info" 
                                       data-bs-toggle="tooltip" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('roles/' . $role['id'] . '/edit') ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit Role">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-<?= $role['is_active'] ? 'warning' : 'success' ?>" 
                                            onclick="toggleStatus(<?= $role['id'] ?>, <?= $role['is_active'] ?>)"
                                            data-bs-toggle="tooltip" 
                                            title="<?= $role['is_active'] ? 'Deactivate' : 'Activate' ?> Role">
                                        <i class="fas fa-<?= $role['is_active'] ? 'pause' : 'play' ?>"></i>
                                    </button>
                                    <?php if ($role['name'] !== 'super_admin'): ?>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete('<?= base_url('roles/' . $role['id'] . '/delete') ?>', 'Role')"
                                            data-bs-toggle="tooltip" 
                                            title="Delete Role">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleStatus(roleId, currentStatus) {
    const action = currentStatus ? 'deactivate' : 'activate';
    
    if (confirm(`Are you sure you want to ${action} this role?`)) {
        fetch(`<?= base_url('roles') ?>/${roleId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to update role status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating role status');
        });
    }
}

// Initialize tooltips
$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
<?= $this->endSection() ?>