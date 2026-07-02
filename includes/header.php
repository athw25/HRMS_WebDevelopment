<?php
/**
 * =========================================================
 * FILE: includes/header.php
 * MODULE: Shared Layout
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Header dùng chung cho toàn bộ hệ thống HRMS.
 *   Chịu trách nhiệm:
 *     - Khởi tạo giao diện HTML
 *     - Nạp CSS dùng chung
 *     - Hiển thị Header
 *     - Hiển thị thông tin người dùng
 *     - Sidebar Toggle
 *     - Notification
 *     - Dark Mode
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

$currentUser = getUserSession();

$fullName = $currentUser['full_name'] ?? '';
$roleName = $currentUser['role'] ?? '';
$avatar   = $currentUser['avatar'] ?? null;

$initials = getInitials($fullName);
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= sanitizeInput(APP_NAME) ?></title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Shared CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/dashboard.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/form.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/table.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/responsive.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/dark-mode.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/auth.css">

    <!-- Module CSS -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/department.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/position.css">

</head>

<body>

<header class="app-header" id="appHeader">

    <div class="app-header__left">

        <button
            type="button"
            class="header-icon-btn"
            id="sidebarToggle"
            aria-label="Thu gọn Sidebar">

            <i class="bi bi-list"></i>

        </button>

        <a
            href="<?= BASE_URL ?>/modules/dashboard/dashboard.php"
            class="app-header__brand">

            <span class="app-header__logo">
                <?= sanitizeInput(APP_SHORT_NAME) ?>
            </span>

        </a>

    </div>

    <div class="app-header__right">

        <!-- Notification -->

        <button
            type="button"
            class="header-icon-btn"
            id="notificationBtn">

            <i class="bi bi-bell"></i>

            <span class="header-icon-btn__dot"></span>

        </button>

        <!-- Dark Mode -->

        <button
            type="button"
            class="header-icon-btn"
            id="darkModeToggle">

            <i
                class="bi bi-moon-stars"
                id="darkModeIcon">
            </i>

        </button>

        <!-- User Menu -->

        <div
            class="header-user"
            id="headerUserMenu">

            <button
                type="button"
                class="header-user__trigger"
                id="headerUserTrigger">

                <span class="header-user__avatar">

                    <?php if (!empty($avatar)): ?>

                        <img
                            src="<?= sanitizeInput($avatar) ?>"
                            alt="Avatar">

                    <?php else: ?>

                        <?= sanitizeInput($initials) ?>

                    <?php endif; ?>

                </span>

                <span class="header-user__info">

                    <span class="header-user__name">

                        <?= sanitizeInput($fullName) ?>

                    </span>

                    <span class="header-user__role">

                        <?= sanitizeInput($roleName) ?>

                    </span>

                </span>

                <i class="bi bi-chevron-down"></i>

            </button>

            <div
                class="header-user__dropdown"
                id="headerUserDropdown">

                <a
                    href="<?= BASE_URL ?>/modules/profile/profile.php"
                    class="header-user__dropdown-item">

                    <i class="bi bi-person"></i>

                    Hồ sơ cá nhân

                </a>

                <a
                    href="<?= BASE_URL ?>/modules/profile/change-password.php"
                    class="header-user__dropdown-item">

                    <i class="bi bi-key"></i>

                    Đổi mật khẩu

                </a>

                <div class="header-user__dropdown-divider"></div>

                <a
                    href="<?= BASE_URL ?>/logout.php"
                    class="header-user__dropdown-item header-user__dropdown-item--danger">

                    <i class="bi bi-box-arrow-right"></i>

                    Đăng xuất

                </a>

            </div>

        </div>

    </div>

</header>

<script>

(function () {

    const body = document.body;

    /**
     * Sidebar
     */

    const sidebarToggle = document.getElementById('sidebarToggle');

    if (localStorage.getItem('hrms_sidebar_collapsed') === '1') {
        body.classList.add('sidebar-collapsed');
    }

    sidebarToggle?.addEventListener('click', function () {

        body.classList.toggle('sidebar-collapsed');

        localStorage.setItem(
            'hrms_sidebar_collapsed',
            body.classList.contains('sidebar-collapsed') ? '1' : '0'
        );

    });

    /**
     * Dark Mode
     */

    const darkModeToggle = document.getElementById('darkModeToggle');

    const darkModeIcon = document.getElementById('darkModeIcon');

    function applyDarkMode(isDark) {

        body.classList.toggle('dark-mode', isDark);

        darkModeIcon.className = isDark
            ? 'bi bi-sun'
            : 'bi bi-moon-stars';

    }

    applyDarkMode(
        localStorage.getItem('hrms_dark_mode') === '1'
    );

    darkModeToggle?.addEventListener('click', function () {

        const isDark = !body.classList.contains('dark-mode');

        applyDarkMode(isDark);

        localStorage.setItem(
            'hrms_dark_mode',
            isDark ? '1' : '0'
        );

    });

    /**
     * User Dropdown
     */

    const trigger = document.getElementById('headerUserTrigger');

    const dropdown = document.getElementById('headerUserDropdown');

    trigger?.addEventListener('click', function (e) {

        e.stopPropagation();

        dropdown.classList.toggle('is-open');

    });

    document.addEventListener('click', function (e) {

        const menu = document.getElementById('headerUserMenu');

        if (menu && !menu.contains(e.target)) {
            dropdown?.classList.remove('is-open');
        }

    });

})();

</script>