<?php
/**
 * =========================================================
 * FILE: helpers/redirect-helper.php
 * MODULE: Core System - Helper Functions
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Chuẩn hóa việc điều hướng (redirect) trong toàn hệ thống,
 *   áp dụng mô hình PRG (Post -> Redirect -> Get) để tránh
 *   resend form khi người dùng F5 trang.
 * DEPENDENCIES:
 *   - config/constants.php (BASE_URL)
 *   - helpers/alert-helper.php (setAlert, setSuccessAlert, setErrorAlert)
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

/**
 * Điều hướng người dùng tới 1 URL nội bộ (tính từ BASE_URL) và dừng script.
 *
 * @param string $path Đường dẫn tương đối, vd: '/modules/employees/employee-list.php'
 */
function redirectTo(string $path): void
{
    $base = defined('BASE_URL') ? BASE_URL : '';
    $target = $base . '/' . ltrim($path, '/');

    header('Location: ' . $target);
    exit;
}

/**
 * Set thông báo thành công rồi redirect - dùng phổ biến nhất trong Controller
 * sau khi Create/Update/Delete thành công.
 */
function redirectWithSuccess(string $path, string $message): void
{
    setSuccessAlert($message);
    redirectTo($path);
}

/**
 * Set thông báo lỗi rồi redirect - dùng khi validate thất bại
 * hoặc thao tác database gặp lỗi.
 */
function redirectWithError(string $path, string $message): void
{
    setErrorAlert($message);
    redirectTo($path);
}

/**
 * Điều hướng về trang trước đó (dùng cho nút "Back" động, nếu cần).
 * Ưu tiên HTTP_REFERER, fallback về $fallbackPath nếu không có.
 */
function redirectBack(string $fallbackPath = '/'): void
{
    $referer = $_SERVER['HTTP_REFERER'] ?? null;

    if ($referer) {
        header('Location: ' . $referer);
        exit;
    }

    redirectTo($fallbackPath);
}
