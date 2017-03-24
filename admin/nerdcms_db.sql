-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 24, 2017 at 11:00 AM
-- Server version: 5.6.35
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `nerdcms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `postal_code` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `album_id` int(255) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` varchar(140) NOT NULL,
  `author` varchar(60) NOT NULL,
  `parent_id` int(255) DEFAULT '0',
  `type` varchar(12) NOT NULL,
  `secured` tinyint(1) NOT NULL,
  `path` varchar(1000) NOT NULL,
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `name`, `description`, `author`, `parent_id`, `type`, `secured`, `path`, `user_id`) VALUES
(3, 'Contacts', '', 'admin', 0, '', 0, 'contacts', 33),
(5, 'Users', '', 'admin', 0, '', 0, 'users', 33),
(8, 'Products', '', 'admin', 0, '', 0, 'products', 33);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(255) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` varchar(160) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `keywords` varchar(3000) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `type` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `parent_id` int(30) DEFAULT '0',
  `album_id` int(10) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contact_id` int(255) NOT NULL,
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
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`contact_id`, `first_name`, `last_name`, `phone_1`, `phone_2`, `email_1`, `email_2`, `dob`, `street`, `street_num`, `street_num_add`, `zip`, `notes`, `trashed`, `approved`, `img_path`, `user_id`, `date`) VALUES
(4, 0x646566353032303063646362643239313865663766623034383966323439353362636437316338663938646634633031386161383235653439633638653262636134333361333431643163363938653763363961363466666464363764366331623536316230343963363862363565376361376532353039303864333239663366353338313231646663623862373965633133386636353736643537653431353435623634663366373432363436, 0x6465663530323030643936643264633062396462643066353436326234393830333264636239373138626239386339376331323864306334363566363439643232653363626161383338396231633536393337653264636530383066613962313431356234333439646466646465343834653431646535383339373065373865373861613462306539663837613763643866323335343937623965373532336264333638363262613662386234366564633738346133626133633634, 0x646566353032303063386662366334623861376462333337663533353264393331633863613665646565323334356561353636376365626537303835306435356339353865386661386636376461626161383334356436366237343830646438613136383233656131646636653635323932656564653966653331653966346338613062323265646231333135306463633330383435343134653830613835323061376436386661383663623364613137633733643031663630, 0x646566353032303039613435663465653761326162316233383366386634316636393362666531316133666531653762346137646433396638386261663061626362373662396236373731666437623264316261303037623037353533396132323663366338363962393063633439663637633162363865393365663738303334643335616630313230663033666635373434306638616535626361353838646534396333666665, '', '', 0x6465663530323030636461636363393435626364326531363838336566653131366432613134313533393761303761646633303033323564386133666464306531666236316266396563333339393861663562623966343962313735393666343431616331313132333532303563646336343533346335656332656663306234343131393731376335396537386263613262616334653236613635623935343635666333616565356166643730376132626665633430663762303533, 0x64656635303230303636313533383730346232653963393765396637666336353235343163326262633132343665376238306466336330333330366531643931326533386563323266623334633434356339336231336162373239333034646133633830633265653561643563363266396361313261613761656366393434323337316365366136356338313335353761343361373963303464623465306466303731313963383838343930663132633963663036363565323362613432, 0x646566353032303031343065363164376664646163313664626431383334636531666135643638653166646439323863333036656464303530323966343562643162343331373864376666646137666334383834323962636132613730383138373939316265343036616161393265623263666235633465656366393064313433623233326237366662623233393162313831613261656434336131383634383737623165666637313334383835, 0x646566353032303062363138643264386236626363616262313861623435313335363534623536616139656131656231386163623133636432373234373565363733323932386437326134613537303232613231383739353336396632623532396634373439343861336636326639643539663039396339383134386330313837613136363265363736393339306263333163633039306566356664666635626632633662643038, 0x646566353032303031663966303837616234386530663936306133353836633263333637666262383465303031633336653130366163396435353963653433656662383038366132386237613933336666626136343335666465653738373861363232313139396665393063386565373462626338393434373539376166373836643366333664306236303165323930643133353835326136313031316334663532623734643736326563653863353463373364, 0x6465663530323030306533633364336161633363336235383566666531336433376431353731633632336533633762343564383661336566613830323461383833313161373364363539353439376664656262356163626130366532313532646430336338383664376630653435623936386262656639363261623161333331623737376636633031343865643739663762666138356132616465373239333363386462633938363630663163306539623764663634623366393838623139343335, 0, 1, '', 33, '2017-03-21 10:33:40');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address1` varchar(200) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(200) NOT NULL,
  `postal` varchar(12) NOT NULL,
  `country_id` int(10) NOT NULL DEFAULT '0',
  `user_id` int(16) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(255) NOT NULL,
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
  `down_url` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `file_id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `thumb_name` varchar(100) NOT NULL,
  `album_id` int(255) NOT NULL,
  `date` date NOT NULL,
  `secured` tinyint(1) NOT NULL,
  `path` varchar(5000) NOT NULL,
  `thumb_path` varchar(5000) NOT NULL,
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `total` float NOT NULL DEFAULT '0',
  `paid` tinyint(1) NOT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders_products`
--

CREATE TABLE `orders_products` (
  `id` int(11) NOT NULL,
  `order_id` int(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page_id` int(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(160) NOT NULL,
  `content` varchar(10000) NOT NULL,
  `approved` tinyint(10) NOT NULL,
  `date` date NOT NULL,
  `parent_id` int(50) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `path` varchar(300) NOT NULL,
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`page_id`, `title`, `description`, `content`, `approved`, `date`, `parent_id`, `trashed`, `path`, `user_id`) VALUES
(74, 'About', 'This is all about Jorn Schalkwijk, read a short or long story, and check his website!', '', 0, '0000-00-00', 0, 0, 'About', 33),
(75, 'Home', 'Self-taught Web-Developer Jorn Schalkwijk, check out his site!', '', 0, '0000-00-00', 0, 0, 'Home', 33),
(77, 'Blog', 'This is a blog about programming by Jorn Schalkwijk . PHP | JavaScript | HTML & CSS | Golang', '', 0, '2016-12-19', 0, 0, 'Blog', 33),
(78, 'Contact', 'Contact Jorn Schalkwijk with e-mail or call!', '', 0, '2016-12-19', 0, 0, 'Contact', 33),
(79, 'Skills', 'Checkout Jorn Schalkwijk his skills. PHP & MySql | JavaScript | HTML & CSS | Golang', '', 0, '0000-00-00', 0, 0, 'Skills', 33),
(80, 'Categories', 'All categories of Jorn Schalkwijk his blog.', '', 0, '2016-12-19', 0, 0, 'Categories', 33),
(81, 'Jorn Schalkwijk', 'Self-taught Web-Developer Jorn Schalkwijk, check out his site!', '', 0, '0000-00-00', 0, 0, 'Home', 33),
(82, 'ccc', 'ccc', '<p>ccc</p>', 0, '2016-12-27', 0, 1, 'ccc', 33);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `failed` tinyint(1) NOT NULL,
  `transaction_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(255) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` varchar(160) DEFAULT NULL,
  `content` varchar(5000) NOT NULL,
  `keywords` varchar(3000) DEFAULT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `category_id` int(50) DEFAULT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(255) NOT NULL,
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
  `quantity` int(255) NOT NULL,
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `category_id`, `price`, `description`, `discount_price`, `savings`, `tax_percentage`, `tax`, `total`, `img_path`, `album_id`, `approved`, `trashed`, `date`, `quantity`, `user_id`) VALUES
(27, 'Joden', 0, 10, '<p>sssss</p>', 0, 0, 0, 0, 0, '', 171, 0, 0, '2017-03-04', 10, 33),
(28, 'Joden', 0, 10, '<p>sssss</p>', 0, 0, 0, 0, 0, '', 0, 0, 0, '2017-03-04', 10, 33),
(29, 'adolf', 0, 10, '<p>xxxx</p>', 0, 0, 0, 0, 0, '', 174, 0, 0, '2017-03-04', 10, 33);

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `profile_id` int(30) NOT NULL,
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
  `approved` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `taggables`
--

CREATE TABLE `taggables` (
  `tag_id` int(11) NOT NULL,
  `taggable_id` int(11) NOT NULL,
  `taggable_type` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(30) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `date` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(30) NOT NULL,
  `username` varchar(500) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `dob` text NOT NULL,
  `email` text NOT NULL,
  `function` text NOT NULL,
  `rights` text NOT NULL,
  `img_path` varchar(150) NOT NULL,
  `album_id` int(55) NOT NULL,
  `token` varchar(100) NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `customer_id` int(11) DEFAULT '0',
  `protected_key` text NOT NULL,
  `date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `first_name`, `last_name`, `dob`, `email`, `function`, `rights`, `img_path`, `album_id`, `token`, `trashed`, `approved`, `customer_id`, `protected_key`, `date`) VALUES
(33, 'admin', '$2y$10$plp/9WxmHikKd6wlAgKKB.h9pr9WRWnxhchMM2yJ8t7nZJa9O3ia6', 'def50200093ad1666050232df305b38690f4218188b7d3e20e287d4d9fafc163225da3756ac58bd6296d4427b07d08e1b64ca38e3fd93e2bc40f34a0fc0c4c7509a2de56a7a61ef99011e2a2fb841e793511b026381cbbc7', 'def502007b1bb7f5dff61c55648fb3d6e904d5928f520fa7fa84f3e3acd85d63bbd309a6eaa00f14b0f47a490193caf4c671dd4c84078bd5d1b88ce667f9ecbfd3e97e97a80d97943ff1b08d3725fc03da0200408adb84117814ee0a13a4', '', 'def502006ffb6488d45769b79a8c6e20d14dd11b1e28d35e1b3bd8a19c2c680e3a33ed041331153a168124b783b07b0ca33ac9964e4cf06b01cc1847c02e7862a36d6597c557eae87c196674c07e49de65a3dc8126d968686ff261c173a5cafc92448a1ca24733edcab88ee2', 'def502009fa4f349a54b2d71222a28d34afa99eac4dcded8417b798d57d1d5ec0198018e7fe213ac74c5aa3fa7f372c52cf0444fa0c1428ea983e5e07f03e123c7886bc6cbf0ebb3330f5c0aa64408c676f31627927e446ef9', 'def5020064ec12caf3fb028887b981dc65dfee690340d9b073951e16810e495d11da866f059846e36c5ef184179c603332a544fe2f8dae238d3f44aecd88f3ce519c67d87d1386ddbe80d1baa0ba3ee2a46cbad92df89c0767', '', 0, '9314944c5e81b73a07f29f9c87ad4365', 0, 0, 0, 'def10000def5020035159e7db7b426c5bb3344df799d53a44c421d00c8e995f5dfd282287d4d43794f4a5d29f7a19994104960adce7a73da1fe9cec768bd9e79d40f704f53512cd6b34b59b4f06381415fd8d3b880c1e19ca0d6c9942b27bda4c09fe1814f2e2710153afcd42df0c1a0cd7533a187ac774f8da80fe00ea94a0ecf1426b9e792788de9ed017d7f5cefd50589cbc708bd466d2d5fa30b7c44855e50880a01c127a3a68c126fe4dba2f105be5af7979e3561493d58a1d88290c483f7c0d5a46d73f14931a015c09dbfd8d8cd9f90944d7ca786f16962279d95cb6e3203eed97ec9a98a5181c67c60d9119ae00ee0bf5a391a96c7b3adf620425c4d', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`album_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD UNIQUE KEY `contact_id` (`contact_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `orders_products`
--
ALTER TABLE `orders_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`profile_id`);

--
-- Indexes for table `taggables`
--
ALTER TABLE `taggables`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contact_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `file_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=284;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;
--
-- AUTO_INCREMENT for table `orders_products`
--
ALTER TABLE `orders_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `profile_id` int(30) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `taggables`
--
ALTER TABLE `taggables`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;