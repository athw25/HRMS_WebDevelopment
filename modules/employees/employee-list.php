<?php

require_once '../../config/database.php';

$sql = "
SELECT *
FROM employees
";

$result = $conn->query($sql);

?>