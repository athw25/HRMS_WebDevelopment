<?php
/**
 * =========================================================
 * FILE: middleware/auth.middleware.php
 * MODULE: Core System - Middleware
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Kiểm tra người dùng đã đăng nhập hay chưa.
 *   Đây là middleware NỀN TẢNG - các middleware phân quyền
 *   khác (admin/manager/employee) đều require lại file này
 *   trước khi kiểm tra thêm điều kiện về vai trò (role).
 *
 * CÁCH DÙNG (đặt ngay sau bootstrap.php, trước logic module):
 *   require_once __DIR__ . '/../../includes/bootstrap.php';
 *   require_once __DIR__ . '/../../middleware/auth.middleware.php';
 *
 * DEPENDENCIES (phải được nạp trước qua bootstrap.php):
 *   - includes/session.php   (isLoggedIn, hasRole)
 *   - helpers/alert-helper.php
 *   - helpers/redirect-helper.php
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

/**
 * Kiểm tra request hiện tại có phải gọi từ AJAX/API (fetch, axios...) hay không.
 * Dùng để quyết định phản hồi JSON thay vì redirect HTML.
 */
function isAjaxRequest(): bool
{
    $requestedWith = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    $accept        = $_SERVER['HTTP_ACCEPT'] ?? '';

    return strtolower($requestedWith) === 'xmlhttprequest'
        || str_contains($accept, 'application/json');
}

/**
 * Chặn request và trả về phản hồi phù hợp theo ngữ cảnh (web hoặc AJAX).
 *
 * @param int    $httpCode 401 (chưa đăng nhập) hoặc 403 (không đủ quyền)
 * @param string $message  Thông báo hiển thị cho người dùng
 * @param string $redirectPath Đường dẫn redirect khi là request web thông thường
 */
function denyAccess(int $httpCode, string $message, string $redirectPath): void
{
    if (isAjaxRequest()) {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => false,
            'message' => $message,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    redirectWithError($redirectPath, $message);
}

// -------------------------------------------
// KIỂM TRA CHÍNH: Người dùng đã đăng nhập chưa?
// -------------------------------------------
if (!isLoggedIn()) {
    denyAccess(401, 'Vui lòng đăng nhập để tiếp tục.', '/login.php');
}
