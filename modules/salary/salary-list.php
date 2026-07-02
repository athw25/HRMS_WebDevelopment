<?php

session_start();

require_once '../../middleware/auth.middleware.php';
require_once 'salary_model.php';

$model = new SalaryModel();

$salaries = $model->getAllSalaries();

include '../../includes/header.php';
include '../../includes/sidebar.php';

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">

        <h3>Salary Management</h3>

        <a href="salary-create.php" class="btn btn-primary">
            + Add Salary
        </a>

    </div>

    <?php if(isset($_SESSION['success'])) : ?>

        <div class="alert alert-success">

            <?= $_SESSION['success']; ?>

        </div>

    <?php unset($_SESSION['success']); endif; ?>

    <table class="table table-bordered table-hover">

        <thead class="table-dark">

        <tr>

            <th>ID</th>

            <th>Employee</th>

            <th>Month</th>

            <th>Basic Salary</th>

            <th>Allowance</th>

            <th>Total Salary</th>

            <th width="220">Action</th>

        </tr>

        </thead>

        <tbody>

        <?php while($row = mysqli_fetch_assoc($salaries)) : ?>

            <tr>

                <td><?= $row['salary_id']; ?></td>

                <td><?= $row['full_name']; ?></td>

                <td><?= $row['salary_month']; ?></td>

                <td><?= number_format($row['basic_salary']); ?></td>

                <td><?= number_format($row['allowance']); ?></td>

                <td class="fw-bold text-success">

                    <?= number_format($row['total_salary']); ?>

                </td>

                <td>

                    <a class="btn btn-info btn-sm"

                       href="salary-detail.php?id=<?= $row['salary_id']; ?>">

                        Detail

                    </a>

                    <a class="btn btn-warning btn-sm"

                       href="salary-edit.php?id=<?= $row['salary_id']; ?>">

                        Edit

                    </a>

                    <a class="btn btn-danger btn-sm"

                       onclick="return confirm('Delete this salary?')"

                       href="salary_controller.php?action=delete&id=<?= $row['salary_id']; ?>">

                        Delete

                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</div>

<?php include '../../includes/footer.php'; ?>