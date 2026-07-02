<?php

header("Content-Type: application/json");

require_once "../config/database.php";
require_once "../modules/dashboard/dashboard_controller.php";

$dashboard = new DashboardController($conn);

echo json_encode([
    "success" => true,
    "data" => $dashboard->layThongKeDashboard()
]);