<?php
/**
 * =========================================================
 * FILE: helpers/alert-helper.php
 * MODULE: Core System - Helper Functions
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Quản lý thông báo (Flash Message / Toast Notification)
 *   dùng chung cho toàn hệ thống, theo Alert Contract đã chốt.
 *   Áp dụng mô hình PRG (Post -> Redirect -> Get):
 *   Thông báo được lưu vào session trước khi redirect,
 *   và tự động bị xóa sau khi hiển thị 1 lần.
 * LOẠI ALERT HỖ TRỢ: success | error | warning | info
 * DEPENDENCIES:
 *   - includes/session.php (session phải được start trước)
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

const ALERT_TYPES = ['success', 'error', 'warning', 'info'];

/**
 * Lưu 1 thông báo vào session để hiển thị ở trang tiếp theo.
 *
 * @param string $type    success | error | warning | info
 * @param string $message Nội dung thông báo
 */
function setAlert(string $type, string $message): void
{
    if (!in_array($type, ALERT_TYPES, true)) {
        $type = 'info';
    }

    $_SESSION['alert'] = [
        'type'    => $type,
        'message' => $message,
    ];
}

/**
 * Shortcut cho các loại thông báo thường dùng.
 */
function setSuccessAlert(string $message): void
{
    setAlert('success', $message);
}

function setErrorAlert(string $message): void
{
    setAlert('error', $message);
}

function setWarningAlert(string $message): void
{
    setAlert('warning', $message);
}

/**
 * Lấy thông báo hiện có và XÓA khỏi session (chỉ hiển thị 1 lần).
 *
 * @return array|null ['type' => string, 'message' => string]
 */
function getAlert(): ?array
{
    if (!isset($_SESSION['alert'])) {
        return null;
    }

    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']);

    return $alert;
}

/**
 * Kiểm tra hiện có thông báo đang chờ hiển thị không (không xóa).
 */
function hasAlert(): bool
{
    return isset($_SESSION['alert']);
}
