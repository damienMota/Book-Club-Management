-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2020 at 06:56 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `application_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE IF NOT EXISTS `activity_log` (
  `application_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`application_id`, `action`, `description`, `time`) VALUES
(9, 'Submitted Application', '', '2020-10-06 17:45:47'),
(9, 'Edited Application', 'Following text fields have changed: Name, Phone Number, Primary Email', '2020-10-09 17:36:51'),
(9, 'Marked Complete', '', '2020-10-12 16:43:38'),
(9, 'Returned Application', 'Please go and fix this issue.', '2020-10-12 17:38:59'),
(9, 'Submitted Application', '', '2020-10-31 23:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `application_main`
--

DROP TABLE IF EXISTS `application_main`;
CREATE TABLE IF NOT EXISTS `application_main` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `primary_phone_number` varchar(255) NOT NULL,
  `primary_email` varchar(255) NOT NULL,
  `application_status` varchar(255) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `reference_number` int(11) NOT NULL,
  `validation_code` int(11) NOT NULL,
  `client_URN` varchar(255) NOT NULL,
  `client_education` varchar(255) NOT NULL,
  `about_me` varchar(255) NOT NULL,
  PRIMARY KEY (`application_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `application_main`
--

INSERT INTO `application_main` (`application_id`, `name`, `primary_phone_number`, `primary_email`, `application_status`, `business_name`, `reference_number`, `validation_code`, `client_URN`, `client_education`, `about_me`) VALUES
(7, 'Paul Atreides', '3238069526', 'outbyaspen@gmail.com', 'pending', '', 0, 222444, '', '', ''),
(9, 'Damien Mota', '3238069526', 'outbyaspen@gmail.com', 'submitted', 'dune', 9, 856884, '4lhfWQsYue^Rv5P9', 'Bachelor degree', 'Mother'),
(10, ' ', '', '', 'pending', '', 0, 545862, 'MXr9VTK#bvI2a45n', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contact_info`
--

DROP TABLE IF EXISTS `emergency_contact_info`;
CREATE TABLE IF NOT EXISTS `emergency_contact_info` (
  `eci_application_id` int(11) NOT NULL,
  `eci_name` varchar(255) NOT NULL,
  `eci_email` varchar(255) NOT NULL,
  `eci_phone_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emergency_contact_info`
--

INSERT INTO `emergency_contact_info` (`eci_application_id`, `eci_name`, `eci_email`, `eci_phone_number`) VALUES
(9, 'Damien', 'Mota', '3238069526'),
(9, 'Paul Atredies', 'atredies.paul@gmail.com', '123456789');

-- --------------------------------------------------------

--
-- Table structure for table `signator_info`
--

DROP TABLE IF EXISTS `signator_info`;
CREATE TABLE IF NOT EXISTS `signator_info` (
  `application_id` int(11) NOT NULL,
  `signedDate` varchar(255) NOT NULL,
  `signatorName` varchar(255) NOT NULL,
  `signatorDecision` varchar(255) NOT NULL,
  `signatorBase64` varchar(15000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `signator_info`
--

INSERT INTO `signator_info` (`application_id`, `signedDate`, `signatorName`, `signatorDecision`, `signatorBase64`) VALUES
(9, '10/08/2020', 'Signed Name', 'signNow', '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'),
(10, '', '', '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
