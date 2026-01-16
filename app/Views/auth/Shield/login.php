<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Auth.login') ?> - CodeIgniter 4 RBAC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0ea5e9;
            --secondary-color: #0284c7;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 1000px;
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            /* justify-content: center; */
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="25" cy="75" r="1" fill="white" opacity="0.05"/><circle cx="75" cy="25" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .login-right {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-logo {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .brand-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: #6b7280;
            margin-bottom: 2rem;
        }

        .form-control {
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }

        .input-group-text {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #6b7280;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        }

        .demo-card {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .demo-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .demo-accounts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .demo-account {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .demo-account-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .demo-account-info {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                margin: 20px;
                border-radius: 16px;
            }
            
            .login-left {
                padding: 40px 20px;
            }
            
            .login-right {
                padding: 40px 20px;
            }
            
            .demo-accounts {
                grid-template-columns: 1fr;
            }
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }

        .feature-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
        }

        .feature-list i {
            margin-right: 0.75rem;
            opacity: 0.8;
        }

        .form-check {
            margin-bottom: 1rem;
        }

        .form-check-label {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div class="position-relative z-index-1">
                <div class="brand-logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="brand-title">RBAC Admin</h1>
                <p class="brand-subtitle">Role-Based Access Control System</p>
                
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Shield Integration</li>
                    <li><i class="fas fa-check"></i> Secure Authentication</li>
                    <li><i class="fas fa-check"></i> Role Management</li>
                    <li><i class="fas fa-check"></i> Permission Control</li>
                    <li><i class="fas fa-check"></i> Modern Dashboard</li>
                </ul>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div>
                <h2 class="welcome-title"><?= lang('Auth.login') ?></h2>
                <p class="welcome-subtitle">Please sign in to your account to continue</p>

                <!-- Flash Messages for Shield -->
                <?php if (session('error') !== null) : ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= esc(session('error')) ?>
                    </div>
                <?php elseif (session('errors') !== null) : ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php if (is_array(session('errors'))) : ?>
                            <?php foreach (session('errors') as $error) : ?>
                                <?= esc($error) ?>
                                <br>
                            <?php endforeach ?>
                        <?php else : ?>
                            <?= esc(session('errors')) ?>
                        <?php endif ?>
                    </div>
                <?php endif ?>

                <?php if (session('message') !== null) : ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= esc(session('message')) ?>
                    </div>
                <?php endif ?>

                <form action="<?= url_to('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="floatingEmailInput" class="form-label fw-semibold"><?= lang('Auth.email') ?></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="floatingEmailInput" name="email" 
                                   inputmode="email" autocomplete="email" 
                                   placeholder="<?= lang('Auth.email') ?>" 
                                   value="<?= old('email') ?>" required>
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-3">
                        <label for="floatingPasswordInput" class="form-label fw-semibold"><?= lang('Auth.password') ?></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="floatingPasswordInput" name="password" 
                                   inputmode="text" autocomplete="current-password" 
                                   placeholder="<?= lang('Auth.password') ?>" required>
                        </div>
                    </div>

                    <!-- Remember me -->
                    <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="remember" class="form-check-input" id="rememberMe" <?php if (old('remember')): ?> checked<?php endif ?>>
                            <label class="form-check-label" for="rememberMe">
                                <?= lang('Auth.rememberMe') ?>
                            </label>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login text-white">
                            <i class="fas fa-sign-in-alt me-2"></i><?= lang('Auth.login') ?>
                        </button>
                    </div>
                </form>

                <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                    <div class="register-link">
                        <span class="text-muted"><?= lang('Auth.forgotPassword') ?> </span>
                        <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a>
                    </div>
                <?php endif ?>

                <?php if (setting('Auth.allowRegistration')) : ?>
                    <div class="register-link">
                        <span class="text-muted"><?= lang('Auth.needAccount') ?> </span>
                        <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a>
                    </div>
                <?php endif ?>

                <!-- Demo Accounts -->
                <!--
                <div class="demo-card">
                    <div class="demo-title">
                        <i class="fas fa-key me-2"></i>Demo Accounts
                    </div>
                    <div class="demo-accounts">
                        <div class="demo-account">
                            <div class="demo-account-title">Admin Account</div>
                            <p class="demo-account-info">Email: admin@example.com</p>
                            <p class="demo-account-info">Password: admin123</p>
                        </div>
                        <div class="demo-account">
                            <div class="demo-account-title">User Account</div>
                            <p class="demo-account-info">Email: user@example.com</p>
                            <p class="demo-account-info">Password: user123</p>
                        </div>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
