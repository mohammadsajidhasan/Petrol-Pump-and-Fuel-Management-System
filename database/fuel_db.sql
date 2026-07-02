-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2026 at 03:45 AM
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
-- Database: `fuel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Pending','Processing','Resolved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `designation` enum('ADMIN','WORKER') NOT NULL DEFAULT 'WORKER',
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `station_id`, `designation`, `password`) VALUES
(1, 1, 'ADMIN', '123'),
(2, 1, 'WORKER', '$2y$10$vOaSsNtyTpgvM.C7n3JKGujcEUmCafSzyRLSnk2vjaSjsK9qWjnHK'),
(3, 1, 'WORKER', ''),
(4, 1, 'WORKER', ''),
(5, 1, 'WORKER', ''),
(6, 1, 'WORKER', ''),
(7, 1, 'ADMIN', ''),
(8, 1, 'WORKER', ''),
(9, 1, 'WORKER', ''),
(10, 1, 'WORKER', ''),
(11, 1, 'WORKER', ''),
(12, 1, 'WORKER', ''),
(13, 1, 'ADMIN', ''),
(14, 1, 'WORKER', ''),
(15, 1, 'WORKER', ''),
(16, 1, 'WORKER', ''),
(17, 1, 'WORKER', ''),
(18, 1, 'WORKER', ''),
(19, 1, 'ADMIN', ''),
(20, 1, 'WORKER', ''),
(21, 1, 'WORKER', ''),
(22, 1, 'WORKER', ''),
(23, 1, 'WORKER', ''),
(24, 1, 'WORKER', ''),
(25, 2, 'ADMIN', ''),
(26, 2, 'WORKER', ''),
(27, 2, 'WORKER', ''),
(28, 2, 'WORKER', ''),
(29, 2, 'WORKER', ''),
(30, 2, 'WORKER', ''),
(31, 2, 'ADMIN', ''),
(32, 2, 'WORKER', ''),
(33, 2, 'WORKER', ''),
(34, 2, 'WORKER', ''),
(35, 2, 'WORKER', ''),
(36, 2, 'WORKER', ''),
(37, 2, 'ADMIN', ''),
(38, 2, 'WORKER', ''),
(39, 2, 'WORKER', ''),
(40, 2, 'WORKER', ''),
(41, 2, 'WORKER', ''),
(42, 2, 'WORKER', ''),
(43, 2, 'ADMIN', ''),
(44, 2, 'WORKER', ''),
(45, 2, 'WORKER', ''),
(46, 2, 'WORKER', ''),
(47, 2, 'WORKER', ''),
(48, 2, 'WORKER', ''),
(49, 3, 'ADMIN', ''),
(50, 3, 'WORKER', ''),
(51, 3, 'WORKER', ''),
(52, 3, 'WORKER', ''),
(53, 3, 'WORKER', ''),
(54, 3, 'WORKER', ''),
(55, 3, 'ADMIN', ''),
(56, 3, 'WORKER', ''),
(57, 3, 'WORKER', ''),
(58, 3, 'WORKER', ''),
(59, 3, 'WORKER', ''),
(60, 3, 'WORKER', ''),
(61, 3, 'ADMIN', ''),
(62, 3, 'WORKER', ''),
(63, 3, 'WORKER', ''),
(64, 3, 'WORKER', ''),
(65, 3, 'WORKER', ''),
(66, 3, 'WORKER', ''),
(67, 3, 'ADMIN', ''),
(68, 3, 'WORKER', ''),
(69, 3, 'WORKER', ''),
(70, 3, 'WORKER', ''),
(71, 3, 'WORKER', ''),
(72, 3, 'WORKER', ''),
(73, 4, 'ADMIN', ''),
(74, 4, 'WORKER', ''),
(75, 4, 'WORKER', ''),
(76, 4, 'WORKER', ''),
(77, 4, 'WORKER', ''),
(78, 4, 'WORKER', ''),
(79, 4, 'ADMIN', ''),
(80, 4, 'WORKER', '$2y$10$QB92vIgXtVdHyCm9VoAmfeRPV9pON20g6oyGwOnMM.e8nt8Een3du'),
(81, 4, 'WORKER', ''),
(82, 4, 'WORKER', ''),
(83, 4, 'WORKER', ''),
(84, 4, 'WORKER', ''),
(85, 4, 'ADMIN', ''),
(86, 4, 'WORKER', ''),
(87, 4, 'WORKER', ''),
(88, 4, 'WORKER', ''),
(89, 4, 'WORKER', ''),
(90, 4, 'WORKER', ''),
(91, 4, 'ADMIN', ''),
(92, 4, 'WORKER', ''),
(93, 4, 'WORKER', ''),
(94, 4, 'WORKER', ''),
(95, 4, 'WORKER', ''),
(96, 4, 'WORKER', ''),
(97, 5, 'ADMIN', ''),
(98, 5, 'WORKER', ''),
(99, 5, 'WORKER', ''),
(100, 5, 'WORKER', ''),
(101, 5, 'WORKER', ''),
(102, 5, 'WORKER', ''),
(103, 5, 'ADMIN', ''),
(104, 5, 'WORKER', ''),
(105, 5, 'WORKER', ''),
(106, 5, 'WORKER', ''),
(107, 5, 'WORKER', ''),
(108, 5, 'WORKER', ''),
(109, 5, 'ADMIN', ''),
(110, 5, 'WORKER', ''),
(111, 5, 'WORKER', ''),
(112, 5, 'WORKER', ''),
(113, 5, 'WORKER', ''),
(114, 5, 'WORKER', ''),
(115, 5, 'ADMIN', ''),
(116, 5, 'WORKER', ''),
(117, 5, 'WORKER', ''),
(118, 5, 'WORKER', ''),
(119, 5, 'WORKER', ''),
(120, 5, 'WORKER', ''),
(1, 1, 'ADMIN', '123'),
(2, 1, 'WORKER', '$2y$10$vOaSsNtyTpgvM.C7n3JKGujcEUmCafSzyRLSnk2vjaSjsK9qWjnHK'),
(3, 1, 'WORKER', ''),
(4, 1, 'WORKER', ''),
(5, 1, 'WORKER', ''),
(6, 1, 'WORKER', ''),
(7, 1, 'ADMIN', ''),
(8, 1, 'WORKER', ''),
(9, 1, 'WORKER', ''),
(10, 1, 'WORKER', ''),
(11, 1, 'WORKER', ''),
(12, 1, 'WORKER', ''),
(13, 1, 'ADMIN', ''),
(14, 1, 'WORKER', ''),
(15, 1, 'WORKER', ''),
(16, 1, 'WORKER', ''),
(17, 1, 'WORKER', ''),
(18, 1, 'WORKER', ''),
(19, 1, 'ADMIN', ''),
(20, 1, 'WORKER', ''),
(21, 1, 'WORKER', ''),
(22, 1, 'WORKER', ''),
(23, 1, 'WORKER', ''),
(24, 1, 'WORKER', ''),
(25, 2, 'ADMIN', ''),
(26, 2, 'WORKER', ''),
(27, 2, 'WORKER', ''),
(28, 2, 'WORKER', ''),
(29, 2, 'WORKER', ''),
(30, 2, 'WORKER', ''),
(31, 2, 'ADMIN', ''),
(32, 2, 'WORKER', ''),
(33, 2, 'WORKER', ''),
(34, 2, 'WORKER', ''),
(35, 2, 'WORKER', ''),
(36, 2, 'WORKER', ''),
(37, 2, 'ADMIN', ''),
(38, 2, 'WORKER', ''),
(39, 2, 'WORKER', ''),
(40, 2, 'WORKER', ''),
(41, 2, 'WORKER', ''),
(42, 2, 'WORKER', ''),
(43, 2, 'ADMIN', ''),
(44, 2, 'WORKER', ''),
(45, 2, 'WORKER', ''),
(46, 2, 'WORKER', ''),
(47, 2, 'WORKER', ''),
(48, 2, 'WORKER', ''),
(49, 3, 'ADMIN', ''),
(50, 3, 'WORKER', ''),
(51, 3, 'WORKER', ''),
(52, 3, 'WORKER', ''),
(53, 3, 'WORKER', ''),
(54, 3, 'WORKER', ''),
(55, 3, 'ADMIN', ''),
(56, 3, 'WORKER', ''),
(57, 3, 'WORKER', ''),
(58, 3, 'WORKER', ''),
(59, 3, 'WORKER', ''),
(60, 3, 'WORKER', ''),
(61, 3, 'ADMIN', ''),
(62, 3, 'WORKER', ''),
(63, 3, 'WORKER', ''),
(64, 3, 'WORKER', ''),
(65, 3, 'WORKER', ''),
(66, 3, 'WORKER', ''),
(67, 3, 'ADMIN', ''),
(68, 3, 'WORKER', ''),
(69, 3, 'WORKER', ''),
(70, 3, 'WORKER', ''),
(71, 3, 'WORKER', ''),
(72, 3, 'WORKER', ''),
(73, 4, 'ADMIN', ''),
(74, 4, 'WORKER', ''),
(75, 4, 'WORKER', ''),
(76, 4, 'WORKER', ''),
(77, 4, 'WORKER', ''),
(78, 4, 'WORKER', ''),
(79, 4, 'ADMIN', ''),
(80, 4, 'WORKER', '$2y$10$QB92vIgXtVdHyCm9VoAmfeRPV9pON20g6oyGwOnMM.e8nt8Een3du'),
(81, 4, 'WORKER', ''),
(82, 4, 'WORKER', ''),
(83, 4, 'WORKER', ''),
(84, 4, 'WORKER', ''),
(85, 4, 'ADMIN', ''),
(86, 4, 'WORKER', ''),
(87, 4, 'WORKER', ''),
(88, 4, 'WORKER', ''),
(89, 4, 'WORKER', ''),
(90, 4, 'WORKER', ''),
(91, 4, 'ADMIN', ''),
(92, 4, 'WORKER', ''),
(93, 4, 'WORKER', ''),
(94, 4, 'WORKER', ''),
(95, 4, 'WORKER', ''),
(96, 4, 'WORKER', ''),
(97, 5, 'ADMIN', ''),
(98, 5, 'WORKER', ''),
(99, 5, 'WORKER', ''),
(100, 5, 'WORKER', ''),
(101, 5, 'WORKER', ''),
(102, 5, 'WORKER', ''),
(103, 5, 'ADMIN', ''),
(104, 5, 'WORKER', ''),
(105, 5, 'WORKER', ''),
(106, 5, 'WORKER', ''),
(107, 5, 'WORKER', ''),
(108, 5, 'WORKER', ''),
(109, 5, 'ADMIN', ''),
(110, 5, 'WORKER', ''),
(111, 5, 'WORKER', ''),
(112, 5, 'WORKER', ''),
(113, 5, 'WORKER', ''),
(114, 5, 'WORKER', ''),
(115, 5, 'ADMIN', ''),
(116, 5, 'WORKER', ''),
(117, 5, 'WORKER', ''),
(118, 5, 'WORKER', ''),
(119, 5, 'WORKER', ''),
(120, 5, 'WORKER', '');

-- --------------------------------------------------------

--
-- Table structure for table `fuelinventory`
--

CREATE TABLE `fuelinventory` (
  `inventory_id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `fuel_type_id` int(11) NOT NULL,
  `quantity_available` decimal(10,2) NOT NULL DEFAULT 0.00,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fuelinventory`
--

INSERT INTO `fuelinventory` (`inventory_id`, `station_id`, `fuel_type_id`, `quantity_available`, `last_updated`) VALUES
(1, 1, 1, 12500.50, '2026-06-02 04:00:00'),
(2, 1, 2, 900.00, '2026-06-02 18:23:39'),
(3, 1, 3, 4200.00, '2026-06-02 03:30:00'),
(4, 2, 1, 19500.00, '2026-06-02 05:45:00'),
(5, 2, 2, 14000.00, '2026-06-02 02:00:00'),
(6, 2, 3, 11200.00, '2026-06-02 02:00:00'),
(7, 3, 1, 9300.00, '2026-06-02 10:20:00'),
(8, 3, 2, 18000.75, '2026-06-02 10:20:00'),
(9, 3, 3, 7100.00, '2026-06-02 10:20:00'),
(10, 4, 1, 1000.00, '2026-06-02 18:22:06'),
(11, 4, 2, 12100.25, '2026-06-02 06:00:00'),
(12, 4, 3, 10800.00, '2026-06-06 18:09:30'),
(13, 5, 1, 750.00, '2026-06-02 18:13:42'),
(14, 5, 2, 22000.00, '2026-06-02 13:40:00'),
(15, 5, 3, 15600.00, '2026-06-02 13:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `fuelorder`
--

CREATE TABLE `fuelorder` (
  `order_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `delivery_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fuelorder`
--

INSERT INTO `fuelorder` (`order_id`, `inventory_id`, `order_date`, `delivery_date`) VALUES
(101, 2, '2026-06-02 14:30:00', NULL),
(102, 13, '2026-06-02 21:15:00', NULL),
(103, 1, '2026-05-28 09:00:00', '2026-05-29 06:00:00'),
(104, 13, '2026-06-02 23:44:31', '2026-06-02 23:44:31'),
(105, 13, '2026-06-02 23:44:46', '2026-06-02 23:44:46'),
(106, 13, '2026-06-02 23:45:02', '2026-06-02 23:45:02'),
(107, 13, '2026-06-02 23:53:19', '2026-06-02 23:53:19'),
(108, 13, '2026-06-03 00:12:27', '2026-06-03 00:12:27'),
(109, 13, '2026-06-03 00:13:42', '2026-06-03 00:13:42'),
(110, 10, '2026-06-03 00:21:19', '2026-06-03 00:21:19'),
(111, 10, '2026-06-03 00:21:26', '2026-06-03 00:21:26'),
(112, 10, '2026-06-03 00:22:06', '2026-06-03 00:22:06'),
(113, 2, '2026-06-03 00:23:39', '2026-06-03 00:23:39'),
(114, 12, '2026-06-07 00:04:01', '2026-06-07 00:09:30'),
(115, 12, '2026-06-07 00:09:03', '2026-06-07 00:09:30'),
(116, 11, '2026-06-07 00:10:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fuelprices`
--

CREATE TABLE `fuelprices` (
  `price_id` int(11) NOT NULL,
  `fuel_type_id` int(11) NOT NULL,
  `price_per_liter` decimal(10,2) NOT NULL,
  `effective_from` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fuelprices`
--

INSERT INTO `fuelprices` (`price_id`, `fuel_type_id`, `price_per_liter`, `effective_from`) VALUES
(1, 1, 130.00, '2026-05-20 04:30:29'),
(2, 2, 115.00, '2026-05-20 04:30:29'),
(3, 3, 125.00, '2026-05-20 04:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `fuelquota`
--

CREATE TABLE `fuelquota` (
  `quota_id` int(11) NOT NULL,
  `vehicle_type_id` int(11) NOT NULL,
  `daily_limit_liters` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fuelquota`
--

INSERT INTO `fuelquota` (`quota_id`, `vehicle_type_id`, `daily_limit_liters`) VALUES
(10, 1, 30),
(11, 2, 5),
(12, 3, 40),
(13, 4, 20),
(14, 5, 100),
(15, 6, 120),
(16, 7, 25),
(17, 8, 15),
(18, 9, 30),
(19, 10, 60),
(20, 11, 40),
(10, 1, 30),
(11, 2, 5),
(12, 3, 40),
(13, 4, 20),
(14, 5, 100),
(15, 6, 120),
(16, 7, 25),
(17, 8, 15),
(18, 9, 30),
(19, 10, 60),
(20, 11, 40);

-- --------------------------------------------------------

--
-- Table structure for table `fueltypes`
--

CREATE TABLE `fueltypes` (
  `fuel_type_id` int(11) NOT NULL,
  `fuel_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fueltypes`
--

INSERT INTO `fueltypes` (`fuel_type_id`, `fuel_name`) VALUES
(1, 'Octane'),
(2, 'Diesel'),
(3, 'Petrol');

-- --------------------------------------------------------

--
-- Table structure for table `inventoryalerts`
--

CREATE TABLE `inventoryalerts` (
  `alert_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `threshold_level` decimal(10,2) NOT NULL,
  `triggered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventoryalerts`
--

INSERT INTO `inventoryalerts` (`alert_id`, `inventory_id`, `threshold_level`, `triggered_at`) VALUES
(1, 2, 2000.00, '2026-06-02 08:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `machine_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `machine_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meterreadings`
--

CREATE TABLE `meterreadings` (
  `reading_id` int(11) NOT NULL,
  `machines_id` int(11) NOT NULL,
  `opening_reading` decimal(10,2) DEFAULT NULL,
  `closing_reading` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `odometerlogs`
--

CREATE TABLE `odometerlogs` (
  `log_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `reading` decimal(10,2) DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `owner_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`owner_id`, `full_name`, `email`, `password_hash`, `created_at`) VALUES
(101, 'Rahat Khan', 'rahat.khan@fuelx.com.bd', 'PassSecure2026!', '2026-06-05 04:48:42'),
(102, 'Nusrat Jahan', 'nusrat.jahan@smartfuel.bd', 'PassSecure2026!', '2026-06-05 04:48:42'),
(103, 'Arif Chowdhury', 'arif.c@quickfill.com', 'PassSecure2026!', '2026-06-05 04:48:42'),
(104, 'Tariq Anam', 'tariq.anam@baddafuel.com', 'PassSecure2026!', '2026-06-05 04:51:07'),
(105, 'Sultana Kamal', 'sultana.kamal@uttarapower.bd', 'PassSecure2026!', '2026-06-05 04:51:07');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `token_id` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `priorityvehicles`
--

CREATE TABLE `priorityvehicles` (
  `priority_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `priority_type` varchar(50) DEFAULT 'Emergency',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shift_id`, `employee_id`, `start_time`, `end_time`) VALUES
(1, 2, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(2, 3, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(3, 4, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(4, 5, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(5, 6, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(6, 8, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(7, 9, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(8, 10, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(9, 11, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(10, 12, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(11, 14, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(12, 15, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(13, 16, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(14, 17, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(15, 18, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(16, 20, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(17, 21, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(18, 22, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(19, 23, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(20, 24, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(21, 26, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(22, 27, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(23, 28, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(24, 29, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(25, 30, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(26, 32, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(27, 33, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(28, 34, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(29, 35, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(30, 36, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(31, 38, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(32, 39, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(33, 40, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(34, 41, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(35, 42, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(36, 44, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(37, 45, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(38, 46, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(39, 47, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(40, 48, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(41, 50, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(42, 51, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(43, 52, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(44, 53, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(45, 54, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(46, 56, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(47, 57, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(48, 58, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(49, 59, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(50, 60, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(51, 62, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(52, 63, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(53, 64, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(54, 65, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(55, 66, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(56, 68, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(57, 69, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(58, 70, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(59, 71, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(60, 72, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(61, 74, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(62, 75, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(63, 76, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(64, 77, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(65, 78, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(66, 80, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(67, 81, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(68, 82, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(69, 83, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(70, 84, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(71, 86, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(72, 87, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(73, 88, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(74, 89, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(75, 90, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(76, 92, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(77, 93, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(78, 94, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(79, 95, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(80, 96, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(81, 98, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(82, 99, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(83, 100, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(84, 101, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(85, 102, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(86, 104, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(87, 105, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(88, 106, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(89, 107, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(90, 108, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(91, 110, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(92, 111, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(93, 112, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(94, 113, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(95, 114, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(96, 116, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(97, 117, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(98, 118, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(99, 119, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(100, 120, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(101, 2, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(102, 3, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(103, 4, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(104, 5, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(105, 6, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(106, 8, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(107, 9, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(108, 10, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(109, 11, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(110, 12, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(111, 14, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(112, 15, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(113, 16, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(114, 17, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(115, 18, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(116, 20, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(117, 21, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(118, 22, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(119, 23, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(120, 24, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(121, 26, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(122, 27, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(123, 28, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(124, 29, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(125, 30, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(126, 32, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(127, 33, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(128, 34, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(129, 35, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(130, 36, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(131, 38, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(132, 39, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(133, 40, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(134, 41, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(135, 42, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(136, 44, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(137, 45, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(138, 46, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(139, 47, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(140, 48, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(141, 50, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(142, 51, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(143, 52, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(144, 53, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(145, 54, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(146, 56, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(147, 57, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(148, 58, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(149, 59, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(150, 60, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(151, 62, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(152, 63, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(153, 64, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(154, 65, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(155, 66, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(156, 68, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(157, 69, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(158, 70, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(159, 71, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(160, 72, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(161, 74, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(162, 75, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(163, 76, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(164, 77, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(165, 78, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(166, 80, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(167, 81, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(168, 82, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(169, 83, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(170, 84, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(171, 86, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(172, 87, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(173, 88, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(174, 89, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(175, 90, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(176, 92, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(177, 93, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(178, 94, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(179, 95, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(180, 96, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(181, 98, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(182, 99, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(183, 100, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(184, 101, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(185, 102, '2026-05-19 00:00:00', '2026-05-19 06:00:00'),
(186, 104, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(187, 105, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(188, 106, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(189, 107, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(190, 108, '2026-05-19 06:01:00', '2026-05-19 12:00:00'),
(191, 110, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(192, 111, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(193, 112, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(194, 113, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(195, 114, '2026-05-19 12:01:00', '2026-05-19 18:00:00'),
(196, 116, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(197, 117, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(198, 118, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(199, 119, '2026-05-19 18:01:00', '2026-05-19 23:59:59'),
(200, 120, '2026-05-19 18:01:00', '2026-05-19 23:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `station_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `current_queue` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `owner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`station_id`, `name`, `location`, `address`, `latitude`, `longitude`, `current_queue`, `status`, `owner_id`) VALUES
(1, 'Fuel-X Banani Center', 'Banani', 'Road 11, Banani, Dhaka', 23.79370000, 90.40660000, 3, 'active', 101),
(2, 'Smart Fuel Gulshan', 'Gulshan 2', 'Gulshan Avenue, Dhaka', 23.79250000, 90.41620000, 12, 'active', 102),
(3, 'QuickFill Mohakhali', 'Mohakhali', 'Amtoli, Mohakhali, Dhaka', 23.77760000, 90.40070000, 7, 'active', 103),
(4, 'Future Energy Uttara', 'Uttara', 'Sector 7, Uttara, Dhaka', 23.87590000, 90.39160000, 2, 'active', 105),
(5, 'Eco Station Badda', 'Badda', 'Pragati Sarani, Badda, Dhaka', 23.78150000, 90.42670000, 15, 'active', 104);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `company_name`, `email`, `password_hash`, `created_at`) VALUES
(1, 'Padma Oil Company Ltd.', 'logistics@padmaoil.gov.bd', 'BPC_Secure2026', '2026-06-05 04:48:42'),
(2, 'Meghna Petroleum Corp.', 'supply@meghnapetroleum.com', 'BPC_Secure2026', '2026-06-05 04:48:42');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `token_id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `station_id` int(11) DEFAULT NULL,
  `fuel_type_id` int(11) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `scheduled_time` datetime DEFAULT NULL,
  `status` enum('Pending','In-Progress','Completed','Cancelled') DEFAULT 'Pending',
  `is_priority` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`token_id`, `vehicle_id`, `station_id`, `fuel_type_id`, `quantity`, `scheduled_time`, `status`, `is_priority`, `created_at`) VALUES
(4, 1, 1, 2, 10.00, '2026-05-16 19:11:20', 'Pending', 0, '2026-05-16 17:11:20'),
(5, 1, 4, 2, 10.00, '2026-05-16 19:24:59', 'Pending', 0, '2026-05-16 17:24:59'),
(6, 3, 4, 2, 10.00, '2026-05-16 19:30:44', 'Pending', 0, '2026-05-16 17:30:44'),
(7, 4, 1, 2, 10.00, '2026-05-17 20:28:29', 'Pending', 0, '2026-05-17 18:25:29'),
(8, 5, 1, 2, 10.00, '2026-05-18 21:53:31', 'Pending', 0, '2026-05-18 19:47:31'),
(9, 1, 4, 2, 10.00, '2026-05-19 07:13:22', 'Pending', 0, '2026-05-19 05:13:22'),
(10, 7, 4, 2, 10.00, '2026-05-19 17:06:59', 'Pending', 0, '2026-05-19 14:57:59'),
(11, 8, 3, 2, 10.00, '2026-05-19 17:13:01', 'Pending', 0, '2026-05-19 15:13:01'),
(12, 1, 1, 2, 10.00, '2026-05-19 20:39:52', 'Pending', 0, '2026-05-19 18:30:52'),
(13, 1, 1, 2, 10.00, '2026-05-19 21:52:29', 'Pending', 0, '2026-05-19 19:40:29'),
(14, 1, 4, 2, 10.00, '2026-05-19 21:58:40', 'Pending', 0, '2026-05-19 19:46:40'),
(15, 1, 4, 2, 10.00, '2026-05-19 22:16:18', 'Pending', 0, '2026-05-19 20:01:18'),
(16, 1, 4, 2, 10.00, '2026-05-19 22:30:01', 'Pending', 0, '2026-05-19 20:12:01'),
(17, 1, 1, 2, 10.00, '2026-05-19 22:55:31', 'Pending', 0, '2026-05-19 20:40:31'),
(18, 1, 1, 2, 10.00, '2026-05-19 23:14:36', 'Pending', 0, '2026-05-19 20:56:36'),
(19, 1, 1, 2, 10.00, '2026-05-19 23:27:11', 'Pending', 0, '2026-05-19 21:06:11'),
(20, 1, 1, 2, 10.00, '2026-05-20 00:43:27', 'Pending', 0, '2026-05-19 22:43:27'),
(21, 1, 4, 2, 10.00, '2026-05-20 05:23:52', 'Pending', 0, '2026-05-20 03:23:52'),
(22, 1, 4, 2, 10.00, '2026-05-20 05:34:56', 'Pending', 0, '2026-05-20 03:34:56'),
(23, 1, 4, 2, 10.00, '2026-05-20 05:40:24', 'Pending', 0, '2026-05-20 03:40:24'),
(24, 19, 4, 2, 10.00, '2026-05-28 18:12:46', 'Pending', 0, '2026-05-28 15:42:46'),
(25, 20, 4, 2, 10.00, '2026-05-28 18:35:37', 'Pending', 0, '2026-05-28 16:02:37'),
(26, 1, 4, 2, 10.00, '2026-05-28 19:47:47', 'Pending', 0, '2026-05-28 17:11:47'),
(27, 22, 4, 2, 10.00, '2026-05-28 20:46:20', 'Pending', 0, '2026-05-28 18:07:20'),
(28, 23, 4, 2, 10.00, '2026-05-30 17:37:16', 'Pending', 0, '2026-05-30 14:55:16'),
(29, 24, 4, 2, 10.00, '2026-05-30 17:59:30', 'Pending', 0, '2026-05-30 15:14:30'),
(30, 24, 4, 2, 10.00, '2026-05-30 18:18:24', 'Pending', 0, '2026-05-30 15:30:24'),
(31, 25, 4, 2, 10.00, '2026-05-30 18:30:50', 'Pending', 0, '2026-05-30 15:39:50'),
(32, 26, 4, 2, 10.00, '2026-05-31 18:36:38', 'Pending', 0, '2026-05-31 15:42:38'),
(33, 1, 4, 2, 10.00, '2026-05-31 19:50:26', 'Pending', 0, '2026-05-31 16:53:26'),
(34, 28, 4, 3, 10.00, '2026-06-01 15:22:00', 'Pending', 0, '2026-06-01 12:22:00'),
(35, 29, 4, 2, 30.00, '2026-06-01 16:13:56', 'Pending', 0, '2026-06-01 13:10:56'),
(36, 47, 4, 2, 14.00, '2026-06-08 14:35:26', 'Pending', 0, '2026-06-08 11:29:26'),
(37, 54, 4, 1, 30.00, '2026-06-09 11:39:24', 'Pending', 0, '2026-06-09 08:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `fuel_type_id` int(11) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `price_per_liter` decimal(10,2) DEFAULT NULL,
  `transaction_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `token_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `transactions`
--
DELIMITER $$
CREATE TRIGGER `UpdateInventoryAfterTransaction` AFTER INSERT ON `transactions` FOR EACH ROW BEGIN
    UPDATE fuelinventory 
    SET quantity_available = quantity_available - NEW.quantity
    WHERE fuel_type_id = NEW.fuel_type_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `phone`, `email`, `password_hash`, `status`, `created_at`) VALUES
(24, 'Alfi Shahriar', '01409717450', 'joyalfi22@gmail.com', '$2y$10$B/MKOg/fAXUQPPKcBh.1VeDq9l6bbh0g5hFJdxn6cwELSNX/.zeMy', 'active', '2026-05-13 02:39:48'),
(26, 'Asia Kabir Shafa', '01314050467', 'joyalfi22@gmail.com', '$2y$10$k7JNh4EUugQxIGZHjkfSJuiNZ6AMNnnDSDgq.UYHB4GnBXIu.s8ae', 'active', '2026-05-19 14:55:27'),
(27, 'Naima islam nabila', '01910721317', 'joyalfi22@gmail.com', '$2y$10$G7hSipXLSvzknYqY1bp28eA.kkqUuH7.KcYk166Z2rp8Lo2zW0OAi', 'active', '2026-05-19 21:03:40'),
(28, 'Robin Khan', '01792909237', 'joyalfi22@gmail.com', '$2y$10$sso6glLYiXRHqfBtC3BGn.XWIXCBOvYlAOMlOCdkA0NlOhuSEOD1O', 'active', '2026-06-08 17:02:01');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `plate_number` varchar(100) NOT NULL,
  `vehicle_type_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `weekly_limit` float DEFAULT 40
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `plate_number`, `vehicle_type_id`, `user_id`, `weekly_limit`) VALUES
(1, 'DHAKA-METRO-GA-11-22', NULL, NULL, 40),
(3, 'DHAKA-METRO-CHHA-11-', 1, 24, 40),
(4, 'DHAKA-METRO-GHA-11-2', NULL, 24, 40),
(5, 'DHAKA-METRO-BA-11-22', 5, 24, 40),
(7, 'DHAKA-METRO-HA-11-22', 4, 26, 40),
(8, 'DHAKA-METRO-HA-01-11', 4, 26, 40),
(19, 'DHAKA-METRO-HA-01-23', 4, 24, 40),
(20, 'DHAKA-METRO-KHA-11-2', 3, 24, 40),
(22, 'DHAKA-METRO-KHA-01-2', 3, 24, 40),
(23, 'KHULNA-METRO-HA-23-0', 4, 24, 40),
(24, 'KHULNA-METRO-HA-11-2', 4, 24, 40),
(25, 'KHULNA-METRO-BA-01-2', 5, 24, 40),
(26, 'DHAKA-METRO-BA-01-23', 5, 24, 40),
(28, 'COMILLA-METRO-BA-01-', 5, 24, 40),
(29, 'BOGRA-METRO-KHA-01-2', 3, 24, 40),
(47, 'RANGPUR-METRO-GA-01-', 2, 24, 40),
(54, 'COMILLA-METRO-CHA-89-2323', 6, 24, 40);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_types`
--

CREATE TABLE `vehicle_types` (
  `vehicle_type_id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `priority_level` int(11) DEFAULT 1,
  `plate_color` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicle_types`
--

INSERT INTO `vehicle_types` (`vehicle_type_id`, `type_name`, `priority_level`, `plate_color`) VALUES
(1, 'Private Car', 1, ''),
(2, 'Motorcycle', 1, ''),
(3, 'Microbus/Jeep', 1, ''),
(4, 'Taxi', 1, ''),
(5, 'Bus', 1, ''),
(6, 'Truck', 1, ''),
(7, 'Tractor', 1, ''),
(8, 'Auto Rickshaw', 1, ''),
(9, 'Private Car (New)', 1, ''),
(10, 'Ambulance', 1, ''),
(11, 'Government', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `station_id` (`station_id`);

--
-- Indexes for table `fuelinventory`
--
ALTER TABLE `fuelinventory`
  ADD PRIMARY KEY (`inventory_id`);

--
-- Indexes for table `fuelorder`
--
ALTER TABLE `fuelorder`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `fuelprices`
--
ALTER TABLE `fuelprices`
  ADD PRIMARY KEY (`price_id`);

--
-- Indexes for table `fueltypes`
--
ALTER TABLE `fueltypes`
  ADD PRIMARY KEY (`fuel_type_id`);

--
-- Indexes for table `inventoryalerts`
--
ALTER TABLE `inventoryalerts`
  ADD PRIMARY KEY (`alert_id`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`machine_id`),
  ADD KEY `inventory_id` (`inventory_id`);

--
-- Indexes for table `meterreadings`
--
ALTER TABLE `meterreadings`
  ADD PRIMARY KEY (`reading_id`),
  ADD KEY `machines_id` (`machines_id`);

--
-- Indexes for table `odometerlogs`
--
ALTER TABLE `odometerlogs`
  ADD PRIMARY KEY (`log_id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD UNIQUE KEY `owner_id` (`owner_id`,`full_name`,`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `priorityvehicles`
--
ALTER TABLE `priorityvehicles`
  ADD PRIMARY KEY (`priority_id`),
  ADD UNIQUE KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`station_id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`token_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fuel_type_id` (`fuel_type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- Indexes for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD PRIMARY KEY (`vehicle_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fuelinventory`
--
ALTER TABLE `fuelinventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `machine_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meterreadings`
--
ALTER TABLE `meterreadings`
  MODIFY `reading_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `odometerlogs`
--
ALTER TABLE `odometerlogs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `machines`
--
ALTER TABLE `machines`
  ADD CONSTRAINT `machines_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `fuelinventory` (`inventory_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `meterreadings`
--
ALTER TABLE `meterreadings`
  ADD CONSTRAINT `meterreadings_ibfk_1` FOREIGN KEY (`machines_id`) REFERENCES `machines` (`machine_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `odometerlogs`
--
ALTER TABLE `odometerlogs`
  ADD CONSTRAINT `odometerlogs_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `odometerlogs_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`fuel_type_id`) REFERENCES `fueltypes` (`fuel_type_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
