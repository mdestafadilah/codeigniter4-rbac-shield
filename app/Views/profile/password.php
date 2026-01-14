<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Change Password</h1>
            <p class="page-subtitle">Update your account password</p>
        </div>
        <a href="<?= base_url('/profile') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Profile
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-key me-2"></i>Password Security
                </h5>
            </div>
            <div class="card-body">
                <?= form_open('/profile/update-password') ?>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye" id="current_password_icon"></i>
                            </button>
                        </div>
                        <div class="form-text">Enter your current password to verify identity</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                        <i class="fas fa-eye" id="new_password_icon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimum 6 characters required</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                        <i class="fas fa-eye" id="confirm_password_icon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Re-enter your new password</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> You will remain logged in after changing your password, but any other active sessions will be terminated.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('/profile') ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shield-alt me-2"></i>Password Requirements
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <small>At least 6 characters long</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info text-primary me-2"></i>
                        <small>Use a mix of letters and numbers</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info text-primary me-2"></i>
                        <small>Include special characters for better security</small>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-info text-primary me-2"></i>
                        <small>Avoid using personal information</small>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Security Tips
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <ul class="mb-0 small">
                        <li>Use a unique password you don't use elsewhere</li>
                        <li>Consider using a password manager</li>
                        <li>Change your password regularly</li>
                        <li>Never share your password with others</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Account Info
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Username:</small>
                    <small class="fw-semibold"><?= session()->get('username') ?></small>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Email:</small>
                    <small class="fw-semibold"><?= session()->get('email') ?></small>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Role:</small>
                    <small class="fw-semibold"><?= ucfirst(session()->get('role')) ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>