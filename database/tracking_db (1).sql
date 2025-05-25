-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 20, 2025 at 05:37 AM
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
(45, 8, 'Invalid credential', '2025-05-18 20:10:20'),
(46, 8, 'Invalid credential', '2025-05-18 20:10:30'),
(47, 8, 'Invalid credential', '2025-05-18 20:10:54'),
(48, 8, 'Invalid credential', '2025-05-18 20:10:58'),
(49, 8, 'Invalid credential', '2025-05-18 20:11:07'),
(50, 8, 'Invalid credential', '2025-05-18 20:13:07');

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
(92, 1, 8, '2025-05-18 18:46:20'),
(93, 1, 8, '2025-05-18 20:11:25');

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
  `token_expiry` datetime NOT NULL,
  `imagePath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`visitor_id`, `full_name`, `contact_number`, `email`, `purpose`, `establishment_id`, `created_at`, `token`, `token_expiry`, `imagePath`) VALUES
(191222, 'Dexter Aromin', '09128171470', 'dex.raromin@gmail.com', 'longanissa seller', 8, '2025-05-18', 'e2f605d29a5a9a99038a8a21d42c095e', '2025-05-19 14:04:35', 'visitor_img/visitor_1747569875.png'),
(191223, 'Dexter Aromin', '09128171470', 'dex.raromin@gmail.com', 'hchghgchgchgf', 8, '2025-05-18', '00f7d970d27e532ac60a3326826088ab', '2025-05-19 14:13:26', 'visitor_img/visitor_1747570406.png'),
(191224, 'Dexter Aromin', '09128171470', 'dex.raromin@gmail.com', 'mag bbq', 8, '2025-05-19', '73177cc7ab9ccab8d57a619db7a4119e', '2025-05-20 21:07:49', 'visitor_img/visitor_1747681669.png');

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
(18, 191222, 8, '2025-05-18 20:10:20', '2025-05-18 20:10:30'),
(19, 191222, 8, '2025-05-18 20:11:07', '2025-05-18 20:11:30'),
(20, 191222, 8, '2025-05-18 20:13:00', '2025-05-18 20:13:07');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `persons`
--
ALTER TABLE `persons`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `person_tracks`
--
ALTER TABLE `person_tracks`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `visitor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191225;

--
-- AUTO_INCREMENT for table `visitor_logs`
--
ALTER TABLE `visitor_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
