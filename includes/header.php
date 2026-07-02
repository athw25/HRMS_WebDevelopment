<?php
if (!defined('HRMS_APP')) { http_response_code(403); die('Direct access is not allowed.'); }

$currentUser = getUserSession();
$fullName = $currentUser['full_name'] ?? '';
$roleName = $currentUser['role'] ?? '';
$avatar = $currentUser['avatar'] ?? null;
$initials = getInitials($fullName);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<header class="app-header" id="appHeader">
    <div class="app-header__left">
        <button type="button" class="header-icon-btn" id="sidebarToggle" aria-label="Thu gọn menu">
            <i class="bi bi-list"></i>
        </button>
        <a href="<?= BASE_URL ?>/modules/dashboard/dashboard.php" class="app-header__brand">
            <span class="app-header__logo"><?= sanitizeInput(APP_SHORT_NAME) ?></span>
        </a>
    </div>

    <div class="app-header__right">
        <button type="button" class="header-icon-btn" id="notificationBtn" aria-label="Thông báo">
            <i class="bi bi-bell"></i>
            <span class="header-icon-btn__dot"></span>
        </button>

        <button type="button" class="header-icon-btn" id="darkModeToggle" aria-label="Chuyển giao diện Sáng/Tối">
            <i class="bi bi-moon-stars" id="darkModeIcon"></i>
        </button>

        <div class="header-user" id="headerUserMenu">
            <button type="button" class="header-user__trigger" id="headerUserTrigger">
                <span class="header-user__avatar">
                    <?php if (!empty($avatar)): ?>
                        <img src="<?= sanitizeInput($avatar) ?>" alt="Avatar">
                    <?php else: ?>
                        <?= sanitizeInput($initials) ?>
                    <?php endif; ?>
                </span>
                <span class="header-user__info">
                    <span class="header-user__name"><?= sanitizeInput($fullName) ?></span>
                    <span class="header-user__role"><?= sanitizeInput($roleName) ?></span>
                </span>
                <i class="bi bi-chevron-down"></i>
            </button>

            <div class="header-user__dropdown" id="headerUserDropdown">
                <a href="<?= BASE_URL ?>/modules/profile/profile.php" class="header-user__dropdown-item">
                    <i class="bi bi-person"></i> Hồ sơ cá nhân
                </a>
                <a href="<?= BASE_URL ?>/modules/profile/change-password.php" class="header-user__dropdown-item">
                    <i class="bi bi-key"></i> Đổi mật khẩu
                </a>
                <div class="header-user__dropdown-divider"></div>
                <a href="<?= BASE_URL ?>/logout.php" class="header-user__dropdown-item header-user__dropdown-item--danger">
                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>
</header>

<script>
(function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const body = document.body;

    if (localStorage.getItem('hrms_sidebar_collapsed') === '1') {
        body.classList.add('sidebar-collapsed');
    }

    sidebarToggle?.addEventListener('click', function () {
        body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('hrms_sidebar_collapsed', body.classList.contains('sidebar-collapsed') ? '1' : '0');
    });

    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');

    function applyDarkMode(isDark) {
        body.classList.toggle('dark-mode', isDark);
        darkModeIcon.className = isDark ? 'bi bi-sun' : 'bi bi-moon-stars';
    }
    applyDarkMode(localStorage.getItem('hrms_dark_mode') === '1');

    darkModeToggle?.addEventListener('click', function () {
        const isDark = !body.classList.contains('dark-mode');
        applyDarkMode(isDark);
        localStorage.setItem('hrms_dark_mode', isDark ? '1' : '0');
    });

    const userTrigger = document.getElementById('headerUserTrigger');
    const userDropdown = document.getElementById('headerUserDropdown');

    userTrigger?.addEventListener('click', function (e) {
        e.stopPropagation();
        userDropdown.classList.toggle('is-open');
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('headerUserMenu').contains(e.target)) {
            userDropdown?.classList.remove('is-open');
        }
    });
})();
</script>
