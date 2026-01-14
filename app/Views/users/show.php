<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">User Details</h1>
            <p class="page-subtitle">View user information and permissions</p>
        </div>
        <div>
            <?php if (has_permission('users.edit')): ?>
            <a href="<?= base_url('/users/edit/' . $user['id']) ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit User
            </a>
            <?php endif; ?>
            <a href="<?= base_url('/users') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>User Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Username</label>
                            <div class="fs-5"><?= esc($user['username']) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Email</label>
                            <div class="fs-5"><?= esc($user['email']) ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Role</label>
                            <div>
                                <?php if ($user['role_name']): ?>
                                    <span class="badge bg-primary fs-6">
                                        <i class="fas fa-user-tag me-1"></i><?= esc($user['role_display_name'] ?? $user['role_name']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary fs-6">No Role Assigned</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Status</label>
                            <div>
                                <?php if ($user['is_active']): ?>
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
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Created</label>
                            <div class="text-dark"><?= date('F j, Y \a\t g:i A', strtotime($user['created_at'])) ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted">Last Updated</label>
                            <div class="text-dark">
                                <?= $user['updated_at'] ? date('F j, Y \a\t g:i A', strtotime($user['updated_at'])) : 'Never' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($permissions)): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-key me-2"></i>Permissions
                </h5>
            </div>
            <div class="card-body">
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
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>User Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Total Permissions</span>
                    <span class="badge bg-primary"><?= count($permissions ?? []) ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Account Age</span>
                    <span class="badge bg-info">
                        <?php
                        $created = new DateTime($user['created_at']);
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
                    <span class="text-muted">User ID</span>
                    <span class="badge bg-secondary">#<?= $user['id'] ?></span>
                </div>
            </div>
        </div>

        <?php if (has_permission('users.edit') || has_permission('users.delete')): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <?php if (has_permission('users.edit')): ?>
                <div class="d-grid gap-2 mb-3">
                    <a href="<?= base_url('/users/edit/' . $user['id']) ?>" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit User Details
                    </a>
                </div>
                <div class="d-grid gap-2 mb-3">
                    <a href="<?= base_url('/users/toggle/' . $user['id']) ?>" class="btn btn-outline-<?= $user['is_active'] ? 'warning' : 'success' ?>">
                        <i class="fas fa-<?= $user['is_active'] ? 'pause' : 'play' ?> me-2"></i>
                        <?= $user['is_active'] ? 'Deactivate' : 'Activate' ?> User
                    </a>
                </div>
                <?php endif; ?>
                <?php if (has_permission('users.delete') && $user['id'] != session()->get('user_id')): ?>
                <div class="d-grid gap-2">
                    <button onclick="confirmDelete('<?= base_url('/users/delete/' . $user['id']) ?>', 'user')" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i>Delete User
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>