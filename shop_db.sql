-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 19, 2024 lúc 10:25 AM
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
-- Cơ sở dữ liệu: `shop_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(1, 1, 'Phạm Huy Thông', '4545353543', 'Huythong0932@gmail.com', 'cash on delivery', 'Số nhà g, ho chi minh, vv, Thành phố Hồ Chí Minh, Việt Nam - 12345', 'Samsung Galaxy S23 Ultra (1300 x 1) - ', 1300, '2024-09-19', 'completed'),
(2, 1, 'Phạm Huy Thông', '2132121321', 'Huythong0932@gmail.com', 'cash on delivery', 'Số nhà werewrwerew, ho chi minh, dfdsfds, Thành phố Hồ Chí Minh, Việt Nam - 12423', 'Samsung Galaxy S23 Ultra (1300 x 1) - ', 1300, '2024-09-19', 'completed');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(10) NOT NULL,
  `image_01` varchar(100) NOT NULL,
  `image_02` varchar(100) NOT NULL,
  `image_03` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `price`, `image_01`, `image_02`, `image_03`) VALUES
(1, 'Apple iPhone 14 Pro', 'Màn hình 6.1 inch, Super Retina XDR, Chip A16 Bionic, 48MP Camera chính, Pin 3200mAh, Face ID, 5G, IP68 chống nước.', 1200, 'iphone14pro_front.jpg', 'iphone14pro_back.jpg', 'iphone14pro_side.jpg'),
(2, 'Samsung Galaxy S23 Ultra', 'Màn hình 6.8 inch, Dynamic AMOLED 2X, Chip Snapdragon 8 Gen 2, Camera chính 200MP, Pin 5000mAh, hỗ trợ bút S-Pen, IP68.', 1300, 'galaxy_s23ultra_front.jpg', 'galaxy_s23ultra_back.jpg', 'galaxy_s23ultra_side.jpg'),
(3, 'Apple iPhone 14 Pro', 'Màn hình 6.1 inch, Super Retina XDR, Chip A16 Bionic, 48MP Camera chính, Pin 3200mAh, Face ID, 5G, IP68 chống nước.', 1200, 'iphone14pro_front.jpg', 'iphone14pro_back.jpg', 'iphone14pro_side.jpg'),
(4, 'Samsung Galaxy S23 Ultra', 'Màn hình 6.8 inch, Dynamic AMOLED 2X, Chip Snapdragon 8 Gen 2, Camera chính 200MP, Pin 5000mAh, hỗ trợ bút S-Pen, IP68.', 1300, 'galaxy_s23ultra_front.jpg', 'galaxy_s23ultra_back.jpg', 'galaxy_s23ultra_side.jpg'),
(5, 'Tủ lạnh LG Inverter 600L', 'Dung tích 600 lít, công nghệ làm lạnh Inverter tiết kiệm điện, ngăn đá trên, công nghệ khử mùi Nano Carbon.', 900, 'tulanh_lg_600l_front.jpg', 'tulanh_lg_600l_inside.jpg', 'tulanh_lg_600l_side.jpg'),
(6, 'Tủ lạnh Samsung Family Hub 680L', 'Tủ lạnh thông minh với màn hình cảm ứng Family Hub, dung tích 680 lít, công nghệ làm lạnh đa chiều, thiết kế 4 cửa hiện đại.', 2500, 'tulanh_samsung_hub_front.jpg', 'tulanh_samsung_hub_inside.jpg', 'tulanh_samsung_hub_side.jpg'),
(7, 'Máy giặt Electrolux 9kg', 'Máy giặt cửa trước, công nghệ Inverter, chế độ giặt hơi nước diệt khuẩn, lồng giặt 9kg, tốc độ quay 1200 vòng/phút.', 600, 'maygiat_electrolux_9kg_front.jpg', 'maygiat_electrolux_9kg_side.jpg', 'maygiat_electrolux_9kg_inside.jpg'),
(8, 'Máy giặt LG AI DD 10kg', 'Máy giặt thông minh với AI phân tích loại vải, công nghệ Inverter, tốc độ quay 1400 vòng/phút, lồng giặt 10kg.', 750, 'maygiat_lg_ai_dd_front.jpg', 'maygiat_lg_ai_dd_side.jpg', 'maygiat_lg_ai_dd_inside.jpg'),
(9, 'Tivi OLED Sony 55 inch', 'Tivi OLED 4K Ultra HD, kích thước 55 inch, hỗ trợ HDR, Dolby Vision, âm thanh vòm Dolby Atmos, hệ điều hành Android TV.', 1200, 'tivi_sony_55inch_front.jpg', 'tivi_sony_55inch_side.jpg', 'tivi_sony_55inch_back.jpg'),
(10, 'Tivi Samsung QLED 65 inch', 'Tivi QLED 8K Ultra HD, kích thước 65 inch, công nghệ Quantum HDR, tích hợp trợ lý ảo Bixby và Google Assistant.', 1500, 'tivi_samsung_65inch_front.jpg', 'tivi_samsung_65inch_side.jpg', 'tivi_samsung_65inch_back.jpg'),
(11, 'Điều hòa Daikin 12000 BTU Inverter', 'Điều hòa 1 chiều Inverter tiết kiệm điện, công suất 12000 BTU, chế độ làm lạnh nhanh, lọc không khí bằng ion plasma.', 500, 'dieuhoa_daikin_12000_front.jpg', 'dieuhoa_daikin_12000_side.jpg', 'dieuhoa_daikin_12000_remote.jpg'),
(12, 'Điều hòa Panasonic 18000 BTU Inverter', 'Điều hòa 2 chiều Inverter tiết kiệm điện, công suất 18000 BTU, chế độ làm lạnh nhanh, tích hợp công nghệ Nanoe-G lọc không khí.', 800, 'dieuhoa_panasonic_18000_front.jpg', 'dieuhoa_panasonic_18000_side.jpg', 'dieuhoa_panasonic_18000_remote.jpg'),
(13, 'Tủ lạnh Sharp 500L', 'Tủ lạnh 2 cửa, dung tích 500 lít, công nghệ làm lạnh nhanh, có ngăn đông riêng biệt, thiết kế hiện đại.', 700, 'tulanh_sharp_500l_front.jpg', 'tulanh_sharp_500l_inside.jpg', 'tulanh_sharp_500l_side.jpg'),
(14, 'Máy giặt Samsung 8kg', 'Máy giặt cửa trên, công nghệ Diamond Drum, chế độ giặt nhanh 15 phút, lồng giặt 8kg, tiết kiệm năng lượng.', 400, 'maygiat_samsung_8kg_front.jpg', 'maygiat_samsung_8kg_side.jpg', 'maygiat_samsung_8kg_inside.jpg'),
(15, 'Máy giặt Whirlpool 7kg', 'Máy giặt cửa trên, công nghệ 6th Sense, chế độ giặt tự động, lồng giặt 7kg, hiệu quả giặt sạch tối ưu.', 350, 'maygiat_whirlpool_7kg_front.jpg', 'maygiat_whirlpool_7kg_side.jpg', 'maygiat_whirlpool_7kg_inside.jpg'),
(16, 'Tivi LG NanoCell 65 inch', 'Tivi NanoCell 4K, kích thước 65 inch, công nghệ NanoCell cho màu sắc rực rỡ, tích hợp AI ThinQ, Dolby Vision.', 1400, 'tivi_lg_nanocell_65inch_front.jpg', 'tivi_lg_nanocell_65inch_side.jpg', 'tivi_lg_nanocell_65inch_back.jpg'),
(17, 'Tivi Toshiba 50 inch', 'Tivi LED Full HD, kích thước 50 inch, hỗ trợ HDR, Dolby Audio, thiết kế mỏng, kết nối đa dạng.', 600, 'tivi_toshiba_50inch_front.jpg', 'tivi_toshiba_50inch_side.jpg', 'tivi_toshiba_50inch_back.jpg'),
(18, 'Điều hòa LG 9000 BTU Inverter', 'Điều hòa 1 chiều Inverter, công suất 9000 BTU, chế độ làm lạnh nhanh, tiết kiệm điện, hoạt động êm ái.', 450, 'dieuhoa_lg_9000_front.jpg', 'dieuhoa_lg_9000_side.jpg', 'dieuhoa_lg_9000_remote.jpg'),
(19, 'Điều hòa Mitsubishi 24000 BTU Inverter', 'Điều hòa 2 chiều Inverter, công suất 24000 BTU, chế độ làm lạnh và sưởi ấm, công nghệ lọc không khí.', 950, 'dieuhoa_mitsubishi_24000_front.jpg', 'dieuhoa_mitsubishi_24000_side.jpg', 'dieuhoa_mitsubishi_24000_remote.jpg'),
(20, 'Lò vi sóng Sharp 23L', 'Lò vi sóng dung tích 23 lít, chế độ nướng và vi sóng, thiết kế nhỏ gọn, dễ sử dụng, công suất 800W.', 150, 'lovisong_sharp_23l_front.jpg', 'lovisong_sharp_23l_side.jpg', 'lovisong_sharp_23l_inside.jpg'),
(21, 'Lò vi sóng Samsung 32L', 'Lò vi sóng dung tích 32 lít, chế độ nướng và vi sóng, thiết kế hiện đại, công suất 1000W, có chức năng rã đông.', 220, 'lovisong_samsung_32l_front.jpg', 'lovisong_samsung_32l_side.jpg', 'lovisong_samsung_32l_inside.jpg'),
(22, 'Nồi cơm điện Panasonic 1.8L', 'Nồi cơm điện dung tích 1.8 lít, chế độ nấu cơm và giữ ấm, thiết kế sang trọng, công suất 1000W.', 80, 'noicom_dien_panasonic_18l_front.jpg', 'noicom_dien_panasonic_18l_side.jpg', 'noicom_dien_panasonic_18l_inside.jpg'),
(23, 'Nồi cơm điện Cuckoo 1.5L', 'Nồi cơm điện dung tích 1.5 lít, công nghệ nấu nhanh, chức năng giữ ấm và hẹn giờ, thiết kế nhỏ gọn.', 90, 'noicom_dien_cuckoo_15l_front.jpg', 'noicom_dien_cuckoo_15l_side.jpg', 'noicom_dien_cuckoo_15l_inside.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'thongdbb', 'Huythong0932@gmail.com', '011c945f30ce2cbafc452f39840f025693339c42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
