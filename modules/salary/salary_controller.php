<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../middleware/auth.middleware.php';
require_once 'salary_model.php';
$model = new SalaryModel();
$action = $_GET['action'] ?? '';
switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'employee_id'  => $_POST['employee_id'],
                'salary_month' => $_POST['salary_month'],
                'basic_salary' => $_POST['basic_salary'],
                'allowance'    => $_POST['allowance'],
                'bonus'        => $_POST['bonus'],
                'deduction'    => $_POST['deduction']
            ];
            if (
                $data['basic_salary'] < 0 ||
                $data['allowance'] < 0 ||
                $data['bonus'] < 0 ||
                $data['deduction'] < 0
            ) {
                $_SESSION['error'] = "Dữ liệu không hợp lệ.";
                header("Location: salary-create.php");
                exit();
            }
            $model->createSalary($data);
            $_SESSION['success'] = "Thêm lương thành công.";
            header("Location: salary-list.php");
            exit();
        }
        break;
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['salary_id'];
            $data = [
                'employee_id'  => $_POST['employee_id'],
                'salary_month' => $_POST['salary_month'],
                'basic_salary' => $_POST['basic_salary'],
                'allowance'    => $_POST['allowance'],
                'bonus'        => $_POST['bonus'],
                'deduction'    => $_POST['deduction']
            ];
            $model->updateSalary($id, $data);
            $_SESSION['success'] = "Cập nhật thành công.";
            header("Location: salary-list.php");
            exit();
        }
        break;
    case 'delete':
        if (isset($_GET['id'])) {
            $model->deleteSalary($_GET['id']);
            $_SESSION['success'] = "Đã xóa.";
            header("Location: salary-list.php");
            exit();
        }
        break;
}