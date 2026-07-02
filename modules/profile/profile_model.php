<?php
/**
 * Profile Model
 */

class ProfileModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Lấy thông tin người dùng
     */
    public function getUserProfile($id)
    {
        $sql = "SELECT
                    id,
                    username,
                    fullname,
                    email,
                    phone,
                    role,
                    created_at
                FROM users
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        $user = $result->fetch_assoc();

        $stmt->close();

        return $user;
    }

    /**
     * Cập nhật thông tin
     */
    public function updateProfile($id, $fullname, $email, $phone)
    {
        $sql = "UPDATE users
                SET
                    fullname=?,
                    email=?,
                    phone=?
                WHERE id=?";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param(
            "sssi",
            $fullname,
            $email,
            $phone,
            $id
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword($id, $password)
    {
        $sql = "UPDATE users
                SET password=?
                WHERE id=?";

        $stmt = $this->db->prepare($sql);

        $stmt->bind_param(
            "si",
            $password,
            $id
        );

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }
}