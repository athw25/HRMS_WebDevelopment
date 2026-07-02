<?php
if (!defined('HRMS_APP')) { http_response_code(403); die('Direct access is not allowed.'); }

$currentScript = $_SERVER['SCRIPT_NAME'] ?? '';

function isSidebarActive(string $path, string $currentScript): bool
{
    return str_contains($currentScript, $path);
}

$menuItems = [
    ['label' => 'Dashboard', 'icon' => 'bi-grid-1x2', 'url' => '/modules/dashboard/dashboard.php', 'match' => '/modules/dashboard/', 'roles' => [ROLE_ADMIN, ROLE_MANAGER, ROLE_EMPLOYEE]],
    ['label' => 'Quản lý tài khoản', 'icon' => 'bi-shield-lock', 'url' => '/modules/users/user-list.php', 'match' => '/modules/users/', 'roles' => [ROLE_ADMIN]],
    ['label' => 'Quản lý nhân viên', 'icon' => 'bi-people', 'url' => '/modules/employees/employee-list.php', 'match' => '/modules/employees/', 'roles' => [ROLE_ADMIN, ROLE_MANAGER]],
    ['label' => 'Quản lý phòng ban', 'icon' => 'bi-building', 'url' => '/modules/departments/department-list.php', 'match' => '/modules/departments/', 'roles' => [ROLE_ADMIN]],
    ['label' => 'Quản lý chức vụ', 'icon' => 'bi-award', 'url' => '/modules/positions/position-list.php', 'match' => '/modules/positions/', 'roles' => [ROLE_ADMIN]],
    ['label' => 'Chấm công', 'icon' => 'bi-clock-history', 'url' => '/modules/attendance/attendance-list.php', 'match' => '/modules/attendance/', 'roles' => [ROLE_ADMIN, ROLE_MANAGER, ROLE_EMPLOYEE]],
    ['label' => 'Nghỉ phép', 'icon' => 'bi-calendar-check', 'url' => '/modules/leave/leave-list.php', 'match' => '/modules/leave/', 'roles' => [ROLE_ADMIN, ROLE_MANAGER, ROLE_EMPLOYEE]],
    ['label' => 'Quản lý lương', 'icon' => 'bi-cash-coin', 'url' => '/modules/salary/salary-list.php', 'match' => '/modules/salary/', 'roles' => [ROLE_ADMIN, ROLE_EMPLOYEE]],
    ['label' => 'Khen thưởng - Kỷ luật', 'icon' => 'bi-trophy', 'url' => '/modules/reward/reward-list.php', 'match' => '/modules/reward/', 'roles' => [ROLE_ADMIN, ROLE_EMPLOYEE]],
    ['label' => 'Báo cáo', 'icon' => 'bi-bar-chart', 'url' => '/modules/reports/reports.php', 'match' => '/modules/reports/', 'roles' => [ROLE_ADMIN, ROLE_MANAGER]],
    ['label' => 'Hồ sơ cá nhân', 'icon' => 'bi-person-circle', 'url' => '/modules/profile/profile.php', 'match' => '/modules/profile/', 'roles' => [ROLE_ADMIN, ROLE_MANAGER, ROLE_EMPLOYEE]],
];
?>
<aside class="app-sidebar" id="appSidebar">
    <nav class="app-sidebar__nav">
        <ul class="app-sidebar__list">
            <?php foreach ($menuItems as $item): ?>
                <?php if (!hasRole($item['roles'])) continue; ?>
                <?php $isActive = isSidebarActive($item['match'], $currentScript); ?>
                <li class="app-sidebar__item">
                    <a href="<?= BASE_URL . $item['url'] ?>" class="app-sidebar__link<?= $isActive ? ' is-active' : '' ?>">
                        <i class="bi <?= sanitizeInput($item['icon']) ?>"></i>
                        <span class="app-sidebar__label"><?= sanitizeInput($item['label']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
</aside>
