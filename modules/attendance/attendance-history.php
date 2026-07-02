<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Chấm Công - HRMS Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            width: 100%; min-height: 100vh;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background: linear-gradient(-45deg, #022c22, #064e3b, #0f766e, #115e59);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            color: #fff;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .particles { position: fixed; inset: 0; overflow: hidden; z-index: 0; pointer-events: none; }
        .particle {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.25);
            box-shadow: 0 0 12px rgba(16, 185, 129, 0.6);
            animation: floatUp var(--dur) linear infinite;
        }
        @keyframes floatUp {
            from { transform: translateY(100vh) scale(1); opacity: 0; }
            10%  { opacity: 0.5; }
            90%  { opacity: 0.5; }
            to   { transform: translateY(-100px) scale(1.5); opacity: 0; }
        }

        .main-container {
            position: relative; z-index: 10;
            max-width: 900px; margin: 0 auto;
            padding: 40px 20px;
        }

        /* Header */
        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px;
            animation: slideDown 0.6s ease both;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .page-title {
            font-family: 'Manrope', sans-serif;
            font-size: 28px; font-weight: 800;
            background: linear-gradient(to right, #6ee7b7, #ccfbf1);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .btn-action {
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            color: #fff; text-decoration: none; padding: 10px 16px;
            border-radius: 10px; font-weight: 600; font-size: 14px;
            transition: all 0.3s ease; backdrop-filter: blur(10px);
            display: flex; align-items: center; gap: 6px;
        }
        .btn-action:hover {
            background: rgba(255,255,255,0.2); transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* ── TABLE GLASSMORPHISM ── */
        .table-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px; padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: fadeIn 0.6s ease both;
            overflow-x: auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.98); }
            to   { opacity: 1; transform: scale(1); }
        }

        table {
            width: 100%; border-collapse: collapse; text-align: left;
        }

        th, td {
            padding: 16px; border-bottom: 1px solid rgba(255,255,255,0.08);
            color: #e2e8f0; font-size: 15px;
        }
        th {
            font-weight: 700; color: #99f6e4; font-family: 'Manrope', sans-serif;
            text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px;
        }
        tr:last-child td { border-bottom: none; }
        
        tr { transition: background 0.3s; }
        tbody tr:hover { background: rgba(255, 255, 255, 0.03); }

        /* Status Badge */
        .status-badge {
            padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;
        }
        .status-badge.present { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3); }
        .status-badge.late { background: rgba(245, 158, 11, 0.2); color: #fcd34d; border: 1px solid rgba(245, 158, 11, 0.3); }

        .loader { text-align: center; padding: 30px; color: #6ee7b7; font-size: 16px; }
        .loader i { font-size: 24px; animation: spin 1s linear infinite; display: inline-block; margin-bottom: 10px; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div class="particles" id="particles"></div>

    <div class="main-container">
        <div class="page-header">
            <div>
                <h1 class="page-title">Lịch Sử Của Bạn</h1>
                <p style="color:#94a3b8; font-size: 14px; margin-top:5px;">Lịch sử Check-in / Check-out cá nhân</p>
            </div>
            <div style="display:flex; gap: 10px;">
                <a href="check-in.php" class="btn-action"><i class="bi bi-box-arrow-in-right"></i> Check-in</a>
                <a href="check-out.php" class="btn-action"><i class="bi bi-box-arrow-left"></i> Check-out</a>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Giờ Vào</th>
                        <th>Giờ Ra</th>
                        <th>Tổng Giờ</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody id="data-body">
                    <tr><td colspan="5">
                        <div class="loader"><i class="bi bi-arrow-repeat"></i><br>Đang tải dữ liệu...</div>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Background particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 15; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const sz = Math.random() * 4 + 2;
            p.style.width = sz + 'px'; p.style.height = sz + 'px';
            p.style.left = Math.random() * 100 + '%';
            p.style.setProperty('--dur', (Math.random() * 6 + 6) + 's');
            p.style.animationDelay = (Math.random() * 5) + 's';
            container.appendChild(p);
        }

        function formatDate(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
        }

        async function fetchHistory() {
            const tbody = document.getElementById('data-body');
            try {
                const response = await fetch('attendance_controller.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'list', filter: 'mine' })
                });
                const result = await response.json();

                if (result.success) {
                    tbody.innerHTML = '';
                    if (result.data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding: 40px; color:#94a3b8;">Chưa có dữ liệu chấm công.</td></tr>`;
                        return;
                    }
                    
                    result.data.forEach(item => {
                        const statusClass = item.status === 'present' ? 'present' : 'late';
                        const statusText = item.status === 'present' ? 'Đúng giờ' : 'Đi trễ';
                        const checkOutText = item.check_out ? item.check_out : '<span style="color:#fbbf24; font-size:13px;">Chưa check-out</span>';
                        const workHours = item.working_hours ? item.working_hours : '-';

                        tbody.innerHTML += `
                            <tr>
                                <td style="font-weight:600;">${formatDate(item.date)}</td>
                                <td>${item.check_in}</td>
                                <td>${checkOutText}</td>
                                <td>${workHours}</td>
                                <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:#ef4444;">Lỗi: ${result.message}</td></tr>`;
                }
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:#ef4444;">Lỗi kết nối máy chủ</td></tr>`;
            }
        }

        document.addEventListener('DOMContentLoaded', fetchHistory);
    </script>
</body>
</html>
