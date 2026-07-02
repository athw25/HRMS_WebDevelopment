<?php
/**
 * Department Edit View
 * Giao diện chỉnh sửa phòng ban
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../middleware/auth.middleware.php';

require_once 'department_controller.php';

$controller = new DepartmentController($conn);

// Xử lý lấy dữ liệu và cập nhật
$dept = $controller->handleEdit();

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="content-wrapper container-fluid px-4 py-4">

    <!-- Tiêu đề -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold text-dark mb-1">
                Chỉnh sửa phòng ban
            </h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="../../index.php" class="text-decoration-none">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="department-list.php" class="text-decoration-none">
                            Phòng ban
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Chỉnh sửa
                    </li>
                </ol>
            </nav>
        </div>

        <div>
            <a href="department-list.php"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left"></i>

                Quay lại
            </a>
        </div>

    </div>

    <!-- Thông báo lỗi -->
    <?php if (isset($_SESSION['error'])) : ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle-fill"></i>

            <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow-sm border-0 card-custom">

        <div class="card-body p-4">

            <form
                action="department-edit.php?id=<?php echo (int)$dept['id']; ?>"
                method="POST"
                id="formEditDepartment">
                                <div class="row g-3">

                    <!-- Mã phòng ban -->
                    <div class="col-md-4">

                        <label
                            for="department_code"
                            class="form-label fw-semibold">
                            Mã phòng ban
                        </label>

                        <input
                            type="text"
                            id="department_code"
                            class="form-control bg-light"
                            value="<?php echo htmlspecialchars($dept['department_code']); ?>"
                            disabled>

                        <div class="form-text">
                            Mã phòng ban không được phép thay đổi.
                        </div>

                    </div>

                    <!-- Tên phòng ban -->
                    <div class="col-md-8">

                        <label
                            for="department_name"
                            class="form-label fw-semibold">

                            Tên phòng ban
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="department_name"
                            name="department_name"
                            value="<?php echo htmlspecialchars($dept['department_name']); ?>"
                            required>

                    </div>

                    <!-- Mô tả -->
                    <div class="col-12">

                        <label
                            for="description"
                            class="form-label fw-semibold">

                            Mô tả

                        </label>

                        <textarea
                            class="form-control"
                            id="description"
                            name="description"
                            rows="5"><?php echo htmlspecialchars($dept['description']); ?></textarea>

                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="department-list.php"
                        class="btn btn-secondary">

                        <i class="bi bi-x-circle"></i>

                        Hủy

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>

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

<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formEditDepartment");

    form.addEventListener("submit", function (e) {

        const departmentName =
            document.getElementById("department_name")
            .value
            .trim();

        if (departmentName === "") {

            alert("Tên phòng ban không được để trống.");

            e.preventDefault();

            return;
        }

        if (
            departmentName.length < 3 ||
            departmentName.length > 100
        ) {

            alert("Tên phòng ban phải từ 3 đến 100 ký tự.");

            e.preventDefault();

            return;
        }

    });

});
</script>