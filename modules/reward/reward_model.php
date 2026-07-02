<?php

require_once __DIR__ . '/../../config/database.php';

class RewardModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllRewards()
    {
        $sql = "SELECT r.*, e.full_name
                FROM reward_discipline r
                INNER JOIN employees e
                ON r.employee_id = e.employee_id
                ORDER BY r.reward_date DESC";

        return mysqli_query($this->conn, $sql);
    }
    public function getRewardById($id)
    {
        $id = (int)$id;

        $sql = "SELECT r.*, e.full_name
                FROM reward_discipline r
                INNER JOIN employees e
                ON r.employee_id = e.employee_id
                WHERE r.reward_id = $id";

        $result = mysqli_query($this->conn, $sql);

        return mysqli_fetch_assoc($result);
    }
    public function getRewardsByEmployee($employee_id)
    {
        $employee_id = (int)$employee_id;

        $sql = "SELECT *
                FROM reward_discipline
                WHERE employee_id = $employee_id
                ORDER BY reward_date DESC";

        return mysqli_query($this->conn, $sql);
    }
    public function createReward($data)
    {
        $sql = "INSERT INTO reward_discipline
        (
            employee_id,
            type,
            description,
            amount,
            reward_date
        )
        VALUES
        (
            '{$data['employee_id']}',
            '{$data['type']}',
            '{$data['description']}',
            '{$data['amount']}',
            '{$data['reward_date']}'
        )";

        return mysqli_query($this->conn, $sql);
    }
    public function updateReward($id, $data)
    {
        $id = (int)$id;

        $sql = "UPDATE reward_discipline SET

        employee_id='{$data['employee_id']}',
        type='{$data['type']}',
        description='{$data['description']}',
        amount='{$data['amount']}',
        reward_date='{$data['reward_date']}'

        WHERE reward_id=$id";

        return mysqli_query($this->conn, $sql);
    }
    public function deleteReward($id)
    {
        $id = (int)$id;

        $sql = "DELETE FROM reward_discipline
                WHERE reward_id=$id";

        return mysqli_query($this->conn, $sql);
    }
}