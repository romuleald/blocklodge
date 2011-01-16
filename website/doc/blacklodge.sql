-- phpMyAdmin SQL Dump
-- version 3.3.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2011 at 09:42 PM
-- Server version: 5.1.45
-- PHP Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `blacklodge`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE IF NOT EXISTS `auth` (
  `hash` varchar(255) NOT NULL,
  `expire` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(25) NOT NULL,
  `id` mediumint(9) NOT NULL,
  `post` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`index`),
  KEY `date` (`date`),
  KEY `index` (`index`),
  FULLTEXT KEY `post` (`post`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

CREATE TABLE IF NOT EXISTS `invitations` (
  `hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  UNIQUE KEY `email` (`email`),
  KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mate`
--

CREATE TABLE IF NOT EXISTS `mate` (
  `id_1` int(11) NOT NULL,
  `id_2` int(11) NOT NULL,
  `date` time NOT NULL,
  PRIMARY KEY (`id_1`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `user` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `ban` date NOT NULL,
  `join` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogin` date NOT NULL,
  `lastactivity` time NOT NULL,
  `parent` mediumint(9) NOT NULL,
  `birth` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `whosonline`
--

CREATE TABLE IF NOT EXISTS `whosonline` (
  `user` varchar(255) NOT NULL,
  `id` mediumint(9) NOT NULL,
  `date` int(20) NOT NULL,
  PRIMARY KEY (`user`),
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
