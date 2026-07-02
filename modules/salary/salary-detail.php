<?php

session_start();

require_once '../../middleware/auth.middleware.php';
require_once 'salary_model.php';

$model = new SalaryModel();

if (!isset($_GET['id'])) {
    header("Location: salary-list.php");
    exit();
}

$salary = $model->getSalaryById($_GET['id']);

include '../../includes/header.php';
include '../../includes/sidebar.php';

?>

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-info text-white">
            <h4>Salary Detail</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="250">Employee</th>
                    <td><?= $salary['full_name']; ?></td>
                </tr>

                <tr>
                    <th>Salary Month</th>
                    <td><?= date('m/Y', strtotime($salary['salary_month'])); ?></td>
                </tr>

                <tr>
                    <th>Basic Salary</th>
                    <td><?= number_format($salary['basic_salary']); ?> VNĐ</td>
                </tr>

                <tr>
                    <th>Allowance</th>
                    <td><?= number_format($salary['allowance']); ?> VNĐ</td>
                </tr>

                <tr>
                    <th>Bonus</th>
                    <td><?= number_format($salary['bonus']); ?> VNĐ</td>
                </tr>

                <tr>
                    <th>Deduction</th>
                    <td><?= number_format($salary['deduction']); ?> VNĐ</td>
                </tr>

                <tr class="table-success">

                    <th>Total Salary</th>

                    <td class="fw-bold text-success">

                        <?= number_format($salary['total_salary']); ?> VNĐ

                    </td>

                </tr>

            </table>

            <a
                href="salary-edit.php?id=<?= $salary['salary_id']; ?>"
                class="btn btn-warning">

                Edit

            </a>

            <a
                href="salary_controller.php?action=delete&id=<?= $salary['salary_id']; ?>"
                class="btn btn-danger"
                onclick="return confirm('Delete this salary?')">

                Delete

            </a>

            <a
                href="salary-list.php"
                class="btn btn-secondary">

                Back

            </a>

        </div>

    </div>

</div>

<?php include '../../includes/footer.php'; ?>