-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- 主機: 127.0.0.1
-- 產生時間： 
-- 伺服器版本: 10.1.22-MariaDB
-- PHP 版本： 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `accommodation`
--

-- --------------------------------------------------------

--
-- 資料表結構 `repair`
--

CREATE TABLE `repair` (
  `id` int(11) NOT NULL,
  `applicant_name` varchar(100) NOT NULL,
  `request_time` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `item` varchar(255) NOT NULL,
  `issue_report` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:待處理 1:處理中 2:已完成'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 資料表的匯出資料 `repair`
--

INSERT INTO `repair` (`id`, `applicant_name`, `request_time`, `location`, `item`, `issue_report`, `status`) VALUES
(1, '王小明', '2025-11-18 21:14:00', '台北市北投區', '冷氣', '冷氣不冷，請檢修', 0);

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `repair`
--
ALTER TABLE `repair`
  ADD PRIMARY KEY (`id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `repair`
--
ALTER TABLE `repair`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
