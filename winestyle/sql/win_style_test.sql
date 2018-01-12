-- phpMyAdmin SQL Dump
-- version 4.3.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 17, 2016 at 06:08 PM
-- Server version: 5.5.47-MariaDB
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `win_style_test`
--
CREATE DATABASE IF NOT EXISTS `win_style_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `win_style_test`;

-- --------------------------------------------------------

--
-- Table structure for table `img_size_types`
--

DROP TABLE IF EXISTS `img_size_types`;
CREATE TABLE IF NOT EXISTS `img_size_types` (
  `size` enum('big','med','min','mic') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'mic' COMMENT 'Вид изображения',
  `width` int(11) NOT NULL DEFAULT '0' COMMENT 'Ширина изображения',
  `height` int(11) NOT NULL DEFAULT '0' COMMENT 'Высота изображения'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Таблица соответствия видов и размеров изображения';

--
-- Dumping data for table `img_size_types`
--

INSERT INTO `img_size_types` (`size`, `width`, `height`) VALUES
('big', 800, 600),
('med', 600, 400),
('min', 300, 200),
('mic', 101, 101);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `img_size_types`
--
ALTER TABLE `img_size_types`
  ADD PRIMARY KEY (`size`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
