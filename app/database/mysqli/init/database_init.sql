-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2016 at 03:01 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jngmanualdb`
--
CREATE DATABASE IF NOT EXISTS `jngmanualdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `jngmanualdb`;

-- --------------------------------------------------------

--
-- Table structure for table `jng_accesslevels`
--

DROP TABLE IF EXISTS `jng_accesslevels`;
CREATE TABLE IF NOT EXISTS `jng_accesslevels` (
`id` int(11) NOT NULL,
  `name` text NOT NULL,
  `alias` text NOT NULL,
  `description` text NOT NULL,
  `level` int(11) NOT NULL COMMENT 'This indicates the hierachy of seniority of roles (0 being the lowest)',
  `permissions` text NOT NULL COMMENT 'This column holds all the rules conferred on each user in this category'
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jng_users`
--

DROP TABLE IF EXISTS `jng_users`;
CREATE TABLE IF NOT EXISTS `jng_users` (
`id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(300) NOT NULL,
  `accesslevels_id` text NOT NULL,
  `user_profiles_id` int(11) NOT NULL,
  `enabled` text NOT NULL,
  `last_login` int(15) DEFAULT NULL,
  `permissions` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=388 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jng_user_profiles`
--

DROP TABLE IF EXISTS `jng_user_profiles`;
CREATE TABLE IF NOT EXISTS `jng_user_profiles` (
`id` int(11) NOT NULL,
  `name` text NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `date_of_birth` int(20) NOT NULL,
  `postal_address` varchar(300) NOT NULL,
  `physical_location` varchar(200) NOT NULL,
  `other_details` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jng_accesslevels`
--
ALTER TABLE `jng_accesslevels`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jng_users`
--
ALTER TABLE `jng_users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jng_user_profiles`
--
ALTER TABLE `jng_user_profiles`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jng_accesslevels`
--
ALTER TABLE `jng_accesslevels`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `jng_users`
--
ALTER TABLE `jng_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=388;
--
-- AUTO_INCREMENT for table `jng_user_profiles`
--
ALTER TABLE `jng_user_profiles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
