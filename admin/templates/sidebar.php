<aside class="sidebar">
    <div class="sidebar-header">
        <a href="dashboard.php" class="logo">CineVerse Admin</a>
    </div>
    <ul class="sidebar-nav">
        <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="quan_ly_phim.php"><i class="fas fa-film"></i> Quản lý Phim</a></li>
        <li><a href="quan_ly_lich_chieu.php"><i class="fas fa-calendar-alt"></i> Quản lý Lịch chiếu</a></li>
        <li><a href="quan_ly_nguoi_dung.php"><i class="fas fa-users"></i> Quản lý Người dùng</a></li>
        <li><a href="quan_ly_dat_ve.php"><i class="fas fa-ticket-alt"></i> Quản lý Đặt vé</a></li>
        <li><a href="bao_cao.php" class="<?php echo ($current_page == 'bao_cao.php') ? 'active' : ''; ?>"><i class="fas fa-chart-pie"></i> Báo cáo Doanh thu</a></li>
        <li style="padding: 15px 20px; color: #777; font-size: 12px; text-transform: uppercase;">Cài đặt Hệ thống</li>
        <li><a href="quan_ly_cum_rap.php" class="<?php echo ($current_page == 'quan_ly_cum_rap.php') ? 'active' : ''; ?>"><i class="fas fa-industry"></i> Quản lý Cụm Rạp</a></li>
        <li><a href="quan_ly_rap_chieu.php" class="<?php echo ($current_page == 'quan_ly_rap_chieu.php') ? 'active' : ''; ?>"><i class="fas fa-building"></i> Quản lý Rạp Chiếu</a></li>
        <li><a href="quan_ly_phong_chieu.php" class="<?php echo ($current_page == 'quan_ly_phong_chieu.php') ? 'active' : ''; ?>"><i class="fas fa-door-closed"></i> Quản lý Phòng Chiếu</a></li>
        <li><a href="../dang_xuat.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
    </ul>
</aside>
<main class="main-content">