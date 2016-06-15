-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 25, 2013 at 04:06 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `marksheet`
--

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `abbrCourse` varchar(20) NOT NULL,
  `courseName` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `abbrCourse` (`abbrCourse`),
  UNIQUE KEY `abbrCourse_2` (`abbrCourse`),
  UNIQUE KEY `abbrCourse_3` (`abbrCourse`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `abbrCourse`, `courseName`) VALUES
(1, 'MCA', 'Master of Computer Applications'),
(2, 'MMS', 'Master of Management Studies'),
(3, 'PGDM', 'Post Graduation Diploma in  Management');

-- --------------------------------------------------------

--
-- Table structure for table `studentreport`
--

CREATE TABLE IF NOT EXISTS `studentreport` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `seatNo` varchar(20) NOT NULL,
  `course` varchar(20) NOT NULL,
  `semester` varchar(15) NOT NULL,
  `studentName` varchar(30) NOT NULL,
  `studentPhoto` varchar(100) DEFAULT NULL,
  `monthYear` varchar(20) NOT NULL,
  `marks` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `studentreport`
--

/*INSERT INTO `studentreport` (`id`, `seatNo`, `course`, `semester`, `studentName`, `studentPhoto`, `monthYear`, `marks`) VALUES
(2, '1234', 'MMS', 'Semester-1', 'Godly Idicula', NULL, 'May-2013', '[{"subject":"Financial Accounting","internal":"54","external":"45"},{"subject":"Managerial Economics","internal":"45","external":"50"},{"subject":"Business Management","internal":"25","external":"80"}]'),
(3, '5678', 'MMS', 'Semester-1', 'Ishwar Bichewar', NULL, 'May-2013', '[{"subject":"Financial Accounting","internal":"24","external":"60"},{"subject":"Managerial Economics","internal":"24","external":"60"},{"subject":"Business Management","internal":"24","external":"60"}]'),
(4, '4567', 'MCA', 'Semester-1', 'Ranganathan Iyer', NULL, 'May-2013', '[{"subject":"Web Technologies","internal":"25","external":"99"},{"subject":"Programming in C","internal":"20","external":"40"}]'),
(6, '1234', 'PGDM', 'Semester-1', 'Kevin', NULL, 'May-2013', '[{"subject":"PG1","internal":"20","external":"46"},{"subject":"PG2","internal":"20","external":"35"}]');*/

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` varchar(20) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `subjects` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `course`, `semester`, `subjects`) VALUES
(1, 'MMS', 'Semester-I', '[{"subject":"Perspective Management","internal":"20","external":"30"},{"subject":"Financial Accounting","internal":"40","external":"60"},{"subject":"Managerial Economics","internal":"40","external":"60"},{"subject":"Operations Management","internal":"40","external":"60"},{"subject":"Organisational Behaviour","internal":"40","external":"60"},{"subject":"Business Mathematics","internal":"40","external":"60"},{"subject":"Information Technology for Management","internal":"20","external":"30"},{"subject":"Communication Skills","internal":"40","external":"60"},{"subject":"Marketing Management","internal":"40","external":"60"},{"subject":"Selling and Negotiation Skills","internal":"40","external":"60"},{"subject":"High Performance Leadership","internal":"40","external":"60"}]'),
(2, 'MMS', 'Semester-II', '[{"subject":"Legal and Tax Aspects of Business","internal":"40","external":"60"},{"subject":"Cost and Management Accounting","internal":"40","external":"60"},{"subject":"Economic Environment of Business","internal":"20","external":"30"},{"subject":"Operations Research","internal":"40","external":"60"},{"subject":"Human Resources Management","internal":"40","external":"60"},{"subject":"Research Methodology and Fundamentals of MR","internal":"40","external":"60"},{"subject":"Management of Information Systems","internal":"20","external":"30"},{"subject":"Financial Management","internal":"40","external":"60"},{"subject":"Market Applications and Practices","internal":"40","external":"60"},{"subject":"Decision Science","internal":"40","external":"60"},{"subject":"Analysis of Financial Statements","internal":"40","external":"60"}]');
/*(2, 'MCA', 'Semester-1', '[{"subject":"Web Technologies","internal":"25","external":"100"},{"subject":"Programming in C","internal":"25","external":"100"}]'),
(4, 'PGDM', 'Semester-1', '[{"subject":"PG1","internal":"20","external":"50"},{"subject":"PG2","internal":"20","external":"50"}]'),*/


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
