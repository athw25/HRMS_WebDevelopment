<?php
/**
 * =========================================================
 * FILE: login.php
 * MODULE: Authentication
 * OWNER: Thành viên 1 - System Architect & Authentication Engineer
 * MỤC ĐÍCH: Trang đăng nhập hệ thống HRMS.
 * PERMISSION: Không yêu cầu đăng nhập (public).
 *             Nếu đã đăng nhập -> tự động chuyển tới Dashboard.
 * =========================================================
 */

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/helpers/auth-helper.php';

// Đã đăng nhập rồi thì không cần xem lại trang login
if (isLoggedIn()) {
    redirectTo('/modules/dashboard/dashboard.php');
}

$errors = [];

// -------------------------------------------
// XỬ LÝ FORM ĐĂNG NHẬP (POST)
// -------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($csrfToken)) {
        $errors[] = 'Phiên làm việc đã hết hạn. Vui lòng thử lại.';
    } else {
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? ''); // Không sanitize password (không hiển thị lại ra HTML)

        // ---- VALIDATION ----
        if (isEmptyValue($username)) {
            $errors[] = 'Vui lòng nhập tên đăng nhập.';
        }
        if (isEmptyValue($password)) {
            $errors[] = 'Vui lòng nhập mật khẩu.';
        }

        // ---- XỬ LÝ ĐĂNG NHẬP ----
        if (empty($errors)) {
            $result = attemptLogin($username, $password);

            if ($result['success']) {
                setUserSession($result['sessionData']);
                redirectWithSuccess('/modules/dashboard/dashboard.php', 'Đăng nhập thành công. Chào mừng trở lại!');
            } else {
                $errors[] = $result['message'];
            }
        }
    }
}

$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - <?= sanitizeInput(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= ASSETS_URL ?>/css/auth.css" rel="stylesheet">
</head>
<body class="auth-body">

    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-brand">
                <div class="auth-logo">HR</div>
                <h1 class="auth-title"><?= sanitizeInput(APP_SHORT_NAME) ?></h1>
                <p class="auth-subtitle">Hệ Thống Quản Lý Nhân Sự</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert-box alert-box--error" role="alert">
                    <ul class="alert-box__list">
                        <?php foreach ($errors as $error): ?>
                            <li><?= sanitizeInput($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/login.php" novalidate id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= sanitizeInput($csrfToken) ?>">

                <div class="form-group">
                    <label for="username" class="form-label">Tên đăng nhập <span class="required">*</span></label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="Nhập tên đăng nhập"
                        value="<?= sanitizeInput($_POST['username'] ?? '') ?>"
                        autocomplete="username"
                        autofocus
                        required>
                    <div class="form-error" id="usernameError"></div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu <span class="required">*</span></label>
                    <div class="password-input-group">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Nhập mật khẩu"
                            autocomplete="current-password"
                            required>
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Hiện/ẩn mật khẩu">👁</button>
                    </div>
                    <div class="form-error" id="passwordError"></div>
                </div>

                <button type="submit" class="btn-primary btn-block">Đăng nhập</button>
            </form>

            <p class="auth-footer-text">© <?= date('Y') ?> <?= sanitizeInput(APP_NAME) ?></p>
        </div>
    </div>

    <script>
        // Toggle hiện/ẩn mật khẩu
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            this.textContent = isHidden ? '🙈' : '👁';
        });

        // Validation phía client (bổ sung cho validation phía server, KHÔNG thay thế)
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            let isValid = true;
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const usernameError = document.getElementById('usernameError');
            const passwordError = document.getElementById('passwordError');

            usernameError.textContent = '';
            passwordError.textContent = '';

            if (username.value.trim() === '') {
                usernameError.textContent = 'Vui lòng nhập tên đăng nhập.';
                isValid = false;
            }
            if (password.value.trim() === '') {
                passwordError.textContent = 'Vui lòng nhập mật khẩu.';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
