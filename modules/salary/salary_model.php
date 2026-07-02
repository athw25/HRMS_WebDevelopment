<?php
require_once __DIR__ . '/../../config/database.php';
class SalaryModel
{
    private $conn;
    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }
    public function getAllSalaries()
    {
        $sql = "SELECT s.*, e.full_name
                FROM salaries s
                INNER JOIN employees e
                ON s.employee_id = e.employee_id
                ORDER BY s.salary_month DESC";

        return mysqli_query($this->conn, $sql);
    }
    public function getSalaryById($id)
    {
        $id = (int)$id;

        $sql = "SELECT s.*, e.full_name
                FROM salaries s
                INNER JOIN employees e
                ON s.employee_id = e.employee_id
                WHERE s.salary_id = $id";

        $result = mysqli_query($this->conn, $sql);

        return mysqli_fetch_assoc($result);
    }
    public function getSalaryByEmployee($employee_id)
    {
        $employee_id = (int)$employee_id;

        $sql = "SELECT *
                FROM salaries
                WHERE employee_id = $employee_id
                ORDER BY salary_month DESC";

        return mysqli_query($this->conn, $sql);
    }
    public function calculateTotal($data)
    {
        return
            $data['basic_salary']
            + $data['allowance']
            + $data['bonus']
            - $data['deduction'];
    }
    public function createSalary($data)
    {
        $total = $this->calculateTotal($data);

        $sql = "INSERT INTO salaries
        (
            employee_id,
            salary_month,
            basic_salary,
            allowance,
            bonus,
            deduction,
            total_salary
        )

        VALUES
        (
            '{$data['employee_id']}',
            '{$data['salary_month']}',
            '{$data['basic_salary']}',
            '{$data['allowance']}',
            '{$data['bonus']}',
            '{$data['deduction']}',
            '$total'
        )";

        return mysqli_query($this->conn, $sql);
    }
    public function updateSalary($id, $data)
    {
        $id = (int)$id;

        $total = $this->calculateTotal($data);

        $sql = "UPDATE salaries SET

        employee_id='{$data['employee_id']}',
        salary_month='{$data['salary_month']}',
        basic_salary='{$data['basic_salary']}',
        allowance='{$data['allowance']}',
        bonus='{$data['bonus']}',
        deduction='{$data['deduction']}',
        total_salary='$total'

        WHERE salary_id=$id";

        return mysqli_query($this->conn, $sql);
    }
    public function deleteSalary($id)
    {
        $id = (int)$id;

        $sql = "DELETE FROM salaries
                WHERE salary_id=$id";

        return mysqli_query($this->conn, $sql);
    }
}