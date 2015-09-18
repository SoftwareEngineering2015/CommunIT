-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2015 at 09:37 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `head_residents`
--

CREATE TABLE IF NOT EXISTS `head_residents` (
  `head_resident_id` int(255) NOT NULL,
  `fk_residence_id` int(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `emergency_number` varchar(255) NOT NULL,
  `phone_one` varchar(255) DEFAULT NULL,
  `phone_two` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `head_residents`
--

INSERT INTO `head_residents` (`head_resident_id`, `fk_residence_id`, `first_name`, `last_name`, `birth_date`, `emergency_number`, `phone_one`, `phone_two`, `email_address`, `date_added`) VALUES
(1, 3, 'Joey', 'Calzone', '1970-01-20', '444-555-8888', '432-555-3356', '111-555-3221', 'Email001@aol.com', '2015-09-17 11:40:39'),
(2, 4, 'Penny', 'Calzone', '1973-01-20', '345-555-6785', '444-555-6789', '334-555-9876', 'Email002@aol.com', '2015-09-17 11:40:39'),
(3, 5, 'Walter', 'Calzone', '1997-05-12', '222-555-3334', '876-555-9999', '', 'Email003@aol.com', '2015-09-17 11:40:39'),
(4, 6, 'Samantha', 'Rigatoni', '1986-12-03', '323-555-6565', '565-555-8865', '432-555-9987', 'Email004@aol.com', '2015-09-17 11:40:39'),
(5, 7, 'Watson', 'Rigatoni', '1987-09-21', '432-555-9876', '124-555-3732', '', 'Email005@aol.com', '2015-09-17 11:40:39');

-- --------------------------------------------------------

--
-- Table structure for table `residences`
--

CREATE TABLE IF NOT EXISTS `residences` (
`residence_id` int(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `residences`
--

INSERT INTO `residences` (`residence_id`, `address`, `username`, `password`) VALUES
(1, '', 'admin', 'password'),
(2, '', 'guest', 'password'),
(3, '501 S Calumet Ave Aurora, IL 60506', 'house001', 'password'),
(4, '502 S Calumet Ave Aurora, IL 60506', 'house002', 'password'),
(5, '503 S Calumet Ave Aurora, IL 60506', 'house003', 'password'),
(6, '504 S Calumet Ave Aurora, IL 60506', 'house005', 'password'),
(7, '505 S Calumet Ave Aurora, IL 60506', 'house004', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `sub_residents`
--

CREATE TABLE IF NOT EXISTS `sub_residents` (
`sub_residents_id` int(255) NOT NULL,
  `fk_head_id` int(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
 ADD PRIMARY KEY (`residence_id`);

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
MODIFY `residence_id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `sub_residents`
--
ALTER TABLE `sub_residents`
MODIFY `sub_residents_id` int(255) NOT NULL AUTO_INCREMENT;
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
