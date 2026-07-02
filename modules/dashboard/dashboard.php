<?php
$pageTitle = "Bảng điều khiển";
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $pageTitle ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/HRMS_WebDevelopment/assets/css/dashboard.css">

</head>

<body>

<div class="container-fluid py-4">

    <h2 class="mb-4">Bảng điều khiển</h2>

    <!-- Thẻ thống kê -->

    <div class="row">

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">

            <div class="dashboard-card">

                <h6>Tổng nhân viên</h6>

                <h2 id="totalEmployees">0</h2>

            </div>

        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">

            <div class="dashboard-card">

                <h6>Tổng phòng ban</h6>

                <h2 id="totalDepartments">0</h2>

            </div>

        </div>

        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">

            <div class="dashboard-card">

                <h6>Tổng chức vụ</h6>

                <h2 id="totalPositions">0</h2>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">

            <div class="dashboard-card">

                <h6>Tổng đơn nghỉ phép</h6>

                <h2 id="totalLeave">0</h2>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">

            <div class="dashboard-card">

                <h6>Điểm danh hôm nay</h6>

                <h2 id="todayAttendance">0</h2>

            </div>

        </div>

    </div>

    <!-- Khu vực biểu đồ -->

    <div class="row mt-4">

        <div class="col-lg-6 mb-4">

            <div class="dashboard-box">

                <h5>Thống kê nhân viên theo phòng ban</h5>

                <canvas id="departmentChart"></canvas>

            </div>

        </div>

        <div class="col-lg-6 mb-4">

            <div class="dashboard-box">

                <h5>Thống kê nhân viên theo chức vụ</h5>

                <canvas id="positionChart"></canvas>

            </div>

        </div>

        <div class="col-lg-6 mb-4">

            <div class="dashboard-box">

                <h5>Thống kê nghỉ phép</h5>

                <canvas id="leaveChart"></canvas>

            </div>

        </div>

        <div class="col-lg-6 mb-4">

            <div class="dashboard-box">

                <h5>Thống kê lương</h5>

                <canvas id="salaryChart"></canvas>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../../assets/js/charts.js"></script>
</body>

</html>