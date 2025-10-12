<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Đặt Vé Xem Phim</title>
    <!-- Swiper CSS (carousel) -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <!-- Project CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <header class="topbar">
        <nav>
            <a href="<?php echo BASE_URL; ?>" class="logo">LOGO</a>
            <ul>
                <li><a href="#">Phim Đang Chiếu</a></li>
                <li><a href="#">Lịch Chiếu</a></li>
                <li><a href="#">Rạp Chiếu</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="#">Đăng Nhập</a>
                <a href="#">Đăng Ký</a>
            </div>
        </nav>
    </header>
    <main class="site-main">