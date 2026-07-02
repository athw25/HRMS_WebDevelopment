<?php
/**
 * Profile Edit
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../middleware/auth.middleware.php';

require_once __DIR__ . '/profile_controller.php';

$controller = new ProfileController($conn);

// Nếu bấm nút Lưu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller->update();
}

$user = $controller->index();

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="container-fluid mt-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-pencil-square"></i>
                Chỉnh sửa hồ sơ
            </h4>
        </div>

        <div class="card-body">

            <?php if(isset($_SESSION['error'])): ?>

                <div class="alert alert-danger">
                    <?= $_SESSION['error']; ?>
                </div>

                <?php unset($_SESSION['error']); ?>

            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Họ và tên
                    </label>

                    <input
                        type="text"
                        name="fullname"
                        class="form-control"
                        value="<?= htmlspecialchars($user['fullname']); ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($user['email']); ?>">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Số điện thoại
                    </label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?= htmlspecialchars($user['phone']); ?>">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($user['username']); ?>"
                        disabled>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Quyền
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars($user['role']); ?>"
                        disabled>

                </div>

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="bi bi-check-circle"></i>

                    Lưu thay đổi

                </button>

                <a
                    href="profile.php"
                    class="btn btn-secondary">

                    Quay lại

                </a>

            </form>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>