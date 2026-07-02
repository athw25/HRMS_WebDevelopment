```php
<?php
/**
 * Position Edit View
 * Chỉnh sửa chức vụ
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../middleware/auth.middleware.php';
require_once __DIR__ . '/position_controller.php';

$controller = new PositionController($conn);

// Kiểm tra ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    $_SESSION['error'] = 'ID chức vụ không hợp lệ.';

    header('Location: position-list.php');

    exit;
}

$id = (int) $_GET['id'];

// Lấy thông tin chức vụ
$pos = $controller->show($id);

if (!$pos) {

    $_SESSION['error'] = 'Không tìm thấy chức vụ.';

    header('Location: position-list.php');

    exit;
}

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $result = $controller->update($id);

    if ($result['success']) {

        $_SESSION['success'] = $result['message'];

        header('Location: position-list.php');

        exit;
    }

    $_SESSION['error'] = $result['message'];

    // Giữ lại dữ liệu vừa nhập
    $pos['position_name'] = $_POST['position_name'];
    $pos['base_salary'] = $_POST['base_salary'];
}

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="content-wrapper container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">

                Chỉnh sửa chức vụ

            </h4>

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">

                        <a href="../../index.php">

                            Dashboard

                        </a>

                    </li>

                    <li class="breadcrumb-item">

                        <a href="position-list.php">

                            Chức vụ

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        Chỉnh sửa

                    </li>

                </ol>

            </nav>

        </div>

        <a
            href="position-list.php"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>

            Quay lại

        </a>

    </div>

    <?php if (isset($_SESSION['error'])) : ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <?= $_SESSION['error']; ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form
                action="position-edit.php?id=<?= $pos['id']; ?>"
                method="POST">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Mã chức vụ

                        </label>

                        <input
                            type="text"
                            class="form-control bg-light"
                            value="<?= htmlspecialchars($pos['position_code']); ?>"
                            disabled>

                    </div>

                    <div class="col-md-8 mb-3">

                        <label
                            for="position_name"
                            class="form-label">

                            Tên chức vụ

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            id="position_name"
                            name="position_name"
                            class="form-control"
                            value="<?= htmlspecialchars($pos['position_name']); ?>"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label
                            for="base_salary"
                            class="form-label">

                            Lương cơ bản

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            id="base_salary"
                            name="base_salary"
                            class="form-control"
                            min="0"
                            value="<?= htmlspecialchars($pos['base_salary_coefficient']); ?>"
                            required>

                    </div>

                </div>

                <hr>

                <div class="text-end">

                    <a
                        href="position-list.php"
                        class="btn btn-secondary">

                        Hủy

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-check-lg"></i>

                        Cập nhật

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php
include '../../includes/footer.php';
?>
