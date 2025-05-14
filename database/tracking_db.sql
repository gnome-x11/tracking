-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 25, 2025 at 06:35 PM
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
(8, 8, 'Invalid credential', '2025-04-25 20:53:13');

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
(20, 'wqer', 'wqer', 'qwer', 21239200, 'qwer', 'wer', 'qwer', 'qwer', 'qwer', 'qwer', '1745563446_Screenshot 2025-04-19 at 6.12.11 PM.png', 1, 'IRREGULAR', 'CBA', 'BSBA - HRDM');

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
(24, 1, 8, '2025-04-25 22:52:34');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `persons`
--
ALTER TABLE `persons`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `person_tracks`
--
ALTER TABLE `person_tracks`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `failed_login_attempts`
--
ALTER TABLE `failed_login_attempts`
  ADD CONSTRAINT `failed_login_attempts_ibfk_1` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
