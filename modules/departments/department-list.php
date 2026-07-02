<?php

require_once '../../config/database.php';

$sql = "
SELECT *
FROM departments
";

$result = $conn->query($sql);

?>