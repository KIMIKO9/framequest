-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql205.infinityfree.com
-- Generation Time: Jun 02, 2024 at 02:08 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_36535538_framequest`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_image`) VALUES
(1, 'Landscape', 'uploads/landscape111.jpg'),
(2, 'Portrait', 'uploads/portrait1.jpg'),
(3, 'Wildlife', 'uploads/animals1.jpg'),
(4, 'Street Photography', 'uploads/street1.jpg'),
(5, 'Architecture', 'uploads/architecture.jpg'),
(6, 'Macro', 'uploads/macro1.jpg'),
(7, 'Travel', 'uploads/travel1.jpg'),
(8, 'Fashion', 'uploads/fashion1.jpg'),
(9, 'Food', 'uploads/food1.jpg'),
(10, 'Documentary', 'uploads/documentary1.jpg'),
(11, 'Night Photography', 'uploads/night1.jpg'),
(12, 'Black and White', 'uploads/baw1.jpg'),
(13, 'Sports', 'uploads/sports1.jpg'),
(14, 'Abstract', 'uploads/art1.jpg'),
(15, 'Still Life', 'uploads/stilllife1.jpg'),
(16, 'Vehicle', 'uploads/car1.jpg'),
(17, 'Wedding', 'uploads/wedding.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_name`) VALUES
(1, 'Riga'),
(2, 'Daugavpils'),
(3, 'Liepaja'),
(4, 'Jelgava'),
(5, 'Jurmala'),
(6, 'Ventspils'),
(7, 'Rezekne'),
(8, 'Valmiera'),
(9, 'Ogre'),
(10, 'Tukums'),
(11, 'Cesis'),
(12, 'Salaspils'),
(13, 'Kuldiga'),
(14, 'Sigulda'),
(15, 'Madona');

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `picture_name` varchar(255) NOT NULL,
  `picture_path` varchar(255) NOT NULL,
  `upload_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`id`, `username`, `picture_name`, `picture_path`, `upload_timestamp`, `category_id`) VALUES
(120, 'MelÄnija37', '', 'uploads/Screenshot_3.jpg', '2024-06-02 12:00:56', 1),
(122, 'BalticShutter', '', 'uploads/nikita-kachanovsky-6loxuv3aXkg-unsplash (1).jpg', '2024-06-02 12:13:06', 1),
(123, 'BalticShutter', '', 'uploads/krisjanis-kazaks-uNCxSjpmJl0-unsplash (1).jpg', '2024-06-02 12:13:13', 1),
(124, 'MelÄnija37', '', 'uploads/Screenshot_6.jpg', '2024-06-02 12:14:04', 1),
(125, 'MelÄnija37', '', 'uploads/Screenshot_4.jpg', '2024-06-02 12:14:28', 6),
(126, 'MelÄnija37', '', 'uploads/Screenshot_8.jpg', '2024-06-02 12:15:03', 2),
(127, 'BalticShutter', '', 'uploads/elvis-bekmanis-jYSsBkVezS4-unsplash (1).jpg', '2024-06-02 12:16:06', 1),
(128, 'BalticShutter', '', 'uploads/inga-gaile-fuKafsW8rH4-unsplash (1).jpg', '2024-06-02 12:16:59', 1),
(129, 'BalticShutter', '', 'uploads/kreit-opQWdcJfNis-unsplash (1).jpg', '2024-06-02 12:18:40', 16),
(130, 'BalticShutter', '', 'uploads/kreit-fgMp2gSdAsg-unsplash (1).jpg', '2024-06-02 12:18:51', 16),
(131, 'BalticShutter', '', 'uploads/Screenshot_17.jpg', '2024-06-02 12:20:41', 14),
(132, 'MelÄnija37', '', 'uploads/Screenshot_11.jpg', '2024-06-02 12:21:34', 3),
(134, 'MelÄnija37', '', 'uploads/Screenshot_12.jpg', '2024-06-02 12:21:55', 3),
(135, 'MelÄnija37', '', 'uploads/Screenshot_14.jpg', '2024-06-02 12:24:40', 3),
(136, 'MelÄnija37', '', 'uploads/Screenshot_18.jpg', '2024-06-02 12:25:59', 1),
(138, 'MelÄnija37', '', 'uploads/Screenshot_19.jpg', '2024-06-02 12:27:39', 15),
(139, 'ElÄ«na7877', '', 'uploads/ron-fung-VQJXJ4IaU_o-unsplash (1).jpg', '2024-06-02 12:52:19', 3),
(140, 'ElÄ«na7877', '', 'uploads/photos-by-beks-B3fLaAAy6nU-unsplash (1).jpg', '2024-06-02 12:52:26', 3),
(141, 'ElÄ«na7877', '', 'uploads/nico-cavallini-Ua8dhbsK-VA-unsplash (1).jpg', '2024-06-02 12:52:36', 6),
(142, 'ElÄ«na7877', '', 'uploads/george-potter--m9lUea7Nq4-unsplash (1).jpg', '2024-06-02 12:52:55', 3),
(143, 'ElÄ«na7877', '', 'uploads/gary-bendig-6GMq7AGxNbE-unsplash (1).jpg', '2024-06-02 12:53:09', 6),
(144, 'ElÄ«na7877', '', 'uploads/afra-ramio-cp2HCVzguw4-unsplash (1).jpg', '2024-06-02 12:53:19', 3);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `photographer_id` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `client_id`, `photographer_id`, `review_text`, `created_at`) VALUES
(12, 78, 77, 'I couldnâ€™t be more impressed with her work.The ability to capture the essence and beauty of animals in their natural habitats is simply extraordinary.', '2024-06-02 14:27:55'),
(13, 79, 76, 'I recently had the opportunity to work with JÄnis KalniÅ†Å¡ on a photoshoot featuring classic cars, and the experience was exceptional. His keen eye for detail and ability to play with lighting and angles resulted in breathtaking images that exceeded my expectations.', '2024-06-02 14:31:11'),
(14, 80, 77, 'Working with ElÄ«na was a fantastic experience. Her dedication and skill are top-notch.', '2024-06-02 14:42:40');

-- --------------------------------------------------------

--
-- Table structure for table `saved_profiles`
--

CREATE TABLE `saved_profiles` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `photographer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `saved_profiles`
--

INSERT INTO `saved_profiles` (`id`, `client_id`, `photographer_id`) VALUES
(10, 78, 77),
(11, 79, 76),
(12, 80, 77);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `userbiography` text DEFAULT NULL,
  `userlocation` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('photographer','client') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `tiktok` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firstname`, `lastname`, `userbiography`, `userlocation`, `email`, `password`, `role`, `phone`, `profile_photo`, `instagram`, `facebook`, `tiktok`) VALUES
(73, 'Anastasija', 'Anastasija', 'Ozola', NULL, NULL, 'marijalauma@gmail.com', '$2y$10$kSlhnvg.NKRY4PrPltEq/u8Tu/j1xJjzBQcpxwIMw/Soggr4AJMsm', 'photographer', '20095628', NULL, NULL, NULL, NULL),
(74, 'Client', 'Client', 'Client', NULL, NULL, 'client@gmail.com', '$2y$10$rmWLkdqMlWZ/ZC5.7e6vS.t6B3bUE7cLHICJV/gcPFE5uebUhk7Vm', 'client', NULL, NULL, NULL, NULL, NULL),
(75, 'MelÄnija37', 'MelÄnija', 'MiÄ·elsone', 'I enjoy capturing the beauty of nature through my photography, transforming those moments into powerful emotions that speak through each image.', 'Cesis', 'melanijamikelsone@gmail.com', '$2y$10$7MAlWgxU/T.g1XWSmvqRQepd4B3NHZw3G2FBHDQP51Z9BLub9P11a', 'photographer', NULL, 'Screenshot_16.jpg', NULL, NULL, NULL),
(76, 'BalticShutter', 'JÄnis', 'KalniÅ†Å¡', 'Seasoned photographer, with over a decade of experience capturing the essence of the Baltic landscapes and urban scenes. Featured in numerous international exhibitions and magazines. I find inspiration in the interplay of natural light and urban geometry.', 'Riga', 'janis.kalnins@gmail.com', '$2y$10$v1eJs1CeEBiHwI2Qk4VYNOOID8smgzr48QiwjqAtkq6QXzIcNQyeK', 'photographer', '+371 29123456', 'guilherme-stecanella-R5BW2qgV5I8-unsplash (1).jpg', 'instagram.com/@janiskalnins', '', 'tiktok.com/@janiskalnins'),
(77, 'ElÄ«na7877', 'ElÄ«na', 'PrÅ«se', 'I specialize in wildlife photography. My work is renowned for its breathtaking portrayal of animals', 'Valmiera', 'elina.pruse@gmail.com', '$2y$10$XkkKjypJShTosuAnev1WJu16TjuGvm3IdQcV5CJmRiLGtr.aExtQK', 'photographer', NULL, 'andre-furtado-JtV6zyOZSrA-unsplash.jpg', 'instagram.com', 'facebook.com', 'tiktok.com'),
(78, 'NatureLover123', 'Laura', 'VÄ«tola', NULL, NULL, 'laura.vitola@gmail.com', '$2y$10$8UPtM1kEvva8FPSToX3S4.yTPcu2aRg.CwJgU2HlZ3cQh4E5zHEtG', 'client', '+371 29234567', 'sander-sammy-2ITD0pQtYzs-unsplash.jpg', NULL, NULL, NULL),
(79, 'AdventureTraveler', 'Rihards', 'SiliÅ†Å¡', NULL, NULL, 'rihards.silins@gmail.com', '$2y$10$FZKrI74rRWkDB/4es2JaLeAC4kDl18Uvq/QS/Byd6aKsJGTTQvYKu', 'client', NULL, 'alfred-kenneally-Jj1FTzIFS9Q-unsplash.jpg', NULL, NULL, NULL),
(80, 'TravelPhotobooker', 'Anna', 'Liepa', NULL, NULL, 'anna.liepa@gmail.com', '$2y$10$NyG9MKNPjwuML6/GlWabSuiz3GKtYMnke5evQT8hOYNnx7yNcEVlm', 'client', NULL, 'andriyko-podilnyk-OavO7GaUruk-unsplash.jpg', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `photographer_id` (`photographer_id`);

--
-- Indexes for table `saved_profiles`
--
ALTER TABLE `saved_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_saved_profiles` (`client_id`,`photographer_id`),
  ADD KEY `photographer_id` (`photographer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `saved_profiles`
--
ALTER TABLE `saved_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `pictures_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`),
  ADD CONSTRAINT `pictures_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`photographer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `saved_profiles`
--
ALTER TABLE `saved_profiles`
  ADD CONSTRAINT `saved_profiles_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `saved_profiles_ibfk_2` FOREIGN KEY (`photographer_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
