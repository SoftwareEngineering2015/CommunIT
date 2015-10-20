-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2015 at 10:17 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `communit`
--

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `community_name` varchar(255) DEFAULT 'Community',
  `max_per_residence` int(255) DEFAULT '10'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`community_name`, `max_per_residence`) VALUES
('CommunITville', 10);

-- --------------------------------------------------------

--
-- Table structure for table `head_residents`
--

CREATE TABLE IF NOT EXISTS `head_residents` (
  `head_resident_id` int(255) NOT NULL,
  `fk_residence_id` int(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `emergency_contact` varchar(255) NOT NULL,
  `phone_one` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `head_residents`
--

INSERT INTO `head_residents` (`head_resident_id`, `fk_residence_id`, `first_name`, `last_name`, `emergency_contact`, `phone_one`, `email_address`, `date_added`) VALUES
(1, 3, 'Joey', 'Calzone', '444-555-8888', '432-555-3356', 'Email001@aol.com', '2015-09-17 11:40:39'),
(2, 4, 'Penny', 'Pasta', '345-555-6785', '444-555-6789', 'Email002@aol.com', '2015-09-17 11:40:39'),
(3, 5, 'Mikey', 'Meatball', '222-555-3334', '876-555-9999', 'Email003@aol.com', '2015-09-17 11:40:39'),
(4, 6, 'Samantha', 'Spaghetti', '323-555-6565', '565-555-8865', 'Email004@aol.com', '2015-09-17 11:40:39'),
(5, 7, 'Richard', 'Rigatoni', '432-555-9876', '124-555-3732', 'Email005@aol.com', '2015-09-17 11:40:39');

-- --------------------------------------------------------

--
-- Table structure for table `residences`
--

CREATE TABLE IF NOT EXISTS `residences` (
`residence_id` int(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT 'password'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `residences`
--

INSERT INTO `residences` (`residence_id`, `address`, `latitude`, `longitude`, `username`, `password`) VALUES
(0, NULL, NULL, NULL, 'admin', 'password'),
(1, NULL, NULL, NULL, 'guest', 'password'),
(3, '501 S Calumet Ave Aurora, IL 60506', '41.751632', '-88.348559', 'house001', 'password'),
(4, '502 S Calumet Ave Aurora, IL 60506', '41.751501', '-88.347945', 'house002', 'password'),
(5, '503 S Calumet Ave Aurora, IL 60506', '41.751418', '-88.348554', 'house003', 'password'),
(6, '504 S Calumet Ave Aurora, IL 60506', '41.751331', '-88.347952', 'house005', 'password'),
(7, '508 S Calumet Ave Aurora, IL 60506', '41.751184', '-88.347958', 'house004', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `sub_residents`
--

CREATE TABLE IF NOT EXISTS `sub_residents` (
`sub_residents_id` int(255) NOT NULL,
  `fk_head_id` int(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_residents`
--

INSERT INTO `sub_residents` (`sub_residents_id`, `fk_head_id`, `first_name`, `last_name`, `phone_number`, `email_address`, `date_added`) VALUES
(1, 1, 'Boris', 'Lakowsky', '555-555-5555', NULL, '2015-10-04 19:23:01'),
(2, 1, 'Peter', 'Peterson', '555-555-5555', NULL, '2015-10-04 19:23:01'),
(3, 2, 'Tom', 'Johnson', '555-555-5555', NULL, '2015-10-04 19:23:01'),
(4, 2, 'John', 'Tomson', '555-555-5555', NULL, '2015-10-04 19:23:01'),
(5, 3, 'Mark', 'Walberg', '555-555-5555', NULL, '2015-10-04 19:23:01'),
(7, 5, 'Howard', 'Johnson', '555-555-5555', NULL, '2015-10-05 23:01:45'),
(8, 5, 'Charlie', 'Donut', '333-555-9999', NULL, '2015-10-05 23:03:01'),
(9, 5, 'Sean', 'Long', '555-555-5555', NULL, '2015-10-06 14:48:36'),
(10, 3, 'Carol', 'Cannoli', '555-555-1111', NULL, '2015-10-16 17:41:32'),
(11, 3, 'Miranda', 'Marinara', '111-333-3333', NULL, '2015-10-16 17:41:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `head_residents`
--
ALTER TABLE `head_residents`
 ADD PRIMARY KEY (`head_resident_id`), ADD KEY `fk_residence_id` (`fk_residence_id`);

--
-- Indexes for table `residences`
--
ALTER TABLE `residences`
 ADD PRIMARY KEY (`residence_id`), ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `sub_residents`
--
ALTER TABLE `sub_residents`
 ADD PRIMARY KEY (`sub_residents_id`), ADD KEY `sub_resident_id_idx` (`fk_head_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `residences`
--
ALTER TABLE `residences`
MODIFY `residence_id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `sub_residents`
--
ALTER TABLE `sub_residents`
MODIFY `sub_residents_id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `head_residents`
--
ALTER TABLE `head_residents`
ADD CONSTRAINT `head_residents_ibfk_1` FOREIGN KEY (`fk_residence_id`) REFERENCES `residences` (`residence_id`);

--
-- Constraints for table `sub_residents`
--
ALTER TABLE `sub_residents`
ADD CONSTRAINT `sub_resident_id` FOREIGN KEY (`fk_head_id`) REFERENCES `head_residents` (`head_resident_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
