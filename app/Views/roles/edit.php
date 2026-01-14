<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit Role</h1>
            <p class="page-subtitle">Update role information and permissions</p>
        </div>
        <a href="<?= base_url('roles') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Roles
        </a>
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
                <?= form_open('roles/' . $role['id'] . '/update') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                       id="name" 
                                       name="name" 
                                       value="<?= old('name', $role['name']) ?>" 
                                       <?= $role['name'] === 'super_admin' ? 'readonly' : '' ?>
                                       placeholder="e.g., manager, editor">
                                <div class="form-text">Unique identifier for the role</div>
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['name'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['display_name']) ? 'is-invalid' : '' ?>" 
                                       id="display_name" 
                                       name="display_name" 
                                       value="<?= old('display_name', $role['display_name']) ?>" 
                                       placeholder="e.g., Manager, Content Editor">
                                <div class="form-text">Human-readable name for the role</div>
                                <?php if (isset($errors['display_name'])): ?>
                                    <div class="invalid-feedback"><?= $errors['display_name'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Brief description of the role's purpose and responsibilities"><?= old('description', $role['description']) ?></textarea>
                        <div class="form-text">Optional description to explain the role's purpose</div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="true" 
                                   <?= old('is_active', $role['is_active']) ? 'checked' : '' ?>
                                   <?= $role['name'] === 'super_admin' ? 'disabled' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                <strong>Active Role</strong>
                                <div class="form-text">Active roles can be assigned to users</div>
                            </label>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Role Details
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Created</label>
                    <div class="text-muted"><?= date('F j, Y \a\t g:i A', strtotime($role['created_at'])) ?></div>
                </div>
                <?php if ($role['updated_at']): ?>
                <div class="mb-3">
                    <label class="form-label">Last Updated</label>
                    <div class="text-muted"><?= date('F j, Y \a\t g:i A', strtotime($role['updated_at'])) ?></div>
                </div>
                <?php endif; ?>
                
                <?php if ($role['name'] === 'super_admin'): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>System Role</strong><br>
                    This is a system role with restrictions on modifications.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-key me-2"></i>Assign Permissions
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($permissions)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No permissions available. Please create permissions first.
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                            <i class="fas fa-check-square me-1"></i>Select All
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">
                            <i class="fas fa-square me-1"></i>Deselect All
                        </button>
                        <div class="ms-auto">
                            <span class="badge bg-info">
                                <span id="selectedCount">0</span> permissions selected
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $groupedPermissions = [];
            foreach ($permissions as $permission) {
                $module = $permission['module'] ?? 'General';
                $groupedPermissions[$module][] = $permission;
            }
            ?>

            <div class="row">
                <?php foreach ($groupedPermissions as $module => $modulePermissions): ?>
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="border rounded p-3 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-folder me-2"></i><?= ucfirst($module) ?>
                            </h6>
                            <div class="ms-auto">
                                <button type="button" 
                                        class="btn btn-sm btn-link p-0" 
                                        onclick="toggleModule('<?= $module ?>')">
                                    <i class="fas fa-check-square text-primary"></i>
                                </button>
                            </div>
                        </div>
                        <?php foreach ($modulePermissions as $permission): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input permission-checkbox module-<?= $module ?>" 
                                   type="checkbox" 
                                   name="permissions[]" 
                                   value="<?= $permission['id'] ?>" 
                                   id="permission_<?= $permission['id'] ?>"
                                   <?= in_array($permission['id'], $assignedPermissions) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="permission_<?= $permission['id'] ?>">
                                <strong><?= esc($permission['display_name']) ?></strong>
                                <?php if ($permission['description']): ?>
                                    <div class="small text-muted"><?= esc($permission['description']) ?></div>
                                <?php endif; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <a href="<?= base_url('roles') ?>" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Update Role
            </button>
        </div>
    </div>
</div>

<?= form_close() ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function selectAll() {
    $('.permission-checkbox').prop('checked', true);
    updateSelectedCount();
}

function deselectAll() {
    $('.permission-checkbox').prop('checked', false);
    updateSelectedCount();
}

function toggleModule(module) {
    const checkboxes = $(`.module-${module}`);
    const checkedCount = checkboxes.filter(':checked').length;
    const totalCount = checkboxes.length;
    
    if (checkedCount === totalCount) {
        checkboxes.prop('checked', false);
    } else {
        checkboxes.prop('checked', true);
    }
    updateSelectedCount();
}

function updateSelectedCount() {
    const selectedCount = $('.permission-checkbox:checked').length;
    $('#selectedCount').text(selectedCount);
}

// Initialize selected count
$(document).ready(function() {
    updateSelectedCount();
    
    // Update count when checkboxes change
    $('.permission-checkbox').on('change', function() {
        updateSelectedCount();
    });
});
</script>
<?= $this->endSection() ?>