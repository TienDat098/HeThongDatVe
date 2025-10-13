<?php require_once __DIR__ . '/../config.php'; ?>
<?php
// === LOGIC LẤY DỮ LIỆU RẠP CHIẾU CHO MEGA MENU ===

// 1. Truy vấn để lấy tất cả rạp chiếu
$sql_rap = "SELECT ten_rap, dia_chi FROM RapChieu ORDER BY ten_rap ASC";
$result_rap = $conn->query($sql_rap);

// 2. Xử lý và nhóm các rạp lại theo cụm (Beta, CGV, Lotte...)
$rap_theo_cum = []; // Mảng để lưu các rạp đã được nhóm
if ($result_rap && $result_rap->num_rows > 0) {
    while ($rap = $result_rap->fetch_assoc()) {
        $ten_rap = $rap['ten_rap'];
        $cum_rap_key = 'Rạp Khác'; // Tên nhóm mặc định

        // Dựa vào tên để xác định cụm rạp
        if (stripos($ten_rap, 'Beta') !== false) {
            $cum_rap_key = 'Beta Cinemas';
        } elseif (stripos($ten_rap, 'CGV') !== false) {
            $cum_rap_key = 'CGV';
        } elseif (stripos($ten_rap, 'Lotte') !== false) {
            $cum_rap_key = 'Lotte Cinema';
        } elseif (stripos($ten_rap, 'Cinestar') !== false) {
            $cum_rap_key = 'Cinestar';
        } // Thêm các cụm rạp khác nếu có...

        // Thêm rạp vào đúng nhóm của nó
        $rap_theo_cum[$cum_rap_key][] = $rap;
    }
}
?>
<?php
// =======================================================
// === LOGIC XÁC ĐỊNH THÀNH PHỐ ĐANG CHỌN (BẮT BUỘC) ===
// =======================================================
$sql_cities_header = "SELECT thanh_pho FROM RapChieu GROUP BY thanh_pho ORDER BY thanh_pho ASC";
$result_cities_header = $conn->query($sql_cities_header);

// 1. Đặt một giá trị mặc định để tránh lỗi
$thanh_pho_duoc_chon = 'Tất cả Tỉnh/Thành';

// 2. Kiểm tra xem người dùng có chọn thành phố nào trên URL không
if (isset($_GET['thanh_pho']) && !empty($_GET['thanh_pho'])) {
    $thanh_pho_duoc_chon = $_GET['thanh_pho'];
} 
// 3. Nếu không, lấy thành phố đầu tiên trong danh sách làm mặc định
elseif ($result_cities_header && $result_cities_header->num_rows > 0) {
    $first_city = $result_cities_header->fetch_assoc();
    $thanh_pho_duoc_chon = $first_city['thanh_pho'];
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Đặt Vé Xem Phim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-container">
            <a href="<?php echo BASE_URL; ?>" class="logo">CineVerse</a>

            <nav class="main-nav">
                <ul>
                    <li><a href="#">Lịch Chiếu</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">
                            Phim <i class="fas fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Đang Chiếu</a></li>
                            <li><a href="#">Sắp Chiếu</a></li>
                            <li><a href="#">Chiếu Sớm</a></li>
                            <li><a href="#">Phim Việt Nam</a></li>
                        </ul>
                    </li>
                   <li class="dropdown mega-menu">
    <a href="#" class="dropdown-toggle">Rạp <i class="fas fa-angle-down"></i></a>
    
    <div class="dropdown-menu cinema-dropdown-menu">
        <div class="cinema-dropdown-header">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Tìm rạp tại">
            </div>
           <div class="city-selector-wrapper">
    <button class="city-selector-button" type="button">
        <span><?php echo htmlspecialchars($thanh_pho_duoc_chon); ?></span>
        <i class="fas fa-angle-down"></i>
    </button>
    
    <div class="city-dropdown-list">
        <ul>
            <?php
            // Lấy danh sách tất cả các thành phố có rạp chiếu
            $sql_all_cities = "SELECT thanh_pho FROM RapChieu GROUP BY thanh_pho ORDER BY thanh_pho ASC";
            $result_all_cities = $conn->query($sql_all_cities);
            if ($result_all_cities && $result_all_cities->num_rows > 0) {
                while ($city = $result_all_cities->fetch_assoc()) {
                    // Tạo link cho mỗi thành phố, khi bấm vào sẽ tải lại trang với tham số mới
                    echo '<li><a href="index.php?thanh_pho=' . urlencode($city['thanh_pho']) . '">' . htmlspecialchars($city['thanh_pho']) . '</a></li>';
                }
            }
            ?>
        </ul>
    </div>
</div>
        </div>

        <div class="cinema-dropdown-list">
            <?php if (!empty($rap_theo_cum)): ?>
                <?php foreach ($rap_theo_cum as $ten_cum => $danh_sach_rap): ?>
                    <div class="cinema-group">
                        <h4 class="cinema-chain-name"><?php echo htmlspecialchars($ten_cum); ?></h4>
                        <ul>
                            <?php foreach ($danh_sach_rap as $rap): ?>
                                <li>
                                    <a href="#" class="cinema-item">
                                        <img src="<?php echo BASE_URL . 'assets/images/logos/' . strtolower(explode(' ', $ten_cum)[0]) . '.png'; ?>" alt="<?php echo htmlspecialchars($ten_cum); ?>" class="cinema-logo">
                                        <div class="cinema-details">
                                            <span class="cinema-name"><?php echo htmlspecialchars($rap['ten_rap']); ?></span>
                                            <span class="cinema-address"><?php echo htmlspecialchars($rap['dia_chi']); ?></span>
                                        </div>
                                        <span class="ticket-tag">Bán vé</span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="padding: 20px;">Không tìm thấy rạp chiếu nào.</p>
            <?php endif; ?>
        </div>
    </div>
</li>
                    <li><a href="#">Tin Tức</a></li>
                </ul>
            </nav>

            <div class="header-right">
                <form action="tim_kiem.php" method="get" class="search-form">
                    <input type="text" name="keyword" placeholder="Tìm kiếm phim...">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <div class="auth-buttons">
                    <a href="#" class="btn btn-secondary">Đăng Nhập</a>
                    <a href="#" class="btn btn-primary">Đăng Ký</a>
                </div>
            </div>
        </div>
    </header>
    <main>