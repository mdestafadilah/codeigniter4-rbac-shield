<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Add New Student</h1>
            <p class="page-subtitle">Create a new student record</p>
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
                    <?= form_open('/mahasiswa/store') ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nim" name="nim" 
                                       placeholder="Enter student ID number" required>
                                <div class="form-text">Unique student identification number</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama" name="nama" 
                                       placeholder="Enter full name" required>
                                <div class="form-text">Student's complete name</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="Enter email address" required>
                        <div class="form-text">Valid email address for communication</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jurusan" class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-select" id="jurusan" name="jurusan" required>
                                    <option value="">Select Department</option>
                                    <option value="Teknik Informatika">Computer Science</option>
                                    <option value="Sistem Informasi">Information Systems</option>
                                    <option value="Teknik Komputer">Computer Engineering</option>
                                    <option value="Teknik Elektro">Electrical Engineering</option>
                                    <option value="Teknik Industri">Industrial Engineering</option>
                                </select>
                                <div class="form-text">Student's academic department</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="angkatan" class="form-label">Year <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="angkatan" name="angkatan" 
                                       min="2015" max="<?= date('Y') ?>" placeholder="<?= date('Y') ?>" required>
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
                    <i class="fas fa-info-circle me-2"></i>Guidelines
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                    <ul class="mb-0 small">
                        <li>Ensure NIM is unique and follows institution format</li>
                        <li>Use student's official full name</li>
                        <li>Provide active email for notifications</li>
                        <li>Select appropriate department and year</li>
                    </ul>
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
                <i class="fas fa-save me-2"></i>Create Student
            </button>
        </div>
    </div>
</div>

<?= form_close() ?>
<?= $this->endSection() ?>