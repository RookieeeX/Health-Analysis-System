-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1:3306
-- 生成日期： 2024-03-30 06:33:52
-- 服务器版本： 8.0.36
-- PHP 版本： 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `psy_data`
--

-- --------------------------------------------------------

--
-- 表的结构 `heart_rate_records`
--

DROP TABLE IF EXISTS `heart_rate_records`;
CREATE TABLE IF NOT EXISTS `heart_rate_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `heart_rate` int DEFAULT NULL,
  `record_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `heart_rate_records`
--

INSERT INTO `heart_rate_records` (`id`, `heart_rate`, `record_time`) VALUES
(1, 99, '2024-03-30 06:24:52');

-- --------------------------------------------------------

--
-- 表的结构 `uploaded_images`
--

DROP TABLE IF EXISTS `uploaded_images`;
CREATE TABLE IF NOT EXISTS `uploaded_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) DEFAULT NULL,
  `upload_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `uploaded_images`
--

INSERT INTO `uploaded_images` (`id`, `image_path`, `upload_time`) VALUES
(1, NULL, '2024-03-30 06:30:31');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
