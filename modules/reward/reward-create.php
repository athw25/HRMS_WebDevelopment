<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../middleware/auth.middleware.php';
require_once '../../config/database.php';

$sql = "SELECT employee_id, full_name
        FROM employees
        ORDER BY full_name";

$employees = mysqli_query($conn, $sql);

include '../../includes/header.php';
include '../../includes/sidebar.php';

?>

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h4>Add Reward / Discipline</h4>

        </div>

        <div class="card-body">

            <form action="reward_controller.php?action=create" method="POST">

                <div class="mb-3">

                    <label>Employee</label>

                    <select
                        name="employee_id"
                        class="form-control"
                        required>

                        <option value="">
                            -- Select Employee --
                        </option>

                        <?php while($row = mysqli_fetch_assoc($employees)) : ?>

                            <option value="<?= $row['employee_id']; ?>">

                                <?= $row['full_name']; ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <div class="mb-3">

                    <label>Type</label>

                    <select
                        name="type"
                        class="form-control"
                        required>

                        <option value="Reward">

                            Reward

                        </option>

                        <option value="Discipline">

                            Discipline

                        </option>

                    </select>

                </div>

                <div class="mb-3">

                    <label>Description</label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                        required></textarea>

                </div>

                <div class="mb-3">

                    <label>Amount</label>

                    <input
                        type="number"
                        name="amount"
                        class="form-control"
                        min="0"
                        required>

                </div>

                <div class="mb-3">

                    <label>Date</label>

                    <input
                        type="date"
                        name="reward_date"
                        class="form-control"
                        required>

                </div>

                <button
                    type="submit"
                    class="btn btn-success">

                    Save

                </button>

                <a
                    href="reward-list.php"
                    class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

<?php include '../../includes/footer.php'; ?>