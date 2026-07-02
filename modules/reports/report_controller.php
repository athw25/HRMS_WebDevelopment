<?php

require_once "report_model.php";

class ReportController
{
    private $model;

    public function __construct()
    {
        $this->model = new ReportModel();
    }

    public function layBaoCao()
    {
        return $this->model->layBaoCao();
    }
}