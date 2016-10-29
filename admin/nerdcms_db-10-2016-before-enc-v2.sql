-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Machine: 127.0.0.1
-- Gegenereerd op: 29 okt 2016 om 14:34
-- Serverversie: 5.6.17
-- PHP-versie: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `nerdcms_db`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `album_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `description` varchar(140) NOT NULL,
  `author` varchar(60) NOT NULL,
  `parent_id` int(255) DEFAULT '0',
  `type` varchar(12) NOT NULL,
  `secured` tinyint(1) NOT NULL,
  `path` varchar(1000) NOT NULL,
  `user_id` int(255) NOT NULL,
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Gegevens worden geëxporteerd voor tabel `albums`
--

INSERT INTO `albums` (`album_id`, `name`, `description`, `author`, `parent_id`, `type`, `secured`, `path`, `user_id`) VALUES
(3, 'Contacts', '', 'admin', 0, '', 0, 'contacts', 26),
(5, 'Users', '', 'admin', 0, '', 0, 'users', 26),
(8, 'Products', '', 'admin', 0, '', 0, 'products', 26);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `categorie_id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` varchar(160) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `keywords` varchar(3000) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `author` varchar(30) NOT NULL,
  `type` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `parent_id` int(255) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`categorie_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

--
-- Gegevens worden geëxporteerd voor tabel `categories`
--

INSERT INTO `categories` (`categorie_id`, `title`, `description`, `content`, `keywords`, `approved`, `author`, `type`, `date`, `parent_id`, `trashed`) VALUES
(9, 'Dieren', '', '', '', 0, 'admin', 'product', '0000-00-00', 0, 0),
(10, 'Mensen', 'mensen', '', '', 0, 'Jorn', '', '0000-00-00', 0, 0),
(54, 'Degoe', '', '', '', 0, '', '', '0000-00-00', 0, 0),
(62, 'shampoo', '', '', '', 0, '', '', '0000-00-00', 0, 0),
(63, 'Porn', '', '', '', 0, '', '', '0000-00-00', 0, 0),
(64, 'jorn', '', '', '', 0, 'admin', 'post', '0000-00-00', 0, 0),
(65, 'psr4', '', '', '', 0, 'admin', 'post', '0000-00-00', 0, 0),
(66, 'xxx', '', '', '', 0, 'admin', 'post', '0000-00-00', 0, 0),
(67, 'jantje', '', '', '', 0, 'admin', 'post', '0000-00-00', 0, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
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
  PRIMARY KEY (`contact_id`),
  UNIQUE KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Gegevens worden geëxporteerd voor tabel `contacts`
--

INSERT INTO `contacts` (`contact_id`, `first_name`, `last_name`, `phone_1`, `phone_2`, `email_1`, `email_2`, `dob`, `street`, `street_num`, `street_num_add`, `zip`, `notes`, `trashed`, `approved`, `img_path`, `user_id`) VALUES
(18, 'a0891e6cca6cb11e8f214ec74eebdc455e08bd5d2ec15f93fa20f74a57f5d229da26ca2e21be31d8c382e37d59da07a2c1e7fded7f9b80551c3cab289b7beb2d', 'c3da897a0f22ac4e478207506a212f2c390b8677c83831f927da825359a915fa19406b900c76861b52a4d9dad94baf2506479bc723023b08a5938bc0b79afe6b', 'a8134c868f35c9c565b96af242cc80311f036163fa25bbe01aea6e8486aab97204af8727b1a5b633287e15b26a7c342e44a3cf1df60a5493510a237e0050f852', 'e3b2f3b7fba2755a151890e0bf830647b09ed0177cef180c9a239a3ee66ef02fb0d9db316b8f32d5d0a09d1e8c2a7098e34d5744d486c0a3ac2712bbffcd8089', '17d2cc289b81eb2473830516d6ca836e86d77928fcf7387401a1c4c29fb9f868889f9d9df494a959d50981e4a1567414cc3a6a7160b0585d9acbe3dec11cd0fa', 'a74ecc6215b062dd03bbece1ed720bea69fa7ab4fa3af9333495c9fe4c30b3e22124d060d7a05b4328ba8559d07977120094e35fdb38a66257e496f358fe220d', '72ee8d00c94ba8e3f629dd5111afb01e87fffad4bc70622fa3fca451137bbc9aa2fcd8fd7648a58a84b0e3ad0bb3d57f22812868283d37871a2dc95dee25da68', '2097f7e6e75e5a1f7cbd37d6edcd21918b26a6cab40145f5332a9af2437864c71d249b278496aa5f4f14a2041ff097930547abf0a18e07d7687609de3a3b5c9a', 'b66519b84768cffc0467b0235642d5602c71e527f16bf1d2d6c15c3fc1614276da2ef1514e0723afeea39cad77be7269406fd45fc3991303ec3048541af27b46', '3a7b0a2480b5b5122b7cd17221ea971c701a9cfc28be6c5734e91d28778babc609a9b0f6c929d2970605c85997c5a421f9e6fd56d7f39bf758492c247d146655', 'a5d5dd9d2a3307cf30ff7cb5fc179be543a8b0da9cf2e893fff4ff5f95a23d8ce3f7c19fa29b7865e984849a1f4875ca5a9cb3684020a503b55321dcb3a8e820', '08b73953d687d274099366791dbd53f2d76668adb94e824666cca39a10c7b55eff516b341d657a506b686594af277dfd492df8aabc693936ad3b05e5604f4a89', 0, 1, 'files/thumbs/contacts/dead.png.thumb_564edda83a0776.80950415.png', 22),
(19, '267a9ec90516fdc0e0a7eb34aa9189f545c46cee8e5615185849a44cdc4fc5e4012548a846adfe65b86c65203cddcd66640cf093139e96758d306c90253f6be5', '8b506b23b40f0da250968a4cb1225f749d819b5169b8d3b37336f9f81661684be4e3c0cd5f051996c9115c903b7719cd3357c2ef0502342126d55c02543b60ae', '40cf5ad7488f2dbc9094e7f149afe6e337755299abeb02d3881c429ac0e83b774fbce47bcc6630386babb75a92c96a9b9ccd91465f6452fb327db72de024bfc3', '2659984c702ce3c695a961bde80cc402d174c1c9825ad9d0e50ccd02487c214555fb0607e55e12f75bd7d5e7923c91ff09b42fb2523e4a9e82684fa69e0c2b86', '86313ab51c9a24627673fc949886c81966b1ee513aa710f855c5ea7cc7f4587de8aee649b39050a7b7b3051a2863053bf76f2ff7318a07faec4d20072be0e9b4', '8c47f139fa38ffdcb7ac6a0a123352c1eb3daecdfba2f398b7e08dd17fe47a83efc5503b39c5bf52fdea0862cba68e56ab156dd645db0b11facb52cbeb713bfe', 'bd06fe4fd38159e4ebd5efea0676d7645d2524e75fb76fecc9f53b046133e80075984e0c520786fc17010fa6dcf2b2d1efb71c60460aa711ef94fca3b6f624d5', 'c53a56fefafb6c9b75d3e31681b159cc757b443d588e67719ef7d8c1359e15ddde38712090b07230fe1274f521e70509d59c54523c76fc61478dab9f7e3ddf9e', '453fbf79c17f5aec069e11060a80130581c59a99fa9034c70301cf7d25f377f033fedef8859003de76c6397eb33e23f008a8f0cd9d293cb1a92a5076d799b4b9', 'f2a9e7bdf110b5b031ae807322efdb3d66128589c3de6ae25d32d70370ab1a7621d8d8402b82e6f8918e3948196f08f36d9754ca94337b676c5fe5bd652b9caf', 'e70a8579eafc7cf4b4b3a3c4582b9a19bcb7befefca2d96a1b590e9f7ec3a8acf01547c68a2c63108ba24631ffb1680a34531bfe2c32baec2f18cbe244cc20ef', '4c3e2bdf798358713d0583b1856d69f6fbd3874dfa660d973f8a754692c4d5899d96e801b2e55951b4868835487a63aa8186fbfc3a7e17df40ef7d83f5155378', 0, 1, 'files/thumbs/contacts/convicted.jpg.thumb_56618150eb2832.88260990.jpg', 26);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Gegevens worden geëxporteerd voor tabel `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `email`, `phone`, `address1`, `address2`, `city`, `postal`, `country_id`, `user_id`) VALUES
(6, 'Jorn Schalkwijk', 'jornschalkwijk@gmail.com', '620562038', 'Goudplevier 111', '', 'IJsselstein, Utrecht', '3403 AS', 0, 0),
(7, 'Ivo Schalkwijk', 'ivoschalkwijk@gmail.com', '620562039', 'Goudplevier 111', '', 'IJsselstein, Utrecht', '3403 AS', 0, 0),
(8, 'Henkie Schalkwijk', 'henkieschalkwijk@gmail.com', '620562038', 'Goudplevier 111', '', 'IJsselstein, Utrecht', '3403 AS', 0, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `downloads`
--

CREATE TABLE IF NOT EXISTS `downloads` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `file_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `thumb_name` varchar(100) NOT NULL,
  `album_id` int(255) NOT NULL,
  `date` date NOT NULL,
  `secured` tinyint(1) NOT NULL,
  `path` varchar(5000) NOT NULL,
  `thumb_path` varchar(5000) NOT NULL,
  `user_id` int(255) NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(255) NOT NULL,
  `total` float NOT NULL DEFAULT '0',
  `paid` tinyint(1) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

--
-- Gegevens worden geëxporteerd voor tabel `orders`
--

INSERT INTO `orders` (`order_id`, `hash`, `total`, `paid`, `customer_id`) VALUES
(80, '94fd0df4dbb6120487502aa987c9532d', 24.2, 0, 6),
(81, '8aa9b5ac7b46194d774dd062a23f7bdc', 24.2, 0, 7),
(82, '3a92f316f7bcea122583f403b5a52254', 24.2, 0, 6),
(83, 'd1d37b69f334b7ddcacfbe6b8493051c', 12.1, 0, 8);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `orders_products`
--

CREATE TABLE IF NOT EXISTS `orders_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

--
-- Gegevens worden geëxporteerd voor tabel `orders_products`
--

INSERT INTO `orders_products` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(103, 80, 9, 2),
(104, 81, 9, 2),
(109, 82, 9, 2),
(112, 83, 9, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` varchar(160) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `approved` tinyint(10) NOT NULL,
  `author` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `category_id` varchar(50) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `path` varchar(100) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `pages`
--

INSERT INTO `pages` (`page_id`, `title`, `description`, `content`, `approved`, `author`, `date`, `category_id`, `trashed`, `path`) VALUES
(1, 'Jorn', '', 'Hallo Lotte', 1, 'admin', '2015-09-14', '', 0, 'Jorn');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `failed` tinyint(1) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(255) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` varchar(160) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `keywords` varchar(3000) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `author` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `category_id` int(50) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Gegevens worden geëxporteerd voor tabel `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `description`, `content`, `keywords`, `approved`, `author`, `date`, `category_id`, `trashed`) VALUES
(29, 'Test1', '', '<p>twst</p>', '', 1, 'admin', '2015-12-17', 9, 0),
(30, 'help', '', '<p>nu</p>', '', 1, 'admin', '2015-12-17', 9, 0),
(31, 'lott', 'test', '<p>hallo lotte</p>', '', 0, '', '0000-00-00', 10, 0),
(32, 'Floki', 'loki', '9', '', 0, '', '0000-00-00', 9, 0),
(33, 'ragnar', 'sss', '10', '', 0, '', '0000-00-00', 0, 0),
(34, 'EDWE', 'dd', '<p>sss</p>', '', 0, '', '0000-00-00', 63, 0),
(35, 'Jorn', 'Jo4rn', '<p>ddd</p>', '', 0, '', '0000-00-00', 63, 0),
(36, 'Ivo', 'ivo', '<p>ss</p>', '', 0, '', '0000-00-00', 10, 0),
(37, 'Fez', 'voe', '<p><a href="http://localhost/cms/admin/files/error/57af33d7272415.47406753.png"><img src="http://localhost/cms/admin/files/error/57af33d7272415.47406753.png" alt="" width="100%" /></a>jorn</p>', '', 0, '', '0000-00-00', 64, 1),
(38, 'pietje', 'snjj', '<p>ss</p>', '', 0, '', '0000-00-00', 62, 1),
(39, 'psr4', 'psr4', '<p>xxx</p>', '', 1, 'admin', '2016-10-19', 65, 0),
(40, 'boeba', '', '<p>xxx</p>\r\n<p>&nbsp;</p>', '', 0, 'admin', '2016-10-29', 66, 0),
(41, 'jantje', '', '<p>hoi</p>', '', 0, 'admin', '2016-10-29', 67, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE IF NOT EXISTS `products` (
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
  `album_id` int(255) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `trashed` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  `author` varchar(100) NOT NULL,
  `quantity` int(255) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`product_id`, `name`, `category_id`, `price`, `description`, `discount_price`, `savings`, `tax_percentage`, `tax`, `total`, `img_path`, `album_id`, `approved`, `trashed`, `date`, `author`, `quantity`) VALUES
(8, 'Hamster', 131, 10, '<p>test</p>', 0, 0, 0, 0, 0, '', 132, 0, 0, '0000-00-00', '', 10),
(9, 'Konijn', 9, 10, '<p>hallo</p>', 0, 0, 0, 0, 0, 'files/thumbs/products/Dieren/Konijn/jemoeder/11707579_10152840908746400_8419897691707416081_n.jpg.thumb_57444b065709c6.34315626.jpg', 133, 0, 0, '0000-00-00', '', 2),
(10, 'jorn', 9, 100, '<p>xx</p>', 0, 0, 0, 0, 0, '', 151, 1, 0, '0000-00-00', '', 5),
(11, 'Cindy', 9, 500, '<p>ddfd</p>', 0, 0, 0, 0, 0, 'files/thumbs/products/Dieren/Cindy/1557445_10202875641426277_1385188966_n.jpg.thumb_575187675abce2.65507233.jpg', 152, 1, 0, '0000-00-00', '', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(30) NOT NULL AUTO_INCREMENT,
  `username` varchar(500) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varbinary(500) NOT NULL,
  `last_name` varbinary(500) NOT NULL,
  `dob` varbinary(500) NOT NULL,
  `email` varbinary(500) NOT NULL,
  `function` varbinary(500) NOT NULL,
  `rights` varbinary(500) NOT NULL,
  `img_path` varchar(150) NOT NULL,
  `token` varchar(100) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `album_id` int(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `dob`, `email`, `function`, `rights`, `img_path`, `token`, `trashed`, `approved`, `album_id`) VALUES
(26, 'admin', '$2y$10$MTc1YjI2OTE0ZmVjN2ZlM.srhqkbAPviVPYFMGYeXEGZlIrIaGoQK', '744eefe56cdc6f2995f9296c7bca43547bf8b7af65df1f4147efe341c7d5c2d55e064e2f879b9c57b15beb0b1b020b88ebf8f9b2401b189b52895aac45c63ffb', '7648e672828d893abc02cc54284156a55bc34513d31e28b6a014a726df1609e25ee328fbc0fada14b001f58c54d0f47e0bcc2d293146477f6721e7729a6e13a2', '', '3c0bb1832bb3b6b70ab3ae96e51d28400191110f2f76d2d6c0d503083e76b8a9d85c3ff38c2723d3003224d247e2c13803c103996c1d8809f2cb42e6fea58265', '07d4582ddeae80d40c9bc9a477e7e9311dc20601261107da02f003778e0875b23c8380bd4de3743c3e13d6a1144bc6d5510aa22d683e90f1bc6593c6d6e93e17', '49116d19b4b51e72ec1dcb9d3add807acaa847e4ef7d3f8e8fcd9f89f95f9fa5c0b86d32d33637a8e7ca6b3e903cb5afc56e1fb48038d61634650d96e80ec975', '', 'b3e743fbef2d8ba143f1586a4c32193f', 0, 0, 0),
(27, 'jorn', '$2y$10$YjlhZmEyODUxZjRhNWJjMu/8NTNSES.tWgBxrFxS569wJm99cZAAG', 'b5e1475a06c234e7e604ac5aa344e972e31c828b8c21b2bbe4fa06ea7b00ad701a8b2cc42f90072332161ffc4ca386a15e0893d8e53e1ada5935d25a78fe5f72', '522a54fd78118ecc201580d7ea6e9f94bb6cf2a1adfb1b3f319f9476b5fa7896eb135b9981d0af16a35fd195286c2359ac56b3e79f5696b58931a4344462bdee', '', 'dc11a797e1c29445ac278ac255b376f009a639e740b250687a19a56071302324fcb841078a5e51d87cdbc590ec77166375b771e75ce8ec11219a1fd785f857b3', 'e164660bf20f2bff7d907cc9659ab97c4174fc407c201d8b9bfd1c7ceb77ea7d78997b8d24043aa75fc4053db7062ac1fb3c71049d852314d13156fbef735955', '7f90f1147610bee37cee3786914cf32c5e6b19fff0dd15706ee79f6aff49c20c9f21cd7ceae6a0c866ad1dadc4363c3e23faa3a5a971caf75c60365736e03beb', 'files/thumbs/users/dj-jorn.jpg.thumb_5666ae268a6564.12268516.jpg', 'fc3f5aa793194e07b9d2376779604e68', 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
