<?php
/**
 * =========================================================
 * FILE: config/constants.php
 * MODULE: Core System
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Định nghĩa toàn bộ hằng số dùng chung cho hệ thống HRMS.
 *   Không được định nghĩa hằng số trùng mục đích ở nơi khác.
 * =========================================================
 */

// Ngăn truy cập trực tiếp file này
if (!defined('HRMS_APP')) {
    http_response_code(403);
    exit('Direct access is not allowed.');
}

// -------------------------------------------
// 1. ĐƯỜNG DẪN HỆ THỐNG (FILESYSTEM PATH)
// -------------------------------------------
define('ROOT_PATH', dirname(__DIR__));                    // .../hrms
define('CONFIG_PATH', ROOT_PATH . '/config');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('HELPERS_PATH', ROOT_PATH . '/helpers');
define('MIDDLEWARE_PATH', ROOT_PATH . '/middleware');
define('MODULES_PATH', ROOT_PATH . '/modules');
define('LAYOUTS_PATH', ROOT_PATH . '/layouts');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');
define('UPLOADS_EMPLOYEES_PATH', UPLOADS_PATH . '/employees');

// -------------------------------------------
// 2. ĐƯỜNG DẪN URL (BASE URL)
// -------------------------------------------
// Chỉnh lại theo môi trường triển khai thực tế (XAMPP htdocs)
define('BASE_URL', '/hrms');
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');

// -------------------------------------------
// 3. THÔNG TIN ỨNG DỤNG
// -------------------------------------------
define('APP_NAME', 'HRMS - Hệ Thống Quản Lý Nhân Sự');
define('APP_SHORT_NAME', 'HRMS');
define('APP_VERSION', '1.0.0');
define('APP_TIMEZONE', 'Asia/Ho_Chi_Minh');

// -------------------------------------------
// 4. VAI TRÒ NGƯỜI DÙNG (ROLES)
// Phải khớp với bảng "roles" trong Database Design
// -------------------------------------------
define('ROLE_ADMIN', 'ADMIN');
define('ROLE_MANAGER', 'MANAGER');
define('ROLE_EMPLOYEE', 'EMPLOYEE');

// -------------------------------------------
// 5. TRẠNG THÁI TÀI KHOẢN (users.status)
// -------------------------------------------
define('USER_STATUS_ACTIVE', 'active');
define('USER_STATUS_LOCKED', 'locked');

// -------------------------------------------
// 6. QUY CHUẨN DỮ LIỆU (theo mục 10 - LTWeb-HRMS)
// -------------------------------------------
// Giới tính
define('GENDER_MALE', 'Male');
define('GENDER_FEMALE', 'Female');
define('GENDER_OTHER', 'Other');

// Trạng thái nghỉ phép
define('LEAVE_STATUS_PENDING', 'Pending');
define('LEAVE_STATUS_APPROVED', 'Approved');
define('LEAVE_STATUS_REJECTED', 'Rejected');

// Trạng thái chấm công
define('ATTENDANCE_PRESENT', 'Present');
define('ATTENDANCE_LATE', 'Late');
define('ATTENDANCE_ABSENT', 'Absent');
define('ATTENDANCE_LEAVE', 'Leave');

// Loại quyết định khen thưởng - kỷ luật
define('DECISION_REWARD', 'Reward');
define('DECISION_DISCIPLINE', 'Discipline');

// -------------------------------------------
// 7. CẤU HÌNH UPLOAD FILE
// -------------------------------------------
define('UPLOAD_MAX_SIZE', 2 * 1024 * 1024); // 2MB
define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('UPLOAD_ALLOWED_EXT', ['jpg', 'jpeg', 'png', 'webp']);

// -------------------------------------------
// 8. CẤU HÌNH PHÂN TRANG (PAGINATION)
// -------------------------------------------
define('DEFAULT_PAGE_SIZE', 10);
define('MAX_PAGE_SIZE', 100);

// -------------------------------------------
// 9. CẤU HÌNH SESSION
// -------------------------------------------
define('SESSION_NAME', 'HRMS_SESSION');
define('SESSION_LIFETIME', 60 * 60 * 2); // 2 giờ (tính bằng giây)

// -------------------------------------------
// 10. THIẾT LẬP MÚI GIỜ MẶC ĐỊNH
// -------------------------------------------
date_default_timezone_set(APP_TIMEZONE);
