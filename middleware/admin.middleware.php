<?php
/**
 * =========================================================
 * FILE: middleware/admin.middleware.php
 * MODULE: Core System - Middleware
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Chỉ cho phép vai trò ADMIN truy cập.
 *   Dùng cho các trang quản trị hệ thống: quản lý tài khoản,
 *   cấu hình toàn hệ thống, các thao tác toàn quyền.
 *
 * CÁCH DÙNG:
 *   require_once __DIR__ . '/../../includes/bootstrap.php';
 *   require_once __DIR__ . '/../../middleware/admin.middleware.php';
 *
 * DEPENDENCIES:
 *   - middleware/auth.middleware.php (đảm bảo đã đăng nhập trước)
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

// Bước 1: Đảm bảo đã đăng nhập (require lại auth.middleware.php)
require_once __DIR__ . '/auth.middleware.php';

// Bước 2: Kiểm tra đúng vai trò ADMIN
if (!hasRole(ROLE_ADMIN)) {
    denyAccess(403, 'Bạn không có quyền truy cập chức năng quản trị này.', '/modules/dashboard/dashboard.php');
}
