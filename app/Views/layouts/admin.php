<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - CodeIgniter 4 RBAC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --primary-color: #0ea5e9;
            --secondary-color: #0284c7;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --sidebar-bg: #ffffff;
            --sidebar-border: #e2e8f0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f1f5f9;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .sidebar-header .brand {
            color: var(--primary-color);
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
        }

        .sidebar-menu {
            padding: 1rem 0;
            height: calc(100vh - var(--header-height));
            overflow-y: auto;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .menu-item {
            margin: 0.25rem 1rem;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .menu-link:hover, .menu-link.active {
            background: #f1f5f9;
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .menu-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }

        .menu-title {
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 1.5rem 1.5rem 0.5rem;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background-color: #f8fafc;
        }

        .top-navbar {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .content-wrapper {
            padding: 2rem;
        }

        .page-header {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-color);
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .page-subtitle {
            color: #64748b;
            margin: 0.5rem 0 0;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 0.75rem 0.75rem 0 0 !important;
            padding: 1.5rem;
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-warning {
            background: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-danger {
            background: var(--danger-color);
            border-color: var(--danger-color);
        }

        .stats-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            padding: 0.625rem 0.875rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }

        .alert {
            border-radius: 0.5rem;
            border: none;
        }

        .table {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            color: var(--dark-color);
        }

        .badge {
            font-weight: 500;
            padding: 0.375rem 0.75rem;
        }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--dark-color);
            font-size: 1.25rem;
            margin-right: 1rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }

            .content-wrapper {
                padding: 1rem;
            }

            .page-header {
                padding: 1rem 1.5rem;
            }
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        @media (max-width: 768px) {
            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="<?= base_url('/dashboard') ?>" class="brand">
                <i class="fas fa-shield-alt me-2"></i>RBAC Admin
            </a>
        </div>
        <div class="sidebar-menu">
            <div class="menu-title">Main</div>
            <div class="menu-item">
                <a href="<?= base_url('/dashboard') ?>" class="menu-link <?= (current_url() == 'dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </div>

            <?php if (has_permission('users.view') || has_permission('roles.view') || has_permission('permissions.view')): ?>
            <div class="menu-title">User Management</div>
            <?php if (has_permission('users.view')): ?>
            <div class="menu-item">
                <a href="<?= base_url('/users') ?>" class="menu-link <?= (strpos(current_url(), 'users') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    Users
                </a>
            </div>
            <?php endif; ?>
            <?php if (has_permission('roles.view')): ?>
            <div class="menu-item">
                <a href="<?= base_url('/roles') ?>" class="menu-link <?= (strpos(current_url(), 'roles') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-user-tag"></i>
                    Roles
                </a>
            </div>
            <?php endif; ?>
            <?php if (has_permission('permissions.view')): ?>
            <div class="menu-item">
                <a href="<?= base_url('/permissions') ?>" class="menu-link <?= (strpos(current_url(), 'permissions') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-key"></i>
                    Permissions
                </a>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            <?php if (has_permission('mahasiswa.view')): ?>
            <div class="menu-title">Data Management</div>
            <div class="menu-item">
                <a href="<?= base_url('/mahasiswa') ?>" class="menu-link <?= (strpos(current_url(), 'mahasiswa') !== false) ? 'active' : '' ?>">
                    <i class="fas fa-graduation-cap"></i>
                    Mahasiswa
                </a>
            </div>
            <?php endif; ?>

            <div class="menu-title">System</div>
            <div class="menu-item">
                <a href="javascript:void(0)" onclick="confirmLogout()" class="menu-link">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Sidebar Backdrop for Mobile -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="me-3 text-end">
                            <div class="fw-semibold text-dark"><?= session()->get('username') ?></div>
                            <small class="text-muted"><?= ucfirst(session()->get('role')) ?></small>
                        </div>
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Account</h6></li>
                        <li><a class="dropdown-item" href="<?= base_url('/profile') ?>"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/profile/password') ?>"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" onclick="confirmLogout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Flash Messages (Hidden - using SweetAlert instead) -->
            <?php $session = session(); ?>

            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        });

        // Close sidebar when clicking backdrop
        document.getElementById('sidebarBackdrop').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        });

        // Initialize DataTables
        $(document).ready(function() {
            $('.data-table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    zeroRecords: "No matching records found",
                    emptyTable: "No data available in table",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });

        // SweetAlert Confirmation dialogs
        function confirmDelete(url, title = 'item') {
            Swal.fire({
                title: `Hapus ${title}?`,
                html: `<div class="text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i><br>
                    <strong>Apakah Anda yakin ingin menghapus ${title.toLowerCase()} ini?</strong><br>
                    <small class="text-muted">Tindakan ini tidak dapat dibatalkan!</small>
                </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: `Sedang menghapus ${title.toLowerCase()}`,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    window.location.href = url;
                }
            });
        }

        function confirmAction(url, title = 'Tindakan', text = 'Apakah Anda yakin?', confirmText = 'Ya, Lanjutkan!') {
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang memproses permintaan Anda',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    window.location.href = url;
                }
            });
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Logout?',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('/logout') ?>';
                }
            });
        }

        // Fungsi untuk menampilkan pesan sukses
        function showSuccess(message, title = 'Berhasil!') {
            Swal.fire({
                icon: 'success',
                title: title,
                text: message,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        // Fungsi untuk menampilkan pesan error
        function showError(message, title = 'Gagal!', details = null) {
            let html = message;
            if (details) {
                html += '<br><br><small class="text-muted">Detail: ' + details + '</small>';
            }
            
            Swal.fire({
                icon: 'error',
                title: title,
                html: html,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Tutup',
                allowOutsideClick: false
            });
        }

        // Fungsi untuk menampilkan pesan peringatan
        function showWarning(message, title = 'Peringatan!') {
            Swal.fire({
                icon: 'warning',
                title: title,
                html: message,
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Mengerti'
            });
        }

        // Fungsi untuk menampilkan pesan info
        function showInfo(message, title = 'Informasi') {
            Swal.fire({
                icon: 'info',
                title: title,
                html: message,
                confirmButtonColor: '#0ea5e9',
                confirmButtonText: 'OK'
            });
        }

        // Fungsi untuk menampilkan error validasi
        function showValidationError(errors, title = 'Kesalahan Validasi!') {
            let errorHtml = '<ul class="text-left mb-0">';
            if (Array.isArray(errors)) {
                errors.forEach(function(error) {
                    errorHtml += '<li>' + error + '</li>';
                });
            } else if (typeof errors === 'object') {
                Object.keys(errors).forEach(function(field) {
                    errorHtml += '<li><strong>' + field + ':</strong> ' + errors[field] + '</li>';
                });
            } else {
                errorHtml += '<li>' + errors + '</li>';
            }
            errorHtml += '</ul>';
            
            Swal.fire({
                icon: 'error',
                title: title,
                html: '<div class="text-left"><strong>Silakan perbaiki kesalahan berikut:</strong><br><br>' + errorHtml + '</div>',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Perbaiki',
                width: '500px'
            });
        }

        // Show SweetAlert for flash messages
        $(document).ready(function() {
            // Success messages
            <?php if ($session->getFlashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '<?= addslashes($session->getFlashdata('success')) ?>',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            <?php endif; ?>

            // Error messages
            <?php if (session()->has('errors')):
                $errorList = '<ul>';
                    foreach (session('errors') as $error) {
                        $errorList .= '<li>' . esc($error) . '</li>';
                    }
                    $errorList .= '</ul>';
                ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: '<?= addslashes($errorList) ?>',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Tutup',
                    allowOutsideClick: false
                });
            <?php endif; ?>

            // Warning messages  
            <?php if ($session->getFlashdata('warning')): ?>
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    html: '<?= addslashes($session->getFlashdata('warning')) ?>',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Mengerti'
                });
            <?php endif; ?>

            // Info messages  
            <?php if ($session->getFlashdata('info')): ?>
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    html: '<?= addslashes($session->getFlashdata('info')) ?>',
                    confirmButtonColor: '#0ea5e9',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>

            // Validation errors
            <?php if (isset($errors) && !empty($errors)): ?>
                let errorList = <?= json_encode($errors) ?>;
                let errorHtml = '<ul class="text-left mb-0">';
                errorList.forEach(function(error) {
                    errorHtml += '<li>' + error + '</li>';
                });
                errorHtml += '</ul>';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Validasi!',
                    html: '<div class="text-left"><strong>Silakan perbaiki kesalahan berikut:</strong><br><br>' + errorHtml + '</div>',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Perbaiki',
                    width: '500px'
                });
            <?php endif; ?>

            // Database errors (if exists)
            <?php if ($session->getFlashdata('db_error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Database!',
                    html: '<div class="text-left"><strong>Terjadi kesalahan pada database:</strong><br><br><?= addslashes($session->getFlashdata('db_error')) ?></div>',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Tutup',
                    footer: '<small class="text-muted">Jika masalah berlanjut, hubungi administrator sistem</small>'
                });
            <?php endif; ?>

            // Permission denied errors
            <?php if ($session->getFlashdata('permission_denied')): ?>
                Swal.fire({
                    icon: 'warning',
                    title: 'Akses Ditolak!',
                    html: '<div class="text-center"><i class="fas fa-shield-alt fa-3x text-warning mb-3"></i><br><strong><?= addslashes($session->getFlashdata('permission_denied')) ?></strong></div>',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Mengerti',
                    footer: '<small class="text-muted">Hubungi administrator jika Anda memerlukan akses ini</small>'
                });
            <?php endif; ?>
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>