<?php
/**
 * File này chịu trách nhiệm duy nhất là tạo kết nối đến CSDL.
 * Mọi file khác khi cần dùng database sẽ gọi file này.
 */

// Dùng require_once để đảm bảo file config chỉ được nhúng một lần.
// __DIR__ . '/../config.php' là đường dẫn tuyệt đối, an toàn hơn.
require_once __DIR__ . '/../config.php';


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


if ($conn->connect_error) {
    
    die("Kết nối CSDL thất bại. Lỗi: " . $conn->connect_error);
}


$conn->set_charset("utf8mb4");

?>