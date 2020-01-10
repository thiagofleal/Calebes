CREATE DATABASE `caleb_mission`;
USE `caleb_mission`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `ask` (
  `id` int(11) NOT NULL,
  `search` int(11) NOT NULL,
  `question_number` int(11) NOT NULL,
  `option_number` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `time` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `leader` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `document` varchar(20) NOT NULL,
  `document_type` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `birth` date DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` char(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` char(32) NOT NULL,
  `point` int(11) DEFAULT NULL,
  `register` datetime DEFAULT CURRENT_TIMESTAMP,
  `tshirt_size` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `option` (
  `search` int(11) NOT NULL,
  `question_number` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `text` varchar(50) DEFAULT NULL,
  `insert` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `point` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `question` (
  `search` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `text` varchar(100) NOT NULL,
  `title` varchar(20) DEFAULT NULL,
  `creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `type` int(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `search` (
  `id` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `token` char(32) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `ask`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search` (`search`,`question_number`,`option_number`),
  ADD KEY `user` (`user`);

ALTER TABLE `leader`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point` (`point`);

ALTER TABLE `option`
  ADD PRIMARY KEY (`search`,`question_number`,`number`);

ALTER TABLE `point`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `question`
  ADD PRIMARY KEY (`search`,`number`);

ALTER TABLE `search`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point` (`point`);

ALTER TABLE `ask`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ask`
  ADD CONSTRAINT `ask_ibfk_1` FOREIGN KEY (`search`,`question_number`,`option_number`) REFERENCES `option` (`search`, `question_number`, `number`),
  ADD CONSTRAINT `ask_ibfk_2` FOREIGN KEY (`user`) REFERENCES `member` (`id`);

ALTER TABLE `leader`
  ADD CONSTRAINT `leader_ibfk_1` FOREIGN KEY (`id`) REFERENCES `member` (`id`);

ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`point`) REFERENCES `point` (`id`);

ALTER TABLE `option`
  ADD CONSTRAINT `option_ibfk_1` FOREIGN KEY (`search`,`question_number`) REFERENCES `question` (`search`, `number`);

ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`search`) REFERENCES `search` (`id`);

ALTER TABLE `search`
  ADD CONSTRAINT `search_ibfk_1` FOREIGN KEY (`point`) REFERENCES `point` (`id`);
COMMIT;