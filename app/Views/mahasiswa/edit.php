<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Edit Student</h1>
            <p class="page-subtitle">Update student information</p>
        </div>
        <a href="<?= base_url('/mahasiswa') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Students
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>Student Information
                </h5>
            </div>
            <div class="card-body">
                    <?= form_open('/mahasiswa/update/' . $mahasiswa['id']) ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nim" name="nim" 
                                       value="<?= esc($mahasiswa['nim']) ?>" required>
                                <div class="form-text">Unique student identification number</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" 
                                       value="<?= esc($mahasiswa['nama']) ?>" required>
                                <div class="form-text">Student's complete name</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= esc($mahasiswa['email']) ?>" required>
                        <div class="form-text">Valid email address for communication</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-select" id="jurusan" name="jurusan" required>
                                    <option value="">Select Department</option>
                                    <option value="Teknik Informatika" <?= $mahasiswa['jurusan'] == 'Teknik Informatika' ? 'selected' : '' ?>>Computer Science</option>
                                    <option value="Sistem Informasi" <?= $mahasiswa['jurusan'] == 'Sistem Informasi' ? 'selected' : '' ?>>Information Systems</option>
                                    <option value="Teknik Komputer" <?= $mahasiswa['jurusan'] == 'Teknik Komputer' ? 'selected' : '' ?>>Computer Engineering</option>
                                    <option value="Teknik Elektro" <?= $mahasiswa['jurusan'] == 'Teknik Elektro' ? 'selected' : '' ?>>Electrical Engineering</option>
                                    <option value="Teknik Industri" <?= $mahasiswa['jurusan'] == 'Teknik Industri' ? 'selected' : '' ?>>Industrial Engineering</option>
                                </select>
                                <div class="form-text">Student's academic department</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="angkatan" class="form-label">Year <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="angkatan" name="angkatan" 
                                       value="<?= esc($mahasiswa['angkatan']) ?>"
                                       min="2015" max="<?= date('Y') ?>" required>
                                <div class="form-text">Academic year of enrollment</div>
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
                    <i class="fas fa-info-circle me-2"></i>Student Details
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Created</label>
                    <div class="text-muted"><?= date('F j, Y \a\t g:i A', strtotime($mahasiswa['created_at'])) ?></div>
                </div>
                <?php if ($mahasiswa['updated_at']): ?>
                <div class="mb-3">
                    <label class="form-label">Last Updated</label>
                    <div class="text-muted"><?= date('F j, Y \a\t g:i A', strtotime($mahasiswa['updated_at'])) ?></div>
                </div>
                <?php endif; ?>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Note:</strong><br>
                    Changes will be saved immediately upon submission.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <a href="<?= base_url('/mahasiswa') ?>" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Update Student
            </button>
        </div>
    </div>
</div>

<?= form_close() ?>
<?= $this->endSection() ?>