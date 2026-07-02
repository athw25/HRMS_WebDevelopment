<?php
/**
 * Profile Controller
 */

require_once __DIR__ . '/profile_model.php';

class ProfileController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new ProfileModel($db);
    }

    /**
     * Hiển thị thông tin Profile
     */
    public function index()
    {
        // Nếu chưa có session thì tạm dùng user id = 1
        // Sau này khi có Login thì thay bằng:
        // $userId = $_SESSION['user_id'];

        $userId = $_SESSION['user_id'] ?? 1;

        $user = $this->model->getUserProfile($userId);

        if (!$user) {
            $_SESSION['error'] = "Không tìm thấy thông tin người dùng.";
            return null;
        }

        return $user;
    }

    /**
     * Cập nhật hồ sơ
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $userId = $_SESSION['user_id'] ?? 1;

        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);

        if (empty($fullname)) {
            $_SESSION['error'] = "Họ tên không được để trống.";
            return;
        }

        $result = $this->model->updateProfile(
            $userId,
            $fullname,
            $email,
            $phone
        );

        if ($result) {
            $_SESSION['success'] = "Cập nhật hồ sơ thành công.";
        } else {
            $_SESSION['error'] = "Cập nhật thất bại.";
        }

        header("Location: profile.php");
        exit;
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        $userId = $_SESSION['user_id'] ?? 1;

        $newPassword = trim($_POST['new_password']);

        if (empty($newPassword)) {
            $_SESSION['error'] = "Mật khẩu mới không được để trống.";
            return;
        }

        $result = $this->model->changePassword(
            $userId,
            $newPassword
        );

        if ($result) {
            $_SESSION['success'] = "Đổi mật khẩu thành công.";
        } else {
            $_SESSION['error'] = "Đổi mật khẩu thất bại.";
        }

        header("Location: profile.php");
        exit;
    }
}