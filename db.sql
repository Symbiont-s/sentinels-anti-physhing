-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 21, 2021 at 10:01 PM
-- Server version: 5.5.24-log
-- PHP Version: 7.4.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phishingtooldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `changelog`
--

CREATE TABLE IF NOT EXISTS `changelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `responsible` varchar(50) NOT NULL,
  `activity` int(11) NOT NULL,
  `description` int(11) NOT NULL,
  `information` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity` (`activity`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;


-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE IF NOT EXISTS `farmers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `friend` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;


-- --------------------------------------------------------

--
-- Table structure for table `phishers`
--

CREATE TABLE IF NOT EXISTS `phishers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;


-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(100) DEFAULT NULL,
  `phisher` varchar(255) DEFAULT NULL,
  `explanation` longtext NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `following_account` varchar(255) NOT NULL,
  `allow_following_account` int(11) NOT NULL DEFAULT '0',
  `allow_auto_alerts` int(11) NOT NULL,
  `bot_phisher_message` longtext NOT NULL,
  `bot_link_message` longtext NOT NULL,
  `account` varchar(255) NOT NULL,
  `posting_key` varchar(255) NOT NULL,
  `min_downvote_percent` int(11) NOT NULL DEFAULT '0',
  `min_rc_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `following_account`, `allow_following_account`, `allow_auto_alerts`, `bot_phisher_message`, `bot_link_message`, `account`, `posting_key`, `min_downvote_percent`, `min_rc_percent`) VALUES
(1, 'the.experimenter', 0, 1, '<center>\r\n\r\n<div class="phishy">\r\n\r\n<strong><h1>[ ATTENTION!]</h2></strong>\r\n\r\n</div>\r\n\r\nThis user @[account] was blacklisted due to his involvement in:\r\n\r\n[operations]\r\n\r\nWe highly advise the community to be careful when dealing with him.\r\n\r\n---\r\n\r\n[Change Password](https://steemitwallet.com/change_password) | [Stolen Accounts Recovery](https://steemitwallet.com/recover_account_step_1) \r\nReport any suspicious behavior on [Steem Sentinels](https://steemsentinels.com/reports)\r\nNeed help? Reach us on [Discord](https://discord.gg/4vvDGWFjA2) \r\n\r\n</center>', '<center>\r\n\r\n<div class="phishy">\r\n\r\n<strong><h1>[ ATTENTION!]</h2></strong>\r\n\r\nThis user is/was involved in spreading phishing links to hack users.\r\n\r\nBe careful and do not click on any of any links!\r\n\r\n</div>\r\n</center>', 'the.experimenter', '', -1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `spammers`
--

CREATE TABLE IF NOT EXISTS `spammers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `status`) VALUES
(1, 'root', '$2y$10$3UJaY8w79G00oPJpNiwcAeDbW2Fefu6s3QVIbHbe/J4gACgMW2Pl.', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
