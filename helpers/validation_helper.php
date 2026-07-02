<?php
/**
 * File: helpers/validation_helper.php
 * Mo ta: Cac ham kiem tra du lieu dau vao dung chung toan he thong.
 */

if (!defined('HRMS_APP')) {
    die('Truy cap truc tiep khong duoc phep.');
}

/**
 * Kiem tra chuoi rong
 */
function isEmptyValue($value): bool
{
    return trim((string) $value) === '';
}

/**
 * Kiem tra do dai mat khau toi thieu
 */
function isValidPasswordLength(string $password, int $minLength = 8): bool
{
    return strlen($password) >= $minLength;
}

/**
 * Lam sach du lieu dau vao dang chuoi
 */
function sanitizeInput(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

/**
 * Kiem tra dinh dang email
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
