<?php

require_once '../modules/salary/salary_model.php';

$model = new SalaryModel();

$result = $model->getAllSalaries();

while ($row = mysqli_fetch_assoc($result)) {
    echo $row['full_name'] . "<br>";
}