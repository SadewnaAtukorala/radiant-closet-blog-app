-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 22, 2026 at 09:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `radiant_closet_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `user_id`, `title`, `content`, `category`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Summer Fashion Trends 2026', 'Latest fashion ideas for summer.', '', NULL, '2026-08-05 19:30:50', '2026-08-05 19:30:50'),
(2, 2, 'My Winter  Fashion Essentials for 2026', 'Hello World', 'Fashion', 'blog_6a7ba49ca78e29.91735845.jpg', '2026-08-08 06:23:22', '2026-08-11 22:39:24'),
(6, 6, 'Autumn Fashion 2026\'', 'Autumn Collections\r\n', 'Fashion', NULL, '2026-08-08 08:33:47', '2026-08-12 10:00:07'),
(7, 7, 'Spring Fashion 2026', 'Spring Collections 2026', '', NULL, '2026-08-08 08:42:47', '2026-08-08 08:43:09'),
(9, 7, 'How to design your closet', 'Closet Details\r\n', '', NULL, '2026-08-08 08:44:20', '2026-08-08 08:44:20'),
(16, 2, '5 Ways to Style a White Shirt', '5 Stylish ways to dress a white shirt', 'Style', 'blog_6a7c35830cfce3.37447003.jpg', '2026-08-12 08:57:39', '2026-08-12 08:57:39');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`, `created_at`) VALUES
(13, 13, 'fec3c64be213f6f76eb37eb0bb6d52d279c0125e6c4ce18f28b342bfec7ff063', '2026-08-22 09:29:00', '2026-08-22 06:29:00'),
(20, 14, '211020da959d9c6eccf8a3aa2ef4d66fdf1afe953f76b4c9dea8a8cb7ad0401f', '2026-08-22 09:58:06', '2026-08-22 06:58:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'demo_user', 'demo@gmail.com', 'test123', 'user'),
(2, 'fashiongirl', 'fashiongirl@gmail.com', '$2y$10$1eTAD5LFSdYtqIfPAw.Zkuc62jrnfkzF9I0hxeeM5Q5v5GlHhsF9K', 'user'),
(6, 'fashion3', 'fashion3@gmail.com', '$2y$10$V9p92rNNEoFj8LeJf.Ip3OptOU35kiYss5pb0KkCj07XjjPYm1YVe', 'user'),
(7, 'fashion4', 'fashion4@gmail.com', '$2y$10$f91z/euald5jrhP/uku9Y.KJV0VgdgaQCsRIxLPcCMNAYyNq1OnCC', 'user'),
(9, 'testuser', 'testuser@gmail.com', '$2y$10$54zLo4ItHkfC037rlsG0QOdwuRpedd50RUfnX7kTR3GjfJExDVDTC', 'user'),
(13, 'sade', 'ssde96033@gmail.com', '$2y$10$MltUgB7FkdDTGAEFo09ypOeQovU4jlglliP4xpv2keF/4wkPVkPJC', 'user'),
(14, 'ssde', 'snn8572@gmail.com', '$2y$10$SVbNYjryGLu2seg159vsA.nYaJLZc0SQ6ldES1zYDtPr/iucKDFxq', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
