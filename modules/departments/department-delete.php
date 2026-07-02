<?php
/**
 * Department Delete
 * Xử lý xóa phòng ban
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../middleware/auth.middleware.php';

require_once 'department_controller.php';

$controller = new DepartmentController($conn);

// Kiểm tra ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    $_SESSION['error'] = "ID phòng ban không hợp lệ.";

    header("Location: department-list.php");

    exit;
}

$id = (int)$_GET['id'];

// Kiểm tra phòng ban có tồn tại hay không
$department = $controller->show($id);

if (!$department) {

    $_SESSION['error'] = "Không tìm thấy phòng ban.";

    header("Location: department-list.php");

    exit;
}

// Thực hiện xóa
$result = $controller->delete($id);

if ($result['success']) {

    $_SESSION['success'] = $result['message'];

} else {

    $_SESSION['error'] = $result['message'];

}

// Quay về danh sách
header("Location: department-list.php");
exit;
?>