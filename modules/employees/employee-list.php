<?php
// modules/employees/employee-list.php

include_once '../../config/database.php';
include_once '../../config/session.php';
include_once '../../config/permissions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['user_role'] = 'Admin'; // Giả lập quyền Admin để chạy thử nghiệm

$current_role = $_SESSION['user_role'] ?? 'Employee';

if (!hasPermission($current_role, 'employee_view')) {
    die("<div class='alert alert-danger mt-5 text-center'>Bạn không có quyền truy cập chức năng này!</div>");
}

$conn = getDBConnection();

// Xử lý Tìm kiếm và Bộ lọc
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

$sql = "SELECT * FROM employees WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (full_name LIKE '%$search%' OR employee_code LIKE '%$search%' OR email LIKE '%$search%')";
}

if (!empty($status_filter)) {
    $sql .= " AND status = '$status_filter'";
}

$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Nhân Viên - HRMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid px-4 mt-4">
    <h1 class="mt-4">Quản Lý Nhân Viên</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Module Nhân sự / Danh sách nhân viên</li>
    </ol>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh Sách Nhân Viên Hiện Có</h5>
            <?php if (hasPermission($current_role, 'employee_add')): ?>
                <a href="employee-create.php" class="btn btn-success btn-sm">+ Thêm Nhân Viên Mới</a>
            <?php endif; ?>
        </div>
        
        <div class="card-body">
            <form method="GET" action="" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Tìm tên, mã nhân viên, email..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="Đang làm việc" <?php if($status_filter == 'Đang làm việc') echo 'selected'; ?>>Đang làm việc</option>
                        <option value="Thử việc" <?php if($status_filter == 'Thử việc') echo 'selected'; ?>>Thử việc</option>
                        <option value="Nghỉ việc" <?php if($status_filter == 'Nghỉ việc') echo 'selected'; ?>>Nghỉ việc</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Lọc</button>
                    <a href="employee-list.php" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th width="7%">Ảnh</th>
                            <th>Mã NV</th>
                            <th>Họ và Tên</th>
                            <th>Giới tính</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Trạng thái</th>
                            <th width="20%" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $badge_class = 'bg-success';
                                if ($row['status'] == 'Thử việc') $badge_class = 'bg-warning text-dark';
                                if ($row['status'] == 'Nghỉ việc') $badge_class = 'bg-danger';
                                
                                $avatar_path = "../../assets/uploads/" . $row['avatar'];
                                if (!file_exists($avatar_path) || empty($row['avatar'])) {
                                    $avatar_path = "https://via.placeholder.com/150";
                                }
                        ?>
                        <tr>
                            <td class="text-center">
                                <img src="<?php echo $avatar_path; ?>" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                            </td>
                            <td><strong><?php echo $row['employee_code']; ?></strong></td>
                            <td><?php echo $row['full_name']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><span class="badge <?php echo $badge_class; ?>"><?php echo $row['status']; ?></span></td>
                            
                            <td class="text-center">
                                <a href="employee-detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-secondary me-1">Xem</a>
                                <?php if (hasPermission($current_role, 'employee_edit')): ?>
                                    <a href="employee-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white me-1">Sửa</a>
                                <?php endif; ?>
                                <?php if (hasPermission($current_role, 'employee_delete')): ?>
                                    <a href="employee-delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa nhân viên này?')">Xóa</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center text-muted py-3'>Không tìm thấy nhân viên nào.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>