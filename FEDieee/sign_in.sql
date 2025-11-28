-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3307
-- 產生時間： 2025-11-25 06:35:00
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
-- 資料表結構 `sign_in`
--

CREATE TABLE `sign_in` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `participant_name` varchar(50) NOT NULL,
  `note` text DEFAULT NULL,
  `sign_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `sign_in`
--

INSERT INTO `sign_in` (`id`, `event_id`, `participant_name`, `note`, `sign_time`) VALUES
(1, 2, '陳思羽', '', '2025-11-18 23:57:17'),
(2, 2, '陳思羽', '', '2025-11-18 23:57:22'),
(3, 4, '羊駝', '愛吃辣', '2025-11-19 00:01:06'),
(4, 1, '羊駝', '我吃素', '2025-11-21 00:12:31'),
(5, 3, '羊駝', '我是羊駝', '2025-11-25 10:28:31'),
(6, 1, '李龍馥', '超級帥', '2025-11-25 13:03:13');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `sign_in`
--
ALTER TABLE `sign_in`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `sign_in`
--
ALTER TABLE `sign_in`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `sign_in`
--
ALTER TABLE `sign_in`
  ADD CONSTRAINT `sign_in_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
