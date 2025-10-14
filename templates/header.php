<?php require_once __DIR__ . '/../config.php'; ?>
<?php

$sql_cities_header = "SELECT thanh_pho FROM RapChieu GROUP BY thanh_pho ORDER BY thanh_pho ASC";
$result_cities_header = $conn->query($sql_cities_header);


$thanh_pho_duoc_chon = 'TP. Hồ Chí Minh';


if (isset($_GET['thanh_pho']) && !empty($_GET['thanh_pho'])) {
    $thanh_pho_duoc_chon = $_GET['thanh_pho'];
}


$rap_theo_cum = [];


$sql_rap = "
    SELECT r.ten_rap, r.dia_chi, cr.ten_cum_rap, cr.logo
    FROM RapChieu r
    JOIN CumRap cr ON r.id_cum_rap = cr.id
";


if ($thanh_pho_duoc_chon) {
    $sql_rap .= " WHERE r.thanh_pho = ?";
}

$sql_rap .= " ORDER BY cr.ten_cum_rap ASC, r.ten_rap ASC";


$stmt = $conn->prepare($sql_rap);


if ($thanh_pho_duoc_chon) {
    $stmt->bind_param("s", $thanh_pho_duoc_chon);
}

$stmt->execute();
$result_rap = $stmt->get_result();


if ($result_rap && $result_rap->num_rows > 0) {
    while ($rap = $result_rap->fetch_assoc()) {
        $ten_cum = $rap['ten_cum_rap'];
        $rap_theo_cum[$ten_cum][] = $rap;
    }
}
$stmt->close();
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
                        <a href="#" class="dropdown-toggle">Phim <i class="fas fa-angle-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"> Đang Chiếu</a></li>
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
                                            if ($result_cities_header && $result_cities_header->num_rows > 0) {
                                                $result_cities_header->data_seek(0);
                                                while ($city = $result_cities_header->fetch_assoc()) {
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
                                                            <img src="<?php echo BASE_URL . 'assets/images/logos/' . htmlspecialchars($rap['logo']); ?>" alt="<?php echo htmlspecialchars($ten_cum); ?>" class="cinema-logo">
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
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle">Tin Tức <i class="fas fa-angle-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Tin điện ảnh</a></li>
                            <li><a href="#">Đánh Giá Phim</a></li>
                            <li><a href="#">Video</a></li>
                            <li><a href="#">TV Series</a></li>
                        </ul>
                    </li>
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