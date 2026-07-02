<?php
/**
 * =========================================================
 * FILE: logout.php
 * MODULE: Authentication
 * OWNER: Thành viên 1 - System Architect & Authentication Engineer
 * MỤC ĐÍCH: Đăng xuất người dùng khỏi hệ thống.
 * PERMISSION: Yêu cầu đã đăng nhập (auth.middleware.php).
 * =========================================================
 */

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/middleware/auth.middleware.php';

// Hủy toàn bộ session (đã bao gồm xóa cookie session theo chuẩn bảo mật)
destroySession();

// Phải khởi động lại session mới để có thể lưu flash alert
// hiển thị trên trang login sau khi redirect.
startSession();
setSuccessAlert('Bạn đã đăng xuất thành công.');
redirectTo('/login.php');
