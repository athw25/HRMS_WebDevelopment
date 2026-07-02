<?php
/**
 * =========================================================
 * FILE: tests/session-test.php
 * MỤC ĐÍCH:
 * Kiểm tra Session hoạt động.
 * =========================================================
 */

define('HRMS_APP', true);

require_once dirname(__DIR__) . '/config/constants.php';
require_once ROOT_PATH . '/config/session.php';

startSession();

$results=[];

function addResult(&$results,$name,$pass,$msg){
    $results[]=[
        'name'=>$name,
        'pass'=>$pass,
        'msg'=>$msg
    ];
}

addResult(
    $results,
    'Session Started',
    session_status()==PHP_SESSION_ACTIVE,
    session_id()
);

$_SESSION['test']='HRMS';

addResult(
    $results,
    'Write Session',
    isset($_SESSION['test']),
    $_SESSION['test']
);

touchSessionActivity();

addResult(
    $results,
    'Last Activity',
    isset($_SESSION['last_activity']),
    $_SESSION['last_activity']
);

$total=count($results);
$pass=count(array_filter($results,fn($r)=>$r['pass']));
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Session Test</title>

<style>

body{font-family:Arial;padding:30px;background:#F8FAFC;}

table{width:100%;border-collapse:collapse;}

td,th{padding:10px;border:1px solid #ddd;}

.pass{color:green;font-weight:bold;}

.fail{color:red;font-weight:bold;}

</style>

</head>

<body>

<h2>Session Test</h2>

<table>

<tr>

<th>Test</th>

<th>Status</th>

<th>Message</th>

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