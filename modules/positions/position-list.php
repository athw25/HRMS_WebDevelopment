<?php
/**
 * Position List View
 * Danh sách chức vụ
 */

// 1. Các file cấu hình hệ thống nằm ở ngoài (lùi 2 cấp: positions -> modules -> thư mục gốc)
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../middleware/auth.middleware.php';

// 2. Gọi file Controller nằm CHUNG một thư mục (gọi trực tiếp, không lùi cấp)
require_once __DIR__ . '/position_controller.php';

$controller = new PositionController($conn);

$data = $controller->index();

$positions = $data['positions'];
$search = $data['search'];
$page = $data['page'];
$totalPages = $data['totalPages'];
$totalRecords = $data['totalRecords'];

// 3. Các file giao diện include (lùi 2 cấp ra ngoài hệ thống)
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<div class="content-wrapper container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Quản lý chức vụ</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="../../index.php" class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Chức vụ</li>
                </ol>
            </nav>
        </div>
        <a href="position-create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm chức vụ
        </a>
    </div>

    <?php if (isset($_SESSION['success'])) : ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])) : ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="position-list.php" method="GET">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Tìm theo mã hoặc tên chức vụ..." value="<?= htmlspecialchars($search); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                    </div>
                    <div class="col-md-2">
                        <a href="position-list.php" class="btn btn-secondary w-100">Làm mới</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="80">STT</th>
                            <th>Mã chức vụ</th>
                            <th>Tên chức vụ</th>
                            <th>Lương cơ bản</th>
                            <th width="150" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($positions)) : ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Không có dữ liệu chức vụ.</td>
                            </tr>
                        <?php else : ?>
                            <?php
                            $stt = (($page - 1) * 10) + 1;
                            foreach ($positions as $position) :
                            ?>
                                <tr>
                                    <td><?= $stt++; ?></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= htmlspecialchars($position['position_code']); ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($position['position_name']); ?></td>
                                    <td><?= number_format($position['base_salary_coefficient'], 2); ?></td>
                                    <td class="text-center">
                                        <a href="position-edit.php?id=<?= $position['id']; ?>" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-position" data-id="<?= $position['id']; ?>" data-name="<?= htmlspecialchars($position['position_name']); ?>" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>  
        
        <?php if ($totalPages > 1) : ?>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>Tổng cộng <strong><?= $totalRecords; ?></strong> chức vụ</div>
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= $page - 1; ?>&search=<?= urlencode($search); ?>">&laquo;</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= $page + 1; ?>&search=<?= urlencode($search); ?>">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Xác nhận xóa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa chức vụ <strong id="deletePositionName"></strong>?
                <br><br>
                <span class="text-danger">Hành động này không thể hoàn tác.</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="btnConfirmDeleteUrl" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const deleteButtons = document.querySelectorAll(".btn-delete-position");
    const modalElement = document.getElementById("deleteConfirmModal");

    if (deleteButtons.length > 0 && modalElement) {

        const deleteModal = new bootstrap.Modal(modalElement);

        const deletePositionName =
            document.getElementById("deletePositionName");

        const btnConfirmDeleteUrl =
            document.getElementById("btnConfirmDeleteUrl");

        deleteButtons.forEach(function (button) {

            button.addEventListener("click", function () {

                const id = this.dataset.id;
                const name = this.dataset.name;

                deletePositionName.textContent = name;

                btnConfirmDeleteUrl.href =
                    "position-delete.php?id=" + id;

                deleteModal.show();

            });

        });

    }

});
</script>

<?php
include __DIR__ . '/../../includes/footer.php';
?>