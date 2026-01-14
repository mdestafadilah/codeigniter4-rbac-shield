<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Permissions Management</h1>
            <p class="page-subtitle">Manage system permissions and access controls</p>
        </div>
        <a href="<?= base_url('permissions/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Permission
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchPermissions" placeholder="Search permissions...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="filterModule">
                            <option value="">All Modules</option>
                            <?php foreach ($modules as $module): ?>
                                <option value="<?= esc($module['module']) ?>"><?= ucfirst(esc($module['module'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-key me-2"></i>All Permissions
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($permissions)): ?>
            <div class="text-center py-5">
                <i class="fas fa-key fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No permissions found</h5>
                <p class="text-muted">Create your first permission to get started.</p>
                <a href="<?= base_url('permissions/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Permission
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
                            <th>Module</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($permissions as $permission): ?>
                        <tr data-module="<?= esc($permission['module']) ?>">
                            <td><?= $permission['id'] ?></td>
                            <td>
                                <code class="text-primary"><?= esc($permission['name']) ?></code>
                            </td>
                            <td>
                                <span class="fw-semibold"><?= esc($permission['display_name']) ?></span>
                            </td>
                            <td>
                                <?php if ($permission['module']): ?>
                                    <span class="badge bg-info">
                                        <i class="fas fa-folder me-1"></i><?= ucfirst(esc($permission['module'])) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="text-muted">
                                    <?= $permission['description'] ? substr(esc($permission['description']), 0, 50) . '...' : '-' ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($permission['is_active']): ?>
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
                                    <?= date('M d, Y', strtotime($permission['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('permissions/' . $permission['id']) ?>" 
                                       class="btn btn-sm btn-outline-info" 
                                       data-bs-toggle="tooltip" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('permissions/' . $permission['id'] . '/edit') ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit Permission">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-<?= $permission['is_active'] ? 'warning' : 'success' ?>" 
                                            onclick="toggleStatus(<?= $permission['id'] ?>, <?= $permission['is_active'] ?>)"
                                            data-bs-toggle="tooltip" 
                                            title="<?= $permission['is_active'] ? 'Deactivate' : 'Activate' ?> Permission">
                                        <i class="fas fa-<?= $permission['is_active'] ? 'pause' : 'play' ?>"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete('<?= base_url('permissions/' . $permission['id'] . '/delete') ?>', 'Permission')"
                                            data-bs-toggle="tooltip" 
                                            title="Delete Permission">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
function toggleStatus(permissionId, currentStatus) {
    const action = currentStatus ? 'deactivate' : 'activate';
    
    if (confirm(`Are you sure you want to ${action} this permission?`)) {
        fetch(`<?= base_url('permissions') ?>/${permissionId}/toggle`, {
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
                alert(data.message || 'Failed to update permission status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating permission status');
        });
    }
}

// Custom search and filter functionality
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Custom search functionality
    $('#searchPermissions').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.data-table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Module filter
    $('#filterModule').on('change', function() {
        var selectedModule = $(this).val();
        
        if (selectedModule === '') {
            $('.data-table tbody tr').show();
        } else {
            $('.data-table tbody tr').each(function() {
                var rowModule = $(this).data('module');
                if (rowModule === selectedModule) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>