<?php
/**
 * ==========================================================
 * Department List View
 * Module: Department Management
 * Project: HRMS
 * Author: Nguyễn Quang Minh
 * ==========================================================
 */

require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../middleware/auth.middleware.php';

require_once 'department_controller.php';

$controller = new DepartmentController($conn);
$data = $controller->index();

$departments = $data['departments'];
$search = $data['search'];
$page = $data['page'];
$limit = $data['limit'];
$offset = $data['offset'];
$totalPages = $data['totalPages'];
$totalRecords = $data['totalRecords'];

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="content-wrapper container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Quản Lý Phòng Ban
            </h3>

            <nav>

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">
                        <a href="../../index.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Phòng Ban
                    </li>

                </ol>

            </nav>

        </div>

        <div>

            <a href="department-create.php"
               class="btn btn-primary shadow">

                <i class="bi bi-plus-circle"></i>

                Thêm Phòng Ban

            </a>

        </div>

    </div>

    <?php if(isset($_SESSION['success'])): ?>

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill"></i>

            <?= $_SESSION['success']; ?>

            <?php unset($_SESSION['success']); ?>

            <button
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>


    <?php if(isset($_SESSION['error'])): ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle-fill"></i>

            <?= $_SESSION['error']; ?>

            <?php unset($_SESSION['error']); ?>

            <button
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    <?php endif; ?>


    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form
                action="department-list.php"
                method="GET"
                class="row g-3">

                <div class="col-md-5">

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="bi bi-search"></i>

                        </span>

                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            placeholder="Nhập mã hoặc tên phòng ban..."
                            value="<?= htmlspecialchars($search); ?>">

                    </div>

                </div>

                <div class="col-md-2">

                    <button
                        type="submit"
                        class="btn btn-primary w-100">

                        Tìm kiếm

                    </button>

                </div>

                <div class="col-md-2">

                    <a
                        href="department-list.php"
                        class="btn btn-secondary w-100">

                        Làm mới

                    </a>

                </div>

            </form>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="80">
                                STT
                            </th>

                            <th width="180">
                                Mã PB
                            </th>

                            <th width="250">
                                Tên phòng ban
                            </th>

                            <th>
                                Mô tả
                            </th>

                            <th width="150" class="text-center">
                                Thao tác
                            </th>

                        </tr>

                    </thead>

                    <tbody>

<?php
if(empty($departments)){
?>

<tr>

    <td colspan="5"
        class="text-center text-muted py-5">

        <i class="bi bi-folder-x display-6"></i>

        <br><br>

        Không có dữ liệu.

    </td>

</tr>

<?php
}else{

$stt = $offset + 1;

foreach($departments as $department){
    

?>
<tr>

    <td>
        <?= $stt++; ?>
    </td>

    <td>

        <span class="badge bg-primary-subtle text-primary">

            <?= htmlspecialchars($department['department_code']); ?>

        </span>

    </td>

    <td class="fw-semibold">

        <?= htmlspecialchars($department['department_name']); ?>

    </td>

    <td>

        <?php
            if(!empty($department['description'])){
                echo htmlspecialchars($department['description']);
            }else{
                echo '<span class="text-muted">---</span>';
            }
        ?>

    </td>

    <td class="text-center">

        <a
            href="department-edit.php?id=<?= $department['id']; ?>"
            class="btn btn-sm btn-warning"
            title="Chỉnh sửa">

            <i class="bi bi-pencil-square"></i>

        </a>

        <button
            type="button"
            class="btn btn-sm btn-danger btn-delete"
            data-id="<?= $department['id']; ?>"
            data-name="<?= htmlspecialchars($department['department_name']); ?>"
            title="Xóa">

            <i class="bi bi-trash"></i>

        </button>

    </td>

</tr>

<?php

    }

}

?>

                    </tbody>

                </table>

            </div>

        </div>

        <?php if($totalPages > 1): ?>

        <div class="card-footer bg-white">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <small class="text-muted">

                        Hiển thị

                        <strong>

                            <?= $offset + 1; ?>

                        </strong>

                        -

                        <strong>

                            <?= min($offset + $limit, $totalRecords); ?>

                        </strong>

                        /

                        <strong>

                            <?= $totalRecords; ?>

                        </strong>

                        phòng ban

                    </small>

                </div>

                <div class="col-md-6">

                    <nav>

                        <ul class="pagination pagination-sm justify-content-end mb-0">

                            <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">

                                <a
                                    class="page-link"
                                    href="?page=<?= $page-1; ?>&search=<?= urlencode($search); ?>">

                                    &laquo;

                                </a>

                            </li>

                            <?php
                            for($i = 1; $i <= $totalPages; $i++):
                            ?>

                            <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">

                                <a
                                    class="page-link"
                                    href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>">

                                    <?= $i; ?>

                                </a>

                            </li>

                            <?php endfor; ?>

                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : ''; ?>">

                                <a
                                    class="page-link"
                                    href="?page=<?= $page+1; ?>&search=<?= urlencode($search); ?>">

                                    &raquo;

                                </a>

                            </li>

                        </ul>

                    </nav>

                </div>

            </div>

        </div>

        <?php endif; ?>

    </div>

</div>
<!-- Delete Modal -->
<div class="modal fade"
     id="deleteModal"
     tabindex="-1"
     aria-labelledby="deleteModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title" id="deleteModalLabel">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    Xác nhận xóa

                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                Bạn có chắc chắn muốn xóa phòng ban

                <strong id="departmentName"></strong>

                ?

                <br><br>

                <span class="text-danger">

                    Hành động này không thể hoàn tác.

                </span>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Hủy

                </button>

                <a
                    href="#"
                    id="confirmDeleteBtn"
                    class="btn btn-danger">

                    Xóa

                </a>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener("DOMContentLoaded", function () {

    const deleteModal = new bootstrap.Modal(
        document.getElementById("deleteModal")
    );

    const departmentName =
        document.getElementById("departmentName");

    const confirmDeleteBtn =
        document.getElementById("confirmDeleteBtn");

    const deleteButtons =
        document.querySelectorAll(".btn-delete");

    deleteButtons.forEach(function(button){

        button.addEventListener("click", function(){

            const id =
                this.dataset.id;

            const name =
                this.dataset.name;

            departmentName.textContent = name;

            confirmDeleteBtn.href =
                "department-delete.php?id=" + id;

            deleteModal.show();

        });

    });

});

</script>

<script src="../../assets/js/department.js"></script>
<?php

include '../../includes/footer.php';

?>