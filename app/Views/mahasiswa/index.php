<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Students Management</h1>
            <p class="page-subtitle">Manage student records and information</p>
        </div>
        <a href="<?= base_url('/mahasiswa/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Student
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-graduation-cap me-2"></i>All Students
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($mahasiswa)): ?>
            <div class="text-center py-5">
                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No students found</h5>
                <p class="text-muted">Click "Add New Student" to create your first student record</p>
                <a href="<?= base_url('/mahasiswa/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Student
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mahasiswa as $index => $mhs): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
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
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($mhs['email']) ?></td>
                            <td>
                                <span class="badge bg-info"><?= esc($mhs['jurusan']) ?></span>
                            </td>
                            <td><?= esc($mhs['angkatan']) ?></td>
                            <td>
                                <small class="text-muted">
                                    <?= date('M d, Y', strtotime($mhs['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('/mahasiswa/edit/' . $mhs['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip" 
                                       title="Edit Student">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="confirmDelete('<?= base_url('/mahasiswa/delete/' . $mhs['id']) ?>', 'Student')"
                                            data-bs-toggle="tooltip" 
                                            title="Delete Student">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
// Initialize tooltips
$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
<?= $this->endSection() ?>