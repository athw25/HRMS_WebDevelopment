<?php
/**
 * File: tests/test-db.php
 * Mo ta: Kiem tra ket noi Database va cac bang du lieu bat buoc.
 * Cach chay: truy cap truc tiep qua trinh duyet hoac CLI (php test-db.php)
 */

define('HRMS_APP', true);

require_once dirname(__DIR__) . '/config/constants.php';
require_once ROOT_PATH . '/config/database.php';

$testResults = [];

/**
 * Ghi nhan ket qua 1 truong hop kiem tra
 */
function recordTestResult(array &$results, string $testName, bool $passed, string $message = ''): void
{
    $results[] = [
        'name'    => $testName,
        'passed'  => $passed,
        'message' => $message,
    ];
}

// Test 1: Ket noi Database
try {
    $pdo = Database::getConnection();
    recordTestResult($testResults, 'Ket noi Database', true, 'Ket noi thanh cong.');
} catch (Throwable $e) {
    recordTestResult($testResults, 'Ket noi Database', false, $e->getMessage());
    $pdo = null;
}

// Test 2: Kiem tra cac bang bat buoc ton tai
$requiredTables = [
    'users', 'roles', 'employees', 'departments', 'positions',
    'attendance', 'leave_requests', 'salaries', 'reward_discipline',
];

if ($pdo !== null) {
    foreach ($requiredTables as $table) {
        try {
            $stmt = $pdo->query("SHOW TABLES LIKE " . $pdo->quote($table));
            $exists = $stmt->rowCount() > 0;
            recordTestResult(
                $testResults,
                "Bang '{$table}' ton tai",
                $exists,
                $exists ? 'Bang ton tai.' : 'Bang khong ton tai trong database.'
            );
        } catch (Throwable $e) {
            recordTestResult($testResults, "Bang '{$table}' ton tai", false, $e->getMessage());
        }
    }

    // Test 3: Kiem tra bang users co du cot bat buoc cho session
    try {
        $stmt = $pdo->query('DESCRIBE users');
        $columns = array_column($stmt->fetchAll(), 'Field');
        $requiredColumns = ['id', 'username', 'password', 'role', 'status', 'employee_id', 'created_at', 'updated_at'];
        $missingColumns = array_diff($requiredColumns, $columns);

        recordTestResult(
            $testResults,
            'Bang users co du cot bat buoc',
            empty($missingColumns),
            empty($missingColumns) ? 'Du cot.' : 'Thieu cot: ' . implode(', ', $missingColumns)
        );
    } catch (Throwable $e) {
        recordTestResult($testResults, 'Bang users co du cot bat buoc', false, $e->getMessage());
    }
}

$totalTests  = count($testResults);
$passedTests = count(array_filter($testResults, fn ($r) => $r['passed']));

if (php_sapi_name() === 'cli') {
    foreach ($testResults as $result) {
        $status = $result['passed'] ? '[PASS]' : '[FAIL]';
        echo "{$status} {$result['name']} - {$result['message']}" . PHP_EOL;
    }
    echo "Ket qua: {$passedTests}/{$totalTests} kiem tra thanh cong." . PHP_EOL;
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Database Test | HRMS</title>
    <style>
        body { font-family: 'Be Vietnam Pro', Arial, sans-serif; padding: 24px; background: #F8FAFC; color: #0F172A; }
        table { width: 100%; border-collapse: collapse; background: #FFFFFF; }
        th, td { padding: 10px 12px; border-bottom: 1px solid #E2E8F0; text-align: left; }
        .pass { color: #22C55E; font-weight: 600; }
        .fail { color: #EF4444; font-weight: 600; }
        h1 { font-size: 20px; }
        .summary { margin-top: 16px; font-weight: 600; }
    </style>
</head>
<body>
    <h1>Ket qua kiem tra Database - HRMS</h1>
    <table>
        <thead>
            <tr><th>Truong hop</th><th>Ket qua</th><th>Chi tiet</th></tr>
        </thead>
        <tbody>
        <?php foreach ($testResults as $result): ?>
            <tr>
                <td><?= htmlspecialchars($result['name']) ?></td>
                <td class="<?= $result['passed'] ? 'pass' : 'fail' ?>"><?= $result['passed'] ? 'PASS' : 'FAIL' ?></td>
                <td><?= htmlspecialchars($result['message']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p class="summary">Ket qua: <?= $passedTests ?>/<?= $totalTests ?> kiem tra thanh cong.</p>
</body>
</html>
