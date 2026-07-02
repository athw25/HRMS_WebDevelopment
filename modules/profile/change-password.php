<?php
/**
 * =========================================================
 * FILE: modules/profile/change-password.php
 * MODULE: Authentication / Profile
 * OWNER: Thành viên 1 - System Architect & Authentication Engineer
 * MỤC ĐÍCH: Cho phép người dùng đang đăng nhập tự đổi mật khẩu.
 * PERMISSION: employee.middleware.php (ADMIN, MANAGER, EMPLOYEE).
 * =========================================================
 */

require_once __DIR__ . '/../../includes/bootstrap.php';
require_once __DIR__ . '/../../middleware/employee.middleware.php';
require_once __DIR__ . '/../../helpers/auth-helper.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($csrfToken)) {
        $errors[] = 'Phiên làm việc đã hết hạn. Vui lòng thử lại.';
    } else {
        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword     = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if (isEmptyValue($currentPassword)) {
            $errors[] = 'Vui lòng nhập mật khẩu hiện tại.';
        }
        if (isEmptyValue($newPassword)) {
            $errors[] = 'Vui lòng nhập mật khẩu mới.';
        } elseif (!isValidPassword($newPassword)) {
            $errors[] = 'Mật khẩu mới phải có tối thiểu 8 ký tự, gồm ít nhất 1 chữ và 1 số.';
        }
        if (isEmptyValue($confirmPassword)) {
            $errors[] = 'Vui lòng nhập xác nhận mật khẩu mới.';
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = 'Xác nhận mật khẩu mới không khớp.';
        }

        if (empty($errors)) {
            $userId = getUserField('user_id');
            $result = changeUserPassword((int) $userId, $currentPassword, $newPassword);

            if ($result['success']) {
                redirectWithSuccess('/modules/profile/change-password.php', $result['message']);
            } else {
                $errors[] = $result['message'];
            }
        }
    }
}

$flashAlert = getAlert();
$csrfToken  = generateCsrfToken();
$user       = getUserSession();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu - <?= sanitizeInput(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= ASSETS_URL ?>/css/style.css" rel="stylesheet">
    <link href="<?= ASSETS_URL ?>/css/form.css" rel="stylesheet">
</head>
<body class="app-body">

<?php
// TODO: Khi Milestone "Shared Layout" hoàn thành, thay khối header/sidebar
// tạm dưới đây bằng:
//   require_once __DIR__ . '/../../includes/header.php';
//   require_once __DIR__ . '/../../includes/sidebar.php';
// để đồng bộ layout Header/Sidebar/Content/Footer toàn hệ thống.
?>
<header class="page-topbar">
    <div class="page-topbar__brand"><?= sanitizeInput(APP_SHORT_NAME) ?></div>
    <div class="page-topbar__user">
        <span class="page-topbar__name"><?= sanitizeInput($user['full_name'] ?? '') ?></span>
        <span class="page-topbar__role"><?= sanitizeInput($user['role'] ?? '') ?></span>
        <a href="<?= BASE_URL ?>/logout.php" class="btn-secondary btn-sm">Đăng xuất</a>
    </div>
</header>

<main class="page-content">
    <div class="page-header">
        <h1 class="page-title">Đổi mật khẩu</h1>
        <p class="page-subtitle">Cập nhật mật khẩu đăng nhập cho tài khoản của bạn</p>
    </div>

    <div class="content-card content-card--narrow">
        <?php if ($flashAlert): ?>
            <div class="alert-box alert-box--<?= sanitizeInput($flashAlert['type']) ?>" role="alert">
                <?= sanitizeInput($flashAlert['message']) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert-box alert-box--error" role="alert">
                <ul class="alert-box__list">
                    <?php foreach ($errors as $error): ?>
                        <li><?= sanitizeInput($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/modules/profile/change-password.php" novalidate id="changePasswordForm">
            <input type="hidden" name="csrf_token" value="<?= sanitizeInput($csrfToken) ?>">

            <div class="form-group">
                <label for="current_password" class="form-label">Mật khẩu hiện tại <span class="required">*</span></label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="form-control"
                    placeholder="Nhập mật khẩu hiện tại"
                    autocomplete="current-password"
                    required>
                <div class="form-error" id="currentPasswordError"></div>
            </div>

            <div class="form-group">
                <label for="new_password" class="form-label">Mật khẩu mới <span class="required">*</span></label>
                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    class="form-control"
                    placeholder="Tối thiểu 8 ký tự, gồm chữ và số"
                    autocomplete="new-password"
                    required>
                <div class="form-error" id="newPasswordError"></div>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới <span class="required">*</span></label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    class="form-control"
                    placeholder="Nhập lại mật khẩu mới"
                    autocomplete="new-password"
                    required>
                <div class="form-error" id="confirmPasswordError"></div>
            </div>

            <div class="form-actions">
                <a href="<?= BASE_URL ?>/modules/dashboard/dashboard.php" class="btn-secondary">Hủy</a>
                <button type="submit" class="btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</main>

<footer class="page-footer">
    <p>&copy; <?= date('Y') ?> <?= sanitizeInput(APP_NAME) ?></p>
</footer>

<script>
    document.getElementById('changePasswordForm').addEventListener('submit', function (e) {
        let isValid = true;

        const currentPassword = document.getElementById('current_password');
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');

        const currentPasswordError = document.getElementById('currentPasswordError');
        const newPasswordError = document.getElementById('newPasswordError');
        const confirmPasswordError = document.getElementById('confirmPasswordError');

        currentPasswordError.textContent = '';
        newPasswordError.textContent = '';
        confirmPasswordError.textContent = '';

        if (currentPassword.value.trim() === '') {
            currentPasswordError.textContent = 'Vui lòng nhập mật khẩu hiện tại.';
            isValid = false;
        }

        const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;
        if (newPassword.value.trim() === '') {
            newPasswordError.textContent = 'Vui lòng nhập mật khẩu mới.';
            isValid = false;
        } else if (!passwordPattern.test(newPassword.value)) {
            newPasswordError.textContent = 'Mật khẩu mới phải có tối thiểu 8 ký tự, gồm ít nhất 1 chữ và 1 số.';
            isValid = false;
        }

        if (confirmPassword.value.trim() === '') {
            confirmPasswordError.textContent = 'Vui lòng xác nhận mật khẩu mới.';
            isValid = false;
        } else if (confirmPassword.value !== newPassword.value) {
            confirmPasswordError.textContent = 'Xác nhận mật khẩu mới không khớp.';
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>
