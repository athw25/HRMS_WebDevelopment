<?php
/**
 * =========================================================
 * FILE: includes/bootstrap.php
 * MODULE: Core System
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Điểm nạp (entry point) trung tâm cho toàn bộ Core System.
 *   MỌI file trong modules/, api/, login.php, logout.php...
 *   phải require file này ĐẦU TIÊN, trước bất kỳ dòng code nào khác.
 *
 * CÁCH DÙNG (bắt buộc ở đầu mỗi module):
 *   require_once __DIR__ . '/../../includes/bootstrap.php';
 *
 * THỨ TỰ NẠP (không được thay đổi):
 *   1. constants.php    - hằng số toàn cục
 *   2. database.php     - class Database (kết nối PDO)
 *   3. session.php      - quản lý session + start session
 *   4. sanitize-helper  - làm sạch/validate input
 *   5. format-helper    - định dạng hiển thị
 *   6. alert-helper     - flash message / toast
 *   7. redirect-helper  - điều hướng chuẩn hóa (phụ thuộc alert-helper)
 * =========================================================
 */


if (!defined('HRMS_APP')) {
    define('HRMS_APP', true);
}

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/permissions.php';

require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/validation_helper.php';
require_once __DIR__ . '/../helpers/format_helper.php';
require_once __DIR__ . '/../helpers/upload_helper.php';
require_once __DIR__ . '/../helpers/redirect_helper.php';
require_once __DIR__ . '/../helpers/alert_helper.php';

// Khởi động session ngay sau khi mọi hàm cần thiết đã được nạp
startSession();
