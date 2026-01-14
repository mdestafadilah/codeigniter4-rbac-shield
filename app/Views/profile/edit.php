<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit Profile</h1>
            <p class="page-subtitle">Update your personal information</p>
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
                    <i class="fas fa-user-edit me-2"></i>Personal Information
                </h5>
            </div>
            <div class="card-body">
                <?= form_open('/profile/update') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= esc($user['username']) ?>" required>
                                <div class="form-text">Your unique username for login</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= esc($user['email']) ?>" required>
                                <div class="form-text">Your email address for notifications</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Your username and email must be unique. Changes will be reflected immediately after saving.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('/profile') ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
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
                    <i class="fas fa-user-circle me-2"></i>Current Profile
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
                    <i class="fas fa-lightbulb me-2"></i>Tips
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <ul class="mb-0 small">
                        <li>Choose a memorable username</li>
                        <li>Use a valid email address</li>
                        <li>Username must be at least 3 characters</li>
                        <li>Email must be unique in the system</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shield-alt me-2"></i>Security
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Want to change your password?</p>
                <div class="d-grid">
                    <a href="<?= base_url('/profile/password') ?>" class="btn btn-outline-warning">
                        <i class="fas fa-key me-2"></i>Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>