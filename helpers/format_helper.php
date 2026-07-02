<?php
/**
 * =========================================================
 * FILE: helpers/format-helper.php
 * MODULE: Core System - Helper Functions
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Chuẩn hóa định dạng hiển thị dữ liệu ra giao diện
 *   (ngày tháng, tiền tệ, chuỗi) theo chuẩn Việt Nam.
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

/**
 * Định dạng ngày từ MySQL (Y-m-d) sang kiểu Việt Nam (d/m/Y).
 *
 * @param string|null $date Chuỗi ngày dạng Y-m-d hoặc Y-m-d H:i:s
 * @param string $format Định dạng đích
 */
function formatDate(?string $date, string $format = 'd/m/Y'): string
{
    if (isEmptyValue($date)) {
        return '';
    }

    $timestamp = strtotime($date);
    return $timestamp !== false ? date($format, $timestamp) : '';
}

/**
 * Định dạng ngày giờ đầy đủ (dùng cho created_at, updated_at).
 */
function formatDateTime(?string $datetime): string
{
    return formatDate($datetime, 'd/m/Y H:i');
}

/**
 * Định dạng số tiền theo kiểu Việt Nam (vd: 15.000.000 ₫).
 *
 * @param float|int|null $amount
 */
function formatCurrency($amount): string
{
    $amount = (float) ($amount ?? 0);
    return number_format($amount, 0, ',', '.') . ' ₫';
}

/**
 * Rút gọn chuỗi nếu vượt quá độ dài cho phép, thêm "..." ở cuối.
 */
function truncateText(?string $text, int $maxLength = 50): string
{
    $text = $text ?? '';
    if (mb_strlen($text) <= $maxLength) {
        return $text;
    }
    return mb_substr($text, 0, $maxLength) . '...';
}

/**
 * Sinh chữ cái đầu tên (initials) để dùng làm avatar mặc định.
 * Vd: "Nguyễn Văn A" -> "NA"
 */
function getInitials(?string $fullName): string
{
    $fullName = trim($fullName ?? '');
    if ($fullName === '') {
        return '?';
    }

    $parts = preg_split('/\s+/', $fullName);
    $first = mb_substr($parts[0], 0, 1);
    $last  = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';

    return mb_strtoupper($first . $last);
}
