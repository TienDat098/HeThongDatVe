-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 12, 2025 lúc 04:31 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dat_ve_xem_phim`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdatve`
--

CREATE TABLE `chitietdatve` (
  `id` int(11) NOT NULL,
  `id_dat_ve` int(11) DEFAULT NULL,
  `id_ghe` int(11) DEFAULT NULL,
  `gia_ve` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdatve`
--

INSERT INTO `chitietdatve` (`id`, `id_dat_ve`, `id_ghe`, `gia_ve`) VALUES
(1, 1, 6, 85000.00),
(2, 1, 7, 85000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgiaphim`
--

CREATE TABLE `danhgiaphim` (
  `id` int(11) NOT NULL,
  `id_nguoi_dung` int(11) DEFAULT NULL,
  `id_phim` int(11) DEFAULT NULL,
  `diem_danh_gia` int(11) DEFAULT NULL CHECK (`diem_danh_gia` between 1 and 5),
  `binh_luan` text DEFAULT NULL,
  `ngay_danh_gia` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgiaphim`
--

INSERT INTO `danhgiaphim` (`id`, `id_nguoi_dung`, `id_phim`, `diem_danh_gia`, `binh_luan`, `ngay_danh_gia`) VALUES
(1, 1, 3, 5, 'Phim hành động mãn nhãn, kỹ xảo tuyệt vời!', '2024-04-01 22:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `datve`
--

CREATE TABLE `datve` (
  `id` int(11) NOT NULL,
  `id_nguoi_dung` int(11) DEFAULT NULL,
  `id_lich_chieu` int(11) DEFAULT NULL,
  `ngay_dat` datetime DEFAULT current_timestamp(),
  `tong_tien` decimal(10,2) DEFAULT NULL,
  `trang_thai` enum('da_dat','da_huy') DEFAULT 'da_dat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `datve`
--

INSERT INTO `datve` (`id`, `id_nguoi_dung`, `id_lich_chieu`, `ngay_dat`, `tong_tien`, `trang_thai`) VALUES
(1, 1, 1, '2025-10-11 10:30:00', 170000.00, 'da_dat');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ghe`
--

CREATE TABLE `ghe` (
  `id` int(11) NOT NULL,
  `id_phong` int(11) DEFAULT NULL,
  `ma_ghe` varchar(10) NOT NULL,
  `loai_ghe` enum('thuong','vip') DEFAULT 'thuong'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ghe`
--

INSERT INTO `ghe` (`id`, `id_phong`, `ma_ghe`, `loai_ghe`) VALUES
(1, 1, 'A1', 'thuong'),
(2, 1, 'A2', 'thuong'),
(3, 1, 'A3', 'thuong'),
(4, 1, 'A4', 'thuong'),
(5, 1, 'B1', 'vip'),
(6, 1, 'B2', 'vip'),
(7, 1, 'B3', 'vip'),
(8, 1, 'B4', 'vip'),
(9, 1, 'C1', 'thuong'),
(10, 1, 'C2', 'thuong');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichchieu`
--

CREATE TABLE `lichchieu` (
  `id` int(11) NOT NULL,
  `id_phim` int(11) DEFAULT NULL,
  `id_phong` int(11) DEFAULT NULL,
  `thoi_gian_bat_dau` datetime NOT NULL,
  `thoi_gian_ket_thuc` datetime NOT NULL,
  `gia_ve` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lichchieu`
--

INSERT INTO `lichchieu` (`id`, `id_phim`, `id_phong`, `thoi_gian_bat_dau`, `thoi_gian_ket_thuc`, `gia_ve`) VALUES
(1, 1, 1, '2025-10-12 19:00:00', '2025-10-12 21:15:00', 85000.00),
(2, 2, 1, '2025-10-12 21:30:00', '2025-10-12 23:05:00', 75000.00),
(3, 1, 2, '2025-10-13 20:00:00', '2025-10-13 22:15:00', 95000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `id` int(11) NOT NULL,
  `ho_ten` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `vai_tro` enum('nguoi_dung','quan_tri') DEFAULT 'nguoi_dung',
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`id`, `ho_ten`, `email`, `mat_khau`, `so_dien_thoai`, `vai_tro`, `ngay_tao`) VALUES
(1, 'Trần Văn An', 'user@gmail.com', '$2y$10$E.V264yQkI.8aO2K4z8Yf.37.C2395vvo/KF2IF5j3g.2d.8pW7W.', '0987654321', 'nguoi_dung', '2025-10-12 19:39:48'),
(2, 'Nguyễn Thị Bích', 'admin@gmail.com', '$2y$10$E.V264yQkI.8aO2K4z8Yf.37.C2395vvo/KF2IF5j3g.2d.8pW7W.', '0123456789', 'quan_tri', '2025-10-12 19:39:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhatkyhethong`
--

CREATE TABLE `nhatkyhethong` (
  `id` int(11) NOT NULL,
  `id_quan_tri_vien` int(11) DEFAULT NULL,
  `hanh_dong` varchar(255) DEFAULT NULL,
  `ngay_thuc_hien` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhatkyhethong`
--

INSERT INTO `nhatkyhethong` (`id`, `id_quan_tri_vien`, `hanh_dong`, `ngay_thuc_hien`) VALUES
(1, 2, 'Thêm mới phim Godzilla x Kong: Đế Chế Mới', '2024-03-28 09:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phim`
--

CREATE TABLE `phim` (
  `id` int(11) NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `thoi_luong_phut` int(11) DEFAULT NULL,
  `ngay_khoi_chieu` date DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `trailer` varchar(255) DEFAULT NULL,
  `ngon_ngu` varchar(50) DEFAULT NULL,
  `trang_thai` enum('dang_chieu','sap_chieu','ngung_chieu') DEFAULT 'sap_chieu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phim`
--

INSERT INTO `phim` (`id`, `tieu_de`, `mo_ta`, `thoi_luong_phut`, `ngay_khoi_chieu`, `poster`, `trailer`, `ngon_ngu`, `trang_thai`) VALUES
(1, 'Lật Mặt 7: Một Điều Ước', 'Câu chuyện cảm động về gia đình bà Hai và 5 người con. Một bộ phim đáng xem của Lý Hải.', 135, '2024-04-26', 'lat-mat-7.jpg', 'https://www.youtube.com/watch?v=AP-k0WkYqdA', 'Tiếng Việt', 'dang_chieu'),
(2, 'Doraemon: Nobita và Bản Giao Hưởng Địa Cầu', 'Chuyến phiêu lưu âm nhạc của Doraemon và những người bạn để giải cứu Trái Đất.', 95, '2024-05-24', 'doraemon.jpg', 'https://www.youtube.com/watch?v=jW0a8f7o_S8', 'Tiếng Nhật', 'dang_chieu'),
(3, 'Godzilla x Kong: Đế Chế Mới', 'Hai quái vật khổng lồ hợp sức chống lại một mối đe dọa mới từ sâu trong lòng đất.', 115, '2024-03-29', 'godzilla-kong.jpg', 'https://www.youtube.com/watch?v=zshz_oth_e0', 'Tiếng Anh', 'ngung_chieu');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phim_theloai`
--

CREATE TABLE `phim_theloai` (
  `id_phim` int(11) NOT NULL,
  `id_the_loai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phim_theloai`
--

INSERT INTO `phim_theloai` (`id_phim`, `id_the_loai`) VALUES
(1, 2),
(2, 5),
(3, 1),
(3, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongchieu`
--

CREATE TABLE `phongchieu` (
  `id` int(11) NOT NULL,
  `id_rap` int(11) DEFAULT NULL,
  `ten_phong` varchar(100) NOT NULL,
  `so_luong_ghe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phongchieu`
--

INSERT INTO `phongchieu` (`id`, `id_rap`, `ten_phong`, `so_luong_ghe`) VALUES
(1, 1, 'Phòng 01', 50),
(2, 1, 'Phòng 02', 60),
(3, 2, 'Phòng A', 40);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rapchieu`
--

CREATE TABLE `rapchieu` (
  `id` int(11) NOT NULL,
  `ten_rap` varchar(255) NOT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `thanh_pho` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `rapchieu`
--

INSERT INTO `rapchieu` (`id`, `ten_rap`, `dia_chi`, `thanh_pho`) VALUES
(1, 'CGV Vincom Trần Phú', 'Tầng 4, TTTM Vincom Plaza, 78-80 Trần Phú, P. Lộc Thọ', 'Nha Trang'),
(2, 'Lotte Cinema Nha Trang', 'Tầng 5, TTTM Nha Trang Center, 20 Trần Phú, P. Lộc Thọ', 'Nha Trang');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanhtoan`
--

CREATE TABLE `thanhtoan` (
  `id` int(11) NOT NULL,
  `id_dat_ve` int(11) DEFAULT NULL,
  `phuong_thuc` enum('momo','vnpay','tien_mat') DEFAULT 'momo',
  `so_tien` decimal(10,2) DEFAULT NULL,
  `ngay_thanh_toan` datetime DEFAULT current_timestamp(),
  `ma_giao_dich` varchar(255) DEFAULT NULL,
  `trang_thai_thanh_toan` enum('thanh_cong','that_bai','cho_xu_ly') DEFAULT 'cho_xu_ly'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thanhtoan`
--

INSERT INTO `thanhtoan` (`id`, `id_dat_ve`, `phuong_thuc`, `so_tien`, `ngay_thanh_toan`, `ma_giao_dich`, `trang_thai_thanh_toan`) VALUES
(1, 1, 'momo', 170000.00, '2025-10-11 10:31:00', 'MOMO12345XYZ', 'thanh_cong');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `theloai`
--

CREATE TABLE `theloai` (
  `id` int(11) NOT NULL,
  `ten_the_loai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `theloai`
--

INSERT INTO `theloai` (`id`, `ten_the_loai`) VALUES
(4, 'Hài Hước'),
(1, 'Hành Động'),
(5, 'Khoa Học Viễn Tưởng'),
(3, 'Kinh Dị'),
(2, 'Tình Cảm');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietdatve`
--
ALTER TABLE `chitietdatve`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_dat_ve` (`id_dat_ve`),
  ADD KEY `id_ghe` (`id_ghe`);

--
-- Chỉ mục cho bảng `danhgiaphim`
--
ALTER TABLE `danhgiaphim`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nguoi_dung` (`id_nguoi_dung`),
  ADD KEY `id_phim` (`id_phim`);

--
-- Chỉ mục cho bảng `datve`
--
ALTER TABLE `datve`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nguoi_dung` (`id_nguoi_dung`),
  ADD KEY `id_lich_chieu` (`id_lich_chieu`);

--
-- Chỉ mục cho bảng `ghe`
--
ALTER TABLE `ghe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_phong` (`id_phong`);

--
-- Chỉ mục cho bảng `lichchieu`
--
ALTER TABLE `lichchieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_phim` (`id_phim`),
  ADD KEY `id_phong` (`id_phong`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `nhatkyhethong`
--
ALTER TABLE `nhatkyhethong`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_quan_tri_vien` (`id_quan_tri_vien`);

--
-- Chỉ mục cho bảng `phim`
--
ALTER TABLE `phim`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `phim_theloai`
--
ALTER TABLE `phim_theloai`
  ADD PRIMARY KEY (`id_phim`,`id_the_loai`),
  ADD KEY `id_the_loai` (`id_the_loai`);

--
-- Chỉ mục cho bảng `phongchieu`
--
ALTER TABLE `phongchieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rap` (`id_rap`);

--
-- Chỉ mục cho bảng `rapchieu`
--
ALTER TABLE `rapchieu`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_dat_ve` (`id_dat_ve`);

--
-- Chỉ mục cho bảng `theloai`
--
ALTER TABLE `theloai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_the_loai` (`ten_the_loai`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chitietdatve`
--
ALTER TABLE `chitietdatve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `danhgiaphim`
--
ALTER TABLE `danhgiaphim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `datve`
--
ALTER TABLE `datve`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `ghe`
--
ALTER TABLE `ghe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `lichchieu`
--
ALTER TABLE `lichchieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `nhatkyhethong`
--
ALTER TABLE `nhatkyhethong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `phim`
--
ALTER TABLE `phim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `phongchieu`
--
ALTER TABLE `phongchieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `rapchieu`
--
ALTER TABLE `rapchieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `theloai`
--
ALTER TABLE `theloai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdatve`
--
ALTER TABLE `chitietdatve`
  ADD CONSTRAINT `chitietdatve_ibfk_1` FOREIGN KEY (`id_dat_ve`) REFERENCES `datve` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chitietdatve_ibfk_2` FOREIGN KEY (`id_ghe`) REFERENCES `ghe` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `danhgiaphim`
--
ALTER TABLE `danhgiaphim`
  ADD CONSTRAINT `danhgiaphim_ibfk_1` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `danhgiaphim_ibfk_2` FOREIGN KEY (`id_phim`) REFERENCES `phim` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `datve`
--
ALTER TABLE `datve`
  ADD CONSTRAINT `datve_ibfk_1` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoidung` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `datve_ibfk_2` FOREIGN KEY (`id_lich_chieu`) REFERENCES `lichchieu` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ghe`
--
ALTER TABLE `ghe`
  ADD CONSTRAINT `ghe_ibfk_1` FOREIGN KEY (`id_phong`) REFERENCES `phongchieu` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lichchieu`
--
ALTER TABLE `lichchieu`
  ADD CONSTRAINT `lichchieu_ibfk_1` FOREIGN KEY (`id_phim`) REFERENCES `phim` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lichchieu_ibfk_2` FOREIGN KEY (`id_phong`) REFERENCES `phongchieu` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `nhatkyhethong`
--
ALTER TABLE `nhatkyhethong`
  ADD CONSTRAINT `nhatkyhethong_ibfk_1` FOREIGN KEY (`id_quan_tri_vien`) REFERENCES `nguoidung` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `phim_theloai`
--
ALTER TABLE `phim_theloai`
  ADD CONSTRAINT `phim_theloai_ibfk_1` FOREIGN KEY (`id_phim`) REFERENCES `phim` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phim_theloai_ibfk_2` FOREIGN KEY (`id_the_loai`) REFERENCES `theloai` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `phongchieu`
--
ALTER TABLE `phongchieu`
  ADD CONSTRAINT `phongchieu_ibfk_1` FOREIGN KEY (`id_rap`) REFERENCES `rapchieu` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `thanhtoan`
--
ALTER TABLE `thanhtoan`
  ADD CONSTRAINT `thanhtoan_ibfk_1` FOREIGN KEY (`id_dat_ve`) REFERENCES `datve` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
