console.log("Charts.js đã chạy");

document.addEventListener("DOMContentLoaded", function () {

    fetch("../../api/dashboard_api.php")
        .then(response => response.json())
        .then(result => {

            if (result.success) {

                document.getElementById("totalEmployees").innerHTML =
                    result.data.tongNhanVien;

                document.getElementById("totalDepartments").innerHTML =
                    result.data.tongPhongBan;

                document.getElementById("totalPositions").innerHTML =
                    result.data.tongChucVu;

                document.getElementById("totalLeave").innerHTML =
                    result.data.tongDonNghi;

                document.getElementById("todayAttendance").innerHTML =
                    result.data.diemDanhHomNay;

            }

        });

    new Chart(document.getElementById("departmentChart"), {

        type: "bar",

        data: {

            labels: [
                "Kế toán",
                "Nhân sự",
                "CNTT",
                "Kinh doanh",
                "Marketing"
            ],

            datasets: [{

                label: "Số nhân viên",

                data: [8, 5, 10, 7, 4]

            }]

        }

    });

    new Chart(document.getElementById("positionChart"), {

        type: "pie",

        data: {

            labels: [
                "Giám đốc",
                "Trưởng phòng",
                "Nhân viên"
            ],

            datasets: [{

                data: [2, 6, 26]

            }]

        }

    });

    new Chart(document.getElementById("leaveChart"), {

        type: "doughnut",

        data: {

            labels: [
                "Đã duyệt",
                "Chờ duyệt",
                "Từ chối"
            ],

            datasets: [{

                data: [10, 3, 2]

            }]

        }

    });

    new Chart(document.getElementById("salaryChart"), {

        type: "line",

        data: {

            labels: [
                "Tháng 1",
                "Tháng 2",
                "Tháng 3",
                "Tháng 4",
                "Tháng 5",
                "Tháng 6"
            ],

            datasets: [{

                label: "Quỹ lương",

                data: [
                    220,
                    240,
                    235,
                    260,
                    280,
                    300
                ]

            }]

        }

    });

});