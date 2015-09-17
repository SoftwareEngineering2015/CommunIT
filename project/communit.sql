-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2015 at 08:01 AM
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
-- Table structure for table `residents`
--

CREATE TABLE IF NOT EXISTS `residents` (
  `resident_id` int(255) NOT NULL,
  `fk_residence_id` int(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `emergency_number` varchar(255) NOT NULL,
  `phone_one` varchar(255) DEFAULT NULL,
  `phone_two` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`resident_id`, `fk_residence_id`, `first_name`, `last_name`, `birth_date`, `emergency_number`, `phone_one`, `phone_two`, `email_address`) VALUES
(1, 3, 'Joey', 'Calzone', '1970-01-20', '444-555-8888', '432-555-3356', '111-555-3221', 'Email001@aol.com'),
(2, 3, 'Penny', 'Calzone', '1973-01-20', '345-555-6785', '444-555-6789', '334-555-9876', 'Email002@aol.com'),
(3, 3, 'Walter', 'Calzone', '1997-05-12', '222-555-3334', '876-555-9999', '', 'Email003@aol.com'),
(4, 4, 'Samantha', 'Rigatoni', '1986-12-03', '323-555-6565', '565-555-8865', '432-555-9987', 'Email004@aol.com'),
(5, 5, 'Watson', 'Rigatoni', '1987-09-21', '432-555-9876', '124-555-3732', '', 'Email005@aol.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `residences`
--
ALTER TABLE `residences`
 ADD PRIMARY KEY (`residence_id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
 ADD PRIMARY KEY (`resident_id`), ADD KEY `fk_residence_id` (`fk_residence_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `residences`
--
ALTER TABLE `residences`
MODIFY `residence_id` int(255) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `residents`
--
ALTER TABLE `residents`
ADD CONSTRAINT `residents_ibfk_1` FOREIGN KEY (`fk_residence_id`) REFERENCES `residences` (`residence_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
