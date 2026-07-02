<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/attendance_model.php';

try {
    $pdo = Database::getConnection();
    $model = new AttendanceModel($pdo);

    // Get input (from fetch API JSON or form data)
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $action = $_GET['action'] ?? $_POST['action'] ?? ($input['action'] ?? '');
    
    // Hardcode employee_id for now as requested
    $employee_id = 1;

    if ($action === 'checkIn') {
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        $result = $model->checkIn($employee_id, $current_date, $current_time);
        echo json_encode($result);
        exit;
    } 
    elseif ($action === 'checkOut') {
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        $result = $model->checkOut($employee_id, $current_date, $current_time);
        echo json_encode($result);
        exit;
    } 
    elseif ($action === 'list') {
        // Read input from POST JSON if available, otherwise fallback to GET/POST
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $filter = $_GET['filter'] ?? $_POST['filter'] ?? ($input['filter'] ?? 'all');
        
        $search_id = ($filter === 'mine') ? $employee_id : null;
        $result = $model->getAttendance($search_id);
        echo json_encode($result);
        exit;
    }
    else {
        echo json_encode(['success' => false, 'message' => 'Invalid action specified']);
        exit;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}
