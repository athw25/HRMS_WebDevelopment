-- =========================================================
-- FILE: database/migrations/001_create_roles_table.sql
-- MODULE: Authentication / Core System
-- OWNER: Thành viên 1 - System Architect
-- MỤC ĐÍCH: Danh mục vai trò người dùng trong hệ thống.
-- Giá trị role_name PHẢI khớp với hằng số ROLE_* trong
-- config/constants.php (ROLE_ADMIN, ROLE_MANAGER, ROLE_EMPLOYEE).
-- =========================================================

CREATE TABLE IF NOT EXISTS roles (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_name  VARCHAR(20) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_roles_role_name (role_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
