<?php
// modules/employees/employee-detail.php

include_once '../../config/database.php';
include_once '../../config/session.php';
include_once '../../config/permissions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['user_role'] = 'Admin'; 

$current_role = $_SESSION['user_role'] ?? 'Employee';

if (!hasPermission($current_role, 'employee_view')) {
    die("<div class='alert alert-danger mt-5 text-center'>Bạn không có quyền truy cập chức năng này!</div>");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<div class='alert alert-danger mt-5 text-center'>Không tìm thấy thông tin nhân viên!</div>");
}

$id = (int)$_GET['id'];
$conn = getDBConnection();

$query = "SELECT * FROM employees WHERE id = $id";
$result = mysqli_query($conn, $query);
$employee = mysqli_fetch_assoc($result);

if (!$employee) {
    die("<div class='alert alert-danger mt-5 text-center'>Nhân viên không tồn tại!</div>");
}

$avatar_path = "../../assets/uploads/" . $employee['avatar'];
if (!file_exists($avatar_path) || empty($employee['avatar'])) {
    $avatar_path = "https://via.placeholder.com/150";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 700px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Hồ Sơ Chi Tiết Nhân Viên</h5>
        </div>
        <div class="card-body text-center bg-white border-bottom">
            <img src="<?php echo $avatar_path; ?>" class="rounded-circle img-thumbnail mb-3" width="130" height="130" style="object-fit: cover;">
            <h4><?php echo htmlspecialchars($employee['full_name']); ?></h4>
            <span class="badge bg-secondary"><?php echo $employee['employee_code']; ?></span>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered align-middle">
                <tr>
                    <th width="35%">Giới tính</th>
                    <td><?php echo $employee['gender']; ?></td>
                </tr>
                <tr>
                    <th>Ngày sinh</th>
                    <td><?php echo ($employee['birthday'] && $employee['birthday'] != '0000-00-00') ? date('d/m/Y', strtotime($employee['birthday'])) : 'Chưa cập nhật'; ?></td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td><?php echo htmlspecialchars($employee['phone'] ?? 'Chưa cập nhật'); ?></td>
                </tr>
                <tr>
                    <th>Email liên hệ</th>
                    <td><?php echo htmlspecialchars($employee['email'] ?? 'Chưa cập nhật'); ?></td>
                </tr>
                <tr>
                    <th>Trạng thái hiện tại</th>
                    <td>
                        <?php 
                        $status = $employee['status'];
                        $class = ($status == 'Đang làm việc') ? 'success' : (($status == 'Thử việc') ? 'warning text-dark' : 'danger');
                        echo "<span class='badge bg-$class'>$status</span>";
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Ngày khởi tạo hồ sơ</th>
                    <td><?php echo date('d/m/Y H:i', strtotime($employee['created_at'])); ?></td>
                </tr>
            </table>
            
            <div class="text-end mt-4">
                <a href="index.php" class="btn btn-secondary">Quay lại danh sách</a>
                <a href="employee-edit.php?id=<?php echo $employee['id']; ?>" class="btn btn-warning text-dark">Sửa thông tin</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>