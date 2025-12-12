-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-12-12 21:12:32
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

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
-- 資料表結構 `vending`
--

CREATE TABLE `vending` (
  `postid` int(11) NOT NULL,
  `applicant_name` varchar(45) NOT NULL,
  `request_time` datetime NOT NULL DEFAULT current_timestamp(),
  `location` varchar(255) NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:待處理 1:處理中 2:已完成'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `vending`
--

INSERT INTO `vending` (`postid`, `applicant_name`, `request_time`, `location`, `amount`, `status`) VALUES
(1, 'al', '2025-12-13 01:37:16', '文開二樓', 25, 1),
(2, 'al', '2025-12-13 03:12:19', '文友一樓', 28, 0);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `vending`
--
ALTER TABLE `vending`
  ADD PRIMARY KEY (`postid`),
  ADD KEY `status` (`status`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `vending`
--
ALTER TABLE `vending`
  MODIFY `postid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
