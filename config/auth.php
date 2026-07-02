<?php
/**
 * =========================================================
 * FILE: config/auth.php
 * MODULE: Core System - Authentication
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Cung cấp các hàm hỗ trợ xác thực và phân quyền.
 *   KHÔNG chứa logic đăng nhập, đổi mật khẩu hay Session.
 *
 * DEPENDENCIES:
 *   - config/session.php
 *   - config/constants.php
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    exit('Direct access is not allowed.');
}

/**
 * Lấy ID người dùng hiện tại.
 */
function getCurrentUserId(): ?int
{
    return getUserField('user_id');
}

/**
 * Lấy Username hiện tại.
 */
function getCurrentUsername(): ?string
{
    return getUserField('username');
}

/**
 * Lấy Role hiện tại.
 */
function getCurrentRole(): ?string
{
    return getUserField('role');
}

/**
 * Lấy Employee ID.
 */
function getCurrentEmployeeId(): ?int
{
    return getUserField('employee_id');
}

/**
 * Lấy Department ID.
 */
function getCurrentDepartmentId(): ?int
{
    return getUserField('department_id');
}

/**
 * Lấy Position ID.
 */
function getCurrentPositionId(): ?int
{
    return getUserField('position_id');
}

/**
 * Lấy họ tên người dùng.
 */
function getCurrentFullName(): ?string
{
    return getUserField('full_name');
}

/**
 * Lấy avatar.
 */
function getCurrentAvatar(): ?string
{
    return getUserField('avatar');
}


function redirectByRole(): void
{
    requireLogin();

    switch (getCurrentRole()) {

        case ROLE_ADMIN:
            header('Location: ' . BASE_URL . '/modules/dashboard/index.php');
            break;

        case ROLE_MANAGER:
            header('Location: ' . BASE_URL . '/modules/dashboard/index.php');
            break;

        case ROLE_EMPLOYEE:
            header('Location: ' . BASE_URL . '/modules/dashboard/index.php');
            break;

        default:
            header('Location: ' . BASE_URL . '/login.php');
            break;
    }

    exit;
}

/**
 * Đăng xuất người dùng.
 */
function logout(): void
{
    destroySession();

    header('Location: ' . BASE_URL . '/login.php');

    exit;
}