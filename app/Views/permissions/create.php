<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Create Permission</h1>
            <p class="page-subtitle">Add a new permission to the system</p>
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
                <?= form_open('/permissions/store') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="module" class="form-label">Module <span class="text-danger">*</span></label>
                                <select class="form-select" id="module" name="module" required>
                                    <option value="">Select Module</option>
                                    <?php foreach ($modules as $moduleData): ?>
                                        <?php $moduleName = $moduleData['module']; ?>
                                        <option value="<?= esc($moduleName) ?>"><?= ucfirst($moduleName) ?></option>
                                    <?php endforeach; ?>
                                    <option value="custom">Custom Module</option>
                                </select>
                                <div class="form-text">Permission category/module</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="action" class="form-label">Action <span class="text-danger">*</span></label>
                                <select class="form-select" id="action" name="action" required>
                                    <option value="">Select Action</option>
                                    <option value="view">View</option>
                                    <option value="create">Create</option>
                                    <option value="edit">Edit</option>
                                    <option value="delete">Delete</option>
                                    <option value="manage">Manage</option>
                                </select>
                                <div class="form-text">Permission action type</div>
                            </div>
                        </div>
                    </div>

                    <div id="custom-module" style="display: none;">
                        <div class="mb-3">
                            <label for="custom_module_name" class="form-label">Custom Module Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="custom_module_name" name="custom_module_name" 
                                   placeholder="Enter custom module name">
                            <div class="form-text">Enter a new module name (lowercase, no spaces)</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control font-monospace" id="name" name="name" 
                                       placeholder="e.g., users.view" required readonly>
                                <div class="form-text">Auto-generated system name</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="display_name" name="display_name" 
                                       placeholder="e.g., View Users" required>
                                <div class="form-text">Human-readable name for the UI</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Optional description of what this permission allows"></textarea>
                        <div class="form-text">Optional description of what this permission allows</div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Permission name will be auto-generated based on module and action selection. This ensures consistency across the system.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('/permissions') ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Permission
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
                    <i class="fas fa-lightbulb me-2"></i>Guidelines
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Tips</h6>
                    <ul class="mb-0 small">
                        <li>Select appropriate module for grouping</li>
                        <li>Choose the correct action type</li>
                        <li>Permission name will be auto-generated</li>
                        <li>Use descriptive display names</li>
                        <li>Add descriptions for clarity</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Common Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong class="text-primary">View:</strong>
                    <p class="small text-muted mb-2">Read/display records and pages</p>
                </div>
                <div class="mb-3">
                    <strong class="text-success">Create:</strong>
                    <p class="small text-muted mb-2">Add new records to the system</p>
                </div>
                <div class="mb-3">
                    <strong class="text-warning">Edit:</strong>
                    <p class="small text-muted mb-2">Modify existing records</p>
                </div>
                <div class="mb-3">
                    <strong class="text-danger">Delete:</strong>
                    <p class="small text-muted mb-2">Remove records from the system</p>
                </div>
                <div class="mb-0">
                    <strong class="text-info">Manage:</strong>
                    <p class="small text-muted mb-0">Full control over the module</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-folder me-2"></i>Available Modules
                </h5>
            </div>
            <div class="card-body">
                <?php foreach ($modules as $moduleData): ?>
                    <?php 
                    $moduleName = $moduleData['module'];
                    $moduleColors = [
                        'users' => 'primary',
                        'roles' => 'success', 
                        'permissions' => 'warning',
                        'mahasiswa' => 'info',
                        'dashboard' => 'secondary'
                    ];
                    $color = $moduleColors[$moduleName] ?? 'dark';
                    ?>
                    <span class="badge bg-<?= $color ?> me-1 mb-1">
                        <i class="fas fa-folder me-1"></i><?= ucfirst($moduleName) ?>
                    </span>
                <?php endforeach; ?>
                <div class="mt-2">
                    <small class="text-muted">Or create a custom module by selecting "Custom Module"</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
// Show/hide custom module field
document.getElementById('module').addEventListener('change', function() {
    const customModuleDiv = document.getElementById('custom-module');
    const customModuleInput = document.getElementById('custom_module_name');
    
    if (this.value === 'custom') {
        customModuleDiv.style.display = 'block';
        customModuleInput.required = true;
    } else {
        customModuleDiv.style.display = 'none';
        customModuleInput.required = false;
        customModuleInput.value = '';
    }
    
    updatePermissionName();
});

// Auto-generate permission name based on module and action selection
document.getElementById('module').addEventListener('change', updatePermissionName);
document.getElementById('action').addEventListener('change', updatePermissionName);
document.getElementById('custom_module_name').addEventListener('input', updatePermissionName);

function updatePermissionName() {
    const moduleSelect = document.getElementById('module');
    const customModuleName = document.getElementById('custom_module_name').value;
    const action = document.getElementById('action').value;
    const nameField = document.getElementById('name');
    
    let module = moduleSelect.value === 'custom' ? customModuleName : moduleSelect.value;
    
    if (module && action) {
        nameField.value = module.toLowerCase() + '.' + action;
    } else {
        nameField.value = '';
    }
}

// Auto-generate display name based on module and action
document.getElementById('module').addEventListener('change', updateDisplayName);
document.getElementById('action').addEventListener('change', updateDisplayName);
document.getElementById('custom_module_name').addEventListener('input', updateDisplayName);

function updateDisplayName() {
    const moduleSelect = document.getElementById('module');
    const customModuleName = document.getElementById('custom_module_name').value;
    const action = document.getElementById('action').value;
    const displayNameField = document.getElementById('display_name');
    
    let module = moduleSelect.value === 'custom' ? customModuleName : moduleSelect.value;
    
    if (module && action) {
        const actionNames = {
            'view': 'View',
            'create': 'Create',
            'edit': 'Edit',
            'delete': 'Delete',
            'manage': 'Manage'
        };
        
        displayNameField.value = actionNames[action] + ' ' + module.charAt(0).toUpperCase() + module.slice(1);
    } else {
        displayNameField.value = '';
    }
}
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>