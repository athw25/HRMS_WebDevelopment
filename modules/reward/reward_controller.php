<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../middleware/auth.middleware.php';
require_once 'reward_model.php';

$model = new RewardModel();

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'create':

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [

                'employee_id' => $_POST['employee_id'],
                'type' => $_POST['type'],
                'description' => trim($_POST['description']),
                'amount' => $_POST['amount'],
                'reward_date' => $_POST['reward_date']

            ];

            if (
                empty($data['employee_id']) ||
                empty($data['type']) ||
                empty($data['reward_date']) ||
                $data['amount'] < 0
            ) {

                $_SESSION['error'] = "Invalid data.";

                header("Location: reward-create.php");
                exit();
            }

            $model->createReward($data);

            $_SESSION['success'] = "Added successfully.";

            header("Location: reward-list.php");
            exit();
        }

        break;

    case 'update':

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $_POST['reward_id'];

            $data = [

                'employee_id' => $_POST['employee_id'],
                'type' => $_POST['type'],
                'description' => trim($_POST['description']),
                'amount' => $_POST['amount'],
                'reward_date' => $_POST['reward_date']

            ];

            $model->updateReward($id, $data);

            $_SESSION['success'] = "Updated successfully.";

            header("Location: reward-list.php");
            exit();
        }

        break;

    case 'delete':

        if (isset($_GET['id'])) {

            $model->deleteReward($_GET['id']);

            $_SESSION['success'] = "Deleted successfully.";

            header("Location: reward-list.php");
            exit();
        }

        break;
}