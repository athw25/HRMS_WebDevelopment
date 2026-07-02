<?php
/**
 * =========================================================
 * FILE: config/database.php
 * MODULE: Core System
 * OWNER: Thành viên 1 - System Architect
 * MỤC ĐÍCH:
 *   Quản lý kết nối cơ sở dữ liệu MySQL bằng PDO.
 *   Áp dụng Singleton Pattern để đảm bảo chỉ có 1 kết nối
 *   duy nhất trong suốt vòng đời của 1 request.
 * DEPENDENCIES:
 *   - config/constants.php (không bắt buộc nhưng nên load trước)
 * =========================================================
 */

if (!defined('HRMS_APP')) {
    http_response_code(403);
    die('Direct access is not allowed.');
}

class Database
{
    // Cấu hình kết nối - CHỈNH LẠI cho môi trường triển khai thực tế
    private static string $host = 'localhost';
    private static string $port = '3306';
    private static string $dbName = 'hrms';
    private static string $username = 'root';
    private static string $password = '';
    private static string $charset = 'utf8mb4';

    // Đối tượng PDO duy nhất (Singleton)
    private static ?PDO $instance = null;

    /**
     * Lấy kết nối PDO duy nhất tới database.
     * getConnection(): tên hàm dạng verb + object theo convention.
     *
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                self::$host,
                self::$port,
                self::$dbName,
                self::$charset
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false, // Bắt buộc dùng prepared statement thật
            ];

            try {
                self::$instance = new PDO($dsn, self::$username, self::$password, $options);
            } catch (PDOException $e) {
                // Không lộ chi tiết kết nối thật ra ngoài (bảo mật)
                error_log('[DATABASE CONNECTION ERROR] ' . $e->getMessage());

                if (self::isDebugMode()) {
                    die('Lỗi kết nối cơ sở dữ liệu: ' . $e->getMessage());
                }

                die('Hệ thống đang gặp sự cố kết nối cơ sở dữ liệu. Vui lòng thử lại sau.');
            }
        }

        return self::$instance;
    }

    /**
     * Kiểm tra chế độ debug (chỉ bật khi phát triển local).
     * Có thể chuyển sang đọc từ biến môi trường APP_ENV khi deploy thật.
     */
    private static function isDebugMode(): bool
    {
        return in_array($_SERVER['SERVER_NAME'] ?? 'localhost', ['localhost', '127.0.0.1'], true);
    }

    // Không cho phép clone hoặc khởi tạo lại instance (Singleton chuẩn)
    private function __construct() {}
    private function __clone() {}
}
