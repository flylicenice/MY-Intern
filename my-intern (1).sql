-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2026 at 03:12 PM
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
-- Database: `my-intern`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(10) NOT NULL,
  `staff_id` int(10) NOT NULL,
  `user_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(10) NOT NULL,
  `registration_no` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `employee_size` varchar(20) NOT NULL,
  `unit` varchar(10) DEFAULT NULL,
  `street` varchar(50) DEFAULT NULL,
  `postal_code` varchar(5) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `at_state` varchar(50) DEFAULT NULL,
  `overall_rating` decimal(2,1) DEFAULT NULL,
  `verification_status` varchar(255) NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_review`
--

CREATE TABLE `company_review` (
  `review_id` int(10) NOT NULL,
  `rating` decimal(4,2) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `review_date` date NOT NULL,
  `company_id` int(10) NOT NULL,
  `matric_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE `evaluation` (
  `evaluation_id` int(10) NOT NULL,
  `evaluation_file` mediumblob DEFAULT NULL,
  `matric_number` varchar(20) NOT NULL,
  `lecturer_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_application`
--

CREATE TABLE `job_application` (
  `application_id` int(10) NOT NULL,
  `matric_number` varchar(20) NOT NULL,
  `job_id` int(10) NOT NULL,
  `application_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_vacancy`
--

CREATE TABLE `job_vacancy` (
  `job_id` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `allowance` decimal(7,2) NOT NULL,
  `location` varchar(255) NOT NULL,
  `post_date` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `company_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lecturer_id` int(10) NOT NULL,
  `staff_id` int(10) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `identification_no` varchar(20) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logbook`
--

CREATE TABLE `logbook` (
  `logbook_id` int(10) NOT NULL,
  `week_number` int(10) NOT NULL,
  `logbook` mediumblob DEFAULT NULL,
  `submitted_at` date NOT NULL,
  `placement_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `placement`
--

CREATE TABLE `placement` (
  `placement_id` int(10) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `lecturer_id` int(10) NOT NULL,
  `application_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `matric_number` varchar(20) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `identification_no` varchar(20) NOT NULL,
  `course` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `resume` mediumblob DEFAULT NULL,
  `profile_pic` mediumblob DEFAULT NULL,
  `intern_status` varchar(20) NOT NULL DEFAULT 'Inactive',
  `user_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`matric_number`, `full_name`, `identification_no`, `course`, `phone_number`, `resume`, `profile_pic`, `intern_status`, `user_id`) VALUES
('124', 'tamkaidit', '124', 'DCS', '124', NULL, NULL, 'Inactive', 35),
('D032410036', 'eugene', '060101', 'DCS', '01126783179', NULL, NULL, 'Inactive', 38),
('D032410113', 'TAM KAI DIT', '060323050323', 'DCS', '0149933340', NULL, NULL, 'Inactive', 36);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(5) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `time_created` date NOT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `code_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `role`, `status`, `time_created`, `verification_code`, `code_expires_at`) VALUES
(35, 'oknevermine0323@gmail.com', '$2y$10$MD02MTRRORacuxnFGjwuLOXTYNLHvgfGg8OjdwGvuvITY5LiQrRwa', 'Student', 'active', '2026-06-02', NULL, NULL),
(36, 'tamkaidit50@gmail.com', '$2y$10$CQeWBtDTsNlH.E1khGsdmu68NOFDBFODETK7VuVesHgRf3Ip0t.xO', 'Student', 'active', '2026-06-02', NULL, NULL),
(38, 'eugenehzc06@gmail.com', '$2y$10$LiVUf.gJPD2YcxoRgEFK4u/NLyIS/y1mbkopls9//MK9OfVmK9Igq', 'Student', 'pending', '2026-06-02', '68dc319df533eedf0268c43a167b28e45764861d5a95756dc794bdd3dba26e5b', '2026-06-03 13:43:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `unique_staff` (`staff_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`),
  ADD UNIQUE KEY `registration_no` (`registration_no`),
  ADD KEY `FK_user_company` (`user_id`);

--
-- Indexes for table `company_review`
--
ALTER TABLE `company_review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `FK_review_company` (`company_id`),
  ADD KEY `FK_review_student` (`matric_number`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`evaluation_id`),
  ADD KEY `FK_evaluation_student` (`matric_number`),
  ADD KEY `FK_evaluation_lecturer` (`lecturer_id`);

--
-- Indexes for table `job_application`
--
ALTER TABLE `job_application`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `FK_application_job` (`job_id`),
  ADD KEY `FK_application_student` (`matric_number`);

--
-- Indexes for table `job_vacancy`
--
ALTER TABLE `job_vacancy`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `FK_company_job` (`company_id`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lecturer_id`),
  ADD UNIQUE KEY `staff_id` (`staff_id`),
  ADD KEY `FK_lecturer_user` (`user_id`);

--
-- Indexes for table `logbook`
--
ALTER TABLE `logbook`
  ADD PRIMARY KEY (`logbook_id`),
  ADD KEY `FK_logbook_placement` (`placement_id`);

--
-- Indexes for table `placement`
--
ALTER TABLE `placement`
  ADD PRIMARY KEY (`placement_id`),
  ADD KEY `FK_placement_application` (`application_id`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`matric_number`),
  ADD UNIQUE KEY `unique_ic` (`identification_no`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `FK_user_company` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `company_review`
--
ALTER TABLE `company_review`
  ADD CONSTRAINT `FK_review_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_review_student` FOREIGN KEY (`matric_number`) REFERENCES `student` (`matric_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `FK_evaluation_lecturer` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_evaluation_student` FOREIGN KEY (`matric_number`) REFERENCES `student` (`matric_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_application`
--
ALTER TABLE `job_application`
  ADD CONSTRAINT `FK_application_job` FOREIGN KEY (`job_id`) REFERENCES `job_vacancy` (`job_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_application_student` FOREIGN KEY (`matric_number`) REFERENCES `student` (`matric_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_vacancy`
--
ALTER TABLE `job_vacancy`
  ADD CONSTRAINT `FK_company_job` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD CONSTRAINT `FK_lecturer_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `logbook`
--
ALTER TABLE `logbook`
  ADD CONSTRAINT `FK_logbook_placement` FOREIGN KEY (`placement_id`) REFERENCES `placement` (`placement_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `placement`
--
ALTER TABLE `placement`
  ADD CONSTRAINT `FK_placement_application` FOREIGN KEY (`application_id`) REFERENCES `job_application` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_placement_lecturer` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
