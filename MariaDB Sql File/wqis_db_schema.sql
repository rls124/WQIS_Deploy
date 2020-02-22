-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2020 at 01:44 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wqis_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bacteria_samples`
--

CREATE TABLE `bacteria_samples` (
  `ID` int(11) NOT NULL,
  `site_location_id` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Sample_Number` bigint(20) NOT NULL,
  `EcoliRawCount` int(11) DEFAULT NULL,
  `Ecoli` int(11) DEFAULT NULL,
  `TotalColiformRawCount` int(11) DEFAULT NULL,
  `TotalColiform` int(11) DEFAULT NULL,
  `BacteriaComments` varchar(200) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `ID` int(11) NOT NULL,
  `Feedback` varchar(4096) NOT NULL,
  `Date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `User` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `measurement_settings`
--

CREATE TABLE `measurement_settings` (
  `measureKey` varchar(100) NOT NULL,
  `measureName` varchar(100) NOT NULL,
  `benchmarkMinimum` double DEFAULT NULL,
  `benchmarkMaximum` double DEFAULT NULL,
  `detectionMinimum` double DEFAULT NULL,
  `detectionMaximum` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nutrient_samples`
--

CREATE TABLE `nutrient_samples` (
  `ID` int(11) NOT NULL,
  `site_location_id` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Sample_Number` bigint(20) NOT NULL DEFAULT 0,
  `Phosphorus` decimal(5,3) DEFAULT NULL,
  `NitrateNitrite` decimal(5,3) DEFAULT NULL,
  `DRP` decimal(5,3) DEFAULT NULL,
  `Ammonia` decimal(5,3) DEFAULT NULL,
  `NutrientComments` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pesticide_samples`
--

CREATE TABLE `pesticide_samples` (
  `ID` int(11) NOT NULL,
  `site_location_id` int(11) DEFAULT NULL,
  `Date` date NOT NULL,
  `Sample_Number` bigint(20) NOT NULL,
  `Atrazine` decimal(5,3) DEFAULT NULL,
  `Alachlor` decimal(5,3) DEFAULT NULL,
  `Metolachlor` decimal(5,3) DEFAULT NULL,
  `PesticideComments` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `physical_samples`
--

CREATE TABLE `physical_samples` (
  `ID` int(11) NOT NULL,
  `site_location_id` int(11) DEFAULT NULL,
  `Date` date NOT NULL,
  `Sample_Number` bigint(20) NOT NULL,
  `Time` time DEFAULT NULL,
  `Bridge_to_Water_Height` double DEFAULT NULL,
  `Water_Temp` decimal(5,2) DEFAULT NULL,
  `pH` decimal(5,3) DEFAULT NULL,
  `Conductivity` decimal(5,3) DEFAULT NULL,
  `TDS` decimal(5,3) DEFAULT NULL,
  `DO` decimal(5,3) DEFAULT NULL,
  `Turbidity` decimal(5,1) DEFAULT NULL,
  `Turbidity_Scale_Value` int(4) DEFAULT NULL,
  `PhysicalComments` varchar(200) DEFAULT '',
  `Import_Date` date DEFAULT NULL,
  `Import_Time` time DEFAULT NULL,
  `Requires_Checking` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_locations`
--

CREATE TABLE `site_locations` (
  `ID` int(11) NOT NULL,
  `Site_Number` int(11) NOT NULL,
  `Monitored` tinyint(1) NOT NULL DEFAULT 0,
  `Longitude` decimal(20,10) NOT NULL,
  `Latitude` decimal(20,10) NOT NULL,
  `Site_Location` varchar(200) NOT NULL,
  `Site_Name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `username` varchar(60) NOT NULL,
  `userpw` varchar(60) NOT NULL,
  `firstname` varchar(40) DEFAULT NULL,
  `lastname` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `organization` varchar(60) NOT NULL,
  `position` varchar(60) NOT NULL,
  `Created` date DEFAULT NULL,
  `securityquestion1` varchar(60) DEFAULT NULL,
  `securityanswer1` varchar(120) DEFAULT NULL,
  `securityquestion2` varchar(60) DEFAULT NULL,
  `securityanswer2` varchar(120) DEFAULT NULL,
  `securityquestion3` varchar(60) DEFAULT NULL,
  `securityanswer3` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `admin`, `username`, `userpw`, `firstname`, `lastname`, `email`, `organization`, `position`, `Created`, `securityquestion1`, `securityanswer1`, `securityquestion2`, `securityanswer2`, `securityquestion3`, `securityanswer3`) VALUES
(0, 1, 'root', '$2y$10$XAGR1xVZGOrxL1r03dNLd.g/WAKeH7UMucJvE4UCO60EyIQYNQ3dS', 'admin', 'admin', 'admin@admin.com', 'WQIS', 'admin', '2018-01-21', NULL, NULL, NULL, NULL, NULL, NULL),

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bacteria_samples`
--
ALTER TABLE `bacteria_samples`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Sample_Number` (`Sample_Number`),
  ADD KEY `fk_site_location_id1` (`site_location_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `measurement_settings`
--
ALTER TABLE `measurement_settings`
  ADD PRIMARY KEY (`measureKey`);

--
-- Indexes for table `nutrient_samples`
--
ALTER TABLE `nutrient_samples`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Sample_Number` (`Sample_Number`),
  ADD KEY `fk_site_location_id2` (`site_location_id`);

--
-- Indexes for table `pesticide_samples`
--
ALTER TABLE `pesticide_samples`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Sample_Number` (`Sample_Number`),
  ADD KEY `fk_site_location_id3` (`site_location_id`);

--
-- Indexes for table `physical_samples`
--
ALTER TABLE `physical_samples`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Sample_Number` (`Sample_Number`),
  ADD KEY `fk_site_location_id4` (`site_location_id`);

--
-- Indexes for table `site_locations`
--
ALTER TABLE `site_locations`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Site_Number` (`Site_Number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bacteria_samples`
--
ALTER TABLE `bacteria_samples`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nutrient_samples`
--
ALTER TABLE `nutrient_samples`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesticide_samples`
--
ALTER TABLE `pesticide_samples`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `physical_samples`
--
ALTER TABLE `physical_samples`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_locations`
--
ALTER TABLE `site_locations`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bacteria_samples`
--
ALTER TABLE `bacteria_samples`
  ADD CONSTRAINT `fk_site_location_id1` FOREIGN KEY (`site_location_id`) REFERENCES `site_locations` (`Site_Number`) ON DELETE CASCADE;

--
-- Constraints for table `nutrient_samples`
--
ALTER TABLE `nutrient_samples`
  ADD CONSTRAINT `fk_site_location_id2` FOREIGN KEY (`site_location_id`) REFERENCES `site_locations` (`Site_Number`) ON DELETE CASCADE;

--
-- Constraints for table `pesticide_samples`
--
ALTER TABLE `pesticide_samples`
  ADD CONSTRAINT `fk_site_location_id3` FOREIGN KEY (`site_location_id`) REFERENCES `site_locations` (`Site_Number`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;