<?php

define('HRMS_APP', true);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

// Giả lập đã đăng nhập
startSession();

$_SESSION['user'] = [
    'user_id' => 1,
    'username' => 'admin',
    'role' => ROLE_ADMIN,
    'full_name' => 'Administrator',
    'avatar' => null
];

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Layout Test</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

</head>

<body>

<?php require_once INCLUDES_PATH . '/header.php'; ?>

<?php require_once INCLUDES_PATH . '/sidebar.php'; ?>

<div class="content">

    <h1>Layout Test</h1>

    <p>Nội dung giả lập để kiểm tra giao diện.</p>

</div>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>

</body>

</html>