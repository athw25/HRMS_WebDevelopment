<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "../modules/reports/report_controller.php";

$report = new ReportController();

echo json_encode([
    "success" => true,
    "data" => $report->layBaoCao()
]);