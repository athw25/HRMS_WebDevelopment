<?php
/**
 * =========================================================
 * FILE: helpers/auth-helper.php
 * MODULE: Authentication
 * OWNER: Thành viên 1 - System Architect & Authentication Engineer
 * MỤC ĐÍCH:
 *   Toàn bộ logic nghiệp vụ xác thực: đăng nhập, đổi mật khẩu,
 *   CSRF token, chống brute-force. Được dùng bởi login.php,
 *   logout.php, modules/profile/change-password.php.
 * DATABASE LIÊN QUAN:
 *   - users (đọc/ghi)
 *   - roles (JOIN lấy role_name)
 *   - employees (CHỈ SELECT - schema do TV2 sở hữu, đã chốt ERD)
 * DEPENDENCIES:
 *   - config/database.php (class Database)
 *   - config/constants.php (ROLE_*, USER_STATUS_*)
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

// -------------------------------------------
// CSRF PROTECTION
// -------------------------------------------

/**
 * Sinh (hoặc lấy lại) CSRF token cho form hiện tại trong session.
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Kiểm tra CSRF token gửi lên từ form có khớp với session không.
 * Dùng hash_equals() để chống timing attack.
 */
function verifyCsrfToken(?string $token): bool
{
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// -------------------------------------------
// CHỐNG BRUTE-FORCE
// -------------------------------------------

const MAX_LOGIN_ATTEMPTS = 5;
const LOCKOUT_MINUTES = 15;

/**
 * Kiểm tra tài khoản có đang bị tạm khóa do đăng nhập sai quá nhiều lần không.
 */
function isAccountTemporarilyLocked(array $user): bool
{
    if (empty($user['locked_until'])) {
        return false;
    }
    return strtotime($user['locked_until']) > time();
}

/**
 * Ghi nhận 1 lần đăng nhập sai: tăng bộ đếm, khóa tạm nếu vượt ngưỡng.
 *
 * @return bool true nếu lần này VỪA kích hoạt khóa tạm (để phản hồi ngay
 *              trong cùng request thay vì phải đợi lần thử kế tiếp).
 */
function registerFailedAttempt(int $userId, int $currentFailedAttempts): bool
{
    $pdo = Database::getConnection();
    $newAttempts = $currentFailedAttempts + 1;
    $justLocked = $newAttempts >= MAX_LOGIN_ATTEMPTS;

    if ($justLocked) {
        // Tính thời điểm hết khóa bằng PHP time() (không dùng MySQL NOW())
        // để tránh sai lệch khi múi giờ hệ điều hành MySQL server khác
        // với múi giờ ứng dụng PHP (APP_TIMEZONE) - đã xảy ra thực tế
        // khi kiểm thử: MySQL SYSTEM timezone = UTC, PHP = UTC+7.
        $lockedUntil = date('Y-m-d H:i:s', time() + (LOCKOUT_MINUTES * 60));

        $stmt = $pdo->prepare(
            'UPDATE users SET failed_attempts = :attempts, locked_until = :locked_until WHERE id = :id'
        );
        $stmt->execute([
            ':attempts'     => $newAttempts,
            ':locked_until' => $lockedUntil,
            ':id'           => $userId,
        ]);
        return true;
    }

    $stmt = $pdo->prepare('UPDATE users SET failed_attempts = :attempts WHERE id = :id');
    $stmt->execute([':attempts' => $newAttempts, ':id' => $userId]);

    return false;
}

/**
 * Reset bộ đếm đăng nhập sai sau khi đăng nhập thành công.
 */
function resetFailedAttempts(int $userId): void
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare(
        'UPDATE users SET failed_attempts = 0, locked_until = NULL, last_login_at = NOW() WHERE id = :id'
    );
    $stmt->execute([':id' => $userId]);
}

// -------------------------------------------
// TRUY VẤN NGƯỜI DÙNG
// -------------------------------------------

/**
 * Tìm user theo username, kèm role_name (JOIN roles).
 *
 * @return array|null
 */
function findUserByUsername(string $username): ?array
{
    $pdo = Database::getConnection();
    $stmt = $pdo->prepare(
        'SELECT u.id, u.username, u.password, u.role_id, u.employee_id,
                u.status, u.failed_attempts, u.locked_until,
                r.role_name
         FROM users u
         INNER JOIN roles r ON r.id = u.role_id
         WHERE u.username = :username
         LIMIT 1'
    );
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    return $user ?: null;
}

/**
 * Lấy thông tin hồ sơ nhân viên (full_name, avatar, department_id,
 * position_id) tương ứng với user, dùng để nạp session đầy đủ.
 * Đọc CHỈ ĐỌC bảng employees (schema do TV2 sở hữu).
 *
 * Nếu user chưa gắn employee_id (vd: tài khoản Admin hệ thống)
 * hoặc bảng employees chưa tồn tại (đang phát triển song song),
 * trả về giá trị mặc định an toàn thay vì làm sập luồng đăng nhập.
 *
 * @return array{full_name: string, avatar: ?string, department_id: ?int, position_id: ?int}
 */
function getEmployeeProfile(?int $employeeId, string $fallbackName): array
{
    $default = [
        'full_name'     => $fallbackName,
        'avatar'        => null,
        'department_id' => null,
        'position_id'   => null,
    ];

    if ($employeeId === null) {
        return $default;
    }

    try {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT full_name, avatar, department_id, position_id
             FROM employees WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $employeeId]);
        $employee = $stmt->fetch();

        return $employee ?: $default;
    } catch (PDOException $e) {
        // Bảng employees có thể chưa được tạo (module TV2 đang phát triển song song).
        // Ghi log để biết, nhưng KHÔNG làm gián đoạn luồng đăng nhập.
        error_log('[AUTH] Không thể tải hồ sơ nhân viên: ' . $e->getMessage());
        return $default;
    }
}

// -------------------------------------------
// ĐĂNG NHẬP
// -------------------------------------------

/**
 * Thực hiện xác thực đăng nhập đầy đủ.
 *
 * @return array{success: bool, message: string, sessionData: ?array}
 */
function attemptLogin(string $username, string $password): array
{
    $user = findUserByUsername($username);

    // Không tiết lộ việc sai username hay sai password riêng biệt (bảo mật)
    $genericErrorMessage = 'Tên đăng nhập hoặc mật khẩu không đúng.';

    if ($user === null) {
        return ['success' => false, 'message' => $genericErrorMessage, 'sessionData' => null];
    }

    if ($user['status'] === USER_STATUS_LOCKED) {
        return ['success' => false, 'message' => 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.', 'sessionData' => null];
    }

    if (isAccountTemporarilyLocked($user)) {
        return [
            'success' => false,
            'message' => 'Tài khoản tạm thời bị khóa do đăng nhập sai quá nhiều lần. Vui lòng thử lại sau ' . LOCKOUT_MINUTES . ' phút.',
            'sessionData' => null,
        ];
    }

    if (!password_verify($password, $user['password'])) {
        $justLocked = registerFailedAttempt((int) $user['id'], (int) $user['failed_attempts']);

        if ($justLocked) {
            return [
                'success' => false,
                'message' => 'Bạn đã nhập sai quá nhiều lần. Tài khoản tạm thời bị khóa trong ' . LOCKOUT_MINUTES . ' phút.',
                'sessionData' => null,
            ];
        }

        return ['success' => false, 'message' => $genericErrorMessage, 'sessionData' => null];
    }

    // Đăng nhập thành công
    resetFailedAttempts((int) $user['id']);

    $profile = getEmployeeProfile(
        $user['employee_id'] !== null ? (int) $user['employee_id'] : null,
        $user['username']
    );

    $sessionData = [
        'user_id'       => (int) $user['id'],
        'username'      => $user['username'],
        'role'          => $user['role_name'],
        'employee_id'   => $user['employee_id'] !== null ? (int) $user['employee_id'] : null,
        'department_id' => $profile['department_id'],
        'position_id'   => $profile['position_id'],
        'full_name'     => $profile['full_name'],
        'avatar'        => $profile['avatar'],
    ];

    return ['success' => true, 'message' => 'Đăng nhập thành công.', 'sessionData' => $sessionData];
}

// -------------------------------------------
// ĐỔI MẬT KHẨU
// -------------------------------------------

/**
 * Đổi mật khẩu cho user hiện tại.
 * Yêu cầu xác thực đúng mật khẩu cũ trước khi cho đổi.
 *
 * @return array{success: bool, message: string}
 */
function changeUserPassword(int $userId, string $currentPassword, string $newPassword): array
{
    $pdo = Database::getConnection();

    $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch();

    if ($user === false) {
        return ['success' => false, 'message' => 'Không tìm thấy tài khoản.'];
    }

    if (!password_verify($currentPassword, $user['password'])) {
        return ['success' => false, 'message' => 'Mật khẩu hiện tại không đúng.'];
    }

    if (password_verify($newPassword, $user['password'])) {
        return ['success' => false, 'message' => 'Mật khẩu mới không được trùng mật khẩu hiện tại.'];
    }

    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
    $update = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
    $update->execute([':password' => $newHash, ':id' => $userId]);

    return ['success' => true, 'message' => 'Đổi mật khẩu thành công.'];
}
