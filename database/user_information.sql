-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2025 at 09:02 AM
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
-- Database: `user_information`
--

-- --------------------------------------------------------

--
-- Table structure for table `dietary_consultations_details`
--

CREATE TABLE `dietary_consultations_details` (
  `consult_id` varchar(50) NOT NULL,
  `Nutritionist_ID` varchar(50) DEFAULT NULL,
  `nutritionist_category` varchar(100) DEFAULT NULL,
  `member_id` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `request_status` varchar(50) DEFAULT 'pending',
  `payment_status` varchar(20) DEFAULT 'paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dietary_consultations_details`
--

INSERT INTO `dietary_consultations_details` (`consult_id`, `Nutritionist_ID`, `nutritionist_category`, `member_id`, `date`, `start_time`, `end_time`, `request_status`, `payment_status`) VALUES
('CONS001', 'N001', 'Dietitian', 'M01', '2024-12-19', '09:30:00', '10:30:00', 'Approved', 'paid'),
('CONS002', 'N002', 'Sports Nutritionist', 'M03', '2024-12-16', '11:00:00', '12:00:00', 'Approved', 'paid'),
('CONS003', 'N010', 'Clinical Nutritionist', 'M04', '2024-10-27', '14:00:00', '15:00:00', 'Approved', 'paid'),
('CONS004', 'N001', 'Dietitian', 'M08', '2024-10-22', '19:00:00', '20:08:00', 'Approved', 'paid'),
('CONS005', 'N002', 'Sports Nutritionist', 'M05', '2024-12-16', '00:00:00', '01:00:00', 'Approved', 'paid'),
('CONS006', 'N005', 'Sport Nutritionist', 'M02', '2024-10-31', '09:30:00', '10:30:00', 'Pending', 'paid'),
('CONS007', 'N011', 'Sport Nutritionist', 'M02', '2025-01-30', '09:30:00', '10:30:00', 'Pending', 'paid'),
('CONS008', 'N004', 'Sport Nutritionist', 'M02', '2025-01-30', '08:30:00', '09:30:00', 'Pending', 'paid'),
('CONS009', 'N002', 'Sport Nutritionist', 'M02', '2025-01-30', '10:30:00', '11:30:00', 'Pending', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `type` enum('strength','cardio') NOT NULL,
  `name` varchar(100) NOT NULL,
  `sets` int(11) DEFAULT NULL,
  `reps` int(11) DEFAULT NULL,
  `minutes` int(11) DEFAULT NULL,
  `distance` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `user_id`, `date`, `type`, `name`, `sets`, `reps`, `minutes`, `distance`) VALUES
(1, 2305012, '2024-10-29', 'strength', 'halo', 1222, 34, NULL, NULL),
(2, 2305012, '2024-10-29', 'cardio', 'banana123', NULL, NULL, 50, 17.00),
(3, 2305012, '2024-10-31', 'strength', 'damn', 12, 6, NULL, NULL),
(4, 2305001, '2024-10-30', 'strength', 'haha21', 12, 23, NULL, NULL),
(5, 2305001, '2024-10-08', 'cardio', 'gogogo', NULL, NULL, 50, 12.00),
(6, 2305001, '2024-10-30', 'strength', 'gg21', 12, 12, NULL, NULL),
(9, 2305002, '2024-10-29', 'strength', 'sa', 21, 21, NULL, NULL),
(11, 2305001, '2024-10-29', 'cardio', '212111', NULL, NULL, 21, 21.00),
(13, 2305001, '2024-10-03', 'cardio', 'hi', NULL, NULL, 21212, 999.99),
(14, 2305001, '2024-10-03', 'cardio', 'hi', NULL, NULL, 21212, 999.99),
(15, 2305001, '2024-10-03', 'cardio', 'hi', NULL, NULL, 21212, 999.99),
(16, 2305001, '2024-10-03', 'cardio', 'hi', NULL, NULL, 21212, 999.99),
(17, 2305001, '2024-10-14', 'strength', '21', 21, 21, NULL, NULL),
(18, 2305001, '2024-10-07', 'strength', '2122', 21, 21, NULL, NULL),
(19, 2305002, '2024-12-18', 'strength', 'hi', 231, 321, NULL, NULL),
(20, 2305002, '2025-01-01', 'strength', 'bench', 2, 2, NULL, NULL),
(21, 2305002, '2025-01-23', 'cardio', 'hi', NULL, NULL, 4, 3.00);

-- --------------------------------------------------------

--
-- Table structure for table `fitness_class_details`
--

CREATE TABLE `fitness_class_details` (
  `fitness_class_id` varchar(10) NOT NULL,
  `fitness_class_category` varchar(100) NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fitness_class_details`
--

INSERT INTO `fitness_class_details` (`fitness_class_id`, `fitness_class_category`, `day`, `start_time`, `end_time`) VALUES
('F01', 'Yoga', 'Monday', '14:30:00', '16:30:00'),
('F02', 'Ballet', 'Tuesday', '10:30:00', '12:30:00'),
('F03', 'Pilates', 'Wednesday', '15:30:00', '17:30:00'),
('F04', 'Zumba', 'Thursday', '09:30:00', '11:30:00'),
('F05', 'Gym class', 'Friday', '08:00:00', '10:00:00'),
('F06', 'Boxing', 'Saturday', '14:30:00', '16:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `fitness_class_member`
--

CREATE TABLE `fitness_class_member` (
  `member_id` varchar(10) NOT NULL,
  `fitness_class_id` varchar(10) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `request_status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fitness_class_member`
--

INSERT INTO `fitness_class_member` (`member_id`, `fitness_class_id`, `category`, `request_status`) VALUES
('M01', 'F01', 'Yoga', 'pending'),
('M01', 'F03', 'Pilates', 'approved'),
('M02', 'F01', 'Yoga', 'pending'),
('M02', 'F02', 'Ballet', 'pending'),
('M02', 'F03', 'Pilates', 'pending'),
('M02', 'F04', 'Zumba', 'pending'),
('M02', 'F06', 'Boxing', 'approved'),
('M03', 'F01', 'Yoga', 'approved'),
('M04', 'F01', 'Yoga', 'approved'),
('M06', 'F01', 'Yoga', 'approved'),
('M10', 'F01', 'Yoga', 'approved'),
('M10', 'F02', 'Ballet', 'approved'),
('M10', 'F03', 'Pilates', 'approved'),
('M12', 'F04', 'Zumba', 'approved'),
('M14', 'F04', 'Zumba', 'approved'),
('M15', 'F01', 'Yoga', 'approved'),
('M15', 'F06', 'Boxing', 'approved'),
('M16', 'F01', 'Yoga', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `huan_fitness_admin`
--

CREATE TABLE `huan_fitness_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `huan_fitness_admin`
--

INSERT INTO `huan_fitness_admin` (`admin_id`, `admin_name`, `email_address`, `phone_number`, `password`, `date_of_birth`) VALUES
(1, 'Alibaba Hen Ge', 'AlibabaYS@gmail.com', '0123456789', 'alibaba_Hen_Ge', '1985-01-15'),
(2, 'Justin B', 'JustinB_berTheTrueDog@gmail.com', '0121212121', 'The_True_Dog_is_Not_The_Fake_Dog', '1992-07-07'),
(3, 'Donald Duck', 'Donald_DuCKDonald_D@gmail.com', '0123123123', 'quekquekquekquekQEUK', '1980-06-20');

-- --------------------------------------------------------

--
-- Table structure for table `huan_fitness_members`
--

CREATE TABLE `huan_fitness_members` (
  `member_id` varchar(10) NOT NULL,
  `regDate` date DEFAULT NULL,
  `exprDate` date DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT 'paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `huan_fitness_members`
--

INSERT INTO `huan_fitness_members` (`member_id`, `regDate`, `exprDate`, `status`, `payment_status`) VALUES
('M01', '2024-11-19', '2025-11-19', 'active', 'paid'),
('M02', '2025-01-29', '2026-01-29', 'active', 'paid'),
('M03', '2024-11-17', '2025-11-17', 'active', 'paid'),
('M04', '2024-10-15', '2025-10-15', 'active', 'paid'),
('M05', '2024-10-27', '2025-10-27', 'active', 'paid'),
('M06', '2024-12-25', '2025-12-25', 'active', 'paid'),
('M07', '2024-11-01', '2025-11-01', 'active', 'paid'),
('M08', '2024-11-05', '2025-11-05', 'active', 'paid'),
('M09', '2024-11-10', '2025-11-10', 'active', 'paid'),
('M10', '2023-01-30', '2024-01-30', 'expired', ''),
('M11', '2023-08-29', '2024-08-29', 'expired', ''),
('M12', '2023-10-01', '2023-10-01', 'expired', ''),
('M13', '2024-12-05', '2025-12-05', 'active', 'paid'),
('M14', '2024-10-31', '2025-10-31', 'active', 'paid'),
('M15', '2024-10-29', '2025-10-29', 'active', 'paid'),
('M16', '2024-10-30', '2025-10-30', 'active', 'paid'),
('M17', '2024-10-30', '2025-10-30', 'active', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `huan_fitness_nutritionist`
--

CREATE TABLE `huan_fitness_nutritionist` (
  `Nutritionist_ID` varchar(5) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  `PhoneNo` varchar(15) NOT NULL,
  `Email_address` varchar(100) NOT NULL,
  `Category` varchar(50) DEFAULT NULL,
  `Date_of_birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `huan_fitness_nutritionist`
--

INSERT INTO `huan_fitness_nutritionist` (`Nutritionist_ID`, `Name`, `Gender`, `PhoneNo`, `Email_address`, `Category`, `Date_of_birth`) VALUES
('N001', 'Liam Smit', 'Male', '+6012-345-6789', 'liam.smith@example.com', 'Dietitian', '1990-05-15'),
('N002', 'Emma Johnson', 'Female', '+6011-234-5678', 'emma.johnson@yahoo.com', 'Sports Nutritionist', '1987-09-10'),
('N003', 'Noah Brown', 'Male', '+6016-789-0123', 'noah.brown@hotmail.com', 'Dietitian', '1992-01-20'),
('N004', 'Olivia Jones', 'Female', '+6019-654-3210', 'olivia.jones@gmail.com', 'Pediatric Nutritionist', '1991-03-22'),
('N005', 'Elijah Davis', 'Male', '+6010-432-1098', 'elijah.davis@yahoo.com', 'Sports Nutritionist', '1989-07-11'),
('N006', 'Sophia Martinez', 'Female', '+6013-876-5432', 'sophia.martinez@hotmail.com', 'Dietitian', '1993-12-05'),
('N007', 'James Garcia', 'Male', '+6012-908-7654', 'james.garcia@gmail.com', 'Sports Nutritionist', '1990-04-09'),
('N008', 'Isabella Miller', 'Female', '+6014-123-4567', 'isabella.miller@yahoo.com', 'Clinical Nutritionist', '1988-08-14'),
('N009', 'banana', 'Male', '+6012-345-6789', 'banana@gmail.com', 'Dietitian', '2024-10-03'),
('N010', 'dsad', 'Male', '+6017-555-4345', 'das@gmail.com', 'Clinical Nutritionist', '2024-10-15'),
('N011', 'Diaba', 'Male', '+6017-888-6758', 'diaba@gmail.com', 'Clinical Nutritionist', '1999-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `huan_fitness_users`
--

CREATE TABLE `huan_fitness_users` (
  `user_id` int(11) NOT NULL,
  `member_id` varchar(10) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `phone_no` varchar(20) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `huan_fitness_users`
--

INSERT INTO `huan_fitness_users` (`user_id`, `member_id`, `username`, `gender`, `phone_no`, `email_address`, `date_of_birth`, `password`, `height`, `profile_photo`) VALUES
(2305001, 'M01', 'Kee Yung Shen', 'Male', '+6013-234-5679', 'keeyungshen@gmail.com', '1990-01-01', 'ys1234', 170, '2305001.jpg'),
(2305002, 'M02', 'Lim Hong Jie', 'Male', '+6016-345-6789', 'hongjie@yahoo.com', '1992-02-02', 'moon456', 165, NULL),
(2305003, 'M03', 'Teoh Chung Jay', 'Male', '+6017-456-7890', 'chungjay@hotmail.com', '1991-03-03', 'star789', 175, NULL),
(2305004, 'M04', 'Ng Khun Yik', 'Male', '+6019-567-8901', 'khunyik@gmail.com', '1993-04-04', 'sky012', 180, '2305004.png'),
(2305005, 'M05', 'Teoh Jing Whei', 'Female', '+6012-678-9012', 'jingwhei@yahoo.com', '1994-05-05', 'cloud345', 162, NULL),
(2305006, 'M06', 'Justin Chai', 'Male', '+6018-789-0123', 'justinchai@gmail.com', '1995-06-06', 'wave678', 155, NULL),
(2305007, 'M07', 'Hafiz Rahman', 'Male', '+6011-890-1234', 'hafiz.rahman@gmail.com', '1996-07-07', 'light910', 172, NULL),
(2305008, 'M08', 'Sabrina Mokhtar', 'Female', '+6013-901-2345', 'sabrina.mokhtar@gmail.com', '1997-08-08', 'fire111', 168, NULL),
(2305009, 'M09', 'Adam Mustafa', 'Male', '+6016-012-3456', 'adam.mustafa@hotmail.com', '1998-09-09', 'earth222', 177, NULL),
(2305010, 'M10', 'Aina Zulkifli', 'Female', '+6017-123-4567', 'aina.zulkifli@gmail.com', '1999-10-10', 'wind333', 163, '2305010.jpg'),
(2305011, 'M11', 'Hakim Ibrahim', 'Male', '+6019-234-5678', 'hakim.ibrahim@gmail.com', '2000-11-11', 'tree444', 158, NULL),
(2305012, 'M12', 'Zulaikha Azman', 'Female', '+6016-345-6789', 'zulaikha.azman@gmail.com', '2001-12-12', 'rock555', 164, NULL),
(2305013, 'M13', 'Rizal Fauzi', 'Male', '+6017-456-7890', 'rizal.fauzi@gmail.com', '1995-01-01', 'bird666', 176, NULL),
(2305014, 'M14', 'Farah Aishah', 'Female', '+6011-567-8901', 'farah.aishah@yahoo.com', '1993-02-02', 'fish777', 170, NULL),
(2305015, 'M15', 'Amirul Shah', 'Male', '+6013-678-9012', 'amirul.shah@gmail.com', '1992-03-03', 'deer888', 169, '2305015.png'),
(2305016, 'M16', 'Faizal Yusuf', 'Male', '+6017-890-1234', 'faizal.yusuf@yahoo.com', '1995-05-05', 'fox000', 172, '2305016.png'),
(2305017, 'M17', 'Nadhirah Mahmud', 'Female', '+6013-901-2345', 'nadhirah.mahmud@hotmail.com', '1996-06-06', 'cat111', 167, '2305017.png'),
(2305018, NULL, 'Khalid Salleh', 'Male', '+6016-012-3456', 'khalid.salleh@gmail.com', '1997-07-07', 'dog222', 179, '2305018.png'),
(2305019, NULL, 'Sofia Nasir', 'Female', '+6018-123-4567', 'sofia.nasir@yahoo.com', '1998-08-08', 'rat333', 173, '2305019.png'),
(2305020, NULL, 'banana', 'Male', '+6017-555-3213', 'das@gmail.com', '2000-01-12', '567', 150, '2305021.png'),
(2305021, NULL, 'Hailey', 'Female', '+6017-555-1324', 'hailey@gmail.com', '2000-01-01', '123', 145, '2305021.jpg'),
(2305022, NULL, 'kila', 'Male', '+6017-333-3213', '312@gmail.com', '2000-01-01', '789', 167, '2305023.png'),
(2305023, NULL, 'Damn Son', 'Male', '+6018-888-8888', 'najib@gmail.com', '2000-01-01', 'shabi', 160, '2305023.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `invoice_number` varchar(255) NOT NULL,
  `payment_method` enum('credit','debit','tng') DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_category` enum('nutritionist','registration','renew') DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('paid','not_paid') DEFAULT 'not_paid',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`invoice_number`, `payment_method`, `payment_date`, `payment_category`, `payment_amount`, `payment_status`, `user_id`) VALUES
('INV-849', 'debit', '2024-10-31', 'renew', 20.00, 'paid', 2305002),
('INV-019', 'debit', '2024-10-31', NULL, 0.00, 'not_paid', 2305001),
('INV-629', 'debit', '2024-10-31', NULL, 0.00, 'not_paid', 2305001),
('', 'debit', '2024-10-31', '', 0.00, '', 2305001),
('', 'credit', '2024-10-31', '', 0.00, '', 2305001),
('', 'credit', '2024-10-31', '', 0.00, '', 2305001),
('', 'credit', '2024-10-31', '', 0.00, '', 2305001),
('', 'credit', '2024-10-31', '', 0.00, '', 2305001),
('INV-568', 'debit', '2024-10-31', '', 20.00, 'paid', 2305001),
('INV-679', 'tng', '2024-10-31', '', 20.00, 'paid', 2305001),
('INV-838', 'tng', '2024-10-31', 'renew', 20.00, 'paid', 2305001),
('INV-685', 'tng', '2024-10-31', 'renew', 20.00, 'paid', 2305003),
('INV-302', 'credit', '2025-01-28', 'renew', 20.00, 'paid', 2305002);

-- --------------------------------------------------------

--
-- Table structure for table `water_consumption`
--

CREATE TABLE `water_consumption` (
  `water_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `other_type` varchar(100) DEFAULT NULL,
  `amount` varchar(50) DEFAULT NULL,
  `goal_amount` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `water_consumption`
--

INSERT INTO `water_consumption` (`water_id`, `user_id`, `date`, `time`, `type`, `other_type`, `amount`, `goal_amount`) VALUES
(1, 2305001, '2024-10-29', '10:11', 'plain_water', NULL, '10000', '2000'),
(2, 2305001, '2024-11-01', '22:56', 'sports_drink', NULL, '200', '2000'),
(3, 2305016, '2024-11-07', '11:00', 'sports_drink', NULL, '400', '2000'),
(4, 2305010, '2024-10-29', '14:22', 'plain_water', NULL, '1000', '2000');

-- --------------------------------------------------------

--
-- Table structure for table `weights`
--

CREATE TABLE `weights` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weights`
--

INSERT INTO `weights` (`id`, `user_id`, `weight`, `date`) VALUES
(1, 2305001, 50.00, '2024-10-30'),
(2, 2305001, 60.00, '2024-10-31'),
(3, 2305001, 63.00, '2024-11-01'),
(4, 2305010, 100.00, '2024-10-30'),
(5, 2305001, 130.00, '2024-11-04'),
(6, 2305002, 55.00, '2025-01-03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dietary_consultations_details`
--
ALTER TABLE `dietary_consultations_details`
  ADD PRIMARY KEY (`consult_id`),
  ADD KEY `Nutritionist_ID` (`Nutritionist_ID`),
  ADD KEY `fk_member_id` (`member_id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_exercises_user_id` (`user_id`);

--
-- Indexes for table `fitness_class_details`
--
ALTER TABLE `fitness_class_details`
  ADD PRIMARY KEY (`fitness_class_id`);

--
-- Indexes for table `fitness_class_member`
--
ALTER TABLE `fitness_class_member`
  ADD PRIMARY KEY (`member_id`,`fitness_class_id`),
  ADD KEY `fitness_class_id` (`fitness_class_id`);

--
-- Indexes for table `huan_fitness_admin`
--
ALTER TABLE `huan_fitness_admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- Indexes for table `huan_fitness_members`
--
ALTER TABLE `huan_fitness_members`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `huan_fitness_nutritionist`
--
ALTER TABLE `huan_fitness_nutritionist`
  ADD PRIMARY KEY (`Nutritionist_ID`);

--
-- Indexes for table `huan_fitness_users`
--
ALTER TABLE `huan_fitness_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `fk_member_id` (`member_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `water_consumption`
--
ALTER TABLE `water_consumption`
  ADD PRIMARY KEY (`water_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `weights`
--
ALTER TABLE `weights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `huan_fitness_admin`
--
ALTER TABLE `huan_fitness_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `weights`
--
ALTER TABLE `weights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dietary_consultations_details`
--
ALTER TABLE `dietary_consultations_details`
  ADD CONSTRAINT `fk_member_id` FOREIGN KEY (`member_id`) REFERENCES `huan_fitness_members` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exercises`
--
ALTER TABLE `exercises`
  ADD CONSTRAINT `fk_exercises_user_id` FOREIGN KEY (`user_id`) REFERENCES `huan_fitness_users` (`user_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `huan_fitness_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `water_consumption`
--
ALTER TABLE `water_consumption`
  ADD CONSTRAINT `water_consumption_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `huan_fitness_users` (`user_id`);

--
-- Constraints for table `weights`
--
ALTER TABLE `weights`
  ADD CONSTRAINT `weights_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `huan_fitness_users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
