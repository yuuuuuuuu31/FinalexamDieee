-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1:3307
-- 產生時間： 2025-11-25 06:38:23
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
-- 資料表結構 `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `event_name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `event_time` datetime NOT NULL,
  `sign_in_area` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `event`
--

INSERT INTO `event` (`id`, `event_name`, `description`, `event_time`, `sign_in_area`) VALUES
(1, '迎新晚會', '新生迎新活動，包含表演與互動遊戲。', '2025-12-01 18:00:00', '學生會堂'),
(2, '宿舍清潔日', '全體宿舍清潔活動，提供清潔用品與點心。', '2025-12-05 09:00:00', '宿舍大廳'),
(3, '運動會', '年度校園運動會，歡迎同學報名各項比賽。', '2025-12-10 08:30:00', '運動場'),
(4, '社團博覽會', '展示各社團活動與招募新成員。', '2025-12-12 10:00:00', '校園廣場'),
(5, '志工服務日', '參與社區服務與環境清潔，積分認證活動。', '2025-12-15 14:00:00', '校園集合點');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
