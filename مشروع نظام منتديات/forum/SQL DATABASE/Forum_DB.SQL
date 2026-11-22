SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `forum_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `forum_db`;

CREATE TABLE `forums` (
  `forum_id` int(11) NOT NULL,
  `forum_title` text NOT NULL,
  `forum_desc` text NOT NULL,
  `forum_active` int(11) NOT NULL,
  `forum_order` int(11) NOT NULL,
  `forum_is` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `replys` (
  `reply_id` int(11) NOT NULL,
  `therad_id` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `reply_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `therads` (
  `therad_id` int(11) NOT NULL,
  `therad_title` text NOT NULL,
  `therad_text` text NOT NULL,
  `forum_id` int(11) NOT NULL,
  `therad_veiws` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `forums`
  ADD PRIMARY KEY (`forum_id`);

ALTER TABLE `replys`
  ADD PRIMARY KEY (`reply_id`);

ALTER TABLE `therads`
  ADD PRIMARY KEY (`therad_id`);

ALTER TABLE `forums`
  MODIFY `forum_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `replys`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `therads`
  MODIFY `therad_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
