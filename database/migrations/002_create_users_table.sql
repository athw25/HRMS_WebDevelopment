-- =========================================================
-- FILE: database/migrations/002_create_users_table.sql
-- MODULE: Authentication / Core System
-- OWNER: Thành viên 1 - System Architect
-- MỤC ĐÍCH: Tài khoản đăng nhập hệ thống.
-- LƯU Ý:
--   - employee_id KHÔNG đặt FOREIGN KEY cứng tới bảng employees
--     vì bảng employees do TV2 tạo song song, tránh lỗi thứ tự
--     migration. Sẽ bổ sung FK qua migration riêng khi cả 2
--     bảng đã sẵn sàng, do người phụ trách tích hợp thực hiện.
--   - password lưu bằng password_hash() (bcrypt), KHÔNG BAO GIỜ
--     lưu plaintext.
-- =========================================================

CREATE TABLE IF NOT EXISTS users (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username       VARCHAR(50) NOT NULL,
    password       VARCHAR(255) NOT NULL,
    role_id        INT UNSIGNED NOT NULL,
    employee_id    INT UNSIGNED NULL,
    status         ENUM('active', 'locked') NOT NULL DEFAULT 'active',
    failed_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    locked_until   DATETIME NULL,
    last_login_at  DATETIME NULL,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_username (username),
    KEY idx_users_employee_id (employee_id),
    CONSTRAINT fk_users_role_id FOREIGN KEY (role_id) REFERENCES roles(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
