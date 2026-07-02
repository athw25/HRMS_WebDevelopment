<?php
/**
 * File: config/permissions.php
 * Mo ta: Trung tam quan ly ma tran phan quyen (Role-Based Access Control) cua he thong.
 * Duoc su dung boi middleware va cac module de kiem tra quyen truy cap.
 */

if (!defined('HRMS_APP')) {
    die('Truy cap truc tiep khong duoc phep.');
}

/**
 * Nhom quyen dung san cho middleware
 */
const PERMISSION_ADMIN_ONLY      = [ROLE_ADMIN];
const PERMISSION_MANAGER_UP      = [ROLE_ADMIN, ROLE_MANAGER];
const PERMISSION_ALL_ROLES       = [ROLE_ADMIN, ROLE_MANAGER, ROLE_EMPLOYEE];

/**
 * Kiem tra vai tro hien tai co nam trong danh sach vai tro duoc phep hay khong
 */
function userHasRole(array $allowedRoles): bool
{
    $role = getCurrentRole();

    if ($role === null) {
        return false;
    }

    return in_array($role, $allowedRoles, true);
}

/**
 * Bat buoc nguoi dung phai co mot trong cac vai tro duoc phep,
 * neu khong se tra ve trang 403.
 */
function requireRole(array $allowedRoles): void
{
    if (!userHasRole($allowedRoles)) {
        http_response_code(403);
        require ROOT_PATH . '/includes/403.php';
        exit;
    }
}

/**
 * Kiem tra nguoi dung co phai chu so huu du lieu ca nhan hay khong
 * (Vi du: Employee chi duoc xem/sua du lieu cua chinh minh).
 */
function isOwnEmployeeData(?int $employeeId): bool
{
    if ($employeeId === null) {
        return false;
    }

    return (int) ($_SESSION['employee_id'] ?? 0) === $employeeId;
}

/**
 * Kiem tra Manager co quan ly phong ban tuong ung hay khong
 */
function isOwnDepartment(?int $departmentId): bool
{
    if ($departmentId === null) {
        return false;
    }

    return (int) ($_SESSION['department_id'] ?? 0) === $departmentId;
}
