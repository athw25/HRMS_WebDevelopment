<?php

session_start();

require_once '../../middleware/auth.middleware.php';
require_once '../../config/database.php';

$sql = "SELECT employee_id, full_name FROM employees ORDER BY full_name";
$employees = mysqli_query($conn, $sql);

include '../../includes/header.php';
include '../../includes/sidebar.php';

?>

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h4>Add Salary</h4>
        </div>

        <div class="card-body">

            <form action="salary_controller.php?action=create" method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Employee
                    </label>

                    <select
                        name="employee_id"
                        class="form-control"
                        required>

                        <option value="">
                            -- Select Employee --
                        </option>

                        <?php while($row=mysqli_fetch_assoc($employees)) : ?>

                            <option
                                value="<?= $row['employee_id']; ?>">

                                <?= $row['full_name']; ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <div class="row">

                    <div class="col-md-6">

                        <label>Basic Salary</label>

                        <input
                            type="number"
                            id="basic_salary"
                            name="basic_salary"
                            class="form-control"
                            value="0"
                            required>

                    </div>

                    <div class="col-md-6">

                        <label>Allowance</label>

                        <input
                            type="number"
                            id="allowance"
                            name="allowance"
                            class="form-control"
                            value="0">

                    </div>

                </div>

                <br>

                <div class="row">

                    <div class="col-md-6">

                        <label>Bonus</label>

                        <input
                            type="number"
                            id="bonus"
                            name="bonus"
                            class="form-control"
                            value="0">

                    </div>

                    <div class="col-md-6">

                        <label>Deduction</label>

                        <input
                            type="number"
                            id="deduction"
                            name="deduction"
                            class="form-control"
                            value="0">

                    </div>

                </div>

                <br>

                <div class="mb-3">

                    <label>Salary Month</label>

                    <input
                        type="month"
                        name="salary_month"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label>Total Salary</label>

                    <input
                        type="text"
                        id="total_salary"
                        class="form-control bg-light"
                        readonly>

                </div>

                <button
                    class="btn btn-success">

                    Save Salary

                </button>

                <a
                    href="salary-list.php"
                    class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

<script>

function calculateSalary(){

    let basic =
        parseFloat(document.getElementById("basic_salary").value) || 0;

    let allowance =
        parseFloat(document.getElementById("allowance").value) || 0;

    let bonus =
        parseFloat(document.getElementById("bonus").value) || 0;

    let deduction =
        parseFloat(document.getElementById("deduction").value) || 0;

    let total =
        basic + allowance + bonus - deduction;

    document.getElementById("total_salary").value =
        total.toLocaleString();

}

document.getElementById("basic_salary").addEventListener("input",calculateSalary);

document.getElementById("allowance").addEventListener("input",calculateSalary);

document.getElementById("bonus").addEventListener("input",calculateSalary);

document.getElementById("deduction").addEventListener("input",calculateSalary);

calculateSalary();

</script>

<?php include '../../includes/footer.php'; ?>