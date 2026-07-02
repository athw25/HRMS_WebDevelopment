<?php
// modules/employees/add.php

// 1. Nhúng các file cấu hình hệ thống
include_once '../../config/database.php';
include_once '../../config/session.php';
include_once '../../config/permissions.php';

// Kích hoạt session để test quyền (Khi nào hệ thống chạy thật sẽ dùng login chung)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['user_role'] = 'Admin'; // Giả lập quyền Admin để test

$current_role = $_SESSION['user_role'] ?? 'Employee';

// 2. Kiểm tra quyền thêm nhân viên
if (!hasPermission($current_role, 'employee_add')) {
    die("<div class='alert alert-danger mt-5 text-center'>Bạn không có quyền truy cập chức năng này!</div>");
}

$error = "";
$success = "";
$conn = getDBConnection();

// 3. Xử lý khi người dùng nhấn nút "Lưu hồ sơ" (Submit Form)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_code = mysqli_real_escape_string($conn, $_POST['employee_code']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Xử lý Upload Ảnh (Avatar)
    $avatar_name = "default-avatar.png"; // Tên file mặc định
    
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        // Định nghĩa đường dẫn lưu file ảnh (Trỏ ra thư mục assets/uploads/ chung của dự án)
        $target_dir = "../../assets/uploads/";
        
        // Tự động tạo thư mục nếu chưa có
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
        // Đổi tên ảnh theo mã nhân viên + thời gian để tránh bị trùng đè file
        $avatar_name = $employee_code . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $avatar_name;

        // Kiểm tra định dạng đuôi file
        $allow_types = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($file_extension, $allow_types)) {
            // Tiến hành di chuyển file từ bộ nhớ tạm vào thư mục assets/uploads
            if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                $error = "Không thể tải lên tệp tin ảnh.";
            }
        } else {
            $error = "Định dạng file không hợp lệ. Chỉ chấp nhận JPG, JPEG, PNG, GIF.";
        }
    }

    // Nếu không có lỗi xảy ra thì tiến hành thêm vào Cơ sở dữ liệu
    if (empty($error)) {
        // Kiểm tra trùng Mã nhân viên trước khi thêm
        $check_query = "SELECT id FROM employees WHERE employee_code = '$employee_code'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Mã nhân viên '$employee_code' đã tồn tại trong hệ thống!";
        } else {
            // Câu lệnh SQL Insert dữ liệu
            $sql_insert = "INSERT INTO employees (employee_code, full_name, gender, birthday, phone, email, avatar, status) 
                           VALUES ('$employee_code', '$full_name', '$gender', '$birthday', '$phone', '$email', '$avatar_name', '$status')";
            
            if (mysqli_query($conn, $sql_insert)) {
                $success = "Thêm mới nhân viên thành công!";
            } else {
                $error = "Lỗi hệ thống: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Mới Nhân Viên - HRMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 800px;">
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Thêm Mới Hồ Sơ Nhân Viên</h5>
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
                    <label class="form-label fw-bold">Mã Nhân Viên <span class="text-danger">*</span></label>
                    <input type="text" name="employee_code" class="form-control" required placeholder="Ví dụ: NV001">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Họ và Tên <span class="text-danger">*</span></label>
                    <input type="text" name="full_name" class="form-control" required placeholder="Nhập đầy đủ họ tên">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Giới tính</label>
                    <select name="gender" class="form-select">
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="birthday" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="example@domain.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Trạng thái làm việc</label>
                    <select name="status" class="form-select">
                        <option value="Đang làm việc">Đang làm việc</option>
                        <option value="Thử việc">Thử việc</option>
                        <option value="Nghỉ việc">Nghỉ việc</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ảnh đại diện (Avatar)</label>
                    <input type="file" name="avatar" class="form-control">
                </div>
                
                <div class="col-12 text-end mt-4 pt-3 border-top">
                    <a href="index.php" class="btn btn-secondary me-2">Quay lại danh sách</a>
                    <button type="submit" class="btn btn-success px-4">Lưu hồ sơ</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>