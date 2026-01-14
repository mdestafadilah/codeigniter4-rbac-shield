<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit User</h1>
            <p class="page-subtitle">Update user information and settings</p>
        </div>
        <a href="<?= base_url('/users') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-edit me-2"></i>User Information
                </h5>
            </div>
            <div class="card-body">
                <?= form_open('/users/' . $user['id'] . '/update') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= esc($user['username']) ?>" required>
                                <div class="form-text">Unique username for login</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= esc($user['email']) ?>" required>
                                <div class="form-text">Valid email address for notifications</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" id="role_id" name="role_id" required>
                                    <option value="">Select Role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id'] ?>" <?= $user['role_id'] == $role['id'] ? 'selected' : '' ?>>
                                            <?= esc($role['display_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">User's assigned role</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <select class="form-select" id="is_active" name="is_active">
                                    <option value="true" <?= $user['is_active'] ? 'selected' : '' ?>>Active</option>
                                    <option value="false" <?= !$user['is_active'] ? 'selected' : '' ?>>Inactive</option>
                                </select>
                                <div class="form-text">User account status</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="change_password" name="change_password" onchange="togglePasswordFields()">
                            <label class="form-check-label" for="change_password">
                                Change Password
                            </label>
                        </div>
                    </div>

                    <div id="password-fields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password_icon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Minimum 6 characters</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                            <i class="fas fa-eye" id="confirm_password_icon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Re-enter the new password</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Username and email must be unique. Leave password fields empty to keep current password.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('/users') ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update User
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
                    <i class="fas fa-user-circle me-2"></i>Current User
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                    <i class="fas fa-user text-white" style="font-size: 2.5rem;"></i>
                </div>
                <h6 class="mb-1"><?= esc($user['username']) ?></h6>
                <p class="text-muted small mb-3"><?= esc($user['email']) ?></p>
                
                <div class="row text-start">
                    <div class="col-12">
                        <div class="mb-2">
                            <small class="text-muted">Current Role:</small><br>
                            <small class="fw-semibold"><?= esc($user['role_name'] ?? 'No Role') ?></small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Status:</small><br>
                            <small>
                                <span class="badge bg-<?= $user['is_active'] ? 'success' : 'danger' ?>">
                                    <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Member since:</small><br>
                            <small><?= date('F j, Y', strtotime($user['created_at'])) ?></small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Last updated:</small><br>
                            <small><?= $user['updated_at'] ? date('F j, Y', strtotime($user['updated_at'])) : 'Never' ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lightbulb me-2"></i>Guidelines
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <ul class="mb-0 small">
                        <li>Username must be unique and at least 3 characters</li>
                        <li>Email address must be valid and unique</li>
                        <li>Choose appropriate role for user permissions</li>
                        <li>Only change password if necessary</li>
                        <li>Inactive users cannot login to the system</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
function togglePasswordFields() {
    const checkbox = document.getElementById('change_password');
    const passwordFields = document.getElementById('password-fields');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (checkbox.checked) {
        passwordFields.style.display = 'block';
        passwordInput.required = true;
        confirmPasswordInput.required = true;
    } else {
        passwordFields.style.display = 'none';
        passwordInput.required = false;
        confirmPasswordInput.required = false;
        passwordInput.value = '';
        confirmPasswordInput.value = '';
    }
}

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