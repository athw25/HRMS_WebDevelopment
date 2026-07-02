<?php

class DashboardModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function layThongKe()
    {
        return [
            "tongNhanVien"   => $this->demNhanVien(),
            "tongPhongBan"   => $this->dem("departments"),
            "tongChucVu"     => $this->dem("positions"),
            "tongDonNghi"    => $this->dem("leave_requests"),
            "diemDanhHomNay" => $this->diemDanhHomNay()
        ];
    }

    private function dem($table)
    {
        if (!$this->kiemTraBang($table)) {
            return 0;
        }

        $sql = "SELECT COUNT(*) AS tong FROM $table";
        $kq = $this->conn->query($sql);

        if (!$kq) {
            return 0;
        }

        return (int)$kq->fetch_assoc()['tong'];
    }

 
    private function demNhanVien()
    {
        if ($this->kiemTraBang("employees")) {
            return $this->dem("employees");
        }

        if ($this->kiemTraBang("users")) {
            return $this->dem("users");
        }

        return 0;
    }

    private function diemDanhHomNay()
    {
        if (!$this->kiemTraBang("attendance")) {
            return 0;
        }

        $sql = "
            SELECT COUNT(*) AS tong
            FROM attendance
            WHERE DATE(check_in)=CURDATE()
        ";

        $kq = $this->conn->query($sql);

        if (!$kq) {
            return 0;
        }

        return (int)$kq->fetch_assoc()['tong'];
    }

 
    private function kiemTraBang($table)
    {
        $sql = "SHOW TABLES LIKE '$table'";
        $kq = $this->conn->query($sql);

        return $kq && $kq->num_rows > 0;
    }
}