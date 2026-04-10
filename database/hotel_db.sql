-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2026 at 05:04 AM
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
-- Database: `hotel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `check_in` date DEFAULT NULL,
  `check_out` date DEFAULT NULL,
  `booking_source` enum('Walk-in','Phone','Online') DEFAULT NULL,
  `booking_status` enum('Reserved','Checked-In','Stay-Over','Checked-Out') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `guest_id`, `room_id`, `staff_id`, `check_in`, `check_out`, `booking_source`, `booking_status`) VALUES
(33, 25, 2, 17, '2026-03-26', '2026-03-28', 'Walk-in', 'Checked-In'),
(34, 26, 3, 17, '2026-03-26', '2026-03-28', 'Walk-in', 'Checked-Out');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `guest_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `id_type` enum('Passport','National ID','Drivers License') DEFAULT NULL,
  `id_number` varchar(50) DEFAULT NULL,
  `contact_info` varchar(100) DEFAULT NULL,
  `loyalty_status` enum('New','Regular','VIP') DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`guest_id`, `full_name`, `id_type`, `id_number`, `contact_info`, `loyalty_status`) VALUES
(25, 'lyas', 'Passport', '123', NULL, 'New'),
(26, 'Motto Toy', 'Passport', '234', NULL, 'New');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `housekeeping_status` enum('Clean','Dirty','Inspecting') DEFAULT 'Clean',
  `price_per_night` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_number`, `room_type`, `housekeeping_status`, `price_per_night`, `description`, `photo`) VALUES
(1, '101', 'Standard', 'Dirty', 120.00, '', '1773331904_room2.jpg'),
(2, '102', 'Deluxe Suite', 'Dirty', 200.00, 'cool room for summer', '1773331286_room1.jpg'),
(3, '103', 'Standard', 'Dirty', 120.00, '', '1773331918_room3.jpg'),
(4, '104', 'Executive King', 'Clean', 250.00, NULL, NULL),
(5, '105', 'Deluxe Suite', 'Clean', 1000.00, NULL, NULL),
(6, '106', 'Standard', 'Clean', 210.00, NULL, NULL),
(7, '107', 'Standard', 'Clean', 2101.00, NULL, NULL),
(8, '109', 'Standard', 'Clean', 3223.00, NULL, NULL),
(9, '112', 'Standard', 'Clean', 232.00, NULL, NULL),
(10, '223', 'Standard', 'Clean', 422.00, NULL, NULL),
(11, '224', 'Deluxe Suite', 'Clean', 245.00, 'Best room in the house brooooo', '1773332015_room4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Receptionist') DEFAULT 'Receptionist'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `username`, `password`, `role`) VALUES
(17, 'kuan', '$2y$10$xTp5OMHddktMxSJswvE2MOuXMNiji6DSHNGTsdcCp2RiNh7CkFRVC', 'Admin'),
(18, 'kuans', '$2y$10$0ofu66ZUqUzytZj/VO/bu.g1td8DdwiQo5PojlqDC..Z1hcqoJQGC', 'Receptionist'),
(19, 'kuan', '$2y$10$YY06UwGuPqyi9YluttqL1ewZwnYt69gry4AHfkJz5o0I16G3LSU9W', 'Receptionist'),
(20, 'you', '$2y$10$.JPIQoH/aciC/KhKTr8jTu90ndrlZkfC.I99D/CPP7qPk60Qm7ERu', ''),
(21, 'youu', '$2y$10$oWLTKSql08PlcCkGwDiy3uNeJVlZlElqofDYILZ8KLsqy8i/.54.i', 'Admin'),
(22, 'test', '$2y$10$IOI.bs6OaTQoZ90Rj7RiEu9eusYfWH55WuofAXdUwjW4Lm0kS4LxO', 'Admin'),
(23, 'Admin', '$2y$10$YEV.vRrqGu2oQsmwv68MMuPutxFEmbc/eWGatdLabv0lkBsNtEcxq', 'Admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`guest_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
