-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2025 at 07:51 AM
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
(198, NULL, '4', 'test'),
(199, NULL, '4', 'Salvacion'),
(200, NULL, '4', 'Salvacion'),
(201, NULL, '4', 'Salvacion'),
(202, NULL, '4', 'Salvacion'),
(203, NULL, '4', 'Salvacion');

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `OWNER_ID` int(11) NOT NULL,
  `SHOP_ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(50) DEFAULT NULL,
  `LAST_NAME` varchar(50) DEFAULT NULL,
  `MIDDLE_NAME` varchar(50) NOT NULL,
  `SUFFIX` varchar(50) NOT NULL,
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

INSERT INTO `owners` (`OWNER_ID`, `SHOP_ID`, `FIRST_NAME`, `LAST_NAME`, `MIDDLE_NAME`, `SUFFIX`, `GENDER`, `EMAIL`, `PHONE_NUMBER`, `JOB_ID`, `HIRED_DATE`, `LOCATION_ID`, `DOCUMENT_1`, `DOCUMENT_2`, `STATUS`) VALUES
(1, 1, 'Admin', 'Admin', '', '', 'Male', 'Admin@email.com', '09991234567', 0, '2025-02-05', 188, '../assets/documents/717348001.png', '../assets/documents/717348002.png', 'Active'),
(18488, 20670572, 'Samarina', 'Hardware', '', '', 'Male', 'samarina2@gmail.com', '09815128418', 0, '2025-03-11', 190, '../assets/documents/206705721.jpg', '../assets/documents/206705722.jpg', 'Active'),
(21338, 42302750, 'Buddy', 'Benasa', '', '', 'Male', 'buddybenasa12@gmail.com', '09164166702', 0, '2025-03-11', 193, '../assets/documents/423027501.jpg', '../assets/documents/423027502.jpg', 'Active'),
(34171, 56772380, 'Jojo', 'marjalino', '', '', 'Male', 'jojomarjalino@wgmail.com', '09164166702', 0, '2025-03-15', 194, '../assets/documents/567723801.jpg', '../assets/documents/567723802.jpg', 'Active'),
(47588, 77981742, 'Richard', 'Alipio', '', '', 'Male', 'alipio123@gmail.com', '09164166702', 0, '2025-03-11', 191, '../assets/documents/779817421.jpg', '../assets/documents/779817422.jpg', 'Active'),
(81930, 61374502, 'test', 'test', 'test', 'test', 'Male', 'test@gmail.com', '9665376905', 0, '2025-03-28', 203, '../assets/documents/613745021.png', '../assets/documents/613745022.png', 'Pending'),
(84785, 67692435, 'jmic', 'malto', '', '', 'Male', 'jmic@gmail.com', '09815128418', 0, '2025-03-15', 195, '../assets/documents/676924351.png', '../assets/documents/676924352.png', 'Active'),
(90452, 73798598, 'Ribancos', 'store', '', '', 'Male', 'ribancos2@gmail.com', '09815128418', 0, '2025-03-11', 192, '../assets/documents/737985981.png', '../assets/documents/737985982.jpg', 'Active');

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
(67692435, 1, 'JMIC MEAT SHOP', '', 0x53637265656e73686f7420323032352d30332d3133203131343834342e706e67, 'Close', '2025-03-15 02:48:00', '05:00 AM', '12:00 PM', 7),
(77981742, 2, 'ALIPIO STORE', 'Legazpi City ', 0x616c6970696f2e6a7067, 'Close', '2025-03-10 23:55:05', '05:00', '19:00', 8),
(61374502, 3, 'Test', NULL, NULL, 'Open', '2025-03-28 01:39:50', '05:00 AM', '12:00 PM', 12);

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
  `CATEGORY` varchar(255) NOT NULL,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_type`
--

INSERT INTO `store_type` (`STORE_ID`, `CATEGORY`, `ID`) VALUES
(2, 'Animal Feeds', 1),
(3, 'Hardware', 2),
(1, 'Meatshop', 4),
(4, 'General Merchandise', 7);

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
(40, 77981742, 47588, 'alipio', 'alipio123', 3, 2),
(42, 42302750, 21338, 'buddy', 'buddy123', 3, 1),
(44, 67692435, 84785, 'malto', 'malto123', 3, 1),
(49, 61374502, 81930, 'test', 'test', 3, 3);

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
-- Indexes for table `store_type`
--
ALTER TABLE `store_type`
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
  MODIFY `LOCATION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `store_locations`
--
ALTER TABLE `store_locations`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `store_type`
--
ALTER TABLE `store_type`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
