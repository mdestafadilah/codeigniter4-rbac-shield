<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Users Management</h1>
            <p class="page-subtitle">Manage system users and their roles</p>
        </div>
        <a href="<?= base_url('users/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New User
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-users me-2"></i>All Users
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($users)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No users found</h5>
                <p class="text-muted">Create your first user to get started.</p>
                <a href="<?= base_url('users/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New User
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?= esc($user['username']) ?></div>
                                        <small class="text-muted">ID: <?= $user['id'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><?= esc($user['email']) ?></div>
                            </td>
                            <td>
                                <?php if ($user['role_display_name']): ?>
                                    <span class="badge bg-<?= $user['role_name'] === 'super_admin' ? 'danger' : ($user['role_name'] === 'admin' ? 'warning' : 'info') ?>">
                                        <i class="fas fa-user-tag me-1"></i><?= esc($user['role_display_name']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-user me-1"></i><?= ucfirst(esc($user['role'])) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Active
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>Inactive
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['last_login']): ?>
                                    <small class="text-muted">
                                        <?= date('M d, Y H:i', strtotime($user['last_login'])) ?>
                                    </small>
                                <?php else: ?>
                                    <small class="text-muted">Never</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y', strtotime($user['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('users/' . $user['id']) ?>" 
                                       class="btn btn-sm btn-outline-info" 
                                       data-bs-toggle="tooltip" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('users/' . $user['id'] . '/edit') ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($user['id'] != session()->get('user_id') && $user['username'] !== 'admin'): ?>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-<?= $user['is_active'] ? 'warning' : 'success' ?>" 
                                            onclick="toggleStatus(<?= $user['id'] ?>, <?= $user['is_active'] ?>)"
                                            data-bs-toggle="tooltip" 
                                            title="<?= $user['is_active'] ? 'Deactivate' : 'Activate' ?> User">
                                        <i class="fas fa-<?= $user['is_active'] ? 'pause' : 'play' ?>"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete('<?= base_url('users/' . $user['id'] . '/delete') ?>', 'User')"
                                            data-bs-toggle="tooltip" 
                                            title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleStatus(userId, currentStatus) {
    const action = currentStatus ? 'nonaktifkan' : 'aktifkan';
    const actionTitle = currentStatus ? 'Nonaktifkan' : 'Aktifkan';
    
    Swal.fire({
        title: `${actionTitle} User?`,
        text: `Apakah Anda yakin ingin ${action} user ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: currentStatus ? '#f59e0b' : '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: `Ya, ${actionTitle}!`,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memperbarui status user',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`<?= base_url('users') ?>/${userId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccess(`User berhasil ${action}!`, 'Berhasil!');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showError(
                        data.message || `Gagal ${action} user`, 
                        'Operasi Gagal!',
                        data.details || 'Silakan coba lagi'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError(
                    `Terjadi kesalahan saat ${action} user`,
                    'Kesalahan Jaringan!',
                    error.message || 'Periksa koneksi internet Anda'
                );
            });
        }
    });
}

// Initialize tooltips
$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
<?= $this->endSection() ?>