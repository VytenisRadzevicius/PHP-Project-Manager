-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 30, 2020 at 10:40 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+02:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phppm`
--
CREATE DATABASE IF NOT EXISTS `phppm` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `phppm`;

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

DROP TABLE IF EXISTS `assignments`;
CREATE TABLE IF NOT EXISTS `assignments` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `u_id` int(11) UNSIGNED NOT NULL,
  `p_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_USER_ASSIGNMENT` (`u_id`),
  KEY `FK_PROJECT_ASSIGNMENT` (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `u_id`, `p_id`) VALUES
(1, 4, 3),
(2, 3, 3),
(3, 2, 3),
(4, 5, 3),
(5, 1, 1),
(6, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `p_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`p_id`, `name`) VALUES
(1, 'PHP Project Manager'),
(2, 'PHP File Manager'),
(3, 'Nakvyne Kaime'),
(4, 'BIT Sprint 3'),
(5, 'TEST');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `u_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `name`) VALUES
(1, 'Vytenis'),
(2, 'Jurgis'),
(3, 'Antanas'),
(4, 'Aluizas'),
(5, 'Martynas'),
(6, 'Ieva'),
(7, 'Ruta'),
(8, 'Testas Testeris');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `FK_PROJECT_ASSIGNMENT` FOREIGN KEY (`p_id`) REFERENCES `projects` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_USER_ASSIGNMENT` FOREIGN KEY (`u_id`) REFERENCES `users` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
