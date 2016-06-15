-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 03. Jul 2013 um 12:13
-- Server Version: 5.5.16
-- PHP-Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `marksheet`
--

CREATE DATABASE `marksheet` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `marksheet`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `abbrCourse` varchar(20) NOT NULL,
  `courseName` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `abbrCourse` (`abbrCourse`),
  UNIQUE KEY `abbrCourse_2` (`abbrCourse`),
  UNIQUE KEY `abbrCourse_3` (`abbrCourse`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `course`
--

INSERT INTO `course` (`id`, `abbrCourse`, `courseName`) VALUES
(1, 'MCA', 'Masterof Computer Application'),
(2, 'MMS', 'Master of Management Studies'),
(3, 'PGDM', 'Post Graduation Diploma in  Management');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `studentreport`
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Daten für Tabelle `studentreport`
--

INSERT INTO `studentreport` (`id`, `seatNo`, `course`, `semester`, `studentName`, `studentPhoto`, `monthYear`, `marks`) VALUES
(2, '1234', 'MMS', 'Semester-1', 'Godly Idicula', 'images/NASA.jpg', 'May-2013', '[{"subject":"Financial Accounting","internal":"14","external":"45"},{"subject":"Managerial Economics","internal":"15","external":"50"},{"subject":"Business Management","internal":"16","external":"50"}]'),
(3, '5678', 'MMS', 'Semester-1', 'Ishwar Bichewar', NULL, 'May-2013', '[{"subject":"Financial Accounting","internal":"24","external":"60"},{"subject":"Managerial Economics","internal":"24","external":"60"},{"subject":"Business Management","internal":"24","external":"60"}]'),
(4, '4567', 'MCA', 'Semester-1', 'Ranganathan Iyer', NULL, 'May-2013', '[{"subject":"Web Technologies","internal":"25","external":"99"},{"subject":"Programming in C","internal":"20","external":"40"}]'),
(6, '1234', 'PGDM', 'Semester-1', 'Kevin', 'images/TryItLogo.jpg', 'May-2013', '[{"subject":"PG1","internal":"20","external":"46"},{"subject":"PG2","internal":"20","external":"35"}]'),
(7, '5656', 'MMS', 'Semester-1', 'asdf', '', 'January-2000', '[{"subject":"Financial Accounting","internal":"10","external":"11"},{"subject":"Managerial Economics","internal":"10","external":"11"},{"subject":"Business Management","internal":"10","external":"11"}]'),
(8, '13244', 'MMS', 'Semester-1', 'afasa', 'images/TryItLogo.jpg', 'January-2000', '[{"subject":"Financial Accounting","internal":"15","external":"11"},{"subject":"Managerial Economics","internal":"10","external":"10"},{"subject":"Business Management","internal":"11","external":"11"}]'),
(10, '7777', 'MMS', 'Semester-1', 'ffffhf', '', 'January-2000', '[{"subject":"Financial Accounting","internal":"16","external":"11"},{"subject":"Managerial Economics","internal":"10","external":"11"},{"subject":"Business Management","internal":"10","external":"11"}]'),
(11, '8888', 'MMS', 'Semester-1', 'afsdgsh', '', 'January-2000', '[{"subject":"Financial Accounting","internal":"10","external":"12"},{"subject":"Managerial Economics","internal":"10","external":"12"},{"subject":"Business Management","internal":"10","external":"12"}]'),
(12, '111', 'MMS', 'Semester-1', 'rtretwt', '', 'January-2000', '[{"subject":"Financial Accounting","internal":"16","external":"11"},{"subject":"Managerial Economics","internal":"10","external":"11"},{"subject":"Business Management","internal":"10","external":"11"}]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` varchar(20) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `subjects` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `subjects`
--

INSERT INTO `subjects` (`id`, `course`, `semester`, `subjects`) VALUES
(1, 'MMS', 'Semester-1', '[{"subject":"Financial Accounting","internal":"20","external":"50"},{"subject":"Managerial Economics","internal":"20","external":"50"},{"subject":"Business Management","internal":"20","external":"50"},{"subject":"PM","internal":"20","external":"50"}]'),
(2, 'MCA', 'Semester-1', '[{"subject":"Web Technologies","internal":"25","external":"100"},{"subject":"Programming in C","internal":"25","external":"100"}]'),
(4, 'PGDM', 'Semester-1', '[{"subject":"PG1","internal":"20","external":"50"},{"subject":"PG2","internal":"20","external":"50"}]'),
(5, 'MMS', 'Semester-2', '[{"subject":"","internal":"","external":""}]');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
