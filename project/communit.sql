-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2015 at 09:42 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;


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
(1, NULL, NULL, NULL, 'guest', 'password');

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
-- Indexes for dumped tables
--

--
-- Indexes for table `head_residents`
--
ALTER TABLE `head_residents`
 ADD PRIMARY KEY (`head_resident_id`), ADD KEY `head_residents_ibfk_1` (`fk_residence_id`);

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
-- AUTO_INCREMENT for table `head_residents`
--
ALTER TABLE `head_residents`
MODIFY `head_resident_id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
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
ADD CONSTRAINT `head_residents_ibfk_1` FOREIGN KEY (`fk_residence_id`) REFERENCES `residences` (`residence_id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_residents`
--
ALTER TABLE `sub_residents`
ADD CONSTRAINT `sub_residents_ibfk_1` FOREIGN KEY (`fk_head_id`) REFERENCES `head_residents` (`head_resident_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
