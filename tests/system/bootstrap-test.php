<?php
/**
 * =========================================================
 * FILE: tests/bootstrap-test.php
 * MỤC ĐÍCH:
 * Kiểm tra Bootstrap đã load đầy đủ thành phần chưa.
 * =========================================================
 */

define('HRMS_APP', true);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$results=[];

function result(&$results,$name,$pass,$msg=''){
    $results[]=[
        'name'=>$name,
        'pass'=>$pass,
        'msg'=>$msg
    ];
}

result(
    $results,
    'Constants Loaded',
    defined('APP_NAME')
);

result(
    $results,
    'Database Class',
    class_exists('Database')
);

result(
    $results,
    'Session Function',
    function_exists('startSession')
);

result(
    $results,
    'PDO Connection',
    Database::getConnection() instanceof PDO
);

$resultHelpers=[
    'redirectTo',
    'sanitizeInput',
    'formatDate'
];

foreach($resultHelpers as $helper){

    result(
        $results,
        "Helper: ".$helper,
        function_exists($helper)
    );

}

$total=count($results);

$pass=count(array_filter($results,fn($r)=>$r['pass']));
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Bootstrap Test</title>

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

td,th{
border:1px solid #ddd;
padding:10px;
}

.pass{
color:green;
font-weight:bold;
}

.fail{
color:red;
font-weight:bold;
}

</style>

</head>

<body>

<h2>Bootstrap Test</h2>

<table>

<tr>

<th>Test</th>

<th>Status</th>

<th>Detail</th>

</tr>

<?php foreach($results as $r):?>

<tr>

<td><?=$r['name']?></td>

<td class="<?=$r['pass']?'pass':'fail'?>">

<?=$r['pass']?'PASS':'FAIL'?>

</td>

<td><?=$r['msg']?></td>

</tr>

<?php endforeach;?>

</table>

<h3>Kết quả <?=$pass?> / <?=$total?></h3>

</body>

</html>