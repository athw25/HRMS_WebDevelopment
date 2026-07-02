<?php
/**
 * =========================================================
 * FILE: helpers/sanitize-helper.php
 * MODULE: Core System - Helper Functions
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Cung cấp các hàm dùng chung để làm sạch (sanitize) và
 *   kiểm tra hợp lệ (validate) dữ liệu đầu vào từ người dùng.
 *   Mọi module (User, Employee, Attendance...) đều tái sử dụng
 *   các hàm này thay vì tự viết validate riêng.
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

/**
 * Làm sạch một chuỗi input: xóa khoảng trắng thừa, mã hóa HTML entity
 * để chống XSS khi hiển thị lại ra giao diện.
 *
 * @param string|null $value
 * @return string
 */
function sanitizeInput(?string $value): string
{
    $value = trim($value ?? '');
    $value = stripslashes($value);
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Làm sạch toàn bộ một mảng dữ liệu (thường dùng cho $_POST).
 *
 * @param array $data
 * @return array
 */
function sanitizeArray(array $data): array
{
    $sanitized = [];
    foreach ($data as $key => $value) {
        $sanitized[$key] = is_array($value) ? sanitizeArray($value) : sanitizeInput($value);
    }
    return $sanitized;
}

/**
 * Kiểm tra chuỗi có rỗng hay không (sau khi trim).
 */
function isEmptyValue(?string $value): bool
{
    return trim($value ?? '') === '';
}

/**
 * Kiểm tra định dạng email hợp lệ.
 */
function isValidEmail(?string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Kiểm tra số điện thoại Việt Nam hợp lệ (10 số, bắt đầu bằng 0).
 */
function isValidPhone(?string $phone): bool
{
    return (bool) preg_match('/^0[0-9]{9}$/', $phone ?? '');
}

/**
 * Kiểm tra độ mạnh mật khẩu tối thiểu.
 * Quy định: tối thiểu 8 ký tự, có ít nhất 1 chữ và 1 số.
 */
function isValidPassword(?string $password): bool
{
    $password = $password ?? '';
    return strlen($password) >= 8
        && preg_match('/[A-Za-z]/', $password)
        && preg_match('/[0-9]/', $password);
}

/**
 * Kiểm tra giá trị có nằm trong danh sách cho phép không.
 * Dùng để validate ENUM (giới tính, trạng thái, role...).
 *
 * @param mixed $value
 * @param array $allowedValues
 */
function isValidEnum($value, array $allowedValues): bool
{
    return in_array($value, $allowedValues, true);
}

/**
 * Kiểm tra một chuỗi có phải số nguyên dương hợp lệ (dùng cho ID).
 */
function isValidId($value): bool
{
    return filter_var($value, FILTER_VALIDATE_INT) !== false && (int) $value > 0;
}
