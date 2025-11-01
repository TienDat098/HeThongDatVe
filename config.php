<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// --- CẤU HÌNH DATABASE ---

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_NAME', 'dat_ve_xem_phim');
define('DB_PORT', '3307');

// --- CẤU HÌNH ĐƯỜNG DẪN ---
// Đường dẫn gốc của website, rất quan trọng để nhúng file CSS, JS, ảnh...
// Base URL phải khớp với đường dẫn thư mục trong `htdocs`.

define('BASE_URL', 'http://localhost/Webdatve/HeThongDatVe/');

?>