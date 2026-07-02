<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/leave_model.php';

try {
    $pdo = Database::getConnection();
    $model = new LeaveModel($pdo);

    // Xử lý cả dạng raw JSON payload hoặc Form Data
    $input = json_decode(file_get_contents('php://input'), true);
    
    $action = $_GET['action'] ?? $_POST['action'] ?? ($input['action'] ?? '');
    
    // Giả lập thông tin session (Sẽ được thay bằng $_SESSION trong thực tế)
    $employee_id = 1; // ID người đang login (nhân viên xin nghỉ)
    $manager_id = 2;  // ID sếp (người duyệt đơn)

    if ($action === 'create') {
        $leave_type = $_POST['leave_type'] ?? ($input['leave_type'] ?? '');
        $start_date = $_POST['start_date'] ?? ($input['start_date'] ?? '');
        $end_date   = $_POST['end_date'] ?? ($input['end_date'] ?? '');
        $reason     = $_POST['reason'] ?? ($input['reason'] ?? '');

        if (!$leave_type || !$start_date || !$end_date || !$reason) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $result = $model->createLeave($employee_id, $leave_type, $start_date, $end_date, $reason);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
    elseif ($action === 'list') {
        $filter = $_GET['filter'] ?? ($input['filter'] ?? 'all'); // 'mine' hoặc 'all'
        
        // Nếu nhân viên xem -> truyền employee_id. Nếu sếp xem -> null
        $search_id = ($filter === 'mine') ? $employee_id : null; 
        
        $result = $model->getLeaves($search_id);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
    elseif ($action === 'approve') {
        $leave_id = $_POST['leave_id'] ?? ($input['leave_id'] ?? 0);
        if (!$leave_id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu ID đơn nghỉ phép'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $result = $model->updateStatus($leave_id, 'approved', $manager_id);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
    elseif ($action === 'reject') {
        $leave_id = $_POST['leave_id'] ?? ($input['leave_id'] ?? 0);
        if (!$leave_id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu ID đơn nghỉ phép'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $result = $model->updateStatus($leave_id, 'rejected', $manager_id);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }
    else {
        echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ'], JSON_UNESCAPED_UNICODE);
        exit;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi máy chủ: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit;
}
