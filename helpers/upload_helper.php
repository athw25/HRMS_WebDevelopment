<?php
/**
 * File: helpers/upload_helper.php
 * Mo ta: Cac ham xu ly upload file dung chung toan he thong
 *        (avatar, tai lieu...). Phan xu ly nghiep vu rieng cua
 *        tung module (vi du avatar nhan vien) do module do tu xu ly.
 */

if (!defined('HRMS_APP')) {
    die('Truy cap truc tiep khong duoc phep.');
}

/**
 * Kiem tra file upload co hop le hay khong (loai file, dung luong)
 */
function isValidUploadFile(array $file, array $allowedTypes, int $maxSize): array
{
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'message' => 'Tai file that bai. Vui long thu lai.'];
    }

    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'message' => 'Dung luong file vuot qua gioi han cho phep.'];
    }

    $mimeType = mime_content_type($file['tmp_name']);

    if (!in_array($mimeType, $allowedTypes, true)) {
        return ['valid' => false, 'message' => 'Dinh dang file khong duoc ho tro.'];
    }

    return ['valid' => true, 'message' => ''];
}

/**
 * Sinh ten file duy nhat de tranh trung lap
 */
function generateUniqueFileName(string $originalName): string
{
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    return uniqid('file_', true) . '_' . time() . '.' . $extension;
}

/**
 * Di chuyen file da upload vao thu muc dich
 */
function moveUploadedFileTo(array $file, string $destinationDir, string $fileName): array
{
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0755, true);
    }

    $destinationPath = rtrim($destinationDir, '/') . '/' . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $destinationPath)) {
        return ['success' => false, 'message' => 'Khong the luu file len he thong.'];
    }

    return ['success' => true, 'message' => 'Tai file thanh cong.', 'path' => $destinationPath];
}

/**
 * Xoa file theo duong dan neu ton tai
 */
function deleteUploadedFile(string $filePath): bool
{
    if ($filePath !== '' && file_exists($filePath)) {
        return unlink($filePath);
    }

    return false;
}
