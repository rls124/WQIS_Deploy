-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 17, 2020 at 11:55 PM
-- Server version: 10.2.14-MariaDB
-- PHP Version: 5.6.35

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

DROP TABLE IF EXISTS `bacteria_samples`;
CREATE TABLE IF NOT EXISTS `bacteria_samples` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `site_location_id` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Sample_Number` bigint(20) NOT NULL,
  `Ecoli` int(11) DEFAULT NULL,
  `TotalColiform` int(11) DEFAULT NULL,
  `BacteriaComments` varchar(200) DEFAULT '',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Sample_Number` (`Sample_Number`),
  KEY `fk_site_location_id1` (`site_location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12982 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Feedback` varchar(4096) NOT NULL,
  `Date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `User` varchar(100) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `measurement_settings`
--

DROP TABLE IF EXISTS `measurement_settings`;
CREATE TABLE IF NOT EXISTS `measurement_settings` (
  `measureKey` varchar(100) NOT NULL,
  `measureName` varchar(100) NOT NULL,
  `unit` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `benchmarkMinimum` double DEFAULT NULL,
  `benchmarkMaximum` double DEFAULT NULL,
  `detectionMinimum` double DEFAULT NULL,
  `detectionMaximum` double DEFAULT NULL,
  `Visible` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`measureKey`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nutrient_samples`
--

DROP TABLE IF EXISTS `nutrient_samples`;
CREATE TABLE IF NOT EXISTS `nutrient_samples` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `site_location_id` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Sample_Number` bigint(20) NOT NULL DEFAULT 0,
  `Phosphorus` decimal(5,3) DEFAULT NULL,
  `NitrateNitrite` decimal(5,3) DEFAULT NULL,
  `DRP` decimal(5,3) DEFAULT NULL,
  `Ammonia` decimal(5,3) DEFAULT NULL,
  `NutrientComments` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Sample_Number` (`Sample_Number`),
  KEY `fk_site_location_id2` (`site_location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4898 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pesticide_samples`
--

DROP TABLE IF EXISTS `pesticide_samples`;
CREATE TABLE IF NOT EXISTS `pesticide_samples` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `site_location_id` int(11) DEFAULT NULL,
  `Date` date NOT NULL,
  `Sample_Number` bigint(20) NOT NULL,
  `Atrazine` decimal(5,3) DEFAULT NULL,
  `Alachlor` decimal(5,3) DEFAULT NULL,
  `Metolachlor` decimal(5,3) DEFAULT NULL,
  `PesticideComments` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Sample_Number` (`Sample_Number`),
  KEY `fk_site_location_id3` (`site_location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12263 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `physical_samples`
--

DROP TABLE IF EXISTS `physical_samples`;
CREATE TABLE IF NOT EXISTS `physical_samples` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `site_location_id` int(11) DEFAULT NULL,
  `Date` date NOT NULL,
  `Sample_Number` bigint(20) NOT NULL,
  `Time` time DEFAULT NULL,
  `Bridge_to_Water_Height` decimal(5,3) DEFAULT NULL,
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
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Sample_Number` (`Sample_Number`),
  KEY `fk_site_location_id4` (`site_location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12328 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_groups`
--

DROP TABLE IF EXISTS `site_groups`;
CREATE TABLE IF NOT EXISTS `site_groups` (
  `groupKey` int(11) NOT NULL AUTO_INCREMENT,
  `groupName` varchar(200) NOT NULL,
  `groupDescription` varchar(500) DEFAULT NULL,
  `owner` varchar(100) NOT NULL,
  PRIMARY KEY (`groupKey`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_locations`
--

DROP TABLE IF EXISTS `site_locations`;
CREATE TABLE IF NOT EXISTS `site_locations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Site_Number` int(11) NOT NULL,
  `Monitored` tinyint(1) NOT NULL DEFAULT 0,
  `Longitude` decimal(20,10) NOT NULL,
  `Latitude` decimal(20,10) NOT NULL,
  `Site_Location` varchar(200) NOT NULL,
  `Site_Name` varchar(200) NOT NULL,
  `groups` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Site_Number` (`Site_Number`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `hasTakenTutorial` tinyint(1) NOT NULL DEFAULT 0,
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
  `securityanswer3` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

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

--
-- Constraints for table `physical_samples`
--
ALTER TABLE `physical_samples`
  ADD CONSTRAINT `fk_site_location_id4` FOREIGN KEY (`site_location_id`) REFERENCES `site_locations` (`Site_Number`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
