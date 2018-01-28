-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 29, 2017 at 02:04 PM
-- Server version: 5.7.13-log
-- PHP Version: 5.6.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `prj_zues`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer`
--

CREATE TABLE IF NOT EXISTS `tb_customer` (
  `cus_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `lastupdate` datetime NOT NULL,
  PRIMARY KEY (`cus_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tb_customer`
--

INSERT INTO `tb_customer` (`cus_id`, `name`, `address`, `phone`, `email`, `lastupdate`) VALUES
(1, 'ลูกค้า 1', '', '01-111-2222', 'a@gmail.com', '2017-10-29 07:31:29'),
(2, 'ลูกค้า 2', '', '02-222-22222', 'b@gmail.com', '2017-10-29 07:31:37'),
(3, 'ลูกค้า 3', '', '02-333-3333', 'c@gmail.com', '2017-10-29 07:31:44'),
(4, 'ลูกค้า 4', '3333', '02-5555', 'e@gmail.com', '2017-10-29 07:46:39'),
(5, 'user', '', '', '', '2017-10-29 11:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `tb_product`
--

CREATE TABLE IF NOT EXISTS `tb_product` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `detail` text,
  `picture` varchar(50) DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `confirm` tinyint(4) NOT NULL DEFAULT '0',
  `lastupdate` datetime NOT NULL,
  PRIMARY KEY (`pro_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tb_product`
--

INSERT INTO `tb_product` (`pro_id`, `code`, `name`, `detail`, `picture`, `qty`, `cost`, `price`, `confirm`, `lastupdate`) VALUES
(1, '001', 'ดอกไม้', 'xxxxxxxxxxxxxx\r\nxxxxxxxxxxxxxxxxxxxxxx\r\nxxxxxxxxxxxxxxxxx', '20171027082713.jpg', 100, '10.00', '20.00', 1, '2017-10-27 08:23:33'),
(2, '002', 'ภูเขา', 'xxxxxxxxxxxxxxxxxx\r\nxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', '20171029071539.jpg', 100, '15.00', '25.00', 1, '2017-10-29 07:15:39'),
(3, '003', 'หมี', 'ปปปปปปปปปปปป\r\nปxxxxxxxxxxxxx', '20171029072100.jpg', 100, '20.00', '30.00', 1, '2017-10-29 07:21:00'),
(4, '004', 'นก', 'xxxxxxxxx', '20171029072243.jpg', 100, '30.00', '35.00', 1, '2017-10-29 07:22:43'),
(5, '005', 'ดอก2', 'xxxxxxxyyyyy', '20171029072433.jpg', 93, '50.00', '55.00', 1, '2017-10-29 07:24:33');

-- --------------------------------------------------------

--
-- Table structure for table `tb_sale`
--

CREATE TABLE IF NOT EXISTS `tb_sale` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_id` int(11) NOT NULL DEFAULT '0',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `delivery` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `note` text,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `lastupdate` datetime NOT NULL,
  PRIMARY KEY (`sale_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tb_sale`
--

INSERT INTO `tb_sale` (`sale_id`, `cus_id`, `total`, `delivery`, `discount`, `grand`, `cost`, `note`, `user_id`, `lastupdate`) VALUES
(3, 1, '130.00', '10.00', '5.00', '135.00', '80.00', 'ทดสอบ', 2, '2017-10-28 11:26:57'),
(7, 0, '55.00', '0.00', '0.00', '55.00', '50.00', '', 2, '2017-10-29 11:38:26'),
(8, 5, '30.00', '0.00', '0.00', '30.00', '20.00', '', 2, '2017-10-29 11:43:18'),
(9, 0, '55.00', '0.00', '0.00', '55.00', '50.00', '', 2, '2017-10-29 13:32:36'),
(11, 0, '330.00', '0.00', '0.00', '330.00', '300.00', '', 2, '2017-10-29 13:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `tb_sale_detail`
--

CREATE TABLE IF NOT EXISTS `tb_sale_detail` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(20) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `qty` int(11) NOT NULL DEFAULT '0',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`detail_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `tb_sale_detail`
--

INSERT INTO `tb_sale_detail` (`detail_id`, `sale_id`, `code`, `cost`, `price`, `qty`, `total`, `cost_total`) VALUES
(16, 7, '005', '50.00', '55.00', 1, '55.00', '50.00'),
(20, 3, '001', '10.00', '20.00', 1, '20.00', '10.00'),
(21, 3, '002', '15.00', '25.00', 2, '50.00', '30.00'),
(22, 3, '003', '20.00', '30.00', 2, '60.00', '40.00'),
(23, 8, '003', '20.00', '30.00', 1, '30.00', '20.00'),
(24, 9, '005', '50.00', '55.00', 1, '55.00', '50.00'),
(26, 11, '005', '50.00', '55.00', 6, '330.00', '300.00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE IF NOT EXISTS `tb_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `idcard` varchar(13) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `confirm` tinyint(4) NOT NULL DEFAULT '0',
  `lastupdate` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `username`, `password`, `name`, `idcard`, `address`, `phone`, `email`, `level`, `confirm`, `lastupdate`) VALUES
(1, 'admin', 'cfcd208495d565ef66e7dff9f98764da', 'Administrator', '1111111111111', '-', '-', 'bb@gmail.com', 1, 1, '2017-10-27 00:00:00'),
(2, 'sale', 'cfcd208495d565ef66e7dff9f98764da', 'Sale', '1111111111111', '-', '-', 'aa@gmail.com', 2, 1, '2017-10-27 07:44:09');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
