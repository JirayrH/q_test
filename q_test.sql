CREATE DATABASE `q_test` CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE `q_test`;

DROP TABLE IF EXISTS `answers`;

CREATE TABLE `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(25) CHARACTER SET latin1 NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` varchar(255) CHARACTER SET utf8 NOT NULL,
  `correct` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;