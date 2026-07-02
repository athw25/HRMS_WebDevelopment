<?php

class ReportModel
{
    public function layBaoCao()
    {
        return [

            "baoCaoNhanVien" => [
                "tongNhanVien" => 25,
                "nhanVienMoi" => 2
            ],

            "baoCaoChamCong" => [
                "coMat" => 20,
                "vangMat" => 5
            ],

            "baoCaoNghiPhep" => [
                "choDuyet" => 2,
                "daDuyet" => 5,
                "tuChoi" => 1
            ],

            "baoCaoLuong" => [
                "tongLuong" => 350000000,
                "tongThuong" => 25000000
            ]

        ];
    }
}