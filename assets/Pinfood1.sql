-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 19, 2025 at 03:23 AM
-- Server version: 5.7.39
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Pinfood1`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `commentId` int(11) NOT NULL,
  `content` varchar(500) NOT NULL,
  `Userid` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `postid` int(11) DEFAULT NULL,
  `recipeid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`commentId`, `content`, `Userid`, `timestamp`, `postid`, `recipeid`) VALUES
(4, 'ssss', 1, '2025-03-31 20:13:01', 4, NULL),
(16, 'asd', 1, '2025-04-13 18:36:53', 4, NULL),
(17, 'dddss', 1, '2025-04-13 18:39:08', 4, NULL),
(29, 'ddd', 1, '2025-04-15 12:50:46', 3, NULL),
(30, 'ddd', 1, '2025-04-15 12:50:48', 3, NULL),
(31, 'ddd', 1, '2025-04-15 12:50:49', 3, NULL),
(32, 'ddfd', 1, '2025-04-15 12:50:52', 3, NULL),
(33, 'eee', 1, '2025-04-15 12:50:53', 3, NULL),
(34, 'ee', 1, '2025-04-15 12:50:56', 3, NULL),
(37, 'this isnt food \r\n', 1, '2025-04-16 09:55:58', 7, NULL),
(38, 'lfff\r\n', 1, '2025-04-16 21:51:08', 7, NULL),
(39, 'dddd', 1, '2025-04-17 21:05:27', NULL, NULL),
(40, 'sss', 1, '2025-04-17 21:05:41', 5, NULL),
(41, 'sss', 1, '2025-04-17 21:05:48', NULL, NULL),
(43, 'fff', 2, '2025-04-17 23:19:59', 5, NULL),
(44, 'fffff', 2, '2025-04-17 23:20:02', 5, NULL),
(45, 'lolllll\r\n\\\r\n', 2, '2025-04-17 23:20:41', 7, NULL),
(46, 'this isnt food itc called pinfod\r\n', 1, '2025-04-17 23:21:55', 9, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `followid` int(11) NOT NULL,
  `Userid` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `FollowedUserid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `follow`
--

INSERT INTO `follow` (`followid`, `Userid`, `timestamp`, `FollowedUserid`) VALUES
(55, 2, '2025-04-18 18:01:33', 1),
(59, 1, '2025-04-18 21:40:31', 2);

-- --------------------------------------------------------

--
-- Table structure for table `like`
--

CREATE TABLE `like` (
  `LikeId` int(11) NOT NULL,
  `Userid` int(11) NOT NULL,
  `postid` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `recipeId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `like`
--

INSERT INTO `like` (`LikeId`, `Userid`, `postid`, `username`, `recipeId`) VALUES
(45, 1, 3, 'kimson030', NULL),
(47, 1, 4, 'kimson030', NULL),
(51, 1, 7, 'kimson030', NULL),
(52, 1, 5, 'kimson030', NULL),
(62, 2, 7, 'Mariah2034', NULL),
(63, 2, NULL, 'Mariah2034', 1),
(64, 2, 8, 'Mariah2034', NULL),
(65, 2, 9, 'Mariah2034', NULL),
(66, 1, 6, 'kimson030', NULL),
(67, 1, 1, 'kimson030', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `timestamp`, `sent_at`) VALUES
(1, 1, 2, 'ffff', '2025-04-18 21:03:40', '2025-04-18 21:03:40'),
(2, 1, 2, 'ffff', '2025-04-18 21:05:06', '2025-04-18 21:05:06'),
(3, 1, 2, 'ffff', '2025-04-18 21:05:58', '2025-04-18 21:05:58'),
(4, 1, 2, 'ffff', '2025-04-18 21:07:08', '2025-04-18 21:07:08'),
(5, 2, 1, 'lol i can text hahaha\r\n', '2025-04-18 21:09:40', '2025-04-18 21:09:40'),
(6, 1, 2, 'ffff', '2025-04-18 21:10:14', '2025-04-18 21:10:14'),
(7, 1, 2, 'yes u can\r\n', '2025-04-18 21:10:19', '2025-04-18 21:10:19'),
(8, 2, 1, 'lol i can text hahaha\r\n', '2025-04-18 21:10:28', '2025-04-18 21:10:28'),
(9, 1, 2, 'yes u can\r\n', '2025-04-18 21:12:15', '2025-04-18 21:12:15'),
(10, 1, 2, 'sss', '2025-04-18 21:12:18', '2025-04-18 21:12:18'),
(11, 2, 1, 'lol i can text hahaha\r\n', '2025-04-18 21:12:26', '2025-04-18 21:12:26'),
(12, 2, 1, 'sss', '2025-04-18 21:12:31', '2025-04-18 21:12:31'),
(13, 1, 2, 'sss', '2025-04-18 21:12:49', '2025-04-18 21:12:49'),
(14, 2, 1, 'sssrt', '2025-04-18 21:12:57', '2025-04-18 21:12:57'),
(15, 1, 2, 'dog', '2025-04-18 21:13:08', '2025-04-18 21:13:08'),
(16, 1, 2, 'ss', '2025-04-18 21:17:21', '2025-04-18 21:17:21'),
(17, 2, 1, 'hate php', '2025-04-18 21:51:35', '2025-04-18 21:51:35'),
(18, 2, 1, 'hate php', '2025-04-18 21:51:40', '2025-04-18 21:51:40'),
(19, 2, 1, 'hate php', '2025-04-18 21:57:53', '2025-04-18 21:57:53'),
(20, 2, 1, 'helllooo\r\n', '2025-04-18 22:03:09', '2025-04-18 22:03:09'),
(21, 1, 2, 'lll\r\n', '2025-04-18 22:05:30', '2025-04-18 22:05:30'),
(22, 2, 1, 'ff', '2025-04-18 22:09:13', '2025-04-18 22:09:13'),
(23, 1, 2, 'ddd\r\n', '2025-04-18 22:10:09', '2025-04-18 22:10:09'),
(24, 1, 2, 'ddd\r\n', '2025-04-18 22:13:45', '2025-04-18 22:13:45'),
(25, 1, 2, 'cdddecw', '2025-04-18 22:13:49', '2025-04-18 22:13:49'),
(26, 1, 2, 'l\r\n', '2025-04-18 22:14:14', '2025-04-18 22:14:14'),
(27, 2, 1, '[l\r\n', '2025-04-18 22:14:42', '2025-04-18 22:14:42'),
(28, 2, 1, '\r\n\r\nll', '2025-04-18 22:15:02', '2025-04-18 22:15:02'),
(29, 2, 1, 'xds', '2025-04-18 22:16:24', '2025-04-18 22:16:24'),
(30, 1, 2, 'deedcdecde', '2025-04-18 22:29:06', '2025-04-18 22:29:06'),
(31, 1, 2, 'll\r\n', '2025-04-18 22:30:57', '2025-04-18 22:30:57'),
(32, 1, 2, 'de', '2025-04-18 22:31:49', '2025-04-18 22:31:49'),
(33, 1, 2, 'dccdsc', '2025-04-18 22:33:09', '2025-04-18 22:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `message_id`, `user_id`, `status`, `timestamp`) VALUES
(1, 21, 2, 'read', '2025-04-18 22:05:30'),
(2, 22, 1, 'read', '2025-04-18 22:09:13'),
(3, 23, 2, 'read', '2025-04-18 22:10:09'),
(4, 29, 1, 'read', '2025-04-18 22:16:24'),
(5, 30, 2, 'read', '2025-04-18 22:29:06'),
(6, 31, 2, 'read', '2025-04-18 22:30:57'),
(7, 32, 2, 'read', '2025-04-18 22:31:49'),
(8, 33, 2, 'read', '2025-04-18 22:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `postId` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(500) NOT NULL,
  `Userid` int(11) NOT NULL,
  `Typeoffood` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`postId`, `title`, `description`, `image`, `Userid`, `Typeoffood`) VALUES
(1, 'lee', 'lmeed\r\n', 'uploads/ButterChicken.jpg', 1, 'kkk'),
(3, 'f', 'f', 'uploads/2.png', 1, 'f'),
(4, '.', 'll', 'uploads/ButterChicken.jpg', 1, 'l'),
(5, 'pmg', 'fff', 'uploads/ButterChicken.jpg', 1, 'food'),
(6, 'omfe', 'eee', 'uploads/ButterChicken.jpg', 1, 'food'),
(7, 'd', 'ddd', 'uploads/Screenshot 2025-04-16 at 08.58.13.png', 1, 'Worldwide'),
(8, 'h', 'h', 'uploads/1744833726_Screenshot 2025-04-16 at 08.58.13.png', 1, 'h'),
(9, 'rffe', 'freawcdcdcd', 'uploads/Screenshot 2025-03-21 at 09.04.55.png', 2, 'not good');

-- --------------------------------------------------------

--
-- Table structure for table `postrecipe`
--

CREATE TABLE `postrecipe` (
  `recipeId` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `ingredients` varchar(500) NOT NULL,
  `Userid` int(11) NOT NULL,
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `postrecipe`
--

INSERT INTO `postrecipe` (`recipeId`, `title`, `description`, `ingredients`, `Userid`, `image`) VALUES
(1, 'dd', 'ddd', 'techno', 1, 'uploads/Screenshot 2025-04-16 at 08.58.13.png'),
(6, 'korean', 'Omg', 'CORN', 2, 'uploads/Screenshot 2025-04-18 at 00.11.21.png');

-- --------------------------------------------------------

--
-- Table structure for table `post_ratings`
--

CREATE TABLE `post_ratings` (
  `rateid` int(11) NOT NULL,
  `postid` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ratings`
--

CREATE TABLE `recipe_ratings` (
  `rating_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `rated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recipe_ratings`
--

INSERT INTO `recipe_ratings` (`rating_id`, `user_id`, `recipe_id`, `rating`, `rated_at`) VALUES
(1, 1, 6, 2, '2025-04-19 01:31:15'),
(2, 1, 1, 3, '2025-04-18 22:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `save`
--

CREATE TABLE `save` (
  `saveid` int(11) NOT NULL,
  `Userid` int(11) NOT NULL,
  `postid` int(11) DEFAULT NULL,
  `recipeid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `save`
--

INSERT INTO `save` (`saveid`, `Userid`, `postid`, `recipeid`) VALUES
(26, 1, 1, NULL),
(30, 2, 1, NULL),
(31, 2, 9, NULL),
(32, 2, NULL, 6),
(33, 2, NULL, 1),
(34, 2, 8, NULL),
(35, 1, 6, NULL),
(37, 1, 9, NULL),
(38, 1, NULL, 6);

-- --------------------------------------------------------

--
-- Table structure for table `search`
--

CREATE TABLE `search` (
  `searchid` int(11) NOT NULL,
  `Userid` int(11) NOT NULL,
  `searchbytitle` varchar(500) NOT NULL,
  `recipeid` int(11) NOT NULL,
  `postid` int(11) NOT NULL,
  `image` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `share`
--

CREATE TABLE `share` (
  `shareid` int(11) NOT NULL,
  `postid` int(11) DEFAULT NULL,
  `reipeid` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `linkurl` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `about` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `username`, `email`, `password`, `profile_picture`, `about`) VALUES
(1, 'kimson030', 'Mariahbugeja82@gmail.com', 'Hiccup0304', '67eb0af3eb490.jpg', 'd'),
(2, 'Mariah2034', 'mariah.bugeja.e23564@mcast.edu.mt', 'Hiccup0304', NULL, ''),
(3, 'Mariah0302', 'Mariahbugeja12@gmail.com', 'Mariah2003', NULL, ''),
(4, 'kimson030333', 'Mariahbugeja112@gmail.com', 'Mariah123', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_ratings`
--

CREATE TABLE `user_ratings` (
  `rating_id` int(11) NOT NULL,
  `rater_userid` int(11) NOT NULL,
  `rated_userid` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_ratings`
--

INSERT INTO `user_ratings` (`rating_id`, `rater_userid`, `rated_userid`, `rating`, `created_at`) VALUES
(1, 1, 2, 3, '2025-04-18 23:49:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `postid` (`postid`),
  ADD KEY `recipeid` (`recipeid`),
  ADD KEY `userid` (`Userid`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`followid`),
  ADD KEY `User_id` (`Userid`);

--
-- Indexes for table `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`LikeId`),
  ADD KEY `postid` (`postid`),
  ADD KEY `User_id` (`Userid`),
  ADD KEY `recipeId` (`recipeId`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_message_id` (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`postId`),
  ADD KEY `User_Id` (`Userid`);

--
-- Indexes for table `postrecipe`
--
ALTER TABLE `postrecipe`
  ADD PRIMARY KEY (`recipeId`),
  ADD KEY `Userid` (`Userid`);

--
-- Indexes for table `post_ratings`
--
ALTER TABLE `post_ratings`
  ADD PRIMARY KEY (`rateid`),
  ADD KEY `postid` (`postid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `recipe_ratings`
--
ALTER TABLE `recipe_ratings`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `save`
--
ALTER TABLE `save`
  ADD PRIMARY KEY (`saveid`),
  ADD KEY `postid` (`postid`),
  ADD KEY `recipeid` (`recipeid`),
  ADD KEY `userid` (`Userid`);

--
-- Indexes for table `search`
--
ALTER TABLE `search`
  ADD PRIMARY KEY (`searchid`),
  ADD KEY `search_ibfk_1` (`recipeid`),
  ADD KEY `search_ibfk_2` (`postid`),
  ADD KEY `search_ibfk_3` (`Userid`);

--
-- Indexes for table `share`
--
ALTER TABLE `share`
  ADD PRIMARY KEY (`shareid`),
  ADD KEY `reipeid` (`reipeid`),
  ADD KEY `postid` (`postid`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `user_ratings`
--
ALTER TABLE `user_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD UNIQUE KEY `rater_userid` (`rater_userid`,`rated_userid`),
  ADD KEY `rated_userid` (`rated_userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `followid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `like`
--
ALTER TABLE `like`
  MODIFY `LikeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `postId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `postrecipe`
--
ALTER TABLE `postrecipe`
  MODIFY `recipeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `post_ratings`
--
ALTER TABLE `post_ratings`
  MODIFY `rateid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipe_ratings`
--
ALTER TABLE `recipe_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `save`
--
ALTER TABLE `save`
  MODIFY `saveid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `search`
--
ALTER TABLE `search`
  MODIFY `searchid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `share`
--
ALTER TABLE `share`
  MODIFY `shareid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_ratings`
--
ALTER TABLE `user_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `post` (`postId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`recipeid`) REFERENCES `postrecipe` (`recipeId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`Userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `follow_ibfk_1` FOREIGN KEY (`Userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `post` (`postId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`Userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `like_ibfk_3` FOREIGN KEY (`recipeId`) REFERENCES `postrecipe` (`recipeId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `user` (`userid`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`userid`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_message_id` FOREIGN KEY (`message_id`) REFERENCES `messages` (`message_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`userid`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `User_Id` FOREIGN KEY (`Userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `postrecipe`
--
ALTER TABLE `postrecipe`
  ADD CONSTRAINT `postrecipe_ibfk_1` FOREIGN KEY (`Userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_ratings`
--
ALTER TABLE `post_ratings`
  ADD CONSTRAINT `post_ratings_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `post` (`postId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_ratings_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `save`
--
ALTER TABLE `save`
  ADD CONSTRAINT `save_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `post` (`postId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `save_ibfk_2` FOREIGN KEY (`recipeid`) REFERENCES `postrecipe` (`recipeId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `save_ibfk_3` FOREIGN KEY (`Userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `search`
--
ALTER TABLE `search`
  ADD CONSTRAINT `search_ibfk_1` FOREIGN KEY (`recipeid`) REFERENCES `postrecipe` (`recipeId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `search_ibfk_2` FOREIGN KEY (`postid`) REFERENCES `post` (`postId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `search_ibfk_3` FOREIGN KEY (`Userid`) REFERENCES `user` (`userid`) ON UPDATE CASCADE;

--
-- Constraints for table `share`
--
ALTER TABLE `share`
  ADD CONSTRAINT `share_ibfk_1` FOREIGN KEY (`reipeid`) REFERENCES `postrecipe` (`recipeId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `share_ibfk_2` FOREIGN KEY (`postid`) REFERENCES `post` (`postId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `share_ibfk_3` FOREIGN KEY (`userId`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_ratings`
--
ALTER TABLE `user_ratings`
  ADD CONSTRAINT `user_ratings_ibfk_1` FOREIGN KEY (`rater_userid`) REFERENCES `user` (`userid`),
  ADD CONSTRAINT `user_ratings_ibfk_2` FOREIGN KEY (`rated_userid`) REFERENCES `user` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
