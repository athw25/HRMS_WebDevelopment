<?php
/**
 * Position Model
 * Xử lý tương tác Database cho Chức vụ
 */

class PositionModel
{
    private $db;

    public function __construct($databaseConnection)
    {
        $this->db = $databaseConnection;
    }

    /**
     * Lấy danh sách chức vụ
     */
    public function getPositions($search = '', $offset = 0, $limit = 10)
    {
        $searchParam = "%{$search}%";

        $sql = "SELECT
                    id,
                    position_code,
                    position_name,
                    base_salary_coefficient,
                    created_at
                FROM positions
                WHERE
                    position_code LIKE ?
                    OR position_name LIKE ?
                ORDER BY id DESC
                LIMIT ?, ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log($this->db->error);
            return [];
        }

        $stmt->bind_param(
            "ssii",
            $searchParam,
            $searchParam,
            $offset,
            $limit
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $positions = [];

        while ($row = $result->fetch_assoc()) {
            $positions[] = $row;
        }

        $stmt->close();

        return $positions;
    }

    /**
     * Đếm tổng số chức vụ
     */
    public function getTotalPositionsCount($search = '')
    {
        $searchParam = "%{$search}%";

        $sql = "SELECT
                    COUNT(*) AS total
                FROM positions
                WHERE
                    position_code LIKE ?
                    OR position_name LIKE ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log($this->db->error);
            return 0;
        }

        $stmt->bind_param(
            "ss",
            $searchParam,
            $searchParam
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        $stmt->close();

        return (int) $row['total'];
    }

    /**
     * Lấy thông tin chức vụ theo ID
     */
    public function getPositionById($id)
    {
        $sql = "SELECT *
                FROM positions
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log($this->db->error);
            return null;
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        $position = $result->fetch_assoc();

        $stmt->close();

        return $position;
    }

    /**
     * Kiểm tra mã chức vụ đã tồn tại
     */
    public function positionCodeExists($positionCode)
    {
        $sql = "SELECT id
                FROM positions
                WHERE position_code = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log($this->db->error);
            return false;
        }

        $stmt->bind_param(
            "s",
            $positionCode
        );

        $stmt->execute();

        $stmt->store_result();

        $exists = $stmt->num_rows > 0;

        $stmt->close();

        return $exists;
    }

    /**
     * Thêm chức vụ
     */
    public function insertPosition(
        $positionCode,
        $positionName,
        $baseSalary
    ) {

        $sql = "INSERT INTO positions
                (
                    position_code,
                    position_name,
                    base_salary_coefficient,
                    created_at
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    NOW()
                )";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log($this->db->error);
            return false;
        }

        $stmt->bind_param(
            "ssd",
            $positionCode,
            $positionName,
            $baseSalary
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    /**
     * Cập nhật chức vụ
     */
    public function updatePosition(
        $id,
        $positionName,
        $baseSalary
    ) {

        $sql = "UPDATE positions
                SET
                    position_name = ?,
                    base_salary_coefficient = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log($this->db->error);
            return false;
        }

        $stmt->bind_param(
            "sdi",
            $positionName,
            $baseSalary,
            $id
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }

    /**
     * Kiểm tra chức vụ đã có nhân viên sử dụng chưa
     */
    public function hasEmployeesWithPosition($positionId)
{
    // Database hiện chưa có bảng employees
    return false;
}

    /**
     * Xóa chức vụ
     */
    public function deletePosition($id)
    {
        $sql = "DELETE
                FROM positions
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            error_log($this->db->error);
            return false;
        }

        $stmt->bind_param(
            "i",
            $id
        );

        $success = $stmt->execute();

        $stmt->close();

        return $success;
    }
}
?>