<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Create New User</h1>
            <p class="page-subtitle">Add a new user to the system</p>
        </div>
        <a href="<?= base_url('users') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
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
                <?= form_open('users/store') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                                       id="username" 
                                       name="username" 
                                       value="<?= old('username') ?>" 
                                       placeholder="Enter username">
                                <div class="form-text">Unique username for login</div>
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback"><?= $errors['username'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" 
                                       name="email" 
                                       value="<?= old('email') ?>" 
                                       placeholder="Enter email address">
                                <div class="form-text">Valid email address</div>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password-icon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimum 6 characters</div>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback"><?= $errors['password'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           placeholder="Confirm password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                        <i class="fas fa-eye" id="confirm_password-icon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Must match password</div>
                                <?php if (isset($errors['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role_id" class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($errors['role_id']) ? 'is-invalid' : '' ?>" 
                                        id="role_id" 
                                        name="role_id">
                                    <option value="">Select a role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id'] ?>" <?= old('role_id') == $role['id'] ? 'selected' : '' ?>>
                                            <?= esc($role['display_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">User's system role</div>
                                <?php if (isset($errors['role_id'])): ?>
                                    <div class="invalid-feedback"><?= $errors['role_id'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="true" 
                                           <?= old('is_active', true) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Active User</strong>
                                        <div class="form-text">Active users can log in to the system</div>
                                    </label>
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
                    <i class="fas fa-info-circle me-2"></i>User Guidelines
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                    <ul class="mb-0 small">
                        <li>Choose a unique username that's easy to remember</li>
                        <li>Use a strong password with at least 6 characters</li>
                        <li>Assign appropriate roles based on user responsibilities</li>
                        <li>Verify email address for password recovery</li>
                    </ul>
                </div>
                <div class="alert alert-warning">
                    <h6><i class="fas fa-shield-alt me-2"></i>Security</h6>
                    <ul class="mb-0 small">
                        <li>Passwords are automatically encrypted</li>
                        <li>Users receive permissions based on their assigned role</li>
                        <li>Inactive users cannot access the system</li>
                        <li>Regular password updates are recommended</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <a href="<?= base_url('users') ?>" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Create User
            </button>
        </div>
    </div>
</div>

<?= form_close() ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
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

// Password strength indicator
$('#password').on('input', function() {
    const password = $(this).val();
    const strength = checkPasswordStrength(password);
    
    // Update UI based on strength
    $('#password').removeClass('is-valid is-invalid');
    if (password.length > 0) {
        if (strength.score >= 3) {
            $('#password').addClass('is-valid');
        } else if (strength.score < 2) {
            $('#password').addClass('is-invalid');
        }
    }
});

function checkPasswordStrength(password) {
    let score = 0;
    const checks = [
        password.length >= 6,
        /[a-z]/.test(password),
        /[A-Z]/.test(password),
        /[0-9]/.test(password),
        /[^A-Za-z0-9]/.test(password)
    ];
    
    score = checks.filter(Boolean).length;
    
    return { score, checks };
}

// Confirm password validation
$('#confirm_password').on('input', function() {
    const password = $('#password').val();
    const confirm = $(this).val();
    
    $(this).removeClass('is-valid is-invalid');
    if (confirm.length > 0) {
        if (password === confirm) {
            $(this).addClass('is-valid');
        } else {
            $(this).addClass('is-invalid');
        }
    }
});
</script>
<?= $this->endSection() ?>