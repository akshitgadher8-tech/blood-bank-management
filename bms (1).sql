-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 25, 2025 at 08:31 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bms`
--
CREATE DATABASE IF NOT EXISTS `bms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bms`;

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE IF NOT EXISTS `adminlogin` (
  `username` varchar(15) NOT NULL,
  `password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adminlogin`
--

INSERT INTO `adminlogin` (`username`, `password`) VALUES
('admin', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `blood_request`
--

CREATE TABLE IF NOT EXISTS `blood_request` (
  `requestid` int(3) NOT NULL AUTO_INCREMENT,
  `patientid` varchar(100) NOT NULL,
  `bloodgroup` varchar(3) NOT NULL,
  `unitsrequired` int(225) DEFAULT NULL,
  PRIMARY KEY (`requestid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blood_requests`
--

CREATE TABLE IF NOT EXISTS `blood_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `PatientName` varchar(255) DEFAULT NULL,
  `BloodGroup` varchar(5) DEFAULT NULL,
  `UnitsRequired` int(11) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `RequestDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`id`, `PatientName`, `BloodGroup`, `UnitsRequired`, `Status`, `RequestDate`) VALUES
(1, 'Satyam Gupta', 'B+', 2, 'Pending', '2025-03-24 17:21:28'),
(2, 'Rudra Patel', 'AB+', 1, 'Pending', '2025-03-24 17:23:52');

-- --------------------------------------------------------

--
-- Table structure for table `newdonor`
--

CREATE TABLE IF NOT EXISTS `newdonor` (
  `Donorid` int(3) NOT NULL AUTO_INCREMENT,
  `Name` varchar(25) NOT NULL,
  `MobileNumber` varchar(10) NOT NULL,
  `BloodGroup` varchar(3) NOT NULL,
  PRIMARY KEY (`Donorid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `newdonor`
--

INSERT INTO `newdonor` (`Donorid`, `Name`, `MobileNumber`, `BloodGroup`) VALUES
(1, 'Akshit M Gadher', '8160622320', 'B+'),
(3, 'Kavya Suthar', '9854678512', 'O+'),
(4, 'Harshida Patel', '8546721542', 'O-');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
