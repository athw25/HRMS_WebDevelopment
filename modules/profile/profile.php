<?php
/**
 * Profile View
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../middleware/auth.middleware.php';

require_once __DIR__ . '/profile_controller.php';

$controller = new ProfileController($conn);
$user = $controller->index();

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="container-fluid mt-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-person-circle"></i>
                Hồ sơ cá nhân
            </h4>
        </div>

        <div class="card-body">

            <?php if(isset($_SESSION['success'])): ?>

                <div class="alert alert-success">
                    <?= $_SESSION['success']; ?>
                </div>

                <?php unset($_SESSION['success']); ?>

            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>

                <div class="alert alert-danger">
                    <?= $_SESSION['error']; ?>
                </div>

                <?php unset($_SESSION['error']); ?>

            <?php endif; ?>


            <?php if($user): ?>

            <div class="row">

                <div class="col-md-3 text-center">

                    <img
                        src="https://ui-avatars.com/api/?name=<?= urlencode($user['fullname']); ?>&size=200"
                        class="img-thumbnail rounded-circle mb-3">

                    <h5><?= htmlspecialchars($user['fullname']); ?></h5>

                    <span class="badge bg-primary">
                        <?= htmlspecialchars($user['role']); ?>
                    </span>

                </div>


                <div class="col-md-9">

                    <table class="table table-bordered">

                        <tr>
                            <th width="200">Username</th>
                            <td><?= htmlspecialchars($user['username']); ?></td>
                        </tr>

                        <tr>
                            <th>Họ tên</th>
                            <td><?= htmlspecialchars($user['fullname']); ?></td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td><?= htmlspecialchars($user['email']); ?></td>
                        </tr>

                        <tr>
                            <th>Số điện thoại</th>
                            <td><?= htmlspecialchars($user['phone']); ?></td>
                        </tr>

                        <tr>
                            <th>Quyền</th>
                            <td><?= htmlspecialchars($user['role']); ?></td>
                        </tr>

                        <tr>
                            <th>Ngày tạo</th>
                            <td><?= date("d/m/Y H:i", strtotime($user['created_at'])); ?></td>
                        </tr>

                    </table>

                    <a href="profile-edit.php"
                       class="btn btn-primary">

                        <i class="bi bi-pencil-square"></i>

                        Chỉnh sửa hồ sơ

                    </a>

                    <a href="change-password.php"
                       class="btn btn-warning">

                        <i class="bi bi-key"></i>

                        Đổi mật khẩu

                    </a>

                </div>

            </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>