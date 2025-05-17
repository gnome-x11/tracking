-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 17, 2025 at 09:38 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tracking_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `establishments`
--

CREATE TABLE `establishments` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `establishments`
--

INSERT INTO `establishments` (`id`, `name`, `address`) VALUES
(2, 'MAIN ENTRANCE', ''),
(6, 'EMERALD HALL', ''),
(7, 'ELEVATOR ENTRANCE', ''),
(8, 'RLRC BUILDING ENTRACE', '');

-- --------------------------------------------------------

--
-- Table structure for table `failed_login_attempts`
--

CREATE TABLE `failed_login_attempts` (
  `id` int(11) NOT NULL,
  `establishment_id` int(11) NOT NULL,
  `error_message` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `failed_login_attempts`
--

INSERT INTO `failed_login_attempts` (`id`, `establishment_id`, `error_message`, `date_created`) VALUES
(11, 8, 'Invalid credential', '2025-04-26 14:38:53'),
(12, 8, 'Invalid credential', '2025-04-26 14:38:53'),
(13, 8, 'Invalid credential', '2025-04-26 14:38:53'),
(14, 8, 'Invalid credential', '2025-04-26 14:38:53'),
(15, 8, 'Invalid credential', '2025-04-26 14:38:53'),
(16, 8, 'Invalid credential', '2025-04-26 14:39:02'),
(17, 8, 'Invalid credential', '2025-04-26 14:39:02'),
(18, 8, 'Invalid credential', '2025-04-26 14:39:02'),
(19, 8, 'Invalid credential', '2025-04-26 14:39:02'),
(20, 8, 'Invalid credential', '2025-04-26 14:39:02'),
(21, 8, 'Invalid credential', '2025-05-15 22:06:22'),
(22, 8, 'Invalid credential', '2025-05-17 00:18:48'),
(23, 8, 'Invalid credential', '2025-05-17 00:30:49'),
(24, 8, 'Invalid credential', '2025-05-17 01:40:00'),
(25, 8, 'Invalid credential', '2025-05-17 09:39:09'),
(26, 8, 'Invalid credential', '2025-05-17 11:41:37'),
(27, 8, 'Invalid credential', '2025-05-17 11:56:05'),
(28, 8, 'Invalid credential', '2025-05-17 14:00:21'),
(30, 8, 'Invalid credential', '2025-05-17 15:16:05'),
(31, 8, 'Invalid credential', '2025-05-17 15:16:11'),
(32, 8, 'Invalid credential', '2025-05-17 15:16:21'),
(33, 8, 'Invalid credential', '2025-05-17 15:19:36'),
(34, 8, 'Invalid credential', '2025-05-17 15:19:51'),
(35, 8, 'Invalid credential', '2025-05-17 15:21:02'),
(36, 8, 'Invalid credential', '2025-05-17 15:26:33'),
(37, 8, 'Invalid credential', '2025-05-17 15:26:40');

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE `persons` (
  `id` int(30) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `student_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `street` text NOT NULL,
  `baranggay` text NOT NULL,
  `city` text NOT NULL,
  `state` text NOT NULL,
  `zip_code` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `year_level` int(11) DEFAULT NULL,
  `standing` enum('REGULAR','IRREGULAR') DEFAULT NULL,
  `college` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`id`, `firstname`, `middlename`, `lastname`, `student_id`, `address`, `street`, `baranggay`, `city`, `state`, `zip_code`, `photo`, `year_level`, `standing`, `college`, `course`) VALUES
(1, 'Aiah', '', 'Bini', 21239299, '123', 'Example', 'Barangay Example', 'Example City', 'Example', '1111', '1745561889_aiah.jpg', 1, 'REGULAR', 'CAS', 'BA Communication'),
(2, 'Colet', '', 'Bini', 21239300, '123', 'Example Street', 'Barangay Example', 'Example City', 'Example', '1111', '1745561992_colet.jpg', 1, 'REGULAR', 'CAS', 'BA Communication'),
(3, 'Gwen', '', 'Bini', 21239301, '123', 'Example Street', 'Barangay Example', 'Example City', 'Example', '1111', '1745562032_gwen.jpg', 1, 'REGULAR', 'CAS', 'BA Communication'),
(4, 'Jhoanna', '', 'Bini', 21239302, '123', 'Example Street', 'Barangay Example', 'Example City', 'Example ', '1111', '1745562082_jho.jpg', 1, 'REGULAR', 'CAS', 'BA Communication'),
(5, 'Maloi', '', 'Bini', 21239303, '123', 'Example Street', 'Barangay Example', 'Example City', 'Example', '1111', '1745562131_maloi.jpg', 1, 'REGULAR', 'CAS', 'BA Communication'),
(6, 'Mikha', '', 'Bini', 21239304, '123', 'Example Street', 'barangay Example', 'Example City', 'Example', '1111', '1745562192_mikha.webp', 1, 'REGULAR', 'CAS', 'BA Communication'),
(7, 'Sheena', '', 'Bini', 21239305, '123', 'Example Street', 'Barangay Example', 'Example City ', 'Example', '1111', '1745562239_sheena.jpg', 1, 'REGULAR', 'CAS', 'BA Communication'),
(8, 'Stacey', '', 'Bini', 21239306, '123', 'Example Street', 'Barangay Example', 'Example city', 'Example ', '1111', '1745562294_stacey.jpg', 1, 'REGULAR', 'CAS', 'BA Communication'),
(9, 'Mikhamot', '', 'Bini', 21239307, '123', 'Example Street', 'Barangay Example', 'Example City', 'Example', '1112', '1745562355_binimikamot.jpeg', 2, 'IRREGULAR', 'CAS', 'BA Communication'),
(10, 'Hua', 'Ping', 'Guo', 21239308, '123', 'China Street', 'Del Carmen ', 'Tarlac', 'Tarlac', '', '1745562441_alice.png', 4, 'IRREGULAR', 'IPPG', 'BS Public Administration'),
(11, 'Camille', '', 'Villar', 21239309, '123', 'Camella Street', 'BF HOME', 'Las Pinas ', 'Metro Manila', '1234', '1745562540_camille.jpg', 4, 'REGULAR', 'CBA', 'BSBA - HRDM'),
(12, 'Cynthia', '', 'Vilar', 21239310, '123', 'Camella St.', 'BF Homes', 'Las Pinas ', 'Metro Manila', '1234', '1745562611_cynthia.jpg', 4, 'REGULAR', 'ISW', 'BS Social Work'),
(13, 'Roady', '', 'Duterte ', 2139311, '', '', '', '', 'The Hauge Netherlands', '1234', '1745562751_duterte.jpeg', 5, 'IRREGULAR', 'CCJ', 'BS Criminology'),
(14, 'Robredo', '', 'Leni', 21239312, '123', 'Naga Street', 'Barangay Naga', 'Naga City', 'Visayas', '1234', '1745562844_leni.jpg', 3, 'REGULAR', 'IPPG', 'BS Public Administration'),
(15, 'Manny', '', 'Pacman', 21239313, '123', 'Boxing Street', 'Tipaklong', 'Edi wow', 'Davao', '1234', '1745562919_manny.webp', 2, 'IRREGULAR', 'CBA', 'BSBA - Marketing'),
(16, 'Blembong ', '', 'Marcos', 21239314, '345', 'Malacanang Palace ', '', 'Pasig City', 'Metro Manila', '666', '1745563006_marcos.jpg', 1, 'REGULAR', 'CTE', 'BSEd - English'),
(17, 'Nigga', '', 'Obama', 21239315, '3434', 'Washington DC', '', '', 'Washington DC', '', '1745563075_obama.jpeg', 3, 'IRREGULAR', 'CITCS', 'BS Information Technology'),
(18, 'Sarah', '', 'Zimerman', 21239316, '23423', 'Malacanang Palace', '', '', 'Metro Manila', '', '1745563137_sarah.jpg', 2, 'REGULAR', 'CTE', 'BSEd - English'),
(19, 'Trump', '', 'Donald ', 21239317, '24242', 'tarif them again street', '', 'Washington', 'USA', '', '1745563196_trump.jpg', 2, 'REGULAR', 'CITCS', 'BS Information Technology'),
(20, 'wqer', 'wqer', 'qwer', 21239200, 'qwer', 'wer', 'qwer', 'qwer', 'qwer', 'qwer', '1745563446_Screenshot 2025-04-19 at 6.12.11 PM.png', 1, 'IRREGULAR', 'CBA', 'BSBA - HRDM'),
(21, 'Angelo', 'Vallente', 'Mendoza', 21239240, '1', '1', '1', '1', '1', '1', '1745649658_ChatGPT Image Apr 18, 2025, 10_23_37 PM_upscayl_4x_digital-art-4x.png', 4, 'REGULAR', 'CITCS', 'BS Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `person_tracks`
--

CREATE TABLE `person_tracks` (
  `id` int(30) NOT NULL,
  `person_id` int(30) NOT NULL,
  `establishment_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person_tracks`
--

INSERT INTO `person_tracks` (`id`, `person_id`, `establishment_id`, `date_created`) VALUES
(16, 1, 8, '2025-04-25 20:20:50'),
(17, 1, 8, '2025-04-25 20:26:58'),
(18, 1, 8, '2025-04-25 20:29:28'),
(19, 1, 8, '2025-04-25 20:29:59'),
(20, 1, 8, '2025-04-25 20:34:49'),
(21, 1, 8, '2025-04-25 20:52:25'),
(22, 1, 8, '2025-04-25 20:53:05'),
(23, 1, 8, '2025-04-25 21:07:21'),
(24, 1, 8, '2025-04-25 22:52:34'),
(25, 2, 8, '2025-04-26 00:56:38'),
(26, 9, 8, '2025-04-26 14:22:43'),
(27, 9, 8, '2025-04-26 14:23:59'),
(28, 17, 8, '2025-04-26 14:30:02'),
(29, 1, 8, '2025-04-26 14:34:05'),
(30, 1, 8, '2025-04-26 14:34:22'),
(31, 17, 8, '2025-04-26 14:35:06'),
(32, 17, 8, '2025-04-26 14:35:47'),
(33, 17, 8, '2025-04-26 14:38:41'),
(34, 17, 8, '2025-04-26 14:38:55'),
(35, 17, 8, '2025-04-26 14:39:05'),
(36, 17, 8, '2025-04-26 14:39:25'),
(37, 21, 8, '2025-04-26 14:41:20'),
(38, 21, 8, '2025-04-26 14:45:14'),
(39, 21, 8, '2025-04-26 14:45:22'),
(40, 21, 8, '2025-04-26 14:46:18'),
(41, 21, 8, '2025-04-26 14:47:56'),
(42, 21, 8, '2025-04-26 14:49:07'),
(43, 21, 8, '2025-04-26 14:49:27'),
(44, 21, 8, '2025-04-26 14:49:44'),
(45, 21, 8, '2025-04-26 14:49:56'),
(46, 21, 8, '2025-04-26 14:50:04'),
(47, 21, 8, '2025-04-26 14:50:08'),
(48, 21, 8, '2025-04-26 14:50:50'),
(49, 21, 8, '2025-04-26 14:51:07'),
(50, 21, 8, '2025-04-26 14:51:11'),
(51, 21, 8, '2025-04-26 14:51:19'),
(52, 21, 8, '2025-04-26 14:51:22'),
(53, 21, 8, '2025-04-26 14:52:10'),
(54, 21, 8, '2025-04-26 14:52:20'),
(55, 21, 8, '2025-04-26 14:52:31'),
(56, 21, 8, '2025-04-26 14:52:47'),
(57, 21, 8, '2025-04-26 15:07:08'),
(58, 21, 8, '2025-04-26 15:07:14'),
(59, 21, 8, '2025-04-26 15:07:16'),
(60, 21, 8, '2025-04-26 15:07:22'),
(61, 21, 8, '2025-04-26 15:07:32'),
(62, 21, 8, '2025-04-26 15:07:38'),
(63, 21, 8, '2025-04-26 15:09:08'),
(64, 21, 8, '2025-04-26 15:09:14'),
(65, 21, 8, '2025-04-26 15:10:38'),
(66, 21, 8, '2025-04-26 15:11:01'),
(67, 21, 8, '2025-04-26 15:11:04'),
(68, 17, 8, '2025-04-26 15:11:22'),
(69, 21, 8, '2025-04-26 15:15:43'),
(70, 21, 8, '2025-04-26 15:15:52'),
(71, 17, 8, '2025-04-26 15:16:00'),
(72, 21, 8, '2025-04-26 15:16:08'),
(73, 21, 8, '2025-04-26 15:16:21'),
(74, 21, 8, '2025-04-26 15:20:44'),
(75, 21, 8, '2025-04-26 15:20:59'),
(76, 21, 8, '2025-04-26 15:21:34'),
(77, 21, 8, '2025-04-26 15:21:51'),
(78, 1, 8, '2025-05-17 00:23:15'),
(79, 1, 8, '2025-05-17 00:30:34'),
(80, 1, 8, '2025-05-17 01:40:10'),
(81, 1, 8, '2025-05-17 09:39:21'),
(82, 1, 8, '2025-05-17 10:00:09'),
(83, 1, 8, '2025-05-17 10:00:20'),
(84, 1, 8, '2025-05-17 10:02:57'),
(85, 1, 8, '2025-05-17 10:03:39'),
(86, 1, 8, '2025-05-17 10:04:14'),
(87, 1, 8, '2025-05-17 10:04:32'),
(88, 1, 8, '2025-05-17 11:54:08'),
(89, 1, 8, '2025-05-17 12:04:46'),
(90, 1, 8, '2025-05-17 12:41:40'),
(91, 1, 8, '2025-05-17 15:17:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 2 COMMENT '1 = Admin, 2= establishment_staff',
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `establishment_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `type`, `username`, `password`, `establishment_id`) VALUES
(1, 'Administrator', 1, 'admin', '0192023a7bbd73250516f069df18b500', 0),
(9, 'EMERALD HALL', 2, 'emerald_hall_kiosk', '25d55ad283aa400af464c76d713c07ad', 6),
(10, 'ELEVATOR ENTRANCE', 2, 'elevator_kiosk', '25f9e794323b453885f5181f1b624d0b', 7),
(11, 'MAIN ENTRACE', 2, 'main_kiosk', '25f9e794323b453885f5181f1b624d0b', 2),
(12, 'RLRC BUILDING ENTRACE', 2, 'rlrc_kiosk', '25f9e794323b453885f5181f1b624d0b', 8);

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `visitor_id` int(11) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `contact_number` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `purpose` text NOT NULL,
  `establishment_id` int(25) NOT NULL,
  `created_at` date NOT NULL,
  `token` varchar(32) NOT NULL,
  `token_expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`visitor_id`, `full_name`, `contact_number`, `email`, `purpose`, `establishment_id`, `created_at`, `token`, `token_expiry`) VALUES
(191201, 'Dexter Aromin', '09128171470', 'chichayaromin76@gmail.com', 'ewrwerwerwer', 0, '2025-05-17', '4b706404127370be1278bddb139e4dd9', '2025-05-18 08:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_logs`
--

CREATE TABLE `visitor_logs` (
  `log_id` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `establishment_id` int(11) NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitor_logs`
--

INSERT INTO `visitor_logs` (`log_id`, `visitor_id`, `establishment_id`, `time_in`, `time_out`) VALUES
(1, 191201, 8, '2025-05-17 14:08:03', '2025-05-17 14:55:31'),
(2, 191201, 8, '2025-05-17 15:04:45', '2025-05-17 15:05:05'),
(3, 191201, 8, '2025-05-17 15:14:09', '2025-05-17 15:15:15'),
(4, 191201, 8, '2025-05-17 15:16:11', '2025-05-17 15:16:21'),
(5, 191201, 8, '2025-05-17 15:17:58', '2025-05-17 15:19:24'),
(6, 191201, 8, '2025-05-17 15:19:36', '2025-05-17 15:19:51'),
(7, 191201, 8, '2025-05-17 15:20:34', '2025-05-17 15:20:52'),
(8, 191201, 8, '2025-05-17 15:21:02', '2025-05-17 15:24:04'),
(9, 191201, 8, '2025-05-17 15:26:33', '2025-05-17 15:26:40'),
(10, 191201, 8, '2025-05-17 15:27:59', '2025-05-17 15:28:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `establishments`
--
ALTER TABLE `establishments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `establishment_id` (`establishment_id`);

--
-- Indexes for table `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `person_tracks`
--
ALTER TABLE `person_tracks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`visitor_id`);

--
-- Indexes for table `visitor_logs`
--
ALTER TABLE `visitor_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_visitor_id` (`visitor_id`),
  ADD KEY `fk_establishment_id` (`establishment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `establishments`
--
ALTER TABLE `establishments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `persons`
--
ALTER TABLE `persons`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `person_tracks`
--
ALTER TABLE `person_tracks`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `visitor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191202;

--
-- AUTO_INCREMENT for table `visitor_logs`
--
ALTER TABLE `visitor_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  ADD CONSTRAINT `failed_login_attempts_ibfk_1` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);

--
-- Constraints for table `visitor_logs`
--
ALTER TABLE `visitor_logs`
  ADD CONSTRAINT `fk_establishment_id` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_visitor_id` FOREIGN KEY (`visitor_id`) REFERENCES `visitors` (`visitor_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
