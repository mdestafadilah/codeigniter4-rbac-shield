<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Role Details</h1>
            <p class="page-subtitle">View role information and assigned permissions</p>
        </div>
        <div>
            <?php if (has_permission('roles.edit')): ?>
            <a href="<?= base_url('/roles/edit/' . $role['id']) ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit Role
            </a>
            <?php endif; ?>
            <a href="<?= base_url('/roles') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Roles
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-tag me-2"></i>Role Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Role Name</label>
                            <div class="fs-5"><?= esc($role['name']) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Display Name</label>
                            <div class="fs-5"><?= esc($role['display_name']) ?></div>
                        </div>
                    </div>
                </div>

                <?php if ($role['description']): ?>
                <div class="mb-4">
                    <label class="form-label fw-semibold text-muted">Description</label>
                    <div class="text-dark"><?= esc($role['description']) ?></div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Status</label>
                            <div>
                                <?php if ($role['is_active']): ?>
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-times-circle me-1"></i>Inactive
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Users with this Role</label>
                            <div>
                                <span class="badge bg-primary fs-6">
                                    <i class="fas fa-users me-1"></i><?= $userCount ?> Users
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Created</label>
                            <div class="text-dark"><?= date('F j, Y \a\t g:i A', strtotime($role['created_at'])) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Last Updated</label>
                            <div class="text-dark">
                                <?= $role['updated_at'] ? date('F j, Y \a\t g:i A', strtotime($role['updated_at'])) : 'Never' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-key me-2"></i>Assigned Permissions
                    <span class="badge bg-primary ms-2"><?= count($permissions) ?></span>
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($permissions)): ?>
                <div class="row">
                    <?php foreach ($permissions as $permission): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="d-flex align-items-center p-3 bg-light rounded">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-shield-alt text-white"></i>
                            </div>
                            <div>
                                <div class="fw-semibold"><?= esc($permission['display_name'] ?? $permission['name']) ?></div>
                                <small class="text-muted"><?= esc($permission['name']) ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-key text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No Permissions Assigned</h5>
                    <p class="text-muted">This role doesn't have any permissions assigned yet.</p>
                    <?php if (has_permission('roles.edit')): ?>
                    <a href="<?= base_url('/roles/edit/' . $role['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Assign Permissions
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
                    <i class="fas fa-chart-bar me-2"></i>Role Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Total Permissions</span>
                    <span class="badge bg-primary"><?= count($permissions) ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Users Assigned</span>
                    <span class="badge bg-info"><?= $userCount ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Role Age</span>
                    <span class="badge bg-secondary">
                        <?php
                        $created = new DateTime($role['created_at']);
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
                    <span class="text-muted">Role ID</span>
                    <span class="badge bg-dark">#<?= $role['id'] ?></span>
                </div>
            </div>
        </div>

        <?php if (has_permission('roles.edit') || has_permission('roles.delete')): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <?php if (has_permission('roles.edit')): ?>
                <div class="d-grid gap-2 mb-3">
                    <a href="<?= base_url('/roles/edit/' . $role['id']) ?>" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Role Details
                    </a>
                </div>
                <div class="d-grid gap-2 mb-3">
                    <a href="<?= base_url('/roles/toggle/' . $role['id']) ?>" class="btn btn-outline-<?= $role['is_active'] ? 'warning' : 'success' ?>">
                        <i class="fas fa-<?= $role['is_active'] ? 'pause' : 'play' ?> me-2"></i>
                        <?= $role['is_active'] ? 'Deactivate' : 'Activate' ?> Role
                    </a>
                </div>
                <?php endif; ?>
                <?php if (has_permission('roles.delete') && $userCount == 0): ?>
                <div class="d-grid gap-2">
                    <button onclick="confirmDelete('<?= base_url('/roles/delete/' . $role['id']) ?>', 'role')" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>Delete Role
                    </button>
                </div>
                <?php elseif ($userCount > 0): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <small>Cannot delete role with assigned users</small>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($userCount > 0): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Users with this Role
                </h5>
            </div>
            <div class="card-body">
                <?php if (has_permission('users.view')): ?>
                <a href="<?= base_url('/users?role=' . $role['id']) ?>" class="btn btn-outline-info w-100">
                    <i class="fas fa-eye me-2"></i>View All Users (<?= $userCount ?>)
                </a>
                <?php else: ?>
                <div class="text-muted text-center">
                    <i class="fas fa-users" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0"><?= $userCount ?> users have this role</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>