-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 19, 2016 at 03:37 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `street` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(20) NOT NULL,
  `zip` int(20) NOT NULL,
  `fax` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `commMedium`
--

CREATE TABLE IF NOT EXISTS `commMedium` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empId` int(11) NOT NULL,
  `msg` tinyint(1) NOT NULL,
  `email` tinyint(1) NOT NULL,
  `call` tinyint(1) NOT NULL,
  `any` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `empId` (`empId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(3) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `middleName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `gender` char(1) NOT NULL,
  `dob` date NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `landline` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `maritalStatus` varchar(10) NOT NULL,
  `employment` varchar(11) NOT NULL,
  `employer` varchar(25) NOT NULL,
  `photo` varchar(30) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`eid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `eid` FOREIGN KEY (`eid`) REFERENCES `employee` (`eid`);

--
-- Constraints for table `commMedium`
--
ALTER TABLE `commMedium`
  ADD CONSTRAINT `empID` FOREIGN KEY (`empId`) REFERENCES `employee` (`eid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
