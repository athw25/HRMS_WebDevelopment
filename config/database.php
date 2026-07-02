<?php
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', ''); 
if (!defined('DB_NAME')) define('DB_NAME', 'hrms_db');
if (!function_exists('getDBConnection')) {
    function getDBConnection() {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn) {
            error_log("Kết nối cơ sở dữ liệu thất bại: " . mysqli_connect_error());
            return false;
        }
        mysqli_set_charset($conn, "utf8mb4");
        
        return $conn;
    }
}