-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2025 at 09:37 PM
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
-- Database: `store_database_system`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_next_request_id` (OUT `next_id` INT)   BEGIN
    INSERT INTO request_sequence (stub) VALUES ('');
    SET next_id = LAST_INSERT_ID();
    DELETE FROM request_sequence WHERE stub = '';
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `action` enum('approved','rejected') NOT NULL,
  `comments` text DEFAULT NULL,
  `escalated` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approvals`
--

INSERT INTO `approvals` (`id`, `request_id`, `manager_id`, `action`, `comments`, `escalated`, `created_at`) VALUES
(1, 1, 1, 'approved', 'cbb', 0, '2025-03-09 18:32:11'),
(2, 2, 1, 'approved', 'checked', 1, '2025-03-09 18:35:01'),
(3, 10, 1, 'rejected', '', 1, '2025-03-09 18:50:43'),
(4, 14, 1, 'approved', 'yes', 1, '2025-03-09 20:56:45'),
(5, 18, 1, 'rejected', 'trrr', 1, '2025-03-09 21:29:09'),
(6, 19, 4, 'rejected', '', 1, '2025-03-14 12:22:00'),
(7, 20, 4, 'approved', 'weqe', 1, '2025-03-14 12:26:45'),
(8, 22, 1, 'approved', '', 1, '2025-03-14 13:39:13'),
(9, 24, 1, 'approved', '', 1, '2025-03-14 14:02:58');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_sequence`
--

CREATE TABLE `request_sequence` (
  `id` int(11) NOT NULL,
  `stub` char(1) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transport_requests`
--

CREATE TABLE `transport_requests` (
  `id` int(11) NOT NULL,
  `request_id` varchar(10) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `request_date` date NOT NULL,
  `request_time` time NOT NULL,
  `destination` varchar(255) NOT NULL,
  `submitted_by` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `document_path` varchar(255) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL,
  `notification_sent` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport_requests`
--

INSERT INTO `transport_requests` (`id`, `request_id`, `purpose`, `request_date`, `request_time`, `destination`, `submitted_by`, `status`, `document_path`, `vehicle_id`, `created_at`, `email`, `notification_sent`) VALUES
(1, 'REQ1741543', 'to travel', '2025-02-04', '17:06:00', 'Harar', 1, 'approved', NULL, NULL, '2025-03-09 18:09:11', '', 0),
(2, 'REQ1741545', 'hi', '2025-04-04', '14:23:00', 'london', 1, 'approved', NULL, NULL, '2025-03-09 18:34:01', '', 0),
(10, 'REQ1741546', 'fffff', '2222-02-02', '14:22:00', 'xcwdc', 1, 'rejected', NULL, NULL, '2025-03-09 18:49:46', '', 0),
(14, 'REQ1741553', 'yyu', '0000-00-00', '18:57:00', 'eddd', 1, 'approved', NULL, NULL, '2025-03-09 20:56:11', '', 0),
(18, 'REQ1741555', 'yyy', '2023-04-04', '16:34:00', 'eddd', 1, 'rejected', NULL, NULL, '2025-03-09 21:28:52', '', 0),
(19, 'REQ1741954', 'to go', '2025-12-31', '12:22:00', 'kolfe', 4, 'rejected', NULL, NULL, '2025-03-14 12:21:42', '', 0),
(20, 'REQ1741955', 'bbashn', '1223-12-02', '14:03:00', 'dwwa', 4, 'approved', NULL, NULL, '2025-03-14 12:24:26', '', 0),
(22, 'REQ1741959', 'ass', '0022-12-02', '11:11:00', 'xcwdc', 1, 'approved', NULL, NULL, '2025-03-14 13:39:03', '', 0),
(24, 'REQ1741960', 'hhy', '4555-03-22', '14:02:00', 'hhh', 1, 'approved', NULL, NULL, '2025-03-14 14:02:50', '', 0),
(34, 'REQ-67d466', 'ghhyy', '2025-02-14', '15:23:00', 'dwwa', NULL, 'approved', NULL, NULL, '2025-03-14 17:25:55', 'paudiriba@gmail.com', 0),
(36, 'REQ1741973', 'ddq', '0022-02-22', '14:22:00', '2w', 1, 'approved', NULL, NULL, '2025-03-14 17:27:58', '', 0),
(37, 'REQ-174197', 'gjjh', '2025-03-14', '15:44:00', 'Addsi Ababa', NULL, 'rejected', NULL, NULL, '2025-03-14 17:37:16', 'paudiriba@gmail.com', 0),
(42, 'REQ-1000', 'rrew', '2025-03-04', '00:22:00', 'kolfe', NULL, 'approved', NULL, NULL, '2025-03-14 19:11:17', 'paudiriba@gmail.com', 0),
(43, 'REQ-1001', 'iii', '2025-03-03', '02:02:00', 'Addsi Ababa', NULL, 'rejected', NULL, NULL, '2025-03-14 19:16:11', 'paudiriba@gmail.com', 0),
(44, 'REQ-1002', 'fFW', '2025-05-02', '02:03:00', 'HARAMAYA', NULL, 'approved', NULL, NULL, '2025-03-14 19:20:57', 'paudiriba@gmail.com', 0),
(45, 'REQ-1003', 'dfdf', '2025-02-03', '02:03:00', 'gojo', NULL, 'approved', NULL, NULL, '2025-03-14 19:32:58', 'paudiriba@gmail.com', 0),
(46, 'REQ-1004', 'selamaw', '2025-04-02', '02:04:00', 'saris', NULL, 'approved', NULL, NULL, '2025-03-14 19:34:03', 'paudiriba@gmail.com', 0),
(47, 'REQ-1005', 'erfrf', '2025-02-03', '02:02:00', 'bole', NULL, 'rejected', NULL, NULL, '2025-03-14 19:36:30', 'paudiriba@gmail.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transport_requests_backup`
--

CREATE TABLE `transport_requests_backup` (
  `id` int(11) NOT NULL DEFAULT 0,
  `request_id` varchar(10) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `request_date` date NOT NULL,
  `request_time` time NOT NULL,
  `destination` varchar(255) NOT NULL,
  `submitted_by` int(11) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `document_path` varchar(255) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL,
  `notification_sent` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport_requests_backup`
--

INSERT INTO `transport_requests_backup` (`id`, `request_id`, `purpose`, `request_date`, `request_time`, `destination`, `submitted_by`, `status`, `document_path`, `vehicle_id`, `created_at`, `email`, `notification_sent`) VALUES
(1, 'REQ1741543', 'to travel', '2025-02-04', '17:06:00', 'Harar', 1, 'approved', NULL, NULL, '2025-03-09 18:09:11', '', 0),
(2, 'REQ1741545', 'hi', '2025-04-04', '14:23:00', 'london', 1, 'approved', NULL, NULL, '2025-03-09 18:34:01', '', 0),
(10, 'REQ1741546', 'fffff', '2222-02-02', '14:22:00', 'xcwdc', 1, 'rejected', NULL, NULL, '2025-03-09 18:49:46', '', 0),
(14, 'REQ1741553', 'yyu', '0000-00-00', '18:57:00', 'eddd', 1, 'approved', NULL, NULL, '2025-03-09 20:56:11', '', 0),
(18, 'REQ1741555', 'yyy', '2023-04-04', '16:34:00', 'eddd', 1, 'rejected', NULL, NULL, '2025-03-09 21:28:52', '', 0),
(19, 'REQ1741954', 'to go', '2025-12-31', '12:22:00', 'kolfe', 4, 'rejected', NULL, NULL, '2025-03-14 12:21:42', '', 0),
(20, 'REQ1741955', 'bbashn', '1223-12-02', '14:03:00', 'dwwa', 4, 'approved', NULL, NULL, '2025-03-14 12:24:26', '', 0),
(22, 'REQ1741959', 'ass', '0022-12-02', '11:11:00', 'xcwdc', 1, 'approved', NULL, NULL, '2025-03-14 13:39:03', '', 0),
(24, 'REQ1741960', 'hhy', '4555-03-22', '14:02:00', 'hhh', 1, 'approved', NULL, NULL, '2025-03-14 14:02:50', '', 0),
(34, 'REQ-67d466', 'ghhyy', '2025-02-14', '15:23:00', 'dwwa', NULL, 'approved', NULL, NULL, '2025-03-14 17:25:55', 'paudiriba@gmail.com', 0),
(36, 'REQ1741973', 'ddq', '0022-02-22', '14:22:00', '2w', 1, 'approved', NULL, NULL, '2025-03-14 17:27:58', '', 0),
(37, 'REQ-174197', 'gjjh', '2025-03-14', '15:44:00', 'Addsi Ababa', NULL, 'rejected', NULL, NULL, '2025-03-14 17:37:16', 'paudiriba@gmail.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('employee','manager','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `email`) VALUES
(1, 'admin', '$2y$10$46Ch05DLUkcrkx1TeWnVxOBCrlcynzUoejvie1ggcwM18c.1C9bFu', 'admin', '2025-03-09 16:48:40', ''),
(3, 'employee', '$2y$10$NKQ7vpb5laQ.3LBA/zCVjuiCblDUnLib3StUTYf.U/EeGpWnyMeCm', 'employee', '2025-03-09 18:23:22', ''),
(4, 'manager', '$2y$10$m4GV3xmcjTYR7dMiUjqQR..lecEponS2DOG.iLoVzXUDb0Xb66tl.', 'manager', '2025-03-09 18:24:04', ''),
(8, 'admin2', '$2y$10$EcPTQ6VOnhS8BULYJbVC7OHcPwGEE0jknX.Bf7bYtUhV0JJ.g29.6', 'admin', '2025-03-09 18:28:08', ''),
(9, 'admin43', '$2y$10$4/28TPDJLEcL/mlbWb5pieKcliAew3NHuJowF0thFvEhe/U3n7qcy', 'admin', '2025-03-09 18:37:56', ''),
(11, 'admin45', '$2y$10$VUQI39kHFEoYqr0JoIDjl.rV4QM1nO53lDUsQnmxPWDMI72pHIQH6', 'admin', '2025-03-09 18:50:09', ''),
(15, 'adminqa', '$2y$10$zftxYk3lslknwKk6EfchdeK7cCfmloZrjnWdogvUpfYojJmACWQkK', 'admin', '2025-03-09 20:55:32', ''),
(16, 'adminbfr', '$2y$10$jgn4F.B3UZcVlkgwXjeLeOuLoGqnerhVdjPeoAPmGdvUGV9iWjQH6', 'admin', '2025-03-09 20:57:13', ''),
(17, 'adminertt', '$2y$10$e1SP.s2JYEXaGhNe/8XjpuJ7BTNXb7AhcvAM1tK2HGI5Fe0jJ9YYm', 'admin', '2025-03-09 21:27:51', ''),
(18, 'employee1', '$2y$10$Xz7YQz8kJ8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z', 'employee', '2025-03-14 13:16:09', ''),
(19, 'manager1', '$2y$10$Xz7YQz8kJ8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z', 'manager', '2025-03-14 13:16:09', ''),
(20, 'admin1', '$2y$10$Xz7YQz8kJ8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z8z', 'admin', '2025-03-14 13:16:09', ''),
(21, 'endndn122', '$2y$10$2LpzhrVvdI.XveTnzV7MCezuF3r4/eB7AbnAXf//BTjS1q86G8OzG', 'manager', '2025-03-14 13:44:45', '');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_id` varchar(10) NOT NULL,
  `type` enum('van','truck','car') NOT NULL,
  `status` enum('available','allocated','maintenance') DEFAULT 'available',
  `location` varchar(100) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `vehicle_id`, `type`, `status`, `location`, `last_updated`) VALUES
(1, '233a', 'truck', 'allocated', 'Addas Ababa', '2025-03-09 18:25:22'),
(3, '5664', 'van', 'available', 'harar', '2025-03-09 18:25:45'),
(4, '123', 'truck', 'available', 'harar', '2025-03-09 18:26:21'),
(5, '12355', 'van', 'available', 'gggg', '2025-03-09 21:27:19'),
(6, '4555', 'car', 'available', 'hawasa', '2025-03-14 12:22:50'),
(7, '621', 'truck', 'available', 'djoubet', '2025-03-14 12:23:16'),
(8, 'V001', 'van', 'available', 'Garage A', '2025-03-14 13:16:09'),
(9, 'V002', 'truck', 'allocated', 'Garage B', '2025-03-14 13:16:09'),
(10, 'V003', 'car', 'available', 'Garage C', '2025-03-14 13:16:09'),
(11, '89877', 'truck', 'available', 'ggfff', '2025-03-14 13:44:08'),
(12, 'hjjjkjkl', 'car', 'available', 'Ertiria', '2025-03-14 17:52:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_sequence`
--
ALTER TABLE `request_sequence`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stub` (`stub`);

--
-- Indexes for table `transport_requests`
--
ALTER TABLE `transport_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_id` (`request_id`),
  ADD UNIQUE KEY `unique_request_id` (`request_id`),
  ADD KEY `submitted_by` (`submitted_by`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicle_id` (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_sequence`
--
ALTER TABLE `request_sequence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1006;

--
-- AUTO_INCREMENT for table `transport_requests`
--
ALTER TABLE `transport_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `transport_requests` (`id`),
  ADD CONSTRAINT `approvals_ibfk_2` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transport_requests`
--
ALTER TABLE `transport_requests`
  ADD CONSTRAINT `transport_requests_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
