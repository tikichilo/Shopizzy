-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 03:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopizzy`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `fullname`, `username`, `email`, `password`) VALUES
(1, 'Tiki Chilomo', 'chilomotiki@gmail.com', 'chilomotiki@gmail.com', '$2y$10$BsM4.sbLxdNRmC2t1hYx2.M2IZ5TCkp8b6FyizCIdlqnfsbp/CffK'),
(2, 'maxwell zulu', 'max20', 'zmaxwell692@gmail.com', '$2y$10$OUMLcMMh.uEVDOPWYN6DdeQzXkn7av50s4BD/REjKDwiVmK.ASLB2'),
(4, 'maxwell zulu', 'max', 'maxwellzulu@gmail.com', '$2y$10$z4fyOaIN77FB5Z8HaKWmzO1l5Z5iDqZUFLnkrRcOJzulDH6mCq86e');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `image` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_name`, `price`, `quantity`, `image`, `created_at`, `updated_at`) VALUES
(337, 2, 'iPhone 1 Pro Max', 9132.00, 1, 'https://bestpricegh.com/cdn/shop/products/12-Pro-1_36a18eff-9d2d-4100-9ca6-744ea1f2ecac_2048x.jpg?v=1606462528', '2025-05-06 23:01:15', '2025-05-06 23:01:15'),
(338, 2, 'Samsung Galaxy Buds2 Pro', 556.00, 1, 'https://images.samsung.com/latin_en/galaxy-buds2-pro/feature/galaxy-buds2-pro-kv-mo.jpg', '2025-05-06 23:01:15', '2025-05-06 23:01:15');

-- --------------------------------------------------------

--
-- Table structure for table `cart_tracking`
--

CREATE TABLE `cart_tracking` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` enum('abandoned','recovered') DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_tracking`
--

INSERT INTO `cart_tracking` (`id`, `user_id`, `action`, `timestamp`) VALUES
(6, 0, 'abandoned', '2025-05-11 04:10:32'),
(7, 0, 'abandoned', '2025-05-12 10:00:01'),
(8, 0, 'abandoned', '2025-05-12 10:13:32'),
(9, 0, 'recovered', '2025-05-12 21:50:33'),
(10, 0, 'abandoned', '2025-05-12 21:51:37'),
(11, 0, 'recovered', '2025-05-13 20:47:59'),
(12, 0, 'abandoned', '2025-05-15 04:54:11'),
(13, 0, 'abandoned', '2025-05-15 04:54:13'),
(14, 0, 'abandoned', '2025-05-15 05:36:35'),
(15, 0, 'recovered', '2025-05-16 20:55:17'),
(16, 0, 'recovered', '2025-05-16 21:43:55'),
(17, 0, 'recovered', '2025-05-16 21:44:05'),
(18, 0, 'recovered', '2025-05-16 21:44:35'),
(19, 0, 'recovered', '2025-05-16 21:44:45'),
(20, 0, 'recovered', '2025-05-16 21:56:07'),
(21, 0, 'recovered', '2025-05-16 21:56:54'),
(22, 0, 'recovered', '2025-05-16 21:58:08'),
(23, 0, 'recovered', '2025-05-16 22:03:38'),
(24, 0, 'recovered', '2025-05-17 05:12:15'),
(25, 0, 'recovered', '2025-05-17 05:59:18'),
(26, 0, 'recovered', '2025-05-17 06:08:09'),
(27, 0, 'recovered', '2025-05-17 06:12:52'),
(28, 0, 'recovered', '2025-05-17 08:27:29'),
(29, 0, 'recovered', '2025-05-17 08:32:01'),
(30, 0, 'recovered', '2025-05-17 08:38:12'),
(31, 0, 'recovered', '2025-05-17 08:40:53'),
(32, 0, 'recovered', '2025-05-17 08:42:51'),
(33, 0, 'recovered', '2025-05-17 08:48:37');

-- --------------------------------------------------------

--
-- Table structure for table `clothes`
--

CREATE TABLE `clothes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `material` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clothes`
--

INSERT INTO `clothes` (`id`, `name`, `price`, `image`, `size`, `color`, `material`, `created_at`, `views`) VALUES
(1, 'Nike Hoodie', 49.99, 'images/nike.jpg', 'L', 'Black', 'Cotton', '2025-04-29 22:10:59', 0),
(2, 'Adidas T-Shirt', 29.99, 'images/addidas.jpg', 'M', 'White', 'Polyester', '2025-04-29 22:10:59', 0),
(6, 'Air Max Plus', 240.00, 'https://www.asphaltgold.com/cdn/shop/files/sv4Mnf9kQnYSjtgS4H4T_604133-050-nike-air-max-plus-black-black-black-sm-2_768x768_crop_center.jpg?v=1736262781', '39-46', 'Black', 'textile, mesh, rubber', '2025-04-29 22:10:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `electronics`
--

CREATE TABLE `electronics` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `warranty` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `electronics`
--

INSERT INTO `electronics` (`id`, `name`, `price`, `image`, `brand`, `warranty`, `created_at`, `views`) VALUES
(3, 'Samsung Galaxy Buds2 Pro', 556.00, 'https://images.samsung.com/latin_en/galaxy-buds2-pro/feature/galaxy-buds2-pro-kv-mo.jpg', 'samsung', '4 months', '2025-04-29 22:10:59', 0),
(4, 'Air buds 2 pro', 147.99, 'https://www.cnet.com/a/img/resize/045a928accda0de6a6edf7903a4790f1359c0f03/hub/2023/09/26/cdecf7b6-069e-48bf-9046-133efae96eca/airpods-pro-2-usb-c-blue-background.jpg?auto=webp&fit=crop&height=900&width=1200', 'Apple', '4 months', '2025-04-29 22:10:59', 0),
(5, 'Hisense 55A6K UHD VIDAA Smart TV | Hisense SA', 540.45, 'https://hisense.co.za/wp-content/uploads/2024/07/55-A6K-TV-2023-v2.jpg', 'Hisense', '1 year', '2025-04-29 22:10:59', 0),
(6, 'Hisense 43\" Q6NAU 4K QLED Smart TV [2024] - JB Hi-Fi', 750.00, 'https://www.jbhifi.com.au/cdn/shop/files/747555-Product-0-I-638579131803224058.jpg?v=1724138420', 'Hisense', '1 year', '2025-04-29 22:10:59', 0),
(7, 'LG 75-inch 4K UHD Smart TV with webOS - 75UQ7070ZUD', 672.00, 'https://i5.walmartimages.com/asr/311923f1-5e09-475a-898f-5b16fe1037f4.139c0f897e7c0f3a026b011d8b8cbac1.jpeg', 'LG', '1 year', '2025-04-29 22:10:59', 0),
(8, '43\" LG Smart TV with webOS - 43LH604V | LG UK', 632.78, 'https://www.lg.com/content/dam/channel/wcms/uk/images/tvs/43LH604V_AEK_EEUK_UK_C/gallery/large01.jpg', 'LG', '1 year', '2025-04-29 22:10:59', 0),
(10, 'Samsung 65 inch 4K silver frame Elite black control Ultra HD', 720.60, 'https://www.hgspot.hr/image/catalog/slike/143196-878.jpg?v=1.1491763105', 'samsung', '1 year', '2025-04-29 22:10:59', 0),
(11, 'HP 15.6 FHD Laptop, Ryzen 5 5500U, 8GB RAM, 256GB', 13772.00, 'https://i5.walmartimages.com/seo/HP-15-6-Screen-FHD-Laptop-Computer-AMD-Ryzen-5-5500U-8GB-RAM-256GB-SSD-Spruce-Blue-Windows-11-Home-15-ef2729wm_8dee5689-db47-45ac-9a0d-5399c95a8ee0.ad15a381ad98aa369a68bfb1527d66a9.jpeg', 'Hp', '1 year', '2025-04-29 22:10:59', 0),
(12, 'HP Stream 14 Laptop - Intel Celeron N4000, 4GB RAM', 7028.44, 'https://images-cdn.ubuy.co.in/64c41e4771c5f52216163af1-hp-stream-14-laptop-intel-celeron.jpg', 'Hp', '1 year', '2025-04-29 22:10:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_number` varchar(100) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `others`
--

CREATE TABLE `others` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `others`
--

INSERT INTO `others` (`id`, `name`, `price`, `image`, `description`, `category`, `created_at`) VALUES
(1, 'Estate Cruiser X', 32367.00, 'https://ultrascooter.co.za/cdn/shop/files/1_16f6c43e-b2ed-43c4-b7a2-98456b57f8de.jpg?v=1716189842', ' 60V 3000W Dual Motor Lithium Electric Scooter', 'custom', '2025-05-14 16:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

CREATE TABLE `phones` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `specs` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phones`
--

INSERT INTO `phones` (`id`, `name`, `price`, `image`, `brand`, `specs`, `created_at`, `views`) VALUES
(5, 'Google pixel 9 pro', 32932.00, 'https://lh3.googleusercontent.com/acQl8sRHjdtWPU_rSYjtIOAcepMnXOceWbUmzQTc5TJxeXwCS7h_7DwijC-HY3OJkqzpEigbOBtqHOvRhfmE2p9boXMqpLJ1oQU=s0', 'google', '5G 128 256 512GB 6.3\"OLED 50MP TensorG4 4700mAh', '2025-05-03 21:54:54', 0),
(6, 'Google pixel 8 pro', 7030.87, 'https://images-cdn.ubuy.com.pr/656a973ee63519053c385947-google-pixel-8-pro-gc3ve-512gb-bay.jpg', 'google', '128gb 12gb Unlocked Black\r\n\r\n', '2025-05-03 21:58:22', 0),
(8, 'Samsung Galaxy S25 Ultra', 65966.00, 'https://m.media-amazon.com/images/I/71P85R392uL._SS400_.jpg', 'samsung', 'Titanium Silverblue, 12GB RAM, 1TB Storage), 200MP Camera, S Pen Included,', '2025-05-04 20:30:30', 0),
(9, 'Samsung Galaxy S24 Ultra', 57208.00, 'https://media.4rgos.it/i/Argos/3930335_R_Z001A?w=500&h=300', 'samsung', '12GB RAM, 512GB Storage, 200MP Camera, S Pen, Long Battery Life, Titanium Black', '2025-05-04 20:35:26', 0),
(10, 'Samsung Galaxy S23 Ultra', 35092.00, 'https://m.media-amazon.com/images/I/51qtXkmbvYL._AC_SL1011_.jpg', 'samsung', '5G 12GB 256GB - Phantom Black', '2025-05-04 20:38:21', 0),
(12, 'Iphone 16 pro max', 83922.21, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTByUrE9Wi8Tw_cI2MkbEAXfKVP3ftRf0AV-A&s', 'apple', '5G 1TB Black Titanium', '2025-05-04 20:48:13', 0),
(13, 'iPhone 13 Pro Max', 19695.00, 'https://i5.walmartimages.com/seo/Restored-Apple-iPhone-13-Pro-Max-256GB-Sierra-Blue-LTE-Cellular-MLJD3VC-A-Refurbished_1a8217bb-9dab-4eeb-8aae-9b8ded70c372.36c80b6f47a30a23ef90f990dbb5cec0.jpeg', 'apple', '256GB Sierra Blue LTE', '2025-05-04 20:50:54', 0),
(14, 'iPhone 1 Pro Max', 9132.00, 'https://bestpricegh.com/cdn/shop/products/12-Pro-1_36a18eff-9d2d-4100-9ca6-744ea1f2ecac_2048x.jpg?v=1606462528', 'apple', '6.1″ display, Apple A14 Bionic chipset, 2815 mAh battery, 256 GB storage, 6 GB RAM', '2025-05-04 20:57:58', 0);

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_details` text NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `receipt_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `user_id`, `customer_name`, `address`, `email`, `phone`, `payment_method`, `payment_details`, `total`, `date`, `receipt_text`) VALUES
(1, 3, 'Tiki Chilomo', 'Off Teagles Road Makeni', 'chilomotiki@gmail.com', '0978918196', 'Mobile Money', 'Mobile Holder: Tiki chilomo, Number: 0978918196, Provider: airtel', 29.99, '2025-05-18 13:13:40', 'Adidas T-Shirt x1 = ZMW 29.99'),
(2, 3, 'Tiki Chilomo', 'Off Teagles Road Makeni', 'chilomotiki@gmail.com', '0978918196', 'Bank', 'Card Holder: tiki chilomo, Card Number: 4383756007050319, Expiry: 1227', 147.99, '2025-05-18 13:19:42', 'Air buds 2 pro x1 = ZMW 147.99'),
(3, 3, 'Tiki Chilomo', 'Off Teagles Road Makeni', 'chilomotiki@gmail.com', '0978918196', 'Mobile Money', 'Mobile Holder: Tiki chilomo, Number: 0978918196, Provider: airtel', 83922.21, '2025-05-18 13:23:41', 'Iphone 16 pro max x1 = ZMW 83,922.21');

-- --------------------------------------------------------

--
-- Table structure for table `recommended_products`
--

CREATE TABLE `recommended_products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recommended_products`
--

INSERT INTO `recommended_products` (`id`, `user_id`, `product_id`, `category`, `created_at`) VALUES
(50, 3, 8, 'phones', '2025-05-14 19:56:24'),
(51, 3, 3, 'electronics', '2025-05-14 19:56:24'),
(52, 3, 1, 'clothes', '2025-05-14 19:56:24'),
(53, 3, 1, 'others', '2025-05-14 19:56:25'),
(54, 3, 14, 'phones', '2025-05-14 20:05:43'),
(55, 3, 10, 'electronics', '2025-05-14 20:05:43'),
(56, 3, 6, 'clothes', '2025-05-14 20:05:44'),
(57, 3, 1, 'others', '2025-05-14 20:05:44'),
(58, 3, 6, 'phones', '2025-05-15 09:42:41'),
(59, 3, 7, 'electronics', '2025-05-15 09:42:42'),
(60, 3, 2, 'clothes', '2025-05-15 09:42:42'),
(61, 3, 1, 'others', '2025-05-15 09:42:43'),
(62, 3, 8, 'phones', '2025-05-16 10:19:20'),
(63, 3, 3, 'electronics', '2025-05-16 10:19:21'),
(64, 3, 6, 'clothes', '2025-05-16 10:19:22'),
(65, 3, 1, 'others', '2025-05-16 10:19:22'),
(66, 3, 12, 'phones', '2025-05-16 11:13:59'),
(67, 3, 12, 'electronics', '2025-05-16 11:13:59'),
(68, 3, 2, 'clothes', '2025-05-16 11:14:00'),
(69, 3, 1, 'others', '2025-05-16 11:14:00'),
(70, 3, 13, 'phones', '2025-05-16 11:26:42'),
(71, 3, 7, 'electronics', '2025-05-16 11:26:42'),
(72, 3, 1, 'clothes', '2025-05-16 11:26:42'),
(73, 3, 1, 'others', '2025-05-16 11:26:43'),
(74, 3, 6, 'phones', '2025-05-16 18:41:56'),
(75, 3, 10, 'electronics', '2025-05-16 18:41:57'),
(76, 3, 1, 'clothes', '2025-05-16 18:41:57'),
(77, 3, 1, 'others', '2025-05-16 18:41:58'),
(78, 3, 9, 'phones', '2025-05-16 19:42:17'),
(79, 3, 6, 'electronics', '2025-05-16 19:42:17'),
(80, 3, 2, 'clothes', '2025-05-16 19:42:17'),
(81, 3, 1, 'others', '2025-05-16 19:42:18'),
(82, 3, 10, 'phones', '2025-05-16 21:56:58'),
(83, 3, 5, 'electronics', '2025-05-16 21:56:58'),
(84, 3, 1, 'clothes', '2025-05-16 21:56:59'),
(85, 3, 1, 'others', '2025-05-16 21:57:00'),
(86, 3, 5, 'phones', '2025-05-16 22:08:04'),
(87, 3, 8, 'electronics', '2025-05-16 22:08:04'),
(88, 3, 2, 'clothes', '2025-05-16 22:08:06'),
(89, 3, 1, 'others', '2025-05-16 22:08:06'),
(90, 3, 14, 'phones', '2025-05-17 14:45:15'),
(91, 3, 12, 'electronics', '2025-05-17 14:45:16'),
(92, 3, 6, 'clothes', '2025-05-17 14:45:16'),
(93, 3, 1, 'others', '2025-05-17 14:45:17'),
(94, 3, 8, 'phones', '2025-05-17 14:45:57'),
(95, 3, 7, 'electronics', '2025-05-17 14:45:57'),
(96, 3, 2, 'clothes', '2025-05-17 14:45:57'),
(97, 3, 1, 'others', '2025-05-17 14:45:58'),
(98, 3, 13, 'phones', '2025-05-17 14:47:15'),
(99, 3, 11, 'electronics', '2025-05-17 14:47:15'),
(100, 3, 1, 'clothes', '2025-05-17 14:47:16'),
(101, 3, 1, 'others', '2025-05-17 14:47:17'),
(102, 3, 12, 'phones', '2025-05-17 15:36:17'),
(103, 3, 3, 'electronics', '2025-05-17 15:36:17'),
(104, 3, 6, 'clothes', '2025-05-17 15:36:17'),
(105, 3, 1, 'others', '2025-05-17 15:36:17'),
(106, 3, 13, 'phones', '2025-05-17 16:15:29'),
(107, 3, 4, 'electronics', '2025-05-17 16:15:30'),
(108, 3, 1, 'clothes', '2025-05-17 16:15:30'),
(109, 3, 1, 'others', '2025-05-17 16:15:31'),
(110, 3, 6, 'phones', '2025-05-17 17:01:20'),
(111, 3, 8, 'electronics', '2025-05-17 17:01:21'),
(112, 3, 6, 'clothes', '2025-05-17 17:01:21'),
(113, 3, 1, 'others', '2025-05-17 17:01:21'),
(114, 3, 13, 'phones', '2025-05-18 21:33:35'),
(115, 3, 6, 'electronics', '2025-05-18 21:33:36'),
(116, 3, 1, 'clothes', '2025-05-18 21:33:36'),
(117, 3, 1, 'others', '2025-05-18 21:33:37'),
(118, 3, 5, 'phones', '2025-05-18 21:47:32'),
(119, 3, 11, 'electronics', '2025-05-18 21:47:33'),
(120, 3, 6, 'clothes', '2025-05-18 21:47:33'),
(121, 3, 1, 'others', '2025-05-18 21:47:33'),
(122, 3, 14, 'phones', '2025-05-18 21:47:54'),
(123, 3, 4, 'electronics', '2025-05-18 21:47:55'),
(124, 3, 2, 'clothes', '2025-05-18 21:47:55'),
(125, 3, 1, 'others', '2025-05-18 21:47:55'),
(126, 3, 10, 'phones', '2025-05-18 21:49:43'),
(127, 3, 10, 'electronics', '2025-05-18 21:49:43'),
(128, 3, 6, 'clothes', '2025-05-18 21:49:44'),
(129, 3, 1, 'others', '2025-05-18 21:49:44'),
(130, 3, 9, 'phones', '2025-05-18 21:53:13'),
(131, 3, 8, 'electronics', '2025-05-18 21:53:13'),
(132, 3, 1, 'clothes', '2025-05-18 21:53:14'),
(133, 3, 1, 'others', '2025-05-18 21:53:14'),
(134, 3, 12, 'phones', '2025-05-18 21:53:41'),
(135, 3, 5, 'electronics', '2025-05-18 21:53:42'),
(136, 3, 6, 'clothes', '2025-05-18 21:53:42'),
(137, 3, 1, 'others', '2025-05-18 21:53:43'),
(138, 3, 6, 'phones', '2025-05-18 21:54:03'),
(139, 3, 3, 'electronics', '2025-05-18 21:54:03'),
(140, 3, 2, 'clothes', '2025-05-18 21:54:04'),
(141, 3, 1, 'others', '2025-05-18 21:54:04'),
(142, 3, 8, 'phones', '2025-05-19 10:29:29'),
(143, 3, 7, 'electronics', '2025-05-19 10:29:30'),
(144, 3, 1, 'clothes', '2025-05-19 10:29:30'),
(145, 3, 1, 'others', '2025-05-19 10:29:31'),
(146, 3, 13, 'phones', '2025-05-19 10:30:48'),
(147, 3, 12, 'electronics', '2025-05-19 10:30:48'),
(148, 3, 2, 'clothes', '2025-05-19 10:30:48'),
(149, 3, 1, 'others', '2025-05-19 10:30:48'),
(150, 3, 10, 'phones', '2025-05-19 11:15:44'),
(151, 3, 5, 'electronics', '2025-05-19 11:15:45'),
(152, 3, 6, 'clothes', '2025-05-19 11:15:47'),
(153, 3, 1, 'others', '2025-05-19 11:15:49'),
(154, 3, 10, 'phones', '2025-05-19 11:19:01'),
(155, 3, 6, 'electronics', '2025-05-19 11:19:02'),
(156, 3, 2, 'clothes', '2025-05-19 11:19:04'),
(157, 3, 1, 'others', '2025-05-19 11:19:05'),
(158, 3, 5, 'phones', '2025-05-19 12:32:37'),
(159, 3, 11, 'electronics', '2025-05-19 12:32:37'),
(160, 3, 6, 'clothes', '2025-05-19 12:32:37'),
(161, 3, 1, 'others', '2025-05-19 12:32:38');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `sale_date` datetime DEFAULT current_timestamp(),
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(2, 'john', 'johnmbewe003@gmail.com', '$2y$10$6ybYjM1ybKRdoeJQLcQWHe7z6rPukA5bf6a13IhE5xVcYnNfUsPJW'),
(3, 'tiki', 'chilomotiki@gmail.com', '$2y$10$lmRr0vH0ITfCIodZzUnKy.RyGdEz3f6NUlju6vzNc70e3N0U0uLl2'),
(4, 'tk', 'tk@gmail.com', '$2y$10$IO6cxKq0HFolplFCLFH4hu879J9y3g7/GYEAEukaQVGBaz.QZHz/S'),
(5, 'chilomotiki@gmail.com', 'zmaxwell692@gmail.com', '$2y$10$lMXj40mN4eLclVcIoA..6eZ57MvKpejXEogr8tZE.V9Ymtm/JOOoC'),
(9, 'max20', 'mz86053@students.cavensh.co.zm', '$2y$10$.E.mZuUTBRDZLyxGcAM46OUZsbX77r12qxo9NXUBPUaEOeSSy1.xy');

-- --------------------------------------------------------

--
-- Table structure for table `user_interactions`
--

CREATE TABLE `user_interactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `timestamp` datetime NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `material` varchar(50) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `specs` text DEFAULT NULL,
  `warranty` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `custom_category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_interactions`
--

INSERT INTO `user_interactions` (`id`, `user_id`, `product_id`, `category`, `action`, `timestamp`, `size`, `color`, `material`, `brand`, `specs`, `warranty`, `description`, `custom_category`) VALUES
(135, 3, 4, 'electronics', 'product_click', '2025-05-14 21:37:44', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_tracking`
--
ALTER TABLE `cart_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clothes`
--
ALTER TABLE `clothes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `electronics`
--
ALTER TABLE `electronics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `others`
--
ALTER TABLE `others`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phones`
--
ALTER TABLE `phones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recommended_products`
--
ALTER TABLE `recommended_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_interactions`
--
ALTER TABLE `user_interactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category` (`category`),
  ADD KEY `action` (`action`),
  ADD KEY `timestamp` (`timestamp`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=405;

--
-- AUTO_INCREMENT for table `cart_tracking`
--
ALTER TABLE `cart_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `clothes`
--
ALTER TABLE `clothes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `electronics`
--
ALTER TABLE `electronics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `others`
--
ALTER TABLE `others`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `phones`
--
ALTER TABLE `phones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recommended_products`
--
ALTER TABLE `recommended_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_interactions`
--
ALTER TABLE `user_interactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_interactions`
--
ALTER TABLE `user_interactions`
  ADD CONSTRAINT `user_interactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
