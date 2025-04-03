-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2025 at 11:41 AM
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
-- Database: `genaiprompt`
--

-- --------------------------------------------------------

--
-- Table structure for table `annotator`
--

CREATE TABLE `annotator` (
  `id` int(11) NOT NULL,
  `Profile_id` int(11) NOT NULL,
  `annotator_code` varchar(50) NOT NULL,
  `annotator_word` varchar(255) NOT NULL,
  `annotator_status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `annotator`
--

INSERT INTO `annotator` (`id`, `Profile_id`, `annotator_code`, `annotator_word`, `annotator_status`) VALUES
(1, 2, '214', 'SRM ap', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `persona`
--

CREATE TABLE `persona` (
  `id` int(11) NOT NULL,
  `Profile_id` int(11) NOT NULL,
  `persona_code` varchar(50) NOT NULL,
  `persona_name` varchar(100) NOT NULL,
  `persona_role` varchar(100) NOT NULL,
  `persona_designation` varchar(100) NOT NULL,
  `persona_email` varchar(100) NOT NULL,
  `persona_details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `persona`
--

INSERT INTO `persona` (`id`, `Profile_id`, `persona_code`, `persona_name`, `persona_role`, `persona_designation`, `persona_email`, `persona_details`) VALUES
(2, 2, '16', 'Ajay', 'HR', 'rhdr', 'fwesgtg@gmail.com', 'dfdsbfsafqwe');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `profile_code` varchar(50) NOT NULL,
  `profile_name` varchar(100) NOT NULL,
  `profile_email` varchar(100) NOT NULL,
  `profile_number` varchar(15) NOT NULL,
  `profile_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `profile_code`, `profile_name`, `profile_email`, `profile_number`, `profile_address`) VALUES
(2, '488', 'Kiran', 'kirankumar_maddukuri@srmap.edu.in', '9032998959', 'segetgh');

-- --------------------------------------------------------

--
-- Table structure for table `prompt`
--

CREATE TABLE `prompt` (
  `id` int(11) NOT NULL,
  `Prompt_code` varchar(50) NOT NULL,
  `Profile_id` int(11) NOT NULL,
  `Stopword_id` int(11) NOT NULL,
  `Annotator_id` int(11) NOT NULL,
  `Prompt_String` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prompt`
--

INSERT INTO `prompt` (`id`, `Prompt_code`, `Profile_id`, `Stopword_id`, `Annotator_id`, `Prompt_String`) VALUES
(2, '464', 2, 1, 1, 'rherh'),
(6, 'a3', 2, 1, 1, 'dtrttu');

-- --------------------------------------------------------

--
-- Table structure for table `stopword`
--

CREATE TABLE `stopword` (
  `id` int(11) NOT NULL,
  `Profile_id` int(11) NOT NULL,
  `Stopword_code` varchar(50) NOT NULL,
  `Stopword_word` varchar(255) NOT NULL,
  `Stopword_status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stopword`
--

INSERT INTO `stopword` (`id`, `Profile_id`, `Stopword_code`, `Stopword_word`, `Stopword_status`) VALUES
(1, 2, '882', 'hello', 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'test', 'test123', '2025-03-25 21:17:03');

-- --------------------------------------------------------

--
-- Table structure for table `user_setting`
--

CREATE TABLE `user_setting` (
  `id` int(11) NOT NULL,
  `Profile_id` int(11) NOT NULL,
  `Persona_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `Status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_setting`
--

INSERT INTO `user_setting` (`id`, `Profile_id`, `Persona_id`, `username`, `password`, `Status`) VALUES
(1, 2, 2, 'KiranKumar_08', '8824', 'Inactive');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `annotator`
--
ALTER TABLE `annotator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Profile_id` (`Profile_id`);

--
-- Indexes for table `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Profile_id` (`Profile_id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profile_email` (`profile_email`);

--
-- Indexes for table `prompt`
--
ALTER TABLE `prompt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Profile_id` (`Profile_id`),
  ADD KEY `Stopword_id` (`Stopword_id`),
  ADD KEY `Annotator_id` (`Annotator_id`);

--
-- Indexes for table `stopword`
--
ALTER TABLE `stopword`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Profile_id` (`Profile_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_setting`
--
ALTER TABLE `user_setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Profile_id` (`Profile_id`),
  ADD KEY `Persona_id` (`Persona_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `annotator`
--
ALTER TABLE `annotator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `persona`
--
ALTER TABLE `persona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `prompt`
--
ALTER TABLE `prompt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stopword`
--
ALTER TABLE `stopword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_setting`
--
ALTER TABLE `user_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `annotator`
--
ALTER TABLE `annotator`
  ADD CONSTRAINT `annotator_ibfk_1` FOREIGN KEY (`Profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`Profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prompt`
--
ALTER TABLE `prompt`
  ADD CONSTRAINT `prompt_ibfk_1` FOREIGN KEY (`Profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prompt_ibfk_2` FOREIGN KEY (`Stopword_id`) REFERENCES `stopword` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prompt_ibfk_3` FOREIGN KEY (`Annotator_id`) REFERENCES `annotator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stopword`
--
ALTER TABLE `stopword`
  ADD CONSTRAINT `stopword_ibfk_1` FOREIGN KEY (`Profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_setting`
--
ALTER TABLE `user_setting`
  ADD CONSTRAINT `user_setting_ibfk_1` FOREIGN KEY (`Profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_setting_ibfk_2` FOREIGN KEY (`Persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
