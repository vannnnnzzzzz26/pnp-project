-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2024 at 05:36 PM
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
-- Database: `dbcomplaints`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brg_official`
--

CREATE TABLE `tbl_brg_official` (
  `offcial_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `position` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_brg_official`
--

INSERT INTO `tbl_brg_official` (`offcial_id`, `name`, `position`, `image`) VALUES
(1, 'bj', 'captain', 'uploads/bj.jpg'),
(5, 'excel', 'Kagawad 1', 'uploads/excel.jpg'),
(6, 'aneluv', 'Kagawad 2', 'uploads/profile_668bfe5787a96.jpg'),
(7, 'haryl', 'Kagawad 3', 'uploads/bj.jpg'),
(8, 'robert', 'kagawad4', 'uploads/387067447_1944047915954275_4632907408411000112_n.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaintcategories`
--

CREATE TABLE `tbl_complaintcategories` (
  `category_id` int(10) NOT NULL,
  `complaints_category` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_complaintcategories`
--

INSERT INTO `tbl_complaintcategories` (`category_id`, `complaints_category`) VALUES
(229, 'Noise Complaints'),
(230, 'Sanitation and Cleanlines');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaints`
--

CREATE TABLE `tbl_complaints` (
  `complaints_id` int(15) NOT NULL,
  `complaint_name` varchar(255) NOT NULL,
  `cp_number` varchar(10) NOT NULL,
  `complaints_person` varchar(15) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT '''unresolved''',
  `complaints` text NOT NULL,
  `responds` enum('barangay','pnp') NOT NULL,
  `date_filed` date NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `category_id` int(1) NOT NULL,
  `barangays_id` int(10) NOT NULL,
  `image_id` int(10) NOT NULL,
  `info_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_complaints`
--

INSERT INTO `tbl_complaints` (`complaints_id`, `complaint_name`, `cp_number`, `complaints_person`, `status`, `complaints`, `responds`, `date_filed`, `approved`, `category_id`, `barangays_id`, `image_id`, `info_id`, `user_id`) VALUES
(243, 'denver castillio Concepcion ', '0955151515', 'bj', 'settled', 'dsfsfsf', 'barangay', '2024-07-15', 0, 229, 80, 257, 172, 0),
(244, 'denver castillio Concepcion ', '0955151515', 'bj', 'Approved', 'daadaa', '', '2024-07-15', 0, 230, 80, 258, 173, 0),
(245, 'trishaaaaaaaaaaaaa nnnn yaranon ', '0955151515', 'scd', 'Unresolved', 'dnsdnsnds', 'barangay', '2024-07-15', 0, 229, 74, 259, 174, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_image`
--

CREATE TABLE `tbl_image` (
  `image_id` int(10) NOT NULL,
  `complaint_id` int(10) NOT NULL,
  `image_type` enum('ID') NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `date_uploaded` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_image`
--

INSERT INTO `tbl_image` (`image_id`, `complaint_id`, `image_type`, `image_path`, `date_uploaded`) VALUES
(257, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-15 14:46:31.000000'),
(258, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-15 14:50:25.000000'),
(259, 0, 'ID', 'uploads/PhilID-specimen-Front_highres1-1024x576.png', '2024-07-15 15:00:42.000000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_info`
--

CREATE TABLE `tbl_info` (
  `info_id` int(10) NOT NULL,
  `age` int(10) NOT NULL,
  `gender` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_info`
--

INSERT INTO `tbl_info` (`info_id`, `age`, `gender`) VALUES
(172, 21, 'Male'),
(173, 21, 'Male'),
(174, 21, 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(15) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `extension_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `accountType` enum('Resident','Barangay Official','PNP Officer') NOT NULL,
  `barangays_id` int(11) NOT NULL,
  `pic_data` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `first_name`, `middle_name`, `last_name`, `extension_name`, `email`, `password`, `accountType`, `barangays_id`, `pic_data`) VALUES
(57, 'leslie', 'pascual', 'Rigor', '', 'leslie05@gmail.com', '$2y$10$QLUcTwKAuuZuydlC523K3OEues.Nj.KJ2XsbvPkmOUK8FaMErXda6', 'Barangay Official', 69, 'uploads/profile_668c193bcead3.jpg'),
(58, 'bj', 'villanueva', 'Aquino', '', 'bj@gmail.com', '$2y$10$F75Q4yMI6q.A4vmireAKK.1v6hC2KIvBipBhIZ99477Ahk6rBhVZu', 'Barangay Official', 70, 'uploads/profile_668c1962cbf04.jpg'),
(59, 'aneluv', 'castillio', 'Gamet', '', 'robert@gmail.com', '$2y$10$eNbLG2VeNQsi46wuU6whAu9sDQgpxYpZ/A6EA6gRd7S8UCzpuH62S', 'Barangay Official', 71, 'uploads/profile_668cb126dad1b.jpg'),
(61, 'denver', 'na', 'gorospe', '', 'denver@gmail.com', '$2y$10$gsUUOjhIG/GXQh.YMaq6Z.MHh0/wEObcN9BBm9KSFqOB0l07xLaJ.', 'Resident', 73, 'uploads/profile_668d495dec94d.jpg'),
(62, 'trishaaaaaaaaaaaaa', 'nnnn', 'yaranon', '', 'trisha@gmail.com', '$2y$10$MmYfrZzXkar1VHzijB9OneKRsN/PLLtx3qOBg7CuFS6m13dPokHRK', 'Resident', 74, 'uploads/profile_668d4c0ed50c7.jpg'),
(64, 'excel', 'nnnn', 'preza', '', 'excel27@gmail.com', '$2y$10$G6zRwH9OBvvZgkCDr2vK1.64/o4fmaTqPCyAjYorUqMw.AXEd/SyC', 'Resident', 76, 'uploads/profile_668d5cd866fa4.jpg'),
(65, 'bj', 'villanueva', 'Aquino', '', 'bjaquinovanz26@gmail.com', '$2y$10$NHlNig5GFhZ6wKq/kpXrF.nJwbf4rlQToSHPqm00667sAGS7hAp/.', 'Resident', 77, 'uploads/profile_668d5d8ed14d5.jpg'),
(66, 'haryl', 'Balla', 'Concepcion', '', 'haryl@gmail.com', '$2y$10$7zswhrBiqL2hihRsdzwi8.sCTP6gJW9D1yp90AEV76M0IdaevVbNO', 'PNP Officer', 78, 'uploads/profile_668e06d10cca2.jpg'),
(67, 'trishaaaaaaaaaaaaa', 'castillio', 'Rigor', '', 'barangay@gmail.com', '$2y$10$BtDlN/zXB.z5GdeDBs9CbOBaYE6kAIvmhqoUruY5xO1gNnfYqiWBC', 'Barangay Official', 79, 'uploads/profile_668e0a02d6931.jpg'),
(68, 'denver', 'castillio', 'Concepcion', '', 'resident@gmail.com', '$2y$10$oJlU4KqM8OgM5R30B19ZLeVaGrljY51NCuAh2GuAMglbEmWEq4CYK', 'Resident', 80, 'uploads/profile_668e0a3d79c36.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_barangay`
--

CREATE TABLE `tbl_users_barangay` (
  `barangays_id` int(11) NOT NULL,
  `barangay_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users_barangay`
--

INSERT INTO `tbl_users_barangay` (`barangays_id`, `barangay_name`) VALUES
(69, 'Angoluan'),
(70, 'Angoluan'),
(71, 'Benguet'),
(73, 'Benguet'),
(74, 'Angoluan'),
(75, 'Angoluan'),
(76, 'Angoluan'),
(77, 'Angoluan'),
(78, 'Benguet'),
(79, 'Villa Tanza'),
(80, 'Villa Tanza');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_brg_official`
--
ALTER TABLE `tbl_brg_official`
  ADD PRIMARY KEY (`offcial_id`);

--
-- Indexes for table `tbl_complaintcategories`
--
ALTER TABLE `tbl_complaintcategories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  ADD PRIMARY KEY (`complaints_id`),
  ADD KEY `tbl_complaints_ibfk_2` (`category_id`),
  ADD KEY `tbl_complaints_ibfk_3` (`image_id`),
  ADD KEY `tbl_complaints_ibfk_4` (`info_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tbl_complaints_ibfk_5` (`barangays_id`);

--
-- Indexes for table `tbl_image`
--
ALTER TABLE `tbl_image`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `tbl_info`
--
ALTER TABLE `tbl_info`
  ADD PRIMARY KEY (`info_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `tbl_users_ibfk_2` (`barangays_id`),
  ADD KEY `tbl_users_ibfk_3` (`pic_data`);

--
-- Indexes for table `tbl_users_barangay`
--
ALTER TABLE `tbl_users_barangay`
  ADD PRIMARY KEY (`barangays_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_brg_official`
--
ALTER TABLE `tbl_brg_official`
  MODIFY `offcial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_complaintcategories`
--
ALTER TABLE `tbl_complaintcategories`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  MODIFY `complaints_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `tbl_image`
--
ALTER TABLE `tbl_image`
  MODIFY `image_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- AUTO_INCREMENT for table `tbl_info`
--
ALTER TABLE `tbl_info`
  MODIFY `info_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tbl_users_barangay`
--
ALTER TABLE `tbl_users_barangay`
  MODIFY `barangays_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_complaints`
--
ALTER TABLE `tbl_complaints`
  ADD CONSTRAINT `tbl_complaints_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `tbl_complaintcategories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_complaints_ibfk_3` FOREIGN KEY (`image_id`) REFERENCES `tbl_image` (`image_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_complaints_ibfk_4` FOREIGN KEY (`info_id`) REFERENCES `tbl_info` (`info_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_complaints_ibfk_5` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `tbl_users_ibfk_2` FOREIGN KEY (`barangays_id`) REFERENCES `tbl_users_barangay` (`barangays_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
