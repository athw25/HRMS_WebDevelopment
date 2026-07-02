<?php
// modules/employees/employee-edit.php

include_once '../../config/database.php';
include_once '../../config/session.php';
include_once '../../config/permissions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['user_role'] = 'Admin'; // Giả lập quyền để test

$current_role = $_SESSION['user_role'] ?? 'Employee';

// Kiểm tra quyền sửa
if (!hasPermission($current_role, 'employee_edit')) {
    die("<div class='alert alert-danger mt-5 text-center'>Bạn không có quyền truy cập chức năng này!</div>");
}

$error = "";
$success = "";
$conn = getDBConnection();

// Kiểm tra xem có ID nhân viên cần sửa không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<div class='alert alert-danger mt-5 text-center'>Không tìm thấy nhân viên cần chỉnh sửa!</div>");
}

$id = (int)$_GET['id'];

// Lấy thông tin hiện tại của nhân viên để đổ vào form
$query = "SELECT * FROM employees WHERE id = $id";
$result = mysqli_query($conn, $query);
$employee = mysqli_fetch_assoc($result);

if (!$employee) {
    die("<div class='alert alert-danger mt-5 text-center'>Nhân viên không tồn tại trên hệ thống!</div>");
}

// Xử lý khi bấm nút "Cập nhật hồ sơ"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $avatar_name = $employee['avatar']; // Mặc định giữ lại tên ảnh cũ

    // Xử lý nếu người dùng chọn ảnh đại diện mới
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $target_dir = "../../assets/uploads/";
        $file_extension = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
        
        $allow_types = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($file_extension, $allow_types)) {
            // Tạo tên file ảnh mới
            $new_avatar_name = $employee['employee_code'] . "_" . time() . "." . $file_extension;
            $target_file = $target_dir . $new_avatar_name;

            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                // Xóa file ảnh cũ trên server (nếu không phải ảnh mặc định)
                if ($employee['avatar'] != 'default-avatar.png' && file_exists($target_dir . $employee['avatar'])) {
                    unlink($target_dir . $employee['avatar']);
                }
                $avatar_name = $new_avatar_name; // Gán tên file mới để cập nhật CSDL
            } else {
                $error = "Không thể tải lên tệp tin ảnh mới.";
            }
        } else {
            $error = "Định dạng file ảnh không hợp lệ.";
        }
    }

    // Nếu không có lỗi, tiến hành cập nhật CSDL
    if (empty($error)) {
        $sql_update = "UPDATE employees SET 
                        full_name = '$full_name', 
                        gender = '$gender', 
                        birthday = '$birthday', 
                        phone = '$phone', 
                        email = '$email', 
                        avatar = '$avatar_name', 
                        status = '$status' 
                       WHERE id = $id";
        
        if (mysqli_query($conn, $sql_update)) {
            $success = "Cập nhật thông tin nhân viên thành công!";
            // Tải lại dữ liệu mới để hiển thị lên form
            $query = "SELECT * FROM employees WHERE id = $id";
            $result = mysqli_query($conn, $query);
            $employee = mysqli_fetch_assoc($result);
        } else {
            $error = "Lỗi hệ thống: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Nhân Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 800px;">
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Chỉnh Sửa Hồ Sơ: <?php echo htmlspecialchars($employee['full_name']); ?> (<?php echo $employee['employee_code']; ?>)</h5>
        </div>
        <div class="card-body">
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Mã Nhân Viên (Không được sửa)</label>
                    <input type="text" class="form-control" value="<?php echo $employee['employee_code']; ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Họ và Tên <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($employee['full_name']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Giới tính</label>
                    <select name="gender" class="form-select">
                        <option value="Nam" <?php if($employee['gender'] == 'Nam') echo 'selected'; ?>>Nam</option>
                        <option value="Nữ" <?php if($employee['gender'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
                        <option value="Khác" <?php if($employee['gender'] == 'Khác') echo 'selected'; ?>>Khác</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="birthday" class="form-control" value="<?php echo $employee['birthday']; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($employee['phone']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($employee['email']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Trạng thái làm việc</label>
                    <select name="status" class="form-select">
                        <option value="Đang làm việc" <?php if($employee['status'] == 'Đang làm việc') echo 'selected'; ?>>Đang làm việc</option>
                        <option value="Thử việc" <?php if($employee['status'] == 'Thử việc') echo 'selected'; ?>>Thử việc</option>
                        <option value="Nghỉ việc" <?php if($employee['status'] == 'Nghỉ việc') echo 'selected'; ?>>Nghỉ việc</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Thay ảnh đại diện mới (Bỏ trống nếu giữ nguyên)</label>
                    <input type="file" name="avatar" class="form-control mb-2">
                    <small class="text-muted">Ảnh hiện tại: <?php echo $employee['avatar']; ?></small>
                </div>
                
                <div class="col-12 text-end mt-4 pt-3 border-top">
                    <a href="index.php" class="btn btn-secondary me-2">Quay lại</a>
                    <button type="submit" class="btn btn-info text-white px-4">Cập nhật hồ sơ</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

</body>
</html>