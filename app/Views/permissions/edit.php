<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit Permission</h1>
            <p class="page-subtitle">Update permission information and settings</p>
        </div>
        <a href="<?= base_url('/permissions') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Permissions
        </a>
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
                <?= form_open('/permissions/' . $permission['id'] . '/update') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control font-monospace" id="name" name="name" 
                                       value="<?= esc($permission['name']) ?>" required>
                                <div class="form-text">System name (e.g., users.view, roles.create)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="display_name" name="display_name" 
                                       value="<?= esc($permission['display_name']) ?>" required>
                                <div class="form-text">Human-readable name for the UI</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="module" class="form-label">Module <span class="text-danger">*</span></label>
                                <select class="form-select" id="module" name="module" required>
                                    <option value="">Select Module</option>
                                    <?php foreach ($modules as $moduleData): ?>
                                        <?php $moduleName = $moduleData['module']; ?>
                                        <option value="<?= esc($moduleName) ?>" <?= $permission['module'] == $moduleName ? 'selected' : '' ?>>
                                            <?= ucfirst($moduleName) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Permission category/module</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="action" class="form-label">Action <span class="text-danger">*</span></label>
                                <select class="form-select" id="action" name="action" required>
                                    <option value="">Select Action</option>
                                    <option value="view" <?= (explode('.', $permission['name'])[1] ?? '') == 'view' ? 'selected' : '' ?>>View</option>
                                    <option value="create" <?= (explode('.', $permission['name'])[1] ?? '') == 'create' ? 'selected' : '' ?>>Create</option>
                                    <option value="edit" <?= (explode('.', $permission['name'])[1] ?? '') == 'edit' ? 'selected' : '' ?>>Edit</option>
                                    <option value="delete" <?= (explode('.', $permission['name'])[1] ?? '') == 'delete' ? 'selected' : '' ?>>Delete</option>
                                    <option value="manage" <?= (explode('.', $permission['name'])[1] ?? '') == 'manage' ? 'selected' : '' ?>>Manage</option>
                                </select>
                                <div class="form-text">Permission action type</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= esc($permission['description']) ?></textarea>
                        <div class="form-text">Optional description of what this permission allows</div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Permission name should follow the format "module.action" (e.g., users.view, roles.create). Changes will affect all roles that have this permission.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('/permissions') ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Permission
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
                    <i class="fas fa-key me-2"></i>Current Permission
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">System Name</label>
                    <div class="font-monospace bg-light p-2 rounded border"><?= esc($permission['name']) ?></div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Display Name</label>
                    <div><?= esc($permission['display_name']) ?></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Module</label>
                    <div>
                        <?php 
                        $moduleColors = [
                            'users' => 'primary',
                            'roles' => 'success', 
                            'permissions' => 'warning',
                            'mahasiswa' => 'info',
                            'dashboard' => 'secondary'
                        ];
                        $color = $moduleColors[$permission['module']] ?? 'dark';
                        ?>
                        <span class="badge bg-<?= $color ?>">
                            <i class="fas fa-folder me-1"></i><?= ucfirst($permission['module']) ?>
                        </span>
                    </div>
                </div>

                <?php if ($permission['description']): ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted">Description</label>
                    <div class="text-muted small"><?= esc($permission['description']) ?></div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-2">
                            <small class="text-muted">Created:</small><br>
                            <small><?= date('F j, Y', strtotime($permission['created_at'])) ?></small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Last updated:</small><br>
                            <small><?= $permission['updated_at'] ? date('F j, Y', strtotime($permission['updated_at'])) : 'Never' ?></small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Permission ID:</small><br>
                            <small class="font-monospace">#<?= $permission['id'] ?></small>
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
                        <li>Permission name should be lowercase and use dots</li>
                        <li>Follow the format: module.action</li>
                        <li>Display name should be user-friendly</li>
                        <li>Module groups related permissions together</li>
                        <li>Description helps explain the permission's purpose</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-tag me-2"></i>Usage
                </h5>
            </div>
            <div class="card-body">
                <?php if (has_permission('roles.view')): ?>
                    <a href="<?= base_url('/permissions/show/' . $permission['id']) ?>" class="btn btn-outline-info w-100">
                        <i class="fas fa-eye me-2"></i>View Assigned Roles
                    </a>
                <?php else: ?>
                    <div class="text-muted text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Permission usage details available in view mode</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
// Auto-generate permission name based on module and action selection
document.getElementById('module').addEventListener('change', updatePermissionName);
document.getElementById('action').addEventListener('change', updatePermissionName);

function updatePermissionName() {
    const module = document.getElementById('module').value;
    const action = document.getElementById('action').value;
    const nameField = document.getElementById('name');
    
    if (module && action) {
        nameField.value = module + '.' + action;
    }
}

// Auto-generate display name based on module and action
document.getElementById('module').addEventListener('change', updateDisplayName);
document.getElementById('action').addEventListener('change', updateDisplayName);

function updateDisplayName() {
    const module = document.getElementById('module').value;
    const action = document.getElementById('action').value;
    const displayNameField = document.getElementById('display_name');
    
    if (module && action && !displayNameField.value) {
        const actionNames = {
            'view': 'View',
            'create': 'Create',
            'edit': 'Edit',
            'delete': 'Delete',
            'manage': 'Manage'
        };
        
        displayNameField.value = actionNames[action] + ' ' + module.charAt(0).toUpperCase() + module.slice(1);
    }
}
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>