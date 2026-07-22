-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2026 at 06:05 PM
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
-- Database: `clothcare`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `A_Id` int(11) NOT NULL,
  `A_Name` varchar(100) DEFAULT NULL,
  `A_Password` varchar(255) DEFAULT NULL,
  `A_Phone` varchar(15) DEFAULT NULL,
  `A_Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`A_Id`, `A_Name`, `A_Password`, `A_Phone`, `A_Email`) VALUES
(1, 'AdminA', 'Abc@123', '01511111111', 'admin1@gmail.com'),
(2, 'AdminB', 'Abc@123', '01522222222', 'admin2@gmail.com'),
(3, 'AdminC', 'Abc@123', '01533333333', 'admin3@gmail.com'),
(4, 'AdminD', 'Abc@123', '01544444444', 'admin4@gmail.com'),
(5, 'AdminE', 'Abc@123', '01555555555', 'admin5@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `admin_phone`
--

CREATE TABLE `admin_phone` (
  `A_Id` int(11) NOT NULL,
  `A_Phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_phone`
--

INSERT INTO `admin_phone` (`A_Id`, `A_Phone`) VALUES
(1, '01305315805');

-- --------------------------------------------------------

--
-- Table structure for table `beneficiary`
--

CREATE TABLE `beneficiary` (
  `B_Id` int(11) NOT NULL,
  `B_Name` varchar(100) DEFAULT NULL,
  `B_NID` varchar(50) DEFAULT NULL,
  `B_Password` varchar(255) DEFAULT NULL,
  `B_Family_Size` int(11) DEFAULT NULL,
  `B_Income` decimal(10,2) DEFAULT NULL,
  `BA_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiary`
--

INSERT INTO `beneficiary` (`B_Id`, `B_Name`, `B_NID`, `B_Password`, `B_Family_Size`, `B_Income`, `BA_Id`) VALUES
(1, 'Rafi', 'N1', 'Abc1234@', 4, 10000.00, 1),
(2, 'Sumi', 'N2', 'Abc@123', 5, 12000.00, 2),
(3, 'Jahid', 'N3', 'Abc@123', 3, 8000.00, 3),
(4, 'Lima', 'N4', 'Abc@123', 6, 15000.00, 4),
(5, 'Rana', 'N5', 'Abc@123', 2, 7000.00, 5);

-- --------------------------------------------------------

--
-- Table structure for table `beneficiary_address`
--

CREATE TABLE `beneficiary_address` (
  `BA_Id` int(11) NOT NULL,
  `B_District` varchar(50) DEFAULT NULL,
  `B_City` varchar(50) DEFAULT NULL,
  `B_Street_No` varchar(20) DEFAULT NULL,
  `B_House_No` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiary_address`
--

INSERT INTO `beneficiary_address` (`BA_Id`, `B_District`, `B_City`, `B_Street_No`, `B_House_No`) VALUES
(1, 'Dhaka', 'Gazipurrr', 'R12', 'H123'),
(2, 'Dhaka', 'Tongi', 'R2', 'H2'),
(3, 'Chittagong', 'City', 'R3', 'H3'),
(4, 'Khulna', 'Town', 'R4', 'H4'),
(5, 'Rajshahi', 'Area', 'R5', 'H5');

-- --------------------------------------------------------

--
-- Table structure for table `beneficiary_email`
--

CREATE TABLE `beneficiary_email` (
  `B_Id` int(11) NOT NULL,
  `B_Email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiary_email`
--

INSERT INTO `beneficiary_email` (`B_Id`, `B_Email`) VALUES
(1, 'b1@gmail.com'),
(2, 'b2@gmail.com'),
(3, 'b3@gmail.com'),
(4, 'b4@gmail.com'),
(5, 'b5@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `beneficiary_phone`
--

CREATE TABLE `beneficiary_phone` (
  `B_Id` int(11) NOT NULL,
  `B_Phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beneficiary_phone`
--

INSERT INTO `beneficiary_phone` (`B_Id`, `B_Phone`) VALUES
(1, '01343434343'),
(2, '01622222222'),
(3, '01633333333'),
(4, '01644444444'),
(5, '01655555555');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Cat_Id` int(11) NOT NULL,
  `Cat_Name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Cat_Id`, `Cat_Name`) VALUES
(1, 'Winter Wear'),
(2, 'Children Wear'),
(3, 'Formal Wear'),
(4, 'Casual Wear'),
(5, 'Sports Wear');

-- --------------------------------------------------------

--
-- Table structure for table `center_address`
--

CREATE TABLE `center_address` (
  `CenA_Id` int(11) NOT NULL,
  `Cen_District` varchar(50) DEFAULT NULL,
  `Cen_City` varchar(50) DEFAULT NULL,
  `Cen_Street_No` varchar(20) DEFAULT NULL,
  `Cen_House_No` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `center_address`
--

INSERT INTO `center_address` (`CenA_Id`, `Cen_District`, `Cen_City`, `Cen_Street_No`, `Cen_House_No`) VALUES
(1, 'Dhaka', 'Gazipur', 'R1', 'H1'),
(2, 'Dhaka', 'Tongi', 'R2', 'H2'),
(3, 'Chittagong', 'City', 'R3', 'H3'),
(4, 'Khulna', 'Town', 'R4', 'H4'),
(5, 'Rajshahi', 'Area', 'R5', 'H5');

-- --------------------------------------------------------

--
-- Table structure for table `center_phone`
--

CREATE TABLE `center_phone` (
  `Cen_Id` int(11) NOT NULL,
  `Cen_Phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clothing_item`
--

CREATE TABLE `clothing_item` (
  `Item_Id` int(11) NOT NULL,
  `C_Quantity` int(11) DEFAULT NULL,
  `C_Size` varchar(10) DEFAULT NULL,
  `Gender_Category` varchar(20) DEFAULT NULL,
  `C_Condition` varchar(50) DEFAULT NULL,
  `C_Clothing_Type` varchar(50) DEFAULT NULL,
  `D_Id` int(11) DEFAULT NULL,
  `Cat_Id` int(11) DEFAULT NULL,
  `Cen_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clothing_item`
--

INSERT INTO `clothing_item` (`Item_Id`, `C_Quantity`, `C_Size`, `Gender_Category`, `C_Condition`, `C_Clothing_Type`, `D_Id`, `Cat_Id`, `Cen_Id`) VALUES
(2, 3, 'L', 'Male', 'New', 'Pant', 2, 2, 2),
(3, 4, 'S', 'Female', 'Used', 'Jacket', 3, 3, 3),
(4, 6, 'M', 'Male', 'Good', 'T-Shirt', 4, 4, 4),
(5, 2, 'XL', 'Female', 'New', 'Sweater', 5, 5, 5),
(6, 1, 'S', 'Female', 'Good', 'Shirt', 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `clothing_request`
--

CREATE TABLE `clothing_request` (
  `Cr_Id` int(11) NOT NULL,
  `Cr_Cloth_Type` varchar(50) DEFAULT NULL,
  `Cr_Size` varchar(10) DEFAULT NULL,
  `Cr_Quantity` int(11) DEFAULT NULL,
  `Cr_Date` date DEFAULT NULL,
  `Cr_Status` varchar(50) DEFAULT NULL,
  `B_Id` int(11) DEFAULT NULL,
  `A_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clothing_request`
--

INSERT INTO `clothing_request` (`Cr_Id`, `Cr_Cloth_Type`, `Cr_Size`, `Cr_Quantity`, `Cr_Date`, `Cr_Status`, `B_Id`, `A_Id`) VALUES
(1, 'Shirt', 'M', 2, '2026-05-11', 'Pending', 1, 1),
(2, 'Pant', 'L', 1, '2026-05-11', 'Approved', 2, 2),
(3, 'Jacket', 'S', 3, '2026-05-11', 'Rejected', 3, 3),
(4, 'T-Shirt', 'M', 2, '2026-05-11', 'Pending', 4, 4),
(5, 'Sweater', 'XL', 1, '2026-05-11', 'Approved', 5, 5),
(6, 'Shirt', 'XL', 2, '2026-05-11', 'Pending', 1, NULL),
(7, 'Pant', 'XL', 1, '2026-05-11', 'Approved', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `collection_center`
--

CREATE TABLE `collection_center` (
  `Cen_Id` int(11) NOT NULL,
  `Cen_Name` varchar(100) DEFAULT NULL,
  `Cen_Manager` varchar(100) DEFAULT NULL,
  `Cen_Email` varchar(100) DEFAULT NULL,
  `CenA_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection_center`
--

INSERT INTO `collection_center` (`Cen_Id`, `Cen_Name`, `Cen_Manager`, `Cen_Email`, `CenA_Id`) VALUES
(1, 'Mirpur', 'Manager1', 'c1@gmail.com', 1),
(2, 'Dhanmondi', 'Manager2', 'c2@gmail.com', 2),
(3, 'Gulshan', 'Manager3', 'c3@gmail.com', 3),
(4, 'Uttara', 'Manager4', 'c4@gmail.com', 4),
(5, 'Badda', 'Manager5', 'c5@gmail.com', 5),
(6, 'Mohammadpur', NULL, NULL, NULL),
(7, 'Jatrabari', NULL, NULL, NULL),
(8, 'Motijheel', NULL, NULL, NULL),
(9, 'Khilgaon', NULL, NULL, NULL),
(10, 'Farmgate', NULL, NULL, NULL),
(11, 'Bashundhara', NULL, NULL, NULL),
(12, 'Banani', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `donor`
--

CREATE TABLE `donor` (
  `D_Id` int(11) NOT NULL,
  `D_Name` varchar(100) DEFAULT NULL,
  `D_Password` varchar(255) DEFAULT NULL,
  `DA_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor`
--

INSERT INTO `donor` (`D_Id`, `D_Name`, `D_Password`, `DA_Id`) VALUES
(1, 'Saima', 'Abc@123', 1),
(2, 'Anika', 'Abc@123', 2),
(3, 'Humayra', 'Abc@123', 3),
(4, 'Zinat', 'Abc@123', 4),
(5, 'Oishe', 'Abc@123', 5),
(6, 'Sultan ', 'Abc@123', 6),
(7, 'John', 'John123@', 20),
(8, 'John1', 'John1123@', 21),
(9, 'Muniaa', 'Muniya123@', 22);

-- --------------------------------------------------------

--
-- Table structure for table `donor_address`
--

CREATE TABLE `donor_address` (
  `DA_Id` int(11) NOT NULL,
  `D_District` varchar(50) DEFAULT NULL,
  `D_City` varchar(50) DEFAULT NULL,
  `D_House_No` varchar(20) DEFAULT NULL,
  `D_Street_No` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor_address`
--

INSERT INTO `donor_address` (`DA_Id`, `D_District`, `D_City`, `D_House_No`, `D_Street_No`) VALUES
(1, 'Dhaka', 'Gazipurr', '12B', 'Road-1'),
(2, 'Dhaka', 'Tongi', '34B', 'Road-2'),
(3, 'Chittagong', 'City', '56C', 'Road-3'),
(4, 'Khulna', 'Town', '78D', 'Road-4'),
(5, 'Rajshahi', 'Area', '90E', 'Road-5'),
(6, 'Dhaka', 'Dhaka', '172', 'Moddho Basabo'),
(7, 'Dhaka', 'Dhaka', '122', 'Uttar Badda '),
(8, 'Narayanganj', 'Narayanganj', '33', 'Kutubail,Fatulla'),
(9, 'Narayanganj', 'Narayanganj', '21', 'Shibu Market,Fatulla'),
(10, 'Narayanganj', 'Narayanganj', '21', 'Shibu Market,Fatulla'),
(11, 'Narayanganj', 'Narayanganj', '123', 'Shibu Market,Fatulla'),
(12, 'Narayanganj', 'Narayanganj', '172', 'Shibu Market,Fatulla'),
(13, 'Dhaka', 'Dhaka', '172', 'Boddhomondir'),
(14, 'Dhaka', 'Dhaka', '173', 'north mugda'),
(15, 'Narayanganj', 'Narayanganj', '75', 'Shibu Market,Fatulla'),
(16, 'Narayanganj', 'Narayanganj', '33', 'Shibu Market,Fatulla'),
(17, 'Narayanganj', 'Narayanganj', '12B', 'Shibu Market,Fatulla'),
(18, 'Dhaka', 'Dhaka', '21', 'Shibu Market'),
(19, 'Dhaka', 'Narayanganj', '123', 'Shibu Market,Fatulla'),
(20, 'Dhaka', 'Narayanganj', 'asa', 'Shibu Market,Fatulla'),
(21, 'Dhaka', 'Dhaka', '12', 'Shibu Markettt'),
(22, 'Dhaka', 'Dhaka', '33', 'Boddhomondir');

-- --------------------------------------------------------

--
-- Table structure for table `donor_email`
--

CREATE TABLE `donor_email` (
  `D_Id` int(11) NOT NULL,
  `D_Email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor_email`
--

INSERT INTO `donor_email` (`D_Id`, `D_Email`) VALUES
(1, 'saima@gmail.com'),
(2, 'anika02@gmail.com'),
(2, 'anika@gmail.com'),
(3, 'humayra@gmail.com'),
(4, 'zinat02@gmail.com'),
(4, 'zinat@gmail.com'),
(5, 'oishe@gmail.com'),
(8, 'john1@gmail.com'),
(9, 'muniaa@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `donor_phone`
--

CREATE TABLE `donor_phone` (
  `D_Id` int(11) NOT NULL,
  `D_Phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor_phone`
--

INSERT INTO `donor_phone` (`D_Id`, `D_Phone`) VALUES
(1, '01711111111'),
(1, '01716722222'),
(2, '01722222222'),
(3, '01733333333'),
(4, '01744444444'),
(5, '01755555555'),
(8, '01305315845'),
(9, '01305315878');

-- --------------------------------------------------------

--
-- Table structure for table `item_beneficiary`
--

CREATE TABLE `item_beneficiary` (
  `Item_Id` int(11) NOT NULL,
  `B_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_beneficiary`
--

INSERT INTO `item_beneficiary` (`Item_Id`, `B_Id`) VALUES
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `pickup_request`
--

CREATE TABLE `pickup_request` (
  `Pr_Id` int(11) NOT NULL,
  `Pr_Date` date DEFAULT NULL,
  `Pr_Time` varchar(20) DEFAULT NULL,
  `Pr_Status` varchar(50) DEFAULT NULL,
  `Pr_Address` varchar(255) DEFAULT NULL,
  `D_Id` int(11) DEFAULT NULL,
  `V_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickup_request`
--

INSERT INTO `pickup_request` (`Pr_Id`, `Pr_Date`, `Pr_Time`, `Pr_Status`, `Pr_Address`, `D_Id`, `V_Id`) VALUES
(1, '2026-04-20', '11:00 AM', 'Completed', NULL, 1, 1),
(2, '2026-04-21', '02:00 PM', 'Completed', NULL, 2, 2),
(3, '2026-04-22', '09:30 AM', 'Pending', NULL, 3, 3),
(4, '2026-04-23', '04:00 PM', 'Completed', NULL, 4, 4),
(5, '2026-04-24', '10:15 AM', 'Pending', NULL, 5, 5),
(6, '2026-05-17', '02:00 PM', 'Pending', '13B Uttar Badda', 1, NULL),
(7, '2026-05-20', '02:00 PM', 'Completed', 'Mirpur 12', 1, 8),
(8, '2026-05-20', '09:00 AM', 'Pending', 'Badda ', 8, NULL),
(9, '2026-05-29', '02:00 PM', 'Completed', '33, Boddhomondir, Dhaka', 9, 7),
(10, '2026-05-20', '02:00 PM', 'Assigned', '12B, Road-1, Gazipurr', 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

CREATE TABLE `volunteer` (
  `V_Id` int(11) NOT NULL,
  `V_Name` varchar(100) DEFAULT NULL,
  `V_Password` varchar(255) DEFAULT NULL,
  `V_Email` varchar(100) DEFAULT NULL,
  `V_Assigned_Area` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer`
--

INSERT INTO `volunteer` (`V_Id`, `V_Name`, `V_Password`, `V_Email`, `V_Assigned_Area`) VALUES
(1, 'Rahim', 'Abc@123', 'rahim@gmail.com', 'Mirpur'),
(2, 'Karim', 'Abc@123', 'karim@gmail.com', 'Area2'),
(3, 'Nusrat', 'Abc@123', 'nusrat@gmail.com', 'Area3'),
(4, 'Tanvir', 'Abc@123', 'tanvir@gmail.com', 'Area4'),
(5, 'Mim', 'Abc@123', 'mim@gmail.com', 'Area5'),
(7, 'MD. JUBAYER HASAN', 'Abc@123', 'jubayerhasanrohan@gmail.com', 'Rampura'),
(8, 'Fahim', 'Fahim123@', 'fahim@gmail.com', 'Mogbazar'),
(9, 'jubayer', 'Jubayer5@', 'jubayer6@gmail.com', 'Dhanmondi');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_phone`
--

CREATE TABLE `volunteer_phone` (
  `V_Id` int(11) NOT NULL,
  `V_Phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_phone`
--

INSERT INTO `volunteer_phone` (`V_Id`, `V_Phone`) VALUES
(1, '01911111134'),
(2, '01922222222'),
(3, '01933333333'),
(4, '01944444444'),
(5, '01955555555');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`A_Id`);

--
-- Indexes for table `admin_phone`
--
ALTER TABLE `admin_phone`
  ADD PRIMARY KEY (`A_Id`);

--
-- Indexes for table `beneficiary`
--
ALTER TABLE `beneficiary`
  ADD PRIMARY KEY (`B_Id`),
  ADD KEY `BA_Id` (`BA_Id`);

--
-- Indexes for table `beneficiary_address`
--
ALTER TABLE `beneficiary_address`
  ADD PRIMARY KEY (`BA_Id`);

--
-- Indexes for table `beneficiary_email`
--
ALTER TABLE `beneficiary_email`
  ADD PRIMARY KEY (`B_Id`,`B_Email`);

--
-- Indexes for table `beneficiary_phone`
--
ALTER TABLE `beneficiary_phone`
  ADD PRIMARY KEY (`B_Id`,`B_Phone`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Cat_Id`);

--
-- Indexes for table `center_address`
--
ALTER TABLE `center_address`
  ADD PRIMARY KEY (`CenA_Id`);

--
-- Indexes for table `center_phone`
--
ALTER TABLE `center_phone`
  ADD PRIMARY KEY (`Cen_Id`,`Cen_Phone`);

--
-- Indexes for table `clothing_item`
--
ALTER TABLE `clothing_item`
  ADD PRIMARY KEY (`Item_Id`),
  ADD KEY `D_Id` (`D_Id`),
  ADD KEY `Cat_Id` (`Cat_Id`),
  ADD KEY `Cen_Id` (`Cen_Id`);

--
-- Indexes for table `clothing_request`
--
ALTER TABLE `clothing_request`
  ADD PRIMARY KEY (`Cr_Id`),
  ADD KEY `B_Id` (`B_Id`),
  ADD KEY `A_Id` (`A_Id`);

--
-- Indexes for table `collection_center`
--
ALTER TABLE `collection_center`
  ADD PRIMARY KEY (`Cen_Id`),
  ADD KEY `CenA_Id` (`CenA_Id`);

--
-- Indexes for table `donor`
--
ALTER TABLE `donor`
  ADD PRIMARY KEY (`D_Id`),
  ADD KEY `DA_Id` (`DA_Id`);

--
-- Indexes for table `donor_address`
--
ALTER TABLE `donor_address`
  ADD PRIMARY KEY (`DA_Id`);

--
-- Indexes for table `donor_email`
--
ALTER TABLE `donor_email`
  ADD PRIMARY KEY (`D_Id`,`D_Email`);

--
-- Indexes for table `donor_phone`
--
ALTER TABLE `donor_phone`
  ADD PRIMARY KEY (`D_Id`,`D_Phone`);

--
-- Indexes for table `item_beneficiary`
--
ALTER TABLE `item_beneficiary`
  ADD PRIMARY KEY (`Item_Id`,`B_Id`),
  ADD KEY `B_Id` (`B_Id`);

--
-- Indexes for table `pickup_request`
--
ALTER TABLE `pickup_request`
  ADD PRIMARY KEY (`Pr_Id`),
  ADD KEY `D_Id` (`D_Id`),
  ADD KEY `V_Id` (`V_Id`);

--
-- Indexes for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD PRIMARY KEY (`V_Id`);

--
-- Indexes for table `volunteer_phone`
--
ALTER TABLE `volunteer_phone`
  ADD PRIMARY KEY (`V_Id`,`V_Phone`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_phone`
--
ALTER TABLE `admin_phone`
  ADD CONSTRAINT `admin_phone_ibfk_1` FOREIGN KEY (`A_Id`) REFERENCES `admin` (`A_Id`) ON DELETE CASCADE;

--
-- Constraints for table `beneficiary`
--
ALTER TABLE `beneficiary`
  ADD CONSTRAINT `beneficiary_ibfk_1` FOREIGN KEY (`BA_Id`) REFERENCES `beneficiary_address` (`BA_Id`);

--
-- Constraints for table `beneficiary_email`
--
ALTER TABLE `beneficiary_email`
  ADD CONSTRAINT `beneficiary_email_ibfk_1` FOREIGN KEY (`B_Id`) REFERENCES `beneficiary` (`B_Id`);

--
-- Constraints for table `beneficiary_phone`
--
ALTER TABLE `beneficiary_phone`
  ADD CONSTRAINT `beneficiary_phone_ibfk_1` FOREIGN KEY (`B_Id`) REFERENCES `beneficiary` (`B_Id`);

--
-- Constraints for table `center_phone`
--
ALTER TABLE `center_phone`
  ADD CONSTRAINT `center_phone_ibfk_1` FOREIGN KEY (`Cen_Id`) REFERENCES `collection_center` (`Cen_Id`);

--
-- Constraints for table `clothing_item`
--
ALTER TABLE `clothing_item`
  ADD CONSTRAINT `clothing_item_ibfk_1` FOREIGN KEY (`D_Id`) REFERENCES `donor` (`D_Id`),
  ADD CONSTRAINT `clothing_item_ibfk_2` FOREIGN KEY (`Cat_Id`) REFERENCES `category` (`Cat_Id`),
  ADD CONSTRAINT `clothing_item_ibfk_3` FOREIGN KEY (`Cen_Id`) REFERENCES `collection_center` (`Cen_Id`);

--
-- Constraints for table `clothing_request`
--
ALTER TABLE `clothing_request`
  ADD CONSTRAINT `clothing_request_ibfk_1` FOREIGN KEY (`B_Id`) REFERENCES `beneficiary` (`B_Id`),
  ADD CONSTRAINT `clothing_request_ibfk_2` FOREIGN KEY (`A_Id`) REFERENCES `admin` (`A_Id`);

--
-- Constraints for table `collection_center`
--
ALTER TABLE `collection_center`
  ADD CONSTRAINT `collection_center_ibfk_1` FOREIGN KEY (`CenA_Id`) REFERENCES `center_address` (`CenA_Id`);

--
-- Constraints for table `donor`
--
ALTER TABLE `donor`
  ADD CONSTRAINT `donor_ibfk_1` FOREIGN KEY (`DA_Id`) REFERENCES `donor_address` (`DA_Id`);

--
-- Constraints for table `donor_email`
--
ALTER TABLE `donor_email`
  ADD CONSTRAINT `donor_email_ibfk_1` FOREIGN KEY (`D_Id`) REFERENCES `donor` (`D_Id`);

--
-- Constraints for table `donor_phone`
--
ALTER TABLE `donor_phone`
  ADD CONSTRAINT `donor_phone_ibfk_1` FOREIGN KEY (`D_Id`) REFERENCES `donor` (`D_Id`);

--
-- Constraints for table `item_beneficiary`
--
ALTER TABLE `item_beneficiary`
  ADD CONSTRAINT `item_beneficiary_ibfk_1` FOREIGN KEY (`Item_Id`) REFERENCES `clothing_item` (`Item_Id`),
  ADD CONSTRAINT `item_beneficiary_ibfk_2` FOREIGN KEY (`B_Id`) REFERENCES `beneficiary` (`B_Id`);

--
-- Constraints for table `pickup_request`
--
ALTER TABLE `pickup_request`
  ADD CONSTRAINT `pickup_request_ibfk_1` FOREIGN KEY (`D_Id`) REFERENCES `donor` (`D_Id`),
  ADD CONSTRAINT `pickup_request_ibfk_2` FOREIGN KEY (`V_Id`) REFERENCES `volunteer` (`V_Id`);

--
-- Constraints for table `volunteer_phone`
--
ALTER TABLE `volunteer_phone`
  ADD CONSTRAINT `volunteer_phone_ibfk_1` FOREIGN KEY (`V_Id`) REFERENCES `volunteer` (`V_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;