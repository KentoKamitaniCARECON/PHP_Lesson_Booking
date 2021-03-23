-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:3306
-- 生成日時: 2021 年 3 月 20 日 16:53
-- サーバのバージョン： 5.7.30
-- PHP のバージョン: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- データベース: `rsv_app`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL COMMENT 'コメント番号',
  `pen_name` varchar(15) NOT NULL COMMENT 'ペンネーム',
  `comment` text NOT NULL COMMENT 'コメント',
  `menbers_id` int(3) NOT NULL COMMENT '会員番号',
  `created` date DEFAULT NULL COMMENT '作成'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `comments`
--

INSERT INTO `comments` (`id`, `pen_name`, `comment`, `menbers_id`, `created`) VALUES
(198, '鬼退治', 'いざ鬼退治', 81, '2021-03-19'),
(199, '村人', '桃太郎こそ悪者', 82, '2021-03-19');

-- --------------------------------------------------------

--
-- テーブルの構造 `informations`
--

CREATE TABLE `informations` (
  `id` int(11) NOT NULL COMMENT 'お知らせ番号',
  `title` varchar(15) NOT NULL COMMENT '題名',
  `info` text NOT NULL COMMENT 'お知らせ',
  `created` date NOT NULL COMMENT '作成'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `informations`
--

INSERT INTO `informations` (`id`, `title`, `info`, `created`) VALUES
(19, '会員対象', '6月6日よりコロナによる臨時休業とさせて頂きます。', '2021-03-19'),
(20, '会員対象', '感染対策を徹底しています。', '2021-03-19');

-- --------------------------------------------------------

--
-- テーブルの構造 `like_comments`
--

CREATE TABLE `like_comments` (
  `id` int(11) NOT NULL,
  `menbers_id` int(3) NOT NULL COMMENT '会員番号',
  `comments_id` int(3) NOT NULL COMMENT 'コメント番号',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `like_comments`
--

INSERT INTO `like_comments` (`id`, `menbers_id`, `comments_id`, `created`) VALUES
(3056, 74, 198, '2021-03-19 15:13:04'),
(3057, 76, 198, '2021-03-19 15:14:55'),
(3058, 82, 199, '2021-03-19 15:17:04'),
(3059, 83, 198, '2021-03-19 15:20:54'),
(3060, 83, 199, '2021-03-19 15:20:57'),
(3061, 84, 198, '2021-03-19 15:23:39');

-- --------------------------------------------------------

--
-- テーブルの構造 `menbers`
--

CREATE TABLE `menbers` (
  `id` int(11) NOT NULL COMMENT '会員番号',
  `name` varchar(32) NOT NULL COMMENT '氏名',
  `address` varchar(128) NOT NULL COMMENT '住所',
  `tel` varchar(20) NOT NULL COMMENT '電話',
  `birth` date NOT NULL COMMENT '生年月日',
  `email` varchar(128) NOT NULL COMMENT 'メールアドレス',
  `password` varchar(255) NOT NULL COMMENT 'パスワード',
  `role` int(11) NOT NULL COMMENT '2：一般ユーザ/1:管理者/3:不良客',
  `other` text COMMENT '備考',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成',
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `menbers`
--

INSERT INTO `menbers` (`id`, `name`, `address`, `tel`, `birth`, `email`, `password`, `role`, `other`, `created`, `updated`) VALUES
(1, '管理者', '東京都新宿区新宿管理者1-1-1', '09012345678', '2000-01-01', 'kanrisha@gmail.com', '$2y$10$.J8ZHIRAcqgz27OnBnMeVun/p4WSL1tVwb8xGO.bT3NSm7Hk6P1LK', 1, '管理者', '2021-01-28 18:49:50', '2021-03-19 14:58:09'),
(74, '犬', '東京都新宿区新宿犬小屋1-1-1', '09012345678', '2000-01-01', 'inu@gmail.com', '$2y$10$LaeewUlj8aYnVBVTHq9T/etFJirlKlh3PJkVsmtbjguwI/ST4di/K', 2, '', '2021-03-11 00:37:55', '2021-03-19 15:12:30'),
(76, '猿', '東京都新宿区新宿猿山1-1-1', '09012345678', '2000-01-01', 'saru@gmail.com', '$2y$10$UfWN.sv2FxIm8liSCBK1u..tqrZ.UgqqdyAml/J2YMIjy2JADj0i6', 2, '', '2021-03-11 00:41:53', '2021-03-19 15:14:22'),
(80, 'きじ', '東京都新宿区新宿裏山1-1-1', '09090909090', '2000-01-01', 'kiji@gmail.com', '$2y$10$.L.ekK0G7ZE.aPqPnbZxp.sm7T8Lt36mNunuPDE5L/i.1ltD4JIwW', 2, '', '2021-03-16 03:40:23', '2021-03-19 15:19:51'),
(81, '桃太郎', '東京都新宿区新宿ふるさと1-1-1', '09090909090', '2000-01-01', 'momotaro@gmail.com', '$2y$10$pEPNc7tDnjkBMK4wyV/qB..k5jM1QuAXzdKJzHAS8or8UR8JTzjc.', 2, '', '2021-03-16 03:56:09', '2021-03-19 15:10:15'),
(82, '鬼', '東京都新宿区新宿鬼ヶ島1-1-1', '09090909090', '2000-01-01', 'oni@gmail.com', '$2y$10$/qwbGKo0sRLWjSsvb8HKceO.UeRfG7Jdf0qFJX2Xwdj0tvEv007v.', 3, '支払い不良の可能性あり', '2021-03-17 01:35:32', '2021-03-19 15:25:11'),
(83, '村人A', '東京都新宿区新宿ふるさと1-1-1', '09090909090', '2000-01-01', 'murabitoa@gmail.com', '$2y$10$DZPx4b0cH5v1fZ43a275MODgxYqCArWHg/udgunqfqsdcIcZ7Wlgu', 2, '', '2021-03-17 01:37:08', '2021-03-19 15:20:49'),
(84, '村人B', '東京都新宿区新宿ふるさと1-1-1', '09090909090', '2000-01-01', 'murabitob@gmail.com', '$2y$10$LdfEIHAZ1F44.q8Ky4MhTuty3.S3HKtI8Jo4qCekysHwEW6aNdMlq', 2, '', '2021-03-17 01:37:49', '2021-03-19 15:22:19');

-- --------------------------------------------------------

--
-- テーブルの構造 `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL COMMENT '予約番号',
  `check_in` date NOT NULL COMMENT '到着日',
  `check_out` date NOT NULL COMMENT '出発日',
  `requests` text COMMENT '備考欄',
  `rooms_id` int(3) NOT NULL COMMENT '部屋番号',
  `menbers_id` int(3) NOT NULL COMMENT '会員番号',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成',
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `reservations`
--

INSERT INTO `reservations` (`id`, `check_in`, `check_out`, `requests`, `rooms_id`, `menbers_id`, `created`, `updated`) VALUES
(152, '2021-05-05', '2021-05-08', '', 1, 1, '2021-03-20 00:08:56', '2021-03-20 00:08:56'),
(153, '2021-05-06', '2021-05-07', '鬼退治の為の宿泊', 2, 81, '2021-03-20 00:11:05', '2021-03-20 00:11:05'),
(154, '2021-05-05', '2021-05-06', '', 2, 74, '2021-03-20 00:12:54', '2021-03-20 00:12:54'),
(155, '2021-05-05', '2021-05-07', '', 4, 76, '2021-03-20 00:14:49', '2021-03-20 00:14:49'),
(156, '2021-05-05', '2021-05-07', '', 3, 83, '2021-03-20 00:21:28', '2021-03-20 00:21:28'),
(157, '2021-05-05', '2021-05-07', '還暦のお祝いでの宿泊です。', 5, 84, '2021-03-20 00:23:17', '2021-03-20 00:23:17');

-- --------------------------------------------------------

--
-- テーブルの構造 `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL COMMENT '部屋番号',
  `room` varchar(16) NOT NULL COMMENT '部屋の名前',
  `beds` varchar(16) NOT NULL COMMENT 'ベッドタイプ',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成',
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `rooms`
--

INSERT INTO `rooms` (`id`, `room`, `beds`, `created`, `updated`) VALUES
(1, '橘', 'ツインベッド', '2021-02-04 13:48:13', '2021-02-04 13:48:13'),
(2, '松', 'ツインベッド', '2021-02-04 13:48:37', '2021-02-04 13:48:37'),
(3, '梅', 'トリプルベッド', '2021-02-04 13:49:10', '2021-02-04 13:49:10'),
(4, '桜', 'トリプルベッド', '2021-02-04 13:49:31', '2021-02-04 13:49:31'),
(5, '楓', 'トリプルベッド', '2021-02-04 13:49:31', '2021-02-04 13:49:31');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `informations`
--
ALTER TABLE `informations`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `like_comments`
--
ALTER TABLE `like_comments`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `menbers`
--
ALTER TABLE `menbers`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'コメント番号', AUTO_INCREMENT=200;

--
-- テーブルのAUTO_INCREMENT `informations`
--
ALTER TABLE `informations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'お知らせ番号', AUTO_INCREMENT=21;

--
-- テーブルのAUTO_INCREMENT `like_comments`
--
ALTER TABLE `like_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3062;

--
-- テーブルのAUTO_INCREMENT `menbers`
--
ALTER TABLE `menbers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会員番号', AUTO_INCREMENT=88;

--
-- テーブルのAUTO_INCREMENT `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '予約番号', AUTO_INCREMENT=158;

--
-- テーブルのAUTO_INCREMENT `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '部屋番号', AUTO_INCREMENT=6;
