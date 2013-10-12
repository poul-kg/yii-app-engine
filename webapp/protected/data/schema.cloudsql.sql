/*
SQLyog Enterprise v10.42 
MySQL - 5.1.43-community : Database - yii-appengine
*********************************************************************
*/

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT 'User ID',
  `google_id` varchar(255) NOT NULL COMMENT 'Google ID',
  `first_name` varchar(50) DEFAULT NULL COMMENT 'First Name',
  `last_name` varchar(50) DEFAULT NULL COMMENT 'Last Name',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Last login date & time',
  `email` varchar(50) NOT NULL COMMENT 'Email',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;