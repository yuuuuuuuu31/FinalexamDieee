-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3307
-- 產生時間： 2025-11-18 16:05:59
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `issue_report` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `repair`
--

INSERT INTO `repair` (`id`, `applicant_name`, `request_time`, `location`, `item`, `issue_report`) VALUES
(1, '王小明', '2025-11-18 21:14:00', '台北市北投區', '冷氣', '冷氣不冷，請檢修');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `repair`
--
ALTER TABLE `repair`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `repair`
--
ALTER TABLE `repair`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
