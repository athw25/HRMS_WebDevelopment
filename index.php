<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';

// Đếm số phòng ban
$departmentCount = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM departments");
if ($result) {
    $departmentCount = $result->fetch_assoc()['total'];
}

// Đếm số chức vụ
$positionCount = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM positions");
if ($result) {
    $positionCount = $result->fetch_assoc()['total'];
}

// Đếm số tài khoản
$userCount = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($result) {
    $userCount = $result->fetch_assoc()['total'];
}
?>

<div class="container-fluid mt-4">

    <h2 class="mb-4 fw-bold">
        Dashboard
    </h2>

    <div class="row">

        <div class="col-md-4 mb-3">
            <div class="card shadow border-0 bg-primary text-white">
                <div class="card-body">
                    <h5>Phòng ban</h5>
                    <h2><?= $departmentCount ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow border-0 bg-success text-white">
                <div class="card-body">
                    <h5>Chức vụ</h5>
                    <h2><?= $positionCount ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow border-0 bg-warning text-dark">
                <div class="card-body">
                    <h5>Người dùng</h5>
                    <h2><?= $userCount ?></h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow mt-4">
        <div class="card-header">
            <h5 class="mb-0">Giới thiệu hệ thống</h5>
        </div>
        <div class="card-body">
            <p>
                Chào mừng bạn đến với hệ thống
                <strong>HRMS - Human Resource Management System</strong>.
            </p>

            <p>
                Chức năng hiện có:
            </p>

            <ul>
                <li>Quản lý phòng ban</li>
                <li>Quản lý chức vụ</li>
                <li>Quản lý hồ sơ cá nhân</li>
            </ul>

        </div>
    </div>

</div>

<?php
include __DIR__ . '/includes/footer.php';
?>