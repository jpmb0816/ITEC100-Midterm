-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2021 at 08:30 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itec100`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(30) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `date_added`) VALUES
(1, 1, 'reset password', '2021-04-24 06:56:20'),
(2, 1, 'login', '2021-05-02 05:55:50'),
(3, 1, 'logout', '2021-05-02 05:56:52'),
(4, 1, 'forgot password', '2021-05-02 05:57:06'),
(5, 1, 'reset password', '2021-05-02 05:57:19'),
(6, 1, 'login', '2021-05-02 06:01:39'),
(7, 1, 'logout', '2021-05-02 06:01:41'),
(8, 1, 'forgot password', '2021-05-02 06:01:43'),
(9, 1, 'reset password', '2021-05-02 06:01:58'),
(10, 1, 'forgot password', '2021-05-02 06:02:31'),
(11, 1, 'reset password', '2021-05-02 06:02:43'),
(12, 1, 'login', '2021-05-02 06:11:36'),
(13, 1, 'logout', '2021-05-02 06:11:37'),
(14, 2, 'forgot password', '2021-05-02 06:29:47'),
(15, 2, 'reset password', '2021-05-02 06:29:52');

-- --------------------------------------------------------

--
-- Table structure for table `authentication_code`
--

CREATE TABLE `authentication_code` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `expiration` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `authentication_code`
--

INSERT INTO `authentication_code` (`id`, `user_id`, `code`, `created_at`, `expiration`) VALUES
(1, 1, 430438, '2021-04-21 16:10:51', '2021-04-21 16:15:51'),
(2, 1, 216087, '2021-04-21 16:11:50', '2021-04-21 16:16:50'),
(3, 1, 312604, '2021-04-21 16:15:12', '2021-04-21 16:20:12'),
(4, 1, 137418, '2021-04-21 16:25:22', '2021-04-21 16:30:22'),
(5, 1, 719749, '2021-04-21 19:07:47', '2021-04-21 19:12:47'),
(6, 1, 167525, '2021-04-21 19:16:07', '2021-04-21 19:21:07'),
(7, 1, 162143, '2021-04-24 14:41:07', '2021-04-24 14:46:07'),
(8, 1, 685130, '2021-04-24 14:42:57', '2021-04-24 14:47:57'),
(9, 1, 715625, '2021-04-24 14:44:57', '2021-04-24 14:49:57'),
(10, 1, 289008, '2021-04-24 14:47:39', '2021-04-24 14:52:39'),
(11, 1, 109813, '2021-04-24 14:51:36', '2021-04-24 14:56:36'),
(12, 1, 882014, '2021-04-24 14:53:14', '2021-04-24 14:58:14'),
(13, 1, 168371, '2021-05-02 13:52:21', '2021-05-02 13:57:21'),
(14, 1, 427184, '2021-05-02 14:01:25', '2021-05-02 14:06:25'),
(15, 1, 926397, '2021-05-02 14:11:30', '2021-05-02 14:16:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `datecreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `datecreated`) VALUES
(1, 'jpmb0816', '$2y$10$dOSvxl1tjpA0PBAIYRXe5ewDnXEimq9x9n5zH6yGbse70zBF5w1QW', 'johnpaulo.beyong0816@gmail.com', '2021-04-21 08:10:31'),
(2, 'jpmb0817', '$2y$10$7Fc/3FcG/4fNL2UXzScguudn2vlItw3V5VTSIhFBq/.0gYIGjlUyS', 'johnpaulo.beyong0817@gmail.com', '2021-05-02 06:29:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_ActivityLogs_UserId_Users_Id` (`user_id`);

--
-- Indexes for table `authentication_code`
--
ALTER TABLE `authentication_code`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_AuthenticationCode_UserId_Users_Id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `authentication_code`
--
ALTER TABLE `authentication_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `FK_ActivityLogs_UserId_Users_Id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `authentication_code`
--
ALTER TABLE `authentication_code`
  ADD CONSTRAINT `FK_AuthenticationCode_UserId_Users_Id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
