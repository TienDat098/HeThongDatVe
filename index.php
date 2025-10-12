<?php
require_once 'core/db_connection.php'; 
require_once 'templates/header.php';
?>

<div class="container">
    <h2 class="section-title">Mua vé theo phim</h2>

    <!-- Top carousel (featured movies) -->
    <div class="top-swiper swiper-container">
        <div class="swiper-wrapper">
            <?php
            $sql_top = "SELECT id, tieu_de, poster FROM Phim WHERE trang_thai = 'dang_chieu' ORDER BY id DESC LIMIT 10";
            $res_top = $conn->query($sql_top);
            if ($res_top && $res_top->num_rows > 0) {
                while ($row = $res_top->fetch_assoc()) {
                    // Determine poster URL: support full URLs, subpaths, or simple filenames
                    $rawPoster = trim($row['poster'] ?? '');
                    if (empty($rawPoster)) {
                        $poster = BASE_URL . 'assets/images/posters/placeholder.svg';
                    } elseif (preg_match('#^https?://#i', $rawPoster) || strpos($rawPoster, '//') === 0) {
                        $poster = $rawPoster;
                    } elseif (strpos($rawPoster, '/') !== false) {
                        $poster = rtrim(BASE_URL, '/') . '/' . ltrim($rawPoster, '/');
                    } else {
                        $poster = BASE_URL . 'assets/images/posters/' . htmlspecialchars($rawPoster);
                    }
                    $title = htmlspecialchars($row['tieu_de']);
                    // optional release date (if column exists)
                    $release = isset($row['ngay_khoi_chieu']) && $row['ngay_khoi_chieu'] ? date('d/m', strtotime($row['ngay_khoi_chieu'])) : '';
                    echo '<div class="swiper-slide"><a href="chi_tiet_phim.php?id='. $row['id'] .'"><img src="'. $poster .'" alt="'. $title .'"><div class="carousel-overlay">'. $title . ($release ? ' — ' . $release : '') .'</div></a></div>';
                }
            } else {
                echo '<div class="swiper-slide"><img src="'. BASE_URL .'assets/images/posters/placeholder.svg" alt="placeholder"></div>';
            }
            ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <!-- Movie list as horizontal swiper -->
    <h2 class="section-title">Phim Đang Chiếu</h2>
    <div class="list-swiper swiper-container movie-list">
        <div class="swiper-wrapper">
            <?php
            $sql = "SELECT id, tieu_de, poster FROM Phim WHERE trang_thai = 'dang_chieu'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($phim = $result->fetch_assoc()) {
                    // Poster resolution logic for movie list
                    $raw = trim($phim['poster'] ?? '');
                    if (empty($raw)) {
                        $posterFile = BASE_URL . 'assets/images/posters/placeholder.svg';
                    } elseif (preg_match('#^https?://#i', $raw) || strpos($raw, '//') === 0) {
                        $posterFile = $raw;
                    } elseif (strpos($raw, '/') !== false) {
                        $posterFile = rtrim(BASE_URL, '/') . '/' . ltrim($raw, '/');
                    } else {
                        $posterFile = BASE_URL . 'assets/images/posters/' . htmlspecialchars($raw);
                    }
                    ?>
                    <div class="swiper-slide">
                        <div class="movie-card">
                            <div class="poster-wrap">
                                <a href="chi_tiet_phim.php?id=<?php echo $phim['id']; ?>">
                                    <img src="<?php echo $posterFile; ?>" alt="<?php echo htmlspecialchars($phim['tieu_de']); ?>">
                                    <div class="poster-gradient"></div>
                                </a>
                            </div>
                            <div class="info">
                                <h3><?php echo htmlspecialchars($phim['tieu_de']); ?></h3>
                                <div class="meta">
                                    <?php if (!empty($phim['ngay_khoi_chieu'])): ?>
                                        Khởi chiếu: <?php echo date('d/m/Y', strtotime($phim['ngay_khoi_chieu'])); ?>
                                    <?php endif; ?>
                                </div>
                                <a class="buy" href="chi_tiet_phim.php?id=<?php echo $phim['id']; ?>">Mua vé</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>Hiện chưa có phim nào.</p>";
            }
            ?>
        </div>
    </div>

</div>

<!-- Theater filter section -->
<div class="container">
    <h2 class="section-title">Mua vé theo rạp</h2>
    <div class="theater-filter">
        <div class="theater-areas">
            <div class="theater-item"><strong>Khu vực</strong></div>
            <div class="theater-item">Tp. Hồ Chí Minh</div>
            <div class="theater-item">Hà Nội</div>
            <div class="theater-item">Đà Nẵng</div>
            <div class="theater-item">Bình Dương</div>
        </div>
        <div class="theater-list">
            <div class="theater-item"><strong>Rạp</strong></div>
            <div class="theater-item">Beta Quang Trung</div>
            <div class="theater-item">Beta Trần Quang Khải</div>
            <div class="theater-item">Cinestar Hai Bà Trưng</div>
            <div class="theater-item">Lotte Cinema</div>
        </div>
    </div>
</div>

<?php
require_once 'templates/footer.php';

$conn->close();
?>