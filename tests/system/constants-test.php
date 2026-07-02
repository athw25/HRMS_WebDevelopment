<?php
/**
 * =========================================================
 * FILE: tests/constants-test.php
 * MỤC ĐÍCH:
 * Kiểm tra toàn bộ Constants của hệ thống.
 * =========================================================
 */

define('HRMS_APP', true);

require_once dirname(__DIR__) . '/config/constants.php';

$tests = [];

function test(&$tests, string $name, bool $passed, string $value = ''): void
{
    $tests[] = [
        'name' => $name,
        'passed' => $passed,
        'value' => $value
    ];
}

$constants = [
    'ROOT_PATH',
    'CONFIG_PATH',
    'INCLUDES_PATH',
    'HELPERS_PATH',
    'MIDDLEWARE_PATH',
    'MODULES_PATH',
    'UPLOADS_PATH',
    'BASE_URL',
    'APP_NAME',
    'APP_VERSION',
    'ROLE_ADMIN',
    'ROLE_MANAGER',
    'ROLE_EMPLOYEE',
    'SESSION_NAME',
    'SESSION_LIFETIME'
];

foreach ($constants as $constant) {
    test(
        $tests,
        $constant,
        defined($constant),
        defined($constant) ? constant($constant) : 'Undefined'
    );
}

$total = count($tests);
$pass = count(array_filter($tests, fn($t) => $t['passed']));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Constants Test</title>

<style>
body{
    font-family:Arial;
    padding:30px;
    background:#F8FAFC;
}
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    border:1px solid #ddd;
    padding:10px;
}
.pass{color:green;font-weight:bold;}
.fail{color:red;font-weight:bold;}
</style>

</head>
<body>

<h2>Constants Test</h2>

<table>

<tr>
<th>Constant</th>
<th>Status</th>
<th>Value</th>
</tr>

<?php foreach($tests as $t): ?>

<tr>

<td><?=htmlspecialchars($t['name'])?></td>

<td class="<?= $t['passed']?'pass':'fail'?>">
<?= $t['passed']?'PASS':'FAIL'?>
</td>

<td><?=htmlspecialchars((string)$t['value'])?></td>

</tr>

<?php endforeach;?>

</table>

<h3>Kết quả: <?=$pass?> / <?=$total?></h3>

</body>
</html>