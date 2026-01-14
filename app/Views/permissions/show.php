<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Permission Details</h1>
            <p class="page-subtitle">View permission information and assigned roles</p>
        </div>
        <div>
            <?php if (has_permission('permissions.edit')): ?>
            <a href="<?= base_url('/permissions/edit/' . $permission['id']) ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit Permission
            </a>
            <?php endif; ?>
            <a href="<?= base_url('/permissions') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Permissions
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-key me-2"></i>Permission Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Permission Name</label>
                            <div class="fs-5 font-monospace text-primary"><?= esc($permission['name']) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Display Name</label>
                            <div class="fs-5"><?= esc($permission['display_name']) ?></div>
                        </div>
                    </div>
                </div>

                <?php if ($permission['description']): ?>
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Description</label>
                    <div class="text-dark"><?= esc($permission['description']) ?></div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Category</label>
                            <div>
                                <?php 
                                $category = explode('.', $permission['name'])[0] ?? 'general';
                                $categoryColors = [
                                    'users' => 'primary',
                                    'roles' => 'success', 
                                    'permissions' => 'warning',
                                    'mahasiswa' => 'info',
                                    'dashboard' => 'secondary'
                                ];
                                $color = $categoryColors[$category] ?? 'dark';
                                ?>
                                <span class="badge bg-<?= $color ?> fs-6">
                                    <i class="fas fa-folder me-1"></i><?= ucfirst($category) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Roles with this Permission</label>
                            <div>
                                <span class="badge bg-primary fs-6">
                                    <i class="fas fa-user-tag me-1"></i><?= count($roles) ?> Roles
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Created</label>
                            <div class="text-dark"><?= date('F j, Y \a\t g:i A', strtotime($permission['created_at'])) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Last Updated</label>
                            <div class="text-dark">
                                <?= $permission['updated_at'] ? date('F j, Y \a\t g:i A', strtotime($permission['updated_at'])) : 'Never' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-tag me-2"></i>Roles with this Permission
                    <span class="badge bg-primary ms-2"><?= count($roles) ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($roles)): ?>
                <div class="row">
                    <?php foreach ($roles as $role): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user-tag text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?= esc($role['display_name']) ?></div>
                                        <small class="text-muted"><?= esc($role['name']) ?></small>
                                        <?php if (!$role['is_active']): ?>
                                        <div><small class="badge bg-warning">Inactive</small></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if (has_permission('roles.view')): ?>
                                <div class="mt-2">
                                    <a href="<?= base_url('/roles/show/' . $role['id']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View Role
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-user-tag text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Roles Assigned</h5>
                    <p class="text-muted">This permission is not assigned to any roles yet.</p>
                    <?php if (has_permission('roles.edit')): ?>
                    <a href="<?= base_url('/roles') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Assign to Role
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Permission Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Assigned to Roles</span>
                    <span class="badge bg-primary"><?= count($roles) ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Active Roles</span>
                    <span class="badge bg-success">
                        <?= count(array_filter($roles, function($role) { return $role['is_active']; })) ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Permission Age</span>
                    <span class="badge bg-info">
                        <?php
                        $created = new DateTime($permission['created_at']);
                        $now = new DateTime();
                        $diff = $created->diff($now);
                        if ($diff->y > 0) {
                            echo $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
                        } elseif ($diff->m > 0) {
                            echo $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
                        } else {
                            echo $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
                        }
                        ?>
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Permission ID</span>
                    <span class="badge bg-dark">#<?= $permission['id'] ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Permission Details
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">System Name</label>
                    <div class="font-monospace bg-light p-2 rounded border"><?= esc($permission['name']) ?></div>
                </div>
                
                <?php if ($permission['description']): ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Purpose</label>
                    <div class="text-muted small"><?= esc($permission['description']) ?></div>
                </div>
                <?php endif; ?>

                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <small>
                        <strong>Note:</strong> This permission controls access to specific features or actions within the system.
                    </small>
                </div>
            </div>
        </div>

        <?php if (has_permission('permissions.edit') || has_permission('permissions.delete')): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <?php if (has_permission('permissions.edit')): ?>
                <div class="d-grid gap-2 mb-3">
                    <a href="<?= base_url('/permissions/edit/' . $permission['id']) ?>" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Permission
                    </a>
                </div>
                <?php endif; ?>
                <?php if (has_permission('permissions.delete') && count($roles) == 0): ?>
                <div class="d-grid gap-2">
                    <button onclick="confirmDelete('<?= base_url('/permissions/delete/' . $permission['id']) ?>', 'permission')" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>Delete Permission
                    </button>
                </div>
                <?php elseif (count($roles) > 0): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <small>Cannot delete permission assigned to roles</small>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>