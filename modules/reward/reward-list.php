<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../middleware/auth.middleware.php';
require_once 'reward_model.php';

$model = new RewardModel();

$rewards = $model->getAllRewards();

include '../../includes/header.php';
include '../../includes/sidebar.php';

?>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">

        <h3>Reward & Discipline Management</h3>

        <a href="reward-create.php"
           class="btn btn-primary">

            + Add New

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

            <th>Type</th>

            <th>Description</th>

            <th>Amount</th>

            <th>Date</th>

            <th width="200">Action</th>

        </tr>

        </thead>

        <tbody>

        <?php while($row=mysqli_fetch_assoc($rewards)) : ?>

            <tr>

                <td><?= $row['reward_id']; ?></td>

                <td><?= $row['full_name']; ?></td>

                <td>

                    <?php if($row['type']=="Reward") : ?>

                        <span class="badge bg-success">

                            Reward

                        </span>

                    <?php else : ?>

                        <span class="badge bg-danger">

                            Discipline

                        </span>

                    <?php endif; ?>

                </td>

                <td>

                    <?= $row['description']; ?>

                </td>

                <td>

                    <?= number_format($row['amount']); ?>

                </td>

                <td>

                    <?= $row['reward_date']; ?>

                </td>

                <td>

                    <a
                    href="reward-edit.php?id=<?= $row['reward_id']; ?>"
                    class="btn btn-warning btn-sm">

                    Edit

                    </a>

                    <a

                    href="reward_controller.php?action=delete&id=<?= $row['reward_id']; ?>"

                    class="btn btn-danger btn-sm"

                    onclick="return confirm('Delete?')">

                    Delete

                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</div>

<?php include '../../includes/footer.php'; ?>