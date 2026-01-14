<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back, <strong><?= session()->get('username') ?></strong>!</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                <i class="fas fa-sync me-1"></i>Refresh
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-primary me-3">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <div class="h4 mb-0"><?= $total_mahasiswa ?></div>
                    <div class="text-muted">Total Mahasiswa</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-success me-3">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="h4 mb-0">4</div>
                    <div class="text-muted">Active Users</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-warning me-3">
                    <i class="fas fa-user-tag"></i>
                </div>
                <div>
                    <div class="h4 mb-0">4</div>
                    <div class="text-muted">System Roles</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="stats-icon bg-info me-3">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="h4 mb-0"><?= ucfirst(session()->get('role')) ?></div>
                    <div class="text-muted">Your Role</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>Recent Students
                </h5>
                <a href="<?= base_url('/mahasiswa') ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($recent_mahasiswa)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No students found</h6>
                        <p class="text-muted mb-3">Add your first student to get started.</p>
                        <a href="<?= base_url('/mahasiswa/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Student
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>NIM</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Year</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_mahasiswa as $mhs): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary"><?= esc($mhs['nim']) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-white small"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= esc($mhs['nama']) ?></div>
                                                <small class="text-muted"><?= esc($mhs['email']) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($mhs['jurusan']) ?></td>
                                    <td><?= esc($mhs['angkatan']) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('/mahasiswa/' . $mhs['id'] . '/edit') ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
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
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/mahasiswa/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Student
                    </a>
                    <a href="<?= base_url('/users/create') ?>" class="btn btn-success">
                        <i class="fas fa-user-plus me-2"></i>Add User
                    </a>
                    <a href="<?= base_url('/roles') ?>" class="btn btn-info">
                        <i class="fas fa-user-tag me-2"></i>Manage Roles
                    </a>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>System Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-code text-primary fa-2x mb-2"></i>
                            <div class="small fw-semibold">Framework</div>
                            <div class="small text-muted">CodeIgniter 4</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-palette text-success fa-2x mb-2"></i>
                            <div class="small fw-semibold">UI Framework</div>
                            <div class="small text-muted">Bootstrap 5</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-database text-info fa-2x mb-2"></i>
                            <div class="small fw-semibold">Database</div>
                            <div class="small text-muted">MySQL</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-shield-alt text-warning fa-2x mb-2"></i>
                            <div class="small fw-semibold">Security</div>
                            <div class="small text-muted">RBAC</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>