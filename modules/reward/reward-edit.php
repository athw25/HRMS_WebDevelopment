<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../middleware/auth.middleware.php';
require_once 'reward_model.php';
require_once '../../config/database.php';

$model = new RewardModel();

if (!isset($_GET['id'])) {
    header("Location: reward-list.php");
    exit();
}

$reward = $model->getRewardById($_GET['id']);

$sql = "SELECT employee_id, full_name
        FROM employees
        ORDER BY full_name";

$employees = mysqli_query($conn, $sql);

include '../../includes/header.php';
include '../../includes/sidebar.php';

?>

<div class="container mt-4">

    <div class="card shadow">

        <div class="card-header bg-warning">

            <h4>Edit Reward / Discipline</h4>

        </div>

        <div class="card-body">

            <form action="reward_controller.php?action=update" method="POST">

                <input
                    type="hidden"
                    name="reward_id"
                    value="<?= $reward['reward_id']; ?>">

                <div class="mb-3">

                    <label>Employee</label>

                    <select
                        name="employee_id"
                        class="form-control"
                        required>

                        <?php while($row = mysqli_fetch_assoc($employees)) : ?>

                            <option
                                value="<?= $row['employee_id']; ?>"
                                <?= ($reward['employee_id'] == $row['employee_id']) ? 'selected' : ''; ?>>

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

                        <option
                            value="Reward"
                            <?= ($reward['type']=="Reward") ? 'selected' : ''; ?>>

                            Reward

                        </option>

                        <option
                            value="Discipline"
                            <?= ($reward['type']=="Discipline") ? 'selected' : ''; ?>>

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
                        required><?= htmlspecialchars($reward['description']); ?></textarea>

                </div>

                <div class="mb-3">

                    <label>Amount</label>

                    <input
                        type="number"
                        name="amount"
                        class="form-control"
                        min="0"
                        value="<?= $reward['amount']; ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label>Date</label>

                    <input
                        type="date"
                        name="reward_date"
                        class="form-control"
                        value="<?= $reward['reward_date']; ?>"
                        required>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary">

                    Update

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