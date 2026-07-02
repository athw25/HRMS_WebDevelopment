<?php
/**
 * =========================================================
 * FILE: middleware/manager.middleware.php
 * MODULE: Core System - Middleware
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Cho phép vai trò MANAGER (và ADMIN, theo nguyên tắc
 *   Admin có toàn quyền) truy cập.
 *   Dùng cho: duyệt/từ chối đơn nghỉ phép, theo dõi chấm công
 *   phòng ban, xem báo cáo phòng ban.
 *
 * CÁCH DÙNG:
 *   require_once __DIR__ . '/../../includes/bootstrap.php';
 *   require_once __DIR__ . '/../../middleware/manager.middleware.php';
 *
 * DEPENDENCIES:
 *   - middleware/auth.middleware.php (đảm bảo đã đăng nhập trước)
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

// Bước 1: Đảm bảo đã đăng nhập
require_once __DIR__ . '/auth.middleware.php';

// Bước 2: Kiểm tra vai trò MANAGER hoặc ADMIN (Admin toàn quyền)
if (!hasRole([ROLE_ADMIN, ROLE_MANAGER])) {
    denyAccess(403, 'Bạn không có quyền truy cập chức năng quản lý phòng ban này.', '/modules/dashboard/dashboard.php');
}
