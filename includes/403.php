<?php
/**
 * File: includes/403.php
 * Mo ta: Trang thong bao khong co quyen truy cap.
 */

if (!defined('HRMS_APP')) {
    die('Truy cap truc tiep khong duoc phep.');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>403 - Khong co quyen truy cap | HRMS</title>
    <link rel="stylesheet" href="<?= ASSET_URL ?>/css/style.css">
</head>
<body class="error-page">
    <div class="error-container">
        <h1 class="error-code">403</h1>
        <p class="error-message">Ban khong co quyen truy cap chuc nang nay.</p>
        <a href="<?= BASE_URL ?>/modules/dashboard/dashboard.php" class="btn btn-primary">Ve Dashboard</a>
    </div>
</body>
</html>
