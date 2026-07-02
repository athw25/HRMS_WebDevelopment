<?php
/**
 * Department Controller
 * Điều hướng và xử lý nghiệp vụ Phòng ban
 */

require_once __DIR__ . '/department_model.php';

class DepartmentController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new DepartmentModel($db);
    }

    /**
     * Hiển thị danh sách phòng ban
     */
    public function index()
    {
        $search = isset($_GET['search'])
            ? trim(htmlspecialchars($_GET['search']))
            : '';

        $page = isset($_GET['page'])
            ? (int)$_GET['page']
            : 1;

        if ($page < 1) {
            $page = 1;
        }

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $departments = $this->model->getDepartments(
            $search,
            $offset,
            $limit
        );

        $totalRecords = $this->model->getTotalDepartmentsCount($search);

        $totalPages = ceil($totalRecords / $limit);

        return [
            'departments'  => $departments,
            'search'       => $search,
            'page'         => $page,
            'limit'        => $limit,
            'offset'       => $offset,
            'totalPages'   => $totalPages,
            'totalRecords' => $totalRecords
        ];
    }

    /**
     * Lấy thông tin phòng ban theo ID
     */
    public function show($id)
    {
        return $this->model->getDepartmentById($id);
    }

    /**
     * Thêm phòng ban
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        $department_code = trim($_POST['department_code']);
        $department_name = trim($_POST['department_name']);
        $description = trim($_POST['description']);

        if (
            empty($department_code) ||
            empty($department_name)
        ) {
            return [
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin.'
            ];
        }

        if ($this->model->departmentCodeExists($department_code)) {
            return [
                'success' => false,
                'message' => 'Mã phòng ban đã tồn tại.'
            ];
        }

        $result = $this->model->insertDepartment(
            $department_code,
            $department_name,
            $description
        );

        if ($result) {
            return [
                'success' => true,
                'message' => 'Thêm phòng ban thành công.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Không thể thêm phòng ban.'
        ];
    }
        /**
     * Cập nhật phòng ban
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        $department_code = trim($_POST['department_code']);
        $department_name = trim($_POST['department_name']);
        $description = trim($_POST['description']);

        if (
            empty($department_code) ||
            empty($department_name)
        ) {
            return [
                'success' => false,
                'message' => 'Vui lòng nhập đầy đủ thông tin.'
            ];
        }

        $result = $this->model->updateDepartment(
            $id,
            $department_code,
            $department_name,
            $description
        );

        if ($result) {
            return [
                'success' => true,
                'message' => 'Cập nhật thành công.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Cập nhật thất bại.'
        ];
    }

    /**
     * Xóa phòng ban
     */
    public function delete($id)
    {
        $result = $this->model->deleteDepartment($id);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Xóa phòng ban thành công.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Không thể xóa phòng ban.'
        ];
    }

    /**
     * Xử lý thêm phòng ban
     */
    public function handleCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return true;
        }

        $code = isset($_POST['department_code'])
            ? strtoupper(trim($_POST['department_code']))
            : '';

        $name = isset($_POST['department_name'])
            ? trim($_POST['department_name'])
            : '';

        $description = isset($_POST['description'])
            ? trim($_POST['description'])
            : '';

        $_SESSION['old_input'] = [
            'department_code' => $code,
            'department_name' => $name,
            'description' => $description
        ];

        if ($code == '') {
            $_SESSION['error'] = "Mã phòng ban không được để trống.";
            return false;
        }

        if (!preg_match('/^[A-Z0-9]{2,10}$/', $code)) {
            $_SESSION['error'] = "Mã phòng ban chỉ được gồm chữ in hoa và số.";
            return false;
        }

        if ($name == '') {
            $_SESSION['error'] = "Tên phòng ban không được để trống.";
            return false;
        }

        if (
            mb_strlen($name, 'UTF-8') < 3 ||
            mb_strlen($name, 'UTF-8') > 100
        ) {
            $_SESSION['error'] = "Tên phòng ban phải từ 3 đến 100 ký tự.";
            return false;
        }

        if ($this->model->departmentCodeExists($code)) {
            $_SESSION['error'] = "Mã phòng ban đã tồn tại.";
            return false;
        }

        $saved = $this->model->insertDepartment(
            $code,
            $name,
            $description
        );

        if ($saved) {

            unset($_SESSION['old_input']);

            $_SESSION['success'] =
                "Thêm phòng ban thành công.";

            header("Location: department-list.php");
            exit;
        }

        $_SESSION['error'] =
            "Không thể thêm phòng ban.";

        return false;
    }
        /**
     * Xử lý chỉnh sửa phòng ban
     */
    public function handleEdit()
    {
        // Kiểm tra ID
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

            $_SESSION['error'] = "ID phòng ban không hợp lệ.";

            header("Location: department-list.php");
            exit;
        }

        $id = (int) $_GET['id'];

        // Lấy thông tin phòng ban
        $department = $this->model->getDepartmentById($id);

        if (!$department) {

            $_SESSION['error'] = "Không tìm thấy phòng ban.";

            header("Location: department-list.php");
            exit;
        }

        // Khi người dùng bấm Cập nhật
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $department_name = isset($_POST['department_name'])
                ? trim($_POST['department_name'])
                : '';

            $description = isset($_POST['description'])
                ? trim($_POST['description'])
                : '';

            // Validate tên
            if ($department_name == '') {

                $_SESSION['error'] =
                    "Tên phòng ban không được để trống.";

                return $department;
            }

            if (
                mb_strlen($department_name, 'UTF-8') < 3 ||
                mb_strlen($department_name, 'UTF-8') > 100
            ) {

                $_SESSION['error'] =
                    "Tên phòng ban phải từ 3 đến 100 ký tự.";

                return $department;
            }

            // Cập nhật
            $success = $this->model->updateDepartment(
                $id,
                $department['department_code'],
                $department_name,
                $description
            );

            if ($success) {

                $_SESSION['success'] =
                    "Cập nhật phòng ban thành công.";

                header("Location: department-list.php");
                exit;
            }

            $_SESSION['error'] =
                "Không thể cập nhật phòng ban.";

            // Giữ dữ liệu vừa nhập
            $department['department_name'] = $department_name;
            $department['description'] = $description;
        }

        return $department;
    }

}
?>