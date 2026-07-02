-- =========================================================
-- FILE: database/migrations/003_seed_roles_and_admin.sql
-- MODULE: Authentication / Core System
-- OWNER: Thành viên 1 - System Architect
-- MỤC ĐÍCH: Seed dữ liệu khởi tạo bắt buộc để hệ thống có thể
--           đăng nhập lần đầu (bootstrap account).
-- LƯU Ý: Mật khẩu admin mặc định là "Admin@123" (đã hash bcrypt).
--        BẮT BUỘC đổi mật khẩu ngay sau lần đăng nhập đầu tiên.
-- =========================================================

INSERT INTO roles (role_name) VALUES
    ('ADMIN'),
    ('MANAGER'),
    ('EMPLOYEE')
ON DUPLICATE KEY UPDATE role_name = role_name;

-- Mật khẩu: Admin@123
-- Hash được sinh bằng password_hash('Admin@123', PASSWORD_BCRYPT)
INSERT INTO users (username, password, role_id, employee_id, status)
SELECT
    'admin',
    '$2y$10$qg4t4n5cXIi8G7aRmOn.cu/rMaaDnwrqFkt8TXGuY4J.clIJO/dmy',
    r.id,
    NULL,
    'active'
FROM roles r
WHERE r.role_name = 'ADMIN'
ON DUPLICATE KEY UPDATE username = username;
