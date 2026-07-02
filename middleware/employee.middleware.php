<?php
/**
 * =========================================================
 * FILE: middleware/employee.middleware.php
 * MODULE: Core System - Middleware
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Cho phép mọi vai trò đã đăng nhập (EMPLOYEE, MANAGER, ADMIN)
 *   truy cập. Dùng cho các chức năng tự phục vụ (self-service):
 *   hồ sơ cá nhân, chấm công, gửi đơn nghỉ phép, xem lương.
 *
 *   Về bản chất tương đương auth.middleware.php, nhưng được
 *   tách thành file riêng để:
 *     1. Thể hiện rõ ý định code (đây là trang cấp EMPLOYEE).
 *     2. Dễ dàng thắt chặt quyền sau này (vd: chỉ EMPLOYEE xem
 *        được, không cho MANAGER/ADMIN) mà không ảnh hưởng
 *        các middleware khác.
 *
 * CÁCH DÙNG:
 *   require_once __DIR__ . '/../../includes/bootstrap.php';
 *   require_once __DIR__ . '/../../middleware/employee.middleware.php';
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

// Bước 2: Kiểm tra vai trò hợp lệ (EMPLOYEE, MANAGER hoặc ADMIN)
if (!hasRole([ROLE_ADMIN, ROLE_MANAGER, ROLE_EMPLOYEE])) {
    denyAccess(403, 'Bạn không có quyền truy cập chức năng này.', '/modules/dashboard/dashboard.php');
}
