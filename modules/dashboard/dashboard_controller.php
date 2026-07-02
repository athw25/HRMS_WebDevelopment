<?php

require_once "dashboard_model.php";

class DashboardController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new DashboardModel($conn);
    }

    public function layThongKeDashboard()
    {
        return $this->model->layThongKe();
    }

    public function thongKePhongBan()
    {
        return $this->model->thongKePhongBan();
    }

    public function thongKeChucVu()
    {
        return $this->model->thongKeChucVu();
    }

    public function thongKeNghiPhep()
    {
        return $this->model->thongKeNghiPhep();
    }

    public function thongKeLuong()
    {
        return $this->model->thongKeLuong();
    }
}