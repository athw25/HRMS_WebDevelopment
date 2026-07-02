<?php
/**
 * Position Delete
 * Xử lý xóa chức vụ
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../middleware/auth.middleware.php';

require_once __DIR__ . '/position_controller.php';

$controller = new PositionController($conn);

// Kiểm tra ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    $_SESSION['error'] = "ID chức vụ không hợp lệ.";

    header("Location: position-list.php");
    exit;
}

$id = (int)$_GET['id'];

// Thực hiện xóa
$result = $controller->delete($id);

if ($result['success']) {

    $_SESSION['success'] = $result['message'];

} else {

    $_SESSION['error'] = $result['message'];

}

header("Location: position-list.php");
exit;