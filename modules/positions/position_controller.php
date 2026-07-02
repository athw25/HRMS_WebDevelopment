<?php
/**
 * Position Controller
 * Điều hướng và xử lý nghiệp vụ Chức vụ
 */

require_once __DIR__ . '/position_model.php';
class PositionController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new PositionModel($db);
    }

    /**
     * Hiển thị danh sách chức vụ
     */
    public function index()
    {
        $search = isset($_GET['search'])
            ? trim($_GET['search'])
            : '';

        $page = isset($_GET['page'])
            ? (int)$_GET['page']
            : 1;

        if ($page < 1) {
            $page = 1;
        }

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $positions = $this->model->getPositions(
            $search,
            $offset,
            $limit
        );

        $totalRecords = $this->model->getTotalPositionsCount($search);

        $totalPages = ceil($totalRecords / $limit);

        return [
            'positions'     => $positions,
            'search'        => $search,
            'page'          => $page,
            'limit'         => $limit,
            'offset'        => $offset,
            'totalPages'    => $totalPages,
            'totalRecords'  => $totalRecords
        ];
    }

    /**
     * Lấy thông tin chức vụ
     */
    public function show($id)
    {
        return $this->model->getPositionById($id);
    }

    /**
     * Thêm chức vụ
     */
    public function store()
    {
        $positionCode = strtoupper(trim($_POST['position_code'] ?? ''));
        $positionName = trim($_POST['position_name'] ?? '');
        $baseSalary   = trim($_POST['base_salary'] ?? '');

        if (
            empty($positionCode) ||
            empty($positionName) ||
            empty($baseSalary)
        ) {

            return [
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin.'
            ];
        }

        if ($this->model->positionCodeExists($positionCode)) {

            return [
                'success' => false,
                'message' => 'Mã chức vụ đã tồn tại.'
            ];
        }

        $result = $this->model->insertPosition(
            $positionCode,
            $positionName,
            $baseSalary
        );

        if ($result) {

            return [
                'success' => true,
                'message' => 'Thêm chức vụ thành công.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Không thể thêm chức vụ.'
        ];
    }

    /**
     * Cập nhật chức vụ
     */
    public function update($id)
    {
        $positionName = trim($_POST['position_name'] ?? '');
        $baseSalary   = trim($_POST['base_salary'] ?? '');

        if (
            empty($positionName) ||
            empty($baseSalary)
        ) {

            return [
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin.'
            ];
        }

        $result = $this->model->updatePosition(
            $id,
            $positionName,
            $baseSalary
        );

        if ($result) {

            return [
                'success' => true,
                'message' => 'Cập nhật chức vụ thành công.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Không thể cập nhật chức vụ.'
        ];
    }

    /**
     * Xóa chức vụ
     */
    public function delete($id)
    {
        if ($this->model->hasEmployeesWithPosition($id)) {

            return [
                'success' => false,
                'message' => 'Không thể xóa vì đang có nhân viên sử dụng chức vụ này.'
            ];
        }

        $result = $this->model->deletePosition($id);

        if ($result) {

            return [
                'success' => true,
                'message' => 'Xóa chức vụ thành công.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Không thể xóa chức vụ.'
        ];
    }

    /**
     * Xử lý xóa
     */
    public function handleDelete()
    {
        $id = isset($_GET['id'])
            ? (int)$_GET['id']
            : 0;

        if ($id <= 0) {

            $_SESSION['error'] = 'ID chức vụ không hợp lệ.';

            header('Location: position-list.php');

            exit;
        }

        $position = $this->model->getPositionById($id);

        if (!$position) {

            $_SESSION['error'] = 'Chức vụ không tồn tại.';

            header('Location: position-list.php');

            exit;
        }

        $result = $this->delete($id);

        if ($result['success']) {

            $_SESSION['success'] = $result['message'];

        } else {

            $_SESSION['error'] = $result['message'];
        }

        header('Location: position-list.php');

        exit;
    }
}
?>