<?php

class AttendanceModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function checkIn($employee_id, $date, $time) {
        try {
            // Check if already checked in today
            $checkQuery = "SELECT id FROM attendance WHERE employee_id = ? AND date = ?";
            $checkStmt = $this->pdo->prepare($checkQuery);
            $checkStmt->execute([$employee_id, $date]);

            if ($checkStmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Already checked in today'];
            }

            // Determine status based on time
            $status = $time <= '09:00:00' ? 'present' : 'late';

            // Insert check-in record
            $insertQuery = "INSERT INTO attendance (employee_id, date, check_in, status) VALUES (?, ?, ?, ?)";
            $insertStmt = $this->pdo->prepare($insertQuery);
            $insertStmt->execute([$employee_id, $date, $time, $status]);

            return ['success' => true, 'message' => 'Check-in successful', 'status' => $status];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function checkOut($employee_id, $date, $time) {
        try {
            // Query for existing record with null check_out
            $selectQuery = "SELECT id, check_in FROM attendance WHERE employee_id = ? AND date = ? AND check_out IS NULL";
            $selectStmt = $this->pdo->prepare($selectQuery);
            $selectStmt->execute([$employee_id, $date]);

            if ($selectStmt->rowCount() === 0) {
                return ['success' => false, 'message' => 'No active check-in record found for today'];
            }

            // Update check_out and calculate working_hours
            $updateQuery = "UPDATE attendance
                           SET check_out = ?,
                               working_hours = TIMEDIFF(?, check_in)
                           WHERE employee_id = ? AND date = ? AND check_out IS NULL";
            $updateStmt = $this->pdo->prepare($updateQuery);
            $updateStmt->execute([$time, $time, $employee_id, $date]);

            return ['success' => true, 'message' => 'Check-out successful'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
    public function getAttendance($employee_id = null) {
        try {
            if ($employee_id) {
                $query = "SELECT * FROM attendance WHERE employee_id = ? ORDER BY date DESC, check_in DESC";
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([$employee_id]);
            } else {
                $query = "SELECT * FROM attendance ORDER BY date DESC, check_in DESC";
                $stmt = $this->pdo->query($query);
            }
            return ['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
