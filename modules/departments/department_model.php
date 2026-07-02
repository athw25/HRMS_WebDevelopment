<?php
/**
 * Department Model
 * Xử lý tương tác Database cho Phòng ban
 * Sử dụng Prepared Statement chống SQL Injection
 */

class DepartmentModel
{
    private $db;

    public function __construct($database_connection)
    {
        $this->db = $database_connection;
    }

    /**
     * Lấy danh sách phòng ban có tìm kiếm và phân trang
     */
    public function getDepartments($search = '', $offset = 0, $limit = 10)
    {
        $offset = (int)$offset;
        $limit = (int)$limit;
        $searchParam = "%{$search}%";

        $sql = "SELECT id,
                       department_code,
                       department_name,
                       description,
                       created_at
                FROM departments
                WHERE department_code LIKE ?
                   OR department_name LIKE ?
                ORDER BY id DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("ssii", $searchParam, $searchParam, $limit, $offset);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        $departments = [];

        while ($department = $result->fetch_assoc()) {
            $departments[] = $department;
        }

        $stmt->close();

        return $departments;
    }

    /**
     * Đếm tổng số phòng ban
     */
    public function getTotalDepartmentsCount($search = '')
    {
        $searchParam = "%{$search}%";

        $sql = "SELECT COUNT(*) AS total
                FROM departments
                WHERE department_code LIKE ?
                   OR department_name LIKE ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("ss", $searchParam, $searchParam);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();

        return (int)$row['total'];
    }

    /**
     * Lấy thông tin phòng ban theo ID
     */
    public function getDepartmentById($id)
    {
        $sql = "SELECT *
                FROM departments
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $department = $result->fetch_assoc();

        $stmt->close();

        return $department;
    }

    /**
     * Kiểm tra mã phòng ban đã tồn tại
     */
    public function departmentCodeExists($department_code)
    {
        $sql = "SELECT id
                FROM departments
                WHERE department_code = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("s", $department_code);

        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        $exists = $result->num_rows > 0;

        $stmt->close();

        return $exists;
    }

    /**
     * Thêm phòng ban
     */
    public function insertDepartment($department_code, $department_name, $description)
    {
        $sql = "INSERT INTO departments
                (department_code, department_name, description)
                VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param(
            "sss",
            $department_code,
            $department_name,
            $description
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    /**
     * Cập nhật phòng ban
     */
    public function updateDepartment($id, $department_code, $department_name, $description)
    {
        $sql = "UPDATE departments
                SET department_code = ?,
                    department_name = ?,
                    description = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param(
            "sssi",
            $department_code,
            $department_name,
            $description,
            $id
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    /**
     * Xóa phòng ban
     */
    public function deleteDepartment($id)
    {    
        $sql = "DELETE FROM departments
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("i", $id);

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }
}
?>