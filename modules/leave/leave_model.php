<?php
class LeaveModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Create a new leave request
     */
    public function createLeave($employee_id, $leave_type, $start_date, $end_date, $reason) {
        try {
            // Calculate days (simple inclusive difference)
            $start = new DateTime($start_date);
            $end = new DateTime($end_date);
            if ($end < $start) {
                return ['success' => false, 'message' => 'Ngày kết thúc không hợp lệ'];
            }
            
            $diff = $end->diff($start);
            $days = $diff->days + 1; // +1 to include both start and end days

            $query = "INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, days, reason, status) 
                      VALUES (?, ?, ?, ?, ?, ?, 'pending')";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$employee_id, $leave_type, $start_date, $end_date, $days, $reason]);

            return ['success' => true, 'message' => 'Gửi đơn xin nghỉ phép thành công'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi Database: ' . $e->getMessage()];
        }
    }

    /**
     * Get leave requests (all or by employee)
     */
    public function getLeaves($employee_id = null) {
        try {
            if ($employee_id) {
                $query = "SELECT * FROM leave_requests WHERE employee_id = ? ORDER BY created_at DESC";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$employee_id]);
            } else {
                // For managers: get all leaves
                $query = "SELECT * FROM leave_requests ORDER BY created_at DESC";
                $stmt = $this->pdo->query($query);
            }
            return ['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi Database: ' . $e->getMessage()];
        }
    }

    /**
     * Update leave status (Approve / Reject)
     */
    public function updateStatus($leave_id, $status, $approved_by) {
        if (!in_array($status, ['approved', 'rejected'])) {
            return ['success' => false, 'message' => 'Trạng thái không hợp lệ'];
        }

        try {
            $query = "UPDATE leave_requests SET status = ?, approved_by = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$status, $approved_by, $leave_id]);
            
            if ($stmt->rowCount() === 0) {
                return ['success' => false, 'message' => 'Không tìm thấy đơn xin nghỉ hoặc trạng thái không đổi'];
            }

            return ['success' => true, 'message' => 'Đã cập nhật trạng thái đơn nghỉ phép'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi Database: ' . $e->getMessage()];
        }
    }
}
