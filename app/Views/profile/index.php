<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">My Profile</h1>
            <p class="page-subtitle">View and manage your account information</p>
        </div>
        <div>
            <a href="<?= base_url('/profile/edit') ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit Profile
            </a>
            <a href="<?= base_url('/profile/password') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-key me-2"></i>Change Password
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Personal Information
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
                            <label class="form-label fw-semibold text-muted">Email Address</label>
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
                            <label class="form-label fw-semibold text-muted">Account Status</label>
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
                            <label class="form-label fw-semibold text-muted">Member Since</label>
                            <div class="text-dark"><?= date('F j, Y', strtotime($user['created_at'])) ?></div>
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
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-circle me-2"></i>Profile Avatar
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px;">
                    <i class="fas fa-user text-white" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mb-1"><?= esc($user['username']) ?></h5>
                <p class="text-muted mb-3"><?= esc($user['email']) ?></p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/profile/edit') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Account Statistics
                </h5>
            </div>
            <div class="card-body">
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Last Login</span>
                    <span class="badge bg-secondary">Today</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">User ID</span>
                    <span class="badge bg-dark">#<?= $user['id'] ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/profile/edit') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-user-edit me-2"></i>Edit Profile
                    </a>
                    <a href="<?= base_url('/profile/password') ?>" class="btn btn-outline-warning">
                        <i class="fas fa-key me-2"></i>Change Password
                    </a>
                    <a href="<?= base_url('/dashboard') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>