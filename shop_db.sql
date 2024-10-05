-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 05, 2024 lúc 11:44 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

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
(2, 'admin', '40bd001563085fc35165329ea1ff5c5ecbdbbeef');

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

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(36, 4, 1, 'Apple iPhone 14 Pro', 1200000, 1, 'iPhone-14-thumb-tim-1-600x600.jpg'),
(37, 4, 5, 'Tủ lạnh LG Inverter 600L', 9000000, 1, 'tu-lanh-lg-gv-b212wb-1-600x600.jpg');

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
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `transId` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`, `transId`) VALUES
(17, 4, 'Thong Thong Thong', '7675674564', 'xobas66544@albarulo.com', 'cod', 'Số nhà 6eer, 435435, ưererr, rr, Vietnam - 456001', 'Samsung Galaxy S23 Ultra (1300000 x 1) - Apple iPhone 14 Pro (1200000 x 1) - ', 2500000, '2024-10-05', 'completed', NULL),
(18, 4, 'Thong Thong Thong', '7675674564', 'xobas66544@albarulo.com', 'cod', 'Số nhà 6eer, 435435, ưererr, rr, Vietnam - 456001', 'Tủ lạnh LG Inverter 600L (9000000 x 1) - Máy giặt LG AI DD 10kg (750000 x 1) - ', 9750000, '2024-10-05', 'completed', NULL),
(19, 4, 'Thong Thong Thong', '7675674564', 'xobas66544@albarulo.com', 'cod', 'Số nhà 6eer, 435435, ưererr, rr, Vietnam - 456001', 'Samsung Galaxy S23 Ultra (1300000 x 1) - ', 1300000, '2024-10-05', 'completed', NULL);

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
  `image_03` varchar(100) NOT NULL,
  `name_no_accent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `price`, `image_01`, `image_02`, `image_03`, `name_no_accent`) VALUES
(1, 'Apple iPhone 14 Pro', 'Màn hình 6.1 inch, Super Retina XDR, Chip A16 Bionic, 48MP Camera chính, Pin 3200mAh, Face ID, 5G, IP68 chống nước.', 1200000, 'iPhone-14-thumb-tim-1-600x600.jpg', 'iPhone-14-plus-thumb-xanh-1-600x600.jpg', 'iPhone-14-thumb-tim-1-600x600.jpg', 'Apple iPhone 14 Pro'),
(2, 'Samsung Galaxy S23 Ultra', 'Màn hình 6.8 inch, Dynamic AMOLED 2X, Chip Snapdragon 8 Gen 2, Camera chính 200MP, Pin 5000mAh, hỗ trợ bút S-Pen, IP68.', 1300000, 'home-img-1.png', 'iPhone-14-thumb-tim-1-600x600.jpg', 'iPhone-14-plus-thumb-xanh-1-600x600.jpg', 'Samsung Galaxy S23 Ultra'),
(5, 'Tủ lạnh LG Inverter 600L', 'Dung tích 600 lít, công nghệ làm lạnh Inverter tiết kiệm điện, ngăn đá trên, công nghệ khử mùi Nano Carbon.', 9000000, 'tu-lanh-lg-gv-b212wb-1-600x600.jpg', 'product-319618-011223-045624-600x600.jpg', 'tu-lanh-lg-d22mb_1620557793.jpg', 'Tu lanh LG Inverter 600L'),
(6, 'Tủ lạnh Samsung Family Hub 680L', 'Tủ lạnh thông minh với màn hình cảm ứng Family Hub, dung tích 680 lít, công nghệ làm lạnh đa chiều, thiết kế 4 cửa hiện đại.', 250000, 'tu-lanh-lg-gv-b212wb-1-600x600.jpg', 'product-319618-011223-045624-600x600.jpg', 'tu-lanh-lg-d22mb_1620557793.jpg', 'Tu lanh Samsung Family Hub 680L'),
(7, 'Máy giặt Electrolux 9kg', 'Máy giặt cửa trước, công nghệ Inverter, chế độ giặt hơi nước diệt khuẩn, lồng giặt 9kg, tốc độ quay 1200 vòng/phút.', 600000, 'may-git-lng-ngang-coex-inverter-85kg-fw---80cw1408igb_728eda25.png', 'may-giat-aqua-inverter-9-5-kg-aqd-a952j-bk-0-600x600.jpg', '10055164-may-giat-electrolux-inverter-ewf1024m3sb-1.jpg', 'May giat Electrolux 9kg'),
(8, 'Máy giặt LG AI DD 10kg', 'Máy giặt thông minh với AI phân tích loại vải, công nghệ Inverter, tốc độ quay 1400 vòng/phút, lồng giặt 10kg.', 750000, 'may-giat-aqua-inverter-9-5-kg-aqd-a952j-bk-0-600x600.jpg', 'may-git-lng-ngang-coex-inverter-85kg-fw---80cw1408igb_728eda25.png', '10055164-may-giat-electrolux-inverter-ewf1024m3sb-1.jpg', 'May giat LG AI DD 10kg'),
(9, 'Tivi OLED Sony 55 inch', 'Tivi OLED 4K Ultra HD, kích thước 55 inch, hỗ trợ HDR, Dolby Vision, âm thanh vòm Dolby Atmos, hệ điều hành Android TV.', 12000000, 'smart-tivi-32-inch-darling-32hd959t2.jpg', '1691806865786-tivi-xiaomi-a-series-43-inch-8.jpg', 'smart-tivi-darling-32hd946t2.jpg', 'Tivi OLED Sony 55 inch'),
(11, 'Điều hòa Daikin 12000 BTU Inverter', 'Điều hòa 1 chiều Inverter tiết kiệm điện, công suất 12000 BTU, chế độ làm lạnh nhanh, lọc không khí bằng ion plasma.', 50000000, 'dieu-hoa-sanaky-snk-12icmf.webp', 'dieu-hoa-treo-tuong-daikin-1.jpg', 'dieu-hoa-1-chieu-panasonic-n12wkh-8m_1582359815.jpg', 'ieu hoa Daikin 12000 BTU Inverter'),
(12, 'Điều hòa Panasonic 18000 BTU Inverter', 'Điều hòa 2 chiều Inverter tiết kiệm điện, công suất 18000 BTU, chế độ làm lạnh nhanh, tích hợp công nghệ Nanoe-G lọc không khí.', 150000000, 'dieu-hoa-treo-tuong-daikin-1.jpg', 'dieu-hoa-sanaky-snk-12icmf.webp', 'dieu-hoa-1-chieu-panasonic-n12wkh-8m_1582359815.jpg', 'ieu hoa Panasonic 18000 BTU Inverter'),
(17, 'Tivi Toshiba 50 inch', 'Tivi LED Full HD, kích thước 50 inch, hỗ trợ HDR, Dolby Audio, thiết kế mỏng, kết nối đa dạng.', 6000000, '1691806865786-tivi-xiaomi-a-series-43-inch-8.jpg', 'smart-tivi-32-inch-darling-32hd959t2.jpg', 'smart-tivi-darling-32hd946t2.jpg', 'Tivi Toshiba 50 inch'),
(18, 'Điều hòa LG 9000 BTU Inverter', 'Điều hòa 1 chiều Inverter, công suất 9000 BTU, chế độ làm lạnh nhanh, tiết kiệm điện, hoạt động êm ái.', 45000000, 'dieu-hoa-1-chieu-panasonic-n12wkh-8m_1582359815.jpg', 'dieu-hoa-sanaky-snk-12icmf.webp', 'dieu-hoa-treo-tuong-daikin-1.jpg', 'ieu hoa LG 9000 BTU Inverter'),
(20, 'Lò vi sóng Sharp 23L', 'Lò vi sóng dung tích 23 lít, chế độ nướng và vi sóng, thiết kế nhỏ gọn, dễ sử dụng, công suất 800W.', 15000000, 'lovisongconuong20lrolerrm3223h.webp', 'sharp-r-205vn-s-20-lit-020223-111427-600x600.jpg', 'naxf0a.webp', 'Lo vi song Sharp 23L'),
(21, 'Lò vi sóng Samsung 32L', 'Lò vi sóng dung tích 32 lít, chế độ nướng và vi sóng, thiết kế hiện đại, công suất 1000W, có chức năng rã đông.', 2200000, 'naxf0a.webp', 'sharp-r-205vn-s-20-lit-020223-111427-600x600.jpg', 'lovisongconuong20lrolerrm3223h.webp', 'Lo vi song Samsung 32L');

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
(1, 'thongdbb', 'Huythong0932@gmail.com', '011c945f30ce2cbafc452f39840f025693339c42'),
(3, 'Duy', 'Huythong044932@gmail.com', '8cb2237d0679ca88db6464eac60da96345513964'),
(4, 'user', 'xobas66544@albarulo.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef');

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
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
