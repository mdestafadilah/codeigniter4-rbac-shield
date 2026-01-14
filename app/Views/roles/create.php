<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Create New Role</h1>
            <p class="page-subtitle">Add a new role and assign permissions</p>
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
                <?= form_open('roles/store') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                       id="name" 
                                       name="name" 
                                       value="<?= old('name') ?>" 
                                       placeholder="e.g., manager, editor">
                                <div class="form-text">Unique identifier for the role (lowercase, no spaces)</div>
                        
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control <?= isset($errors['display_name']) ? 'is-invalid' : '' ?>" 
                                       id="display_name" 
                                       name="display_name" 
                                       value="<?= old('display_name') ?>" 
                                       placeholder="e.g., Manager, Content Editor">
                                <div class="form-text">Human-readable name for the role</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="3" 
                                  placeholder="Brief description of the role's purpose and responsibilities"><?= old('description') ?></textarea>
                        <div class="form-text">Optional description to explain the role's purpose</div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="true" 
                                   <?= old('is_active', true) ? 'checked' : '' ?>>
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
                    <i class="fas fa-info-circle me-2"></i>Role Guidelines
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                    <ul class="mb-0 small">
                        <li>Use descriptive names that clearly indicate the role's purpose</li>
                        <li>Role names should be lowercase and use underscores instead of spaces</li>
                        <li>Consider creating roles based on job functions or access levels</li>
                        <li>Review permissions carefully before creating the role</li>
                    </ul>
                </div>
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Important</h6>
                    <ul class="mb-0 small">
                        <li>Role names cannot be changed after creation</li>
                        <li>Inactive roles cannot be assigned to users</li>
                        <li>System roles (like super_admin) have special restrictions</li>
                    </ul>
                </div>
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
                                   id="permission_<?= $permission['id'] ?>">
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
                <i class="fas fa-save me-2"></i>Create Role
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
}

function deselectAll() {
    $('.permission-checkbox').prop('checked', false);
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
}

// Auto-generate display name from name
$('#name').on('input', function() {
    if (!$('#display_name').val()) {
        const name = $(this).val();
        const displayName = name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        $('#display_name').val(displayName);
    }
});
</script>
<?= $this->endSection() ?>