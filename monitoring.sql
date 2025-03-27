-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 09:45 AM
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
-- Database: `monitoring`
--

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `LOCATION_ID` int(11) NOT NULL,
  `STREET` varchar(255) DEFAULT NULL,
  `PUROK` varchar(255) NOT NULL,
  `BARANGAY` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`LOCATION_ID`, `STREET`, `PUROK`, `BARANGAY`) VALUES
(188, NULL, '1', 'Salvacion'),
(189, NULL, '1', 'Salvacion'),
(190, NULL, '5', 'Salvacion'),
(191, NULL, '1', 'salvacion'),
(192, NULL, '1', 'salvacion'),
(193, NULL, 'Public Market', 'Daraga'),
(194, NULL, '1', 'Salvacion'),
(195, NULL, '5', 'Salvacion'),
(196, NULL, '4', 'Salvacion'),
(197, NULL, '4', 'Salvacion'),
(198, NULL, '4', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `OWNER_ID` int(11) NOT NULL,
  `SHOP_ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(50) DEFAULT NULL,
  `LAST_NAME` varchar(50) DEFAULT NULL,
  `GENDER` varchar(50) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `PHONE_NUMBER` varchar(20) NOT NULL,
  `JOB_ID` int(11) DEFAULT NULL,
  `HIRED_DATE` varchar(50) NOT NULL,
  `LOCATION_ID` int(11) DEFAULT NULL,
  `DOCUMENT_1` varchar(255) DEFAULT NULL,
  `DOCUMENT_2` varchar(255) DEFAULT NULL,
  `STATUS` enum('Active','Pending','Processing','Reject') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`OWNER_ID`, `SHOP_ID`, `FIRST_NAME`, `LAST_NAME`, `GENDER`, `EMAIL`, `PHONE_NUMBER`, `JOB_ID`, `HIRED_DATE`, `LOCATION_ID`, `DOCUMENT_1`, `DOCUMENT_2`, `STATUS`) VALUES
(1, 1, 'Admin', 'Admin', 'Male', 'Admin@email.com', '09991234567', 0, '2025-02-05', 188, '../assets/documents/717348001.png', '../assets/documents/717348002.png', 'Active'),
(18488, 20670572, 'Samarina', 'Hardware', 'Male', 'samarina2@gmail.com', '09815128418', 0, '2025-03-11', 190, '../assets/documents/206705721.jpg', '../assets/documents/206705722.jpg', 'Active'),
(21338, 42302750, 'Buddy', 'Benasa', 'Male', 'buddybenasa12@gmail.com', '09164166702', 0, '2025-03-11', 193, '../assets/documents/423027501.jpg', '../assets/documents/423027502.jpg', 'Active'),
(34171, 56772380, 'Jojo', 'marjalino', 'Male', 'jojomarjalino@wgmail.com', '09164166702', 0, '2025-03-15', 194, '../assets/documents/567723801.jpg', '../assets/documents/567723802.jpg', 'Active'),
(47588, 77981742, 'Richard', 'Alipio', 'Male', 'alipio123@gmail.com', '09164166702', 0, '2025-03-11', 191, '../assets/documents/779817421.jpg', '../assets/documents/779817422.jpg', 'Active'),
(84785, 67692435, 'jmic', 'malto', 'Male', 'jmic@gmail.com', '09815128418', 0, '2025-03-15', 195, '../assets/documents/676924351.png', '../assets/documents/676924352.png', 'Active'),
(90452, 73798598, 'Ribancos', 'store', 'Male', 'ribancos2@gmail.com', '09815128418', 0, '2025-03-11', 192, '../assets/documents/737985981.png', '../assets/documents/737985982.jpg', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `SHOP_ID` int(11) NOT NULL,
  `PRODUCT_ID` int(11) NOT NULL,
  `STORE_ID` int(11) NOT NULL,
  `PRODUCT_CODE` varchar(20) NOT NULL,
  `NAME` varchar(50) DEFAULT NULL,
  `DESCRIPTION` varchar(250) NOT NULL,
  `QTY_STOCK` int(50) DEFAULT NULL,
  `MEASURE` varchar(100) NOT NULL,
  `PRICE` decimal(14,3) DEFAULT NULL,
  `IMAGE` varchar(255) DEFAULT NULL,
  `DATE_STOCK_IN` varchar(50) NOT NULL,
  `DATE_EXPIRY` date DEFAULT NULL,
  `pro_status` varchar(255) DEFAULT NULL,
  `CATEGORY_ID` int(11) DEFAULT NULL,
  `SUPPLIER_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`SHOP_ID`, `PRODUCT_ID`, `STORE_ID`, `PRODUCT_CODE`, `NAME`, `DESCRIPTION`, `QTY_STOCK`, `MEASURE`, `PRICE`, `IMAGE`, `DATE_STOCK_IN`, `DATE_EXPIRY`, `pro_status`, `CATEGORY_ID`, `SUPPLIER_ID`) VALUES
(77981742, 1227, 2, '1111', 'Pedigree', 'Dog Food', 10, 'kilo', 135.000, '../../assets/product_img/Pedigree779817421227.jpg', '2025-03-08', '2025-10-20', 'active', NULL, NULL),
(77981742, 1228, 2, '2222', 'Triple Crown', 'Bird Feeds', 5, 'kilo', 70.000, '../../assets/product_img/Triple Crown779817421228.jpg', '2025-02-03', '2025-08-13', 'active', NULL, NULL),
(77981742, 1233, 2, '3333', 'PRESTO Regular Pellet', 'cockfeeds', 0, 'kilo', 31.000, '../../assets/product_img/PRESTO Regular Pellet779817421233.jpg', '2025-01-31', '2025-12-27', 'active', NULL, NULL),
(77981742, 1234, 2, '4444', 'Premium Pre-Starter', 'feeds', 30, 'kilo', 62.250, '../../assets/product_img/Premium Pre-Starter779817421234.jpg', '2025-01-31', '2025-12-28', 'active', NULL, NULL),
(77981742, 1235, 2, '5555', 'Lucy Cat Food', 'catfood', 20, 'kilo', 130.000, '../../assets/product_img/Lucy Cat Food779817421235.jpg', '2025-03-01', '2025-11-10', 'active', NULL, NULL),
(73798598, 1236, 2, '1222', 'PIGROLAC Hog Starter', 'feeds', 30, 'kilo', 41.500, '../../assets/product_img/PIGROLAC Hog Starter737985981236.jpg', '2024-12-16', '2025-08-27', 'active', NULL, NULL),
(73798598, 1237, 2, '1333', 'WHISKAS ADULT', 'CatFood', 20, 'kilo', 160.000, '../../assets/product_img/WHISKAS ADULT737985981237.jpg', '2025-01-15', '2025-07-09', 'active', NULL, NULL),
(73798598, 1238, 2, '1444', 'WHISKAS KITTEN', 'Cat Food', 20, 'kilo', 170.000, '../../assets/product_img/WHISKAS KITTEN737985981238.jpg', '2024-12-09', '2025-07-16', 'active', NULL, NULL),
(73798598, 1239, 2, '1555', 'YUM YUM ADULT', 'Dog Food', 20, 'kilo', 85.000, '../../assets/product_img/YUM YUM ADULT737985981239.jpg', '2025-02-04', '2025-11-03', 'active', NULL, NULL),
(73798598, 1240, 2, '1666', 'SUPRA Turbo Conditioning Concentrate', 'Cock Feeds', 30, 'kilo', 40.000, '../../assets/product_img/SUPRA Turbo Conditioning Concentrate737985981240.jpg', '2025-01-09', '2025-10-17', 'active', NULL, NULL),
(73798598, 1241, 2, '1777', 'FIRE BIRD Conditioner', 'Cock Feeds', 30, 'kilo', 37.500, '../../assets/product_img/FIRE BIRD Conditioner737985981241.jpg', '2025-01-23', '2025-09-17', 'active', NULL, NULL),
(73798598, 1242, 2, '1888', 'SUPRA Cockers Brown', 'Cock Feeds', 30, 'kilo', 32.000, '../../assets/product_img/SUPRA Cockers Brown737985981242.jpg', '2025-01-08', '2025-09-11', 'active', NULL, NULL),
(73798598, 1243, 2, '1999', 'PIGROLAC Hog Grower', 'Pig Feeds', 30, 'kilo', 39.500, '../../assets/product_img/PIGROLAC Hog Grower737985981243.jpg', '2025-02-05', '2025-09-04', 'active', NULL, NULL),
(42302750, 1244, 1, '2111', 'Giniling', 'Pork', 5, 'kilo', 380.000, '../../assets/product_img/Giniling423027501244.jpg', '2025-03-12', '2025-03-13', 'active', NULL, NULL),
(42302750, 1245, 1, '2333', 'MEAT', 'Pork', 20, 'kilo', 360.000, '../../assets/product_img/MEAT423027501245.jpg', '2025-03-12', '2025-03-13', 'active', NULL, NULL),
(42302750, 1246, 1, '2444', 'LIVER', 'Pork', 5, 'kilo', 300.000, '../../assets/product_img/LIVER423027501246.jpg', '2025-03-12', '2025-03-13', 'active', NULL, NULL),
(67692435, 1248, 1, '3111', 'MEAT', 'Pork', 10, 'kilo', 360.000, '../../assets/product_img/MEAT676924351742015653.png', '2025-03-15', '2025-03-16', 'active', NULL, NULL),
(67692435, 1249, 1, '3222', 'PATA', 'Pork', 4, 'kilo', 200.000, '../../assets/product_img/PATA676924351742015701.png', '2025-03-15', '2025-03-16', 'active', NULL, NULL),
(67692435, 1250, 1, '3444', 'LIVER', 'Pork', 5, 'kilo', 300.000, '../../assets/product_img/LIVER676924351742015758.png', '2025-03-15', '2025-03-15', 'active', NULL, NULL),
(20670572, 1251, 3, '4111', 'GALVANIZE SCREEN', 'Screen', 10, 'meter', 120.000, '../../assets/product_img/GALVANIZE SCREEN206705721742016242.jpg', '2025-01-30', '2025-09-09', 'active', NULL, NULL),
(20670572, 1252, 3, '4222', 'PLAIN SHEET', 'PLAIN SHEET', 5, 'roll', 40.000, '../../assets/product_img/PLAIN SHEET206705721742016334.jpg', '2025-03-01', '2025-11-18', 'active', NULL, NULL),
(20670572, 1253, 3, '4333', 'MOLD FLEX', 'Mold Flex', 5, 'piece', 15.000, '../../assets/product_img/MOLD FLEX206705721742016601.jpg', '2025-02-18', '2025-09-30', 'active', NULL, NULL),
(20670572, 1254, 3, '4555', 'HOLLOW BLOCKS', 'Hollow Blocks', 50, 'piece', 12.000, '../../assets/product_img/HOLLOW BLOCKS206705721742016779.jpg', '2025-03-15', '2026-01-08', 'active', NULL, NULL),
(20670572, 1255, 3, '4666', 'RSB', '10mm', 40, 'kilo', 120.000, '../../assets/product_img/r206705721742016864.jpg', '2025-03-05', '2025-10-14', 'active', NULL, NULL),
(20670572, 1256, 3, '4777', 'RSB', '12mm', 50, 'piece', 180.000, '../../assets/product_img/RSB206705721742016954.jpg', '2025-03-05', '2025-11-19', 'active', NULL, NULL),
(20670572, 1257, 3, '488', 'DEDOS', 'Nail', 15, 'set', 90.000, '../../assets/product_img/DEDOS206705721742017023.jpg', '2025-03-04', '2025-10-15', 'active', NULL, NULL),
(20670572, 1258, 3, '4999', 'TIE WIRE', 'Wire', 10, 'set', 90.000, '../../assets/product_img/TIE WIRE206705721742017110.jpg', '2025-03-05', '2025-10-15', 'active', NULL, NULL),
(56772380, 1259, 3, '5111', 'RSB', '10mm', 50, 'piece', 139.000, '../../assets/product_img/RSB567723801742017343.jpg', '2025-03-12', '2025-08-07', 'active', NULL, NULL),
(56772380, 1260, 3, '5222', 'RSB', '12mm', 50, 'piece', 192.000, '../../assets/product_img/RSB567723801742017505.jpg', '2025-03-05', '2025-09-11', 'active', NULL, NULL),
(56772380, 1261, 3, '5333', 'GALVANIZE SCREEN', 'ScreeN', 5, 'meter', 150.000, '../../assets/product_img/GALVANIZE SCREEN567723801742017765.jpg', '2025-01-10', '2025-07-04', 'active', NULL, NULL),
(56772380, 1262, 3, '5444', 'DECORATED BLOCK', 'Block', 20, 'piece', 40.000, '../../assets/product_img/DECORATED BLOCK567723801742017850.jpg', '2025-02-12', '2025-10-31', 'active', NULL, NULL),
(56772380, 1263, 3, '5666', 'CYCLONE WIRE', 'Wire', 5, 'meter', 900.000, '../../assets/product_img/CYCLONE WIRE567723801742018043.jpg', '2025-01-12', '2025-11-02', 'active', NULL, NULL),
(56772380, 1264, 3, '5777', 'TIE WIRE', 'Wire', 10, 'set', 90.000, '../../assets/product_img/TIE WIRE567723801742018208.jpg', '2025-03-12', '2025-12-11', 'active', NULL, NULL),
(56772380, 1265, 3, '5888', 'CONCRETE NAIL', 'Nail', 20, 'set', 100.000, '../../assets/product_img/CONCRETE NAIL567723801742018306.jpg', '2025-01-12', '2025-11-04', 'active', NULL, NULL),
(56772380, 1266, 3, '5999', 'UMBRELLA NAIL', 'Nail', 20, 'kilo', 90.000, '../../assets/product_img/UM567723801742018407.jpg', '2025-01-12', '2025-11-04', 'active', NULL, NULL),
(77981742, 1267, 2, '6111', 'pellets', 'sacsa', 12, 'kilo', 32.000, '../../assets/product_img/pellets779817421742029833.jpg', '2025-03-11', '2025-10-01', 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `RESERVATION_ID` int(11) NOT NULL,
  `SHOP_ID` int(11) NOT NULL,
  `PRODUCT_CODE` varchar(50) NOT NULL,
  `CUSTOMER_NAME` varchar(255) NOT NULL,
  `CONTACT_NUMBER` varchar(20) NOT NULL,
  `QUANTITY` int(11) NOT NULL,
  `PICKUP_DATE` date NOT NULL,
  `STATUS` enum('Pending','Approved','Rejected','For Pick Up','Picked Up') DEFAULT 'Pending',
  `TIMESTAMP` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`RESERVATION_ID`, `SHOP_ID`, `PRODUCT_CODE`, `CUSTOMER_NAME`, `CONTACT_NUMBER`, `QUANTITY`, `PICKUP_DATE`, `STATUS`, `TIMESTAMP`) VALUES
(7, 77981742, '2222', 'test', '09815133675', 5, '2025-03-31', 'Picked Up', '2025-03-27 05:29:07');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `SHOP_ID` int(11) DEFAULT NULL,
  `STORE_ID` int(11) NOT NULL,
  `SHOP_NAME` varchar(100) NOT NULL,
  `ADDRESS` varchar(255) DEFAULT NULL,
  `IMAGE` blob DEFAULT NULL,
  `AVAILABILITY` enum('Open','Close') NOT NULL,
  `TIMESTAMP` timestamp NOT NULL DEFAULT current_timestamp(),
  `TIME_OPEN` varchar(10) DEFAULT '05:00 AM',
  `TIME_CLOSE` varchar(10) DEFAULT '12:00 PM',
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`SHOP_ID`, `STORE_ID`, `SHOP_NAME`, `ADDRESS`, `IMAGE`, `AVAILABILITY`, `TIMESTAMP`, `TIME_OPEN`, `TIME_CLOSE`, `id`) VALUES
(20670572, 3, 'SAMARINA CONTRUCTION HARDWARE', 'Legazpi City Albay', 0x616c6970696f2e6a7067, 'Open', '2025-03-11 07:55:05', '05:00', '19:00', 3),
(42302750, 1, 'A. BOSEO', '', 0x626f73656f2e6a7067, 'Open', '2025-03-11 21:27:06', '05:00', '17:00', 5),
(67692435, 1, 'JMIC MEAT SHOP', '', 0x53637265656e73686f7420323032352d30332d3133203131343834342e706e67, 'Open', '2025-03-15 02:48:00', '05:00 AM', '12:00 PM', 7),
(77981742, 2, 'ALIPIO STORE', 'Legazpi City ', 0x616c6970696f2e6a7067, '', '2025-03-10 23:55:05', '05:00', '19:00', 8);

-- --------------------------------------------------------

--
-- Table structure for table `store_locations`
--

CREATE TABLE `store_locations` (
  `ID` int(11) NOT NULL,
  `SHOP_ID` int(11) NOT NULL,
  `LATITUDE` decimal(10,8) NOT NULL,
  `LONGITUDE` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_locations`
--

INSERT INTO `store_locations` (`ID`, `SHOP_ID`, `LATITUDE`, `LONGITUDE`) VALUES
(1, 77981742, 13.17414680, 123.68087947);

-- --------------------------------------------------------

--
-- Table structure for table `store_type`
--

CREATE TABLE `store_type` (
  `STORE_ID` int(11) NOT NULL,
  `CATEGORY` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_type`
--

INSERT INTO `store_type` (`STORE_ID`, `CATEGORY`) VALUES
(2, 'Animal Feeds'),
(3, 'Hardware'),
(4, 'General Merchandise'),
(1, 'Meatshop');

-- --------------------------------------------------------

--
-- Table structure for table `store_visit`
--

CREATE TABLE `store_visit` (
  `ID` int(11) NOT NULL,
  `SHOP_ID` int(11) NOT NULL,
  `VISIT_TIMESTAMP` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SUPPLIER_ID` int(11) NOT NULL,
  `COMPANY_NAME` varchar(50) DEFAULT NULL,
  `LOCATION_ID` int(11) NOT NULL,
  `PHONE_NUMBER` varchar(11) DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SUPPLIER_ID`, `COMPANY_NAME`, `LOCATION_ID`, `PHONE_NUMBER`, `status`) VALUES
(11, 'Alpha Quickpay Company', 114, '09457488521', 'active'),
(12, 'Liwayway Marketing Corporation', 115, '09635877412', 'active'),
(13, 'Nestle ', 111, '09587855685', 'active'),
(15, 'Grow-Sari Corp.', 116, '09124033805', 'active'),
(16, 'San Miguel Corporation', 155, '09916054806', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `TYPE_ID` int(11) NOT NULL,
  `TYPE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`TYPE_ID`, `TYPE`) VALUES
(1, 'Admin'),
(2, 'Customer'),
(3, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `SHOP_ID` int(11) NOT NULL,
  `OWNER_ID` int(11) NOT NULL,
  `USERNAME` varchar(50) DEFAULT NULL,
  `PASSWORD` varchar(50) DEFAULT NULL,
  `TYPE_ID` int(11) DEFAULT NULL,
  `STORE_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `SHOP_ID`, `OWNER_ID`, `USERNAME`, `PASSWORD`, `TYPE_ID`, `STORE_ID`) VALUES
(37, 1, 1, 'Admin', 'Admin1234', 1, 1),
(39, 20670572, 18488, 'samarina', 'samarina123', 3, 3),
(40, 77981742, 47588, 'alipio', 'alipio123', 2, 2),
(42, 42302750, 21338, 'buddy', 'buddy123', 1, 1),
(44, 67692435, 84785, 'malto', 'malto123', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`LOCATION_ID`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`OWNER_ID`),
  ADD UNIQUE KEY `EMPLOYEE_ID` (`OWNER_ID`),
  ADD KEY `LOCATION_ID` (`LOCATION_ID`),
  ADD KEY `JOB_ID` (`JOB_ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`PRODUCT_ID`),
  ADD KEY `CATEGORY_ID` (`CATEGORY_ID`),
  ADD KEY `SUPPLIER_ID` (`SUPPLIER_ID`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`RESERVATION_ID`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_locations`
--
ALTER TABLE `store_locations`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `store_visit`
--
ALTER TABLE `store_visit`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SUPPLIER_ID`),
  ADD KEY `LOCATION_ID` (`LOCATION_ID`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`TYPE_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TYPE_ID` (`TYPE_ID`),
  ADD KEY `EMPLOYEE_ID` (`OWNER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `LOCATION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `PRODUCT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1268;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `RESERVATION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `store_locations`
--
ALTER TABLE `store_locations`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `store_visit`
--
ALTER TABLE `store_visit`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SUPPLIER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
