<?php
/**
 * ==========================================================
 * Department Create View
 * Module : Department Management
 * Project: Human Resource Management System (HRMS)
 * Author : Nguyễn Quang Minh (TV3)
 * ==========================================================
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../middleware/auth.middleware.php';

require_once '../../modules/departments/department_controller.php';

$controller = new DepartmentController($conn);

// Xử lý khi Submit Form
$controller->handleCreate();

// Lấy lại dữ liệu nếu Validate lỗi
$old = $_SESSION['old_input'] ?? [
    'department_code' => '',
    'department_name' => '',
    'description' => ''
];

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="content-wrapper container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">

                Thêm Phòng Ban Mới

            </h3>

            <nav>

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">
                        <a href="../../index.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="department-list.php">
                            Phòng Ban
                        </a>
                    </li>

                    <li class="breadcrumb-item active">

                        Thêm Mới

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

    <?php if(isset($_SESSION['error'])): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle-fill me-2"></i>

            <?= $_SESSION['error']; ?>

            <?php unset($_SESSION['error']); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>


    <div class="card card-custom shadow-sm border-0 col-xl-8 col-lg-10 mx-auto">

        <div class="card-body p-4">

            <form
                method="POST"
                action="department-create.php"
                id="formCreateDepartment"
                novalidate>

                <div class="row g-3">

                    <div class="col-md-4">

                        <label
                            for="department_code"
                            class="form-label fw-semibold">

                            Mã phòng ban

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="department_code"
                            name="department_code"
                            maxlength="10"
                            placeholder="VD: IT, HR"

                            value="<?= htmlspecialchars($old['department_code']); ?>"

                            required>

                        <div class="form-text">

                            Chỉ nhập chữ IN HOA hoặc số (2-10 ký tự)

                        </div>

                    </div>


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

                            value="<?= htmlspecialchars($old['department_name']); ?>"

                            placeholder="Nhập tên phòng ban"

                            required>

                    </div>


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
                            rows="5"
                            placeholder="Nhập mô tả phòng ban..."><?= htmlspecialchars($old['description']); ?></textarea>

                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="department-list.php"
                        class="btn btn-light border">

                        Hủy

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-save me-1"></i>

                        Lưu phòng ban

                    </button>

                </div>
                            </form>

        </div>

    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formCreateDepartment");

    const code = document.getElementById("department_code");
    const name = document.getElementById("department_name");

    form.addEventListener("submit", function (e) {

        let hasError = false;

        // Xóa trạng thái lỗi cũ
        code.classList.remove("is-invalid");
        name.classList.remove("is-invalid");

        // Lấy dữ liệu
        const departmentCode = code.value.trim();
        const departmentName = name.value.trim();

        // Kiểm tra mã phòng ban
        if (departmentCode === "") {

            code.classList.add("is-invalid");
            hasError = true;

        } else {

            // Chỉ cho phép A-Z và số, từ 2 đến 10 ký tự
            const regex = /^[A-Z0-9]{2,10}$/;

            if (!regex.test(departmentCode)) {

                code.classList.add("is-invalid");
                hasError = true;

            }

        }

        // Kiểm tra tên phòng ban
        if (departmentName === "") {

            name.classList.add("is-invalid");
            hasError = true;

        }

        if (hasError) {

            e.preventDefault();

            alert("Vui lòng kiểm tra lại thông tin trước khi lưu.");

        }

    });

});
</script>

<?php
// Xóa dữ liệu cũ sau khi render xong
unset($_SESSION['old_input']);
?>

<?php include '../../includes/footer.php'; ?>