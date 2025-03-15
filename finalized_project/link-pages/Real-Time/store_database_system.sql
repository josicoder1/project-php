-- Set SQL mode and start transaction
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Set character set and collation
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `store_database_system`;
USE `store_database_system`;

-- --------------------------------------------------------

-- Table structure for `request_sequence`
CREATE TABLE `request_sequence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stub` varchar(1) NOT NULL DEFAULT 'a',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Stored procedure to generate next request ID
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_next_request_id` (OUT `next_id` INT)  
BEGIN
    INSERT INTO request_sequence (stub) VALUES ('a');
    SET next_id = LAST_INSERT_ID();
    DELETE FROM request_sequence WHERE stub = 'a';
END$$
DELIMITER ;

-- --------------------------------------------------------

-- Table structure for `approvals`
CREATE TABLE `approvals` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `action` enum('approved','rejected') NOT NULL,
  `comments` text DEFAULT NULL,
  `escalated` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  KEY `manager_id` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `approvals`
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

-- Table structure for `notifications`
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table structure for `transport_requests`
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
  `notification_sent` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_id` (`request_id`),
  KEY `submitted_by` (`submitted_by`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `transport_requests`
INSERT INTO `transport_requests` (`id`, `request_id`, `purpose`, `request_date`, `request_time`, `destination`, `submitted_by`, `status`, `document_path`, `vehicle_id`, `created_at`, `email`, `notification_sent`) VALUES
(1, 'REQ1741543', 'to travel', '2025-02-04', '17:06:00', 'Harar', 1, 'approved', NULL, NULL, '2025-03-09 18:09:11', '', 0),
(2, 'REQ1741545', 'hi', '2025-04-04', '14:23:00', 'london', 1, 'approved', NULL, NULL, '2025-03-09 18:34:01', '', 0),
(10, 'REQ1741546', 'fffff', '2222-02-02', '14:22:00', 'xcwdc', 1, 'rejected', NULL, NULL, '2025-03-09 18:49:46', '', 0),
(14, 'REQ1741553', 'yyu', '2025-03-09', '18:57:00', 'eddd', 1, 'approved', NULL, NULL, '2025-03-09 20:56:11', '', 0),
(18, 'REQ1741555', 'yyy', '2023-04-04', '16:34:00', 'eddd', 1, 'rejected', NULL, NULL, '2025-03-09 21:28:52', '', 0),
(19, 'REQ1741954', 'to go', '2025-12-31', '12:22:00', 'kolfe', 4, 'rejected', NULL, NULL, '2025-03-14 12:21:42', '', 0),
(20, 'REQ1741955', 'bbashn', '2025-12-02', '14:03:00', 'dwwa', 4, 'approved', NULL, NULL, '2025-03-14 12:24:26', '', 0),
(22, 'REQ1741959', 'ass', '2025-12-02', '11:11:00', 'xcwdc', 1, 'approved', NULL, NULL, '2025-03-14 13:39:03', '', 0),
(24, 'REQ1741960', 'hhy', '2025-03-22', '14:02:00', 'hhh', 1, 'approved', NULL, NULL, '2025-03-14 14:02:50', '', 0);

-- --------------------------------------------------------

-- Table structure for `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('employee','manager','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `users`
INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `email`) VALUES
(1, 'admin', '$2y$10$46Ch05DLUkcrkx1TeWnVxOBCrlcynzUoejvie1ggcwM18c.1C9bFu', 'admin', '2025-03-09 16:48:40', ''),
(3, 'employee', '$2y$10$NKQ7vpb5laQ.3LBA/zCVjuiCblDUnLib3StUTYf.U/EeGpWnyMeCm', 'employee', '2025-03-09 18:23:22', ''),
(4, 'manager', '$2y$10$m4GV3xmcjTYR7dMiUjqQR..lecEponS2DOG.iLoVzXUDb0Xb66tl.', 'manager', '2025-03-09 18:24:04', '');

-- --------------------------------------------------------

-- Table structure for `vehicles`
CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_id` varchar(10) NOT NULL,
  `type` enum('van','truck','car') NOT NULL,
  `status` enum('available','allocated','maintenance') DEFAULT 'available',
  `location` varchar(100) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicle_id` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert data into `vehicles`
INSERT INTO `vehicles` (`id`, `vehicle_id`, `type`, `status`, `location`, `last_updated`) VALUES
(1, '233a', 'truck', 'allocated', 'Addas Ababa', '2025-03-09 18:25:22'),
(3, '5664', 'van', 'available', 'harar', '2025-03-09 18:25:45'),
(4, '123', 'truck', 'available', 'harar', '2025-03-09 18:26:21');

-- --------------------------------------------------------

-- Add foreign key constraints
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `transport_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `approvals_ibfk_2` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `transport_requests`
  ADD CONSTRAINT `transport_requests_ibfk_1` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transport_requests_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Commit the transaction
COMMIT;

-- Restore original SQL mode
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;