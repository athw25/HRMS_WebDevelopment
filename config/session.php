<?php
/**
 * =========================================================
 * FILE: config/session.php
 * MODULE: Core System
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Quản lý tập trung phiên đăng nhập (session) của người dùng.
 *   Mọi module KHÔNG được thao tác trực tiếp $_SESSION,
 *   phải sử dụng các hàm cung cấp trong file này.
 * REQUIRED SESSION KEYS (theo Project Instructions mục 9):
 *   user_id, username, role, employee_id,
 *   department_id, position_id, full_name, avatar
 * DEPENDENCIES:
 *   - config/constants.php (SESSION_NAME, SESSION_LIFETIME, ROLE_*)
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

/**
 * Khởi tạo session với cấu hình bảo mật.
 * Phải được gọi ở đầu mọi file entry point (trước khi output HTML).
 */
function startSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_name(defined('SESSION_NAME') ? SESSION_NAME : 'HRMS_SESSION');

    session_set_cookie_params([
        'lifetime' => defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 7200,
        'path'     => '/',
        'httponly' => true,     // Chống truy cập cookie qua JavaScript (XSS)
        'samesite' => 'Strict', // Chống tấn công CSRF
        'secure'   => isHttps(),
    ]);

    session_start();

    // Tự động hủy session nếu quá thời gian không hoạt động
    regenerateSessionIfExpired();
}

/**
 * Kiểm tra kết nối hiện tại có phải HTTPS không.
 */
function isHttps(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
}

/**
 * Hủy session nếu người dùng không hoạt động quá SESSION_LIFETIME.
 */
function regenerateSessionIfExpired(): void
{
    $lifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 7200;

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $lifetime)) {
        destroySession();
        startSession();
        return;
    }

    $_SESSION['last_activity'] = time();
}

/**
 * Lưu thông tin người dùng vào session sau khi đăng nhập thành công.
 * Được gọi từ module Authentication (login.php).
 *
 * @param array $userData Mảng chứa đầy đủ các khóa bắt buộc
 */
function setUserSession(array $userData): void
{
    $requiredKeys = [
        'user_id', 'username', 'role', 'employee_id',
        'department_id', 'position_id', 'full_name', 'avatar',
    ];

    foreach ($requiredKeys as $key) {
        $_SESSION['user'][$key] = $userData[$key] ?? null;
    }

    $_SESSION['last_activity'] = time();

    // Chống Session Fixation Attack: cấp session ID mới sau khi đăng nhập
    session_regenerate_id(true);
}

/**
 * Lấy toàn bộ thông tin user hiện tại trong session.
 *
 * @return array|null
 */
function getUserSession(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Lấy một trường cụ thể trong session user (vd: getUserField('role')).
 *
 * @param string $field
 * @return mixed|null
 */
function getUserField(string $field)
{
    return $_SESSION['user'][$field] ?? null;
}

/**
 * Kiểm tra người dùng đã đăng nhập hay chưa.
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user']['user_id']) && !empty($_SESSION['user']['user_id']);
}

/**
 * Kiểm tra người dùng hiện tại có đúng vai trò được truyền vào không.
 * Hỗ trợ kiểm tra nhiều vai trò cùng lúc.
 *
 * @param string|array $roles
 */
function hasRole($roles): bool
{
    if (!isLoggedIn()) {
        return false;
    }

    $currentRole = getUserField('role');
    $roles = is_array($roles) ? $roles : [$roles];

    return in_array($currentRole, $roles, true);
}

/**
 * Hủy toàn bộ session hiện tại (dùng khi logout).
 */
function destroySession(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
