# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.6.35)
# Database: nerdcms_db
# Generation Time: 2018-10-23 06:26:46 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table addresses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `addresses`;

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `postal_code` varchar(12) NOT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `category_id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` varchar(160) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `keywords` varchar(3000) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `type` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `parent_id` int(30) DEFAULT '0',
  `folder_id` int(10) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(255) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `contact_id` int(255) NOT NULL AUTO_INCREMENT,
  `first_name` varbinary(500) NOT NULL,
  `last_name` varbinary(500) NOT NULL,
  `phone_1` varbinary(500) NOT NULL,
  `phone_2` varbinary(500) NOT NULL,
  `email_1` varbinary(500) NOT NULL,
  `email_2` varbinary(500) NOT NULL,
  `dob` varbinary(500) NOT NULL,
  `street` varbinary(500) NOT NULL,
  `street_num` varbinary(500) NOT NULL,
  `street_num_add` varbinary(500) NOT NULL,
  `zip` varbinary(500) NOT NULL,
  `notes` varbinary(500) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(4) NOT NULL DEFAULT '1',
  `img_path` varbinary(500) NOT NULL,
  `user_id` int(255) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`contact_id`),
  UNIQUE KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address1` varchar(200) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(200) NOT NULL,
  `postal` varchar(12) NOT NULL,
  `country_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table downloads
# ------------------------------------------------------------

DROP TABLE IF EXISTS `downloads`;

CREATE TABLE `downloads` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` varchar(160) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `keywords` varchar(3000) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `author` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `category` varchar(30) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `demo_url` varchar(1000) NOT NULL,
  `down_url` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table files
# ------------------------------------------------------------

DROP TABLE IF EXISTS `files`;

CREATE TABLE `files` (
  `file_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `thumb_name` varchar(100) NOT NULL,
  `folder_id` int(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `secured` tinyint(1) NOT NULL,
  `path` varchar(5000) NOT NULL,
  `thumb_path` varchar(5000) NOT NULL,
  `user_id` int(255) NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table folders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `folders`;

CREATE TABLE `folders` (
  `folder_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `description` varchar(140) NOT NULL,
  `author` varchar(60) NOT NULL,
  `parent_id` int(255) DEFAULT '0',
  `type` varchar(12) NOT NULL,
  `secured` tinyint(1) NOT NULL,
  `path` varchar(1000) NOT NULL,
  `user_id` int(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`folder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) NOT NULL,
  `total` float NOT NULL DEFAULT '0',
  `paid` tinyint(1) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table orders_products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders_products`;

CREATE TABLE `orders_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `page_id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` varchar(160) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `approved` tinyint(10) NOT NULL,
  `date` date NOT NULL,
  `parent_id` int(50) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `path` varchar(300) NOT NULL,
  `user_id` int(255) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table payments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `failed` tinyint(1) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `permission_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `post_id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` varchar(160) DEFAULT NULL,
  `content` varchar(5000) NOT NULL,
  `keywords` varchar(3000) DEFAULT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `category_id` int(50) DEFAULT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(255) NOT NULL,
  `locked_till` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `product_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `category_id` int(50) NOT NULL,
  `price` float NOT NULL,
  `description` varchar(5000) NOT NULL,
  `discount_price` float NOT NULL,
  `savings` float NOT NULL,
  `tax_percentage` tinyint(2) NOT NULL,
  `tax` float NOT NULL,
  `total` float NOT NULL,
  `img_path` varchar(250) NOT NULL,
  `folder_id` int(255) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `trashed` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  `quantity` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table profiles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `profiles`;

CREATE TABLE `profiles` (
  `profile_id` int(30) NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(500) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varbinary(500) NOT NULL,
  `last_name` varbinary(500) NOT NULL,
  `dob` varbinary(500) NOT NULL,
  `email` varbinary(500) NOT NULL,
  `function` varbinary(500) NOT NULL,
  `rights` varchar(500) NOT NULL,
  `token` varchar(100) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table replies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `replies`;

CREATE TABLE `replies` (
  `reply_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` longtext,
  `comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `approved` tinyint(1) NOT NULL DEFAULT '1',
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reply_id`),
  KEY `comment_id` (`comment_id`),
  CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table roles_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles_permissions`;

CREATE TABLE `roles_permissions` (
  `role_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL,
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `roles_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE,
  CONSTRAINT `roles_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table taggables
# ------------------------------------------------------------

DROP TABLE IF EXISTS `taggables`;

CREATE TABLE `taggables` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `taggable_id` int(11) NOT NULL,
  `taggable_type` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(500) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `dob` text NOT NULL,
  `email` text NOT NULL,
  `function` text NOT NULL,
  `img_path` varchar(150) NOT NULL,
  `album_id` int(55) NOT NULL,
  `token` varchar(100) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `customer_id` int(11) DEFAULT '0',
  `protected_key` text NOT NULL,
  `date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_permissions`;

CREATE TABLE `users_permissions` (
  `user_id` int(11) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL,
  KEY `permission_id` (`permission_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `users_permissions_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE,
  CONSTRAINT `users_permissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table users_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_roles`;

CREATE TABLE `users_roles` (
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `users_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `users_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
