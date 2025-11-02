-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 07:04 PM
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
-- Database: `euphoria`
--

-- --------------------------------------------------------

--
-- Table structure for table `faction_gang_requests`
--

CREATE TABLE `faction_gang_requests` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `name_hrp` varchar(255) NOT NULL,
  `age_hrp` int(11) NOT NULL,
  `name_rp` varchar(255) NOT NULL,
  `age_rp` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `faction_gang_name` varchar(255) DEFAULT NULL,
  `story` text DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_project`
--

CREATE TABLE `request_project` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `character_name` varchar(255) NOT NULL,
  `project_details` text NOT NULL,
  `project_type` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_requests`
--

CREATE TABLE `staff_requests` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `name_irl` varchar(255) NOT NULL,
  `age_irl` int(11) NOT NULL,
  `experience` text DEFAULT NULL,
  `mic_status` varchar(10) DEFAULT NULL,
  `active_hours` varchar(50) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `discord_id` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `discord_id`, `created_at`, `username`, `role`) VALUES
(4, 'rayanibouajijane3@gmail.com', '$2y$10$fxFdoDTQs5CEUSG8QE4QD.drrImnSp5NXABQYQ8HD1x1nf4.RKJJC', NULL, '2025-10-21 23:32:34', NULL, 'user'),
(5, 'bilal.ibouajijane18@gmail.com', '$2y$10$fxFdoDTQs5CEUSG8QE4QD.drrImnSp5NXABQYQ8HD1x1nf4.RKJJC', NULL, '2025-10-21 23:47:21', NULL, 'admin'),
(6, 'bilal.ibouajijane17@gmail.com', NULL, '907040603865501706', '2025-10-22 12:20:10', '_qx4r1#0', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `whitelist_requests`
--

CREATE TABLE `whitelist_requests` (
  `id` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `experience` varchar(10) DEFAULT NULL,
  `serial` varchar(30) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `story` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `whitelist_requests`
--

INSERT INTO `whitelist_requests` (`id`, `user_email`, `gender`, `nickname`, `age`, `experience`, `serial`, `nationality`, `story`, `created_at`, `status`) VALUES
(7, 'bilal.ibouajijane18@gmail.com', 'Male', 'bilal', '27', '2 ans', 'E1ETBIUZGC8Y2663GV3THJ4KJ2JK', 'MOROCCO', 'ghdèiyuhgvitèytrhgby_otèdiyufgvh', '2025-10-26 15:37:45', 'Rejected'),
(8, 'bilal.ibouajijane18@gmail.com', 'Male', 'bilal', '27', '2 ans', 'E1ETBIUZGC8Y2663GV3THJ4KJ2JK', 'MOROCCO', 'ghdèiyuhgvitèytrhgby_otèdiyufgvh', '2025-10-26 15:40:08', 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faction_gang_requests`
--
ALTER TABLE `faction_gang_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_project`
--
ALTER TABLE `request_project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_requests`
--
ALTER TABLE `staff_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whitelist_requests`
--
ALTER TABLE `whitelist_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `whitelist_requests`
--
ALTER TABLE `whitelist_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
