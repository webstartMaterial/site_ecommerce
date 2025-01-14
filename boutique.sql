-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 01, 2020 at 06:27 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(3) NOT NULL,
  `member_id` int(3) DEFAULT NULL,
  `amount` int(3) NOT NULL,
  `created_at` datetime NOT NULL,
  `state` enum('en cours de traitement','envoyé','livré') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `member_id`, `amount`, `created_at`, `state`) VALUES
(1, 6, 118, '2020-05-26 11:11:06', 'en cours de traitement'),
(2, 6, 118, '2020-05-26 12:55:20', 'en cours de traitement'),
(3, 6, 267, '2020-05-26 14:13:13', 'en cours de traitement');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `message` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `first_name`, `last_name`, `email`, `message`) VALUES
(1, 'Samih', 'Habbani', 'samihhabbani@gmail.com', 'Ceci est mon message !');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(3) NOT NULL,
  `order_id` int(3) DEFAULT NULL,
  `product_id` int(3) DEFAULT NULL,
  `quantity` int(3) NOT NULL,
  `price` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 2, 7, 2, 59),
(2, 3, 2, 2, 15),
(3, 3, 8, 3, 79);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(3) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `sexe` enum('m','f') NOT NULL,
  `city` varchar(20) NOT NULL,
  `postal_code` int(5) UNSIGNED ZEROFILL NOT NULL,
  `address` varchar(50) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--
-- --------------------------------------------------------

INSERT INTO `member` (`id`, `pseudo`, `password`, `last_name`, `first_name`, `email`, `sexe`, `city`, `postal_code`, `address`, `status`) VALUES
(6, 'samih', '$2y$10$Emwm0Vo6whxbY55zelx9NusexdCgr5yXuipACc2Y4zxtHEhzKd1Hi', 'Habbani', 'Samih', 'samihhabbani@gmail.com', 'm', 'Soissons', 02200, 'rue d\'edouard phillipe', 2),
(7, 'david', '$2y$10$bqrclhf5tUM6kNEeRGSDs.5tzhunNqfMeiWYKYO3X9DBXa86Idmv.', 'david', 'cohen', 'samihhabbani@gmail.com', 'm', 'paris', 75020, 'rue de l\'élysée', 2),
(8, 'admin', '$2y$10$kIAEVIcwU6Sni36nT2CPB.KOSB4EktWUrg63YY1lXzW/V6DHfChiK', 'Habbani', 'Samih', 'samihhabbani@gmail.com', 'm', 'Soissons', 02200, 'rue d\'edouard phillipe', 1),
(9, 'natalia', '$2y$10$CnLXhliw4tYzmBNUItu.V.DsUmmnI1Lr79zqNGAyLsoyDvp4HmpsS', 'Fabiano', 'Natalia', 'natalia@gmail.com', 'f', 'Houdan', 78550, '13 rue Saint-Mathieu', 2);


--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(3) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `category` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `color` varchar(20) NOT NULL,
  `size` varchar(5) NOT NULL,
  `public` enum('m','f','mixte') NOT NULL,
  `picture` varchar(250) NOT NULL,
  `price` int(3) NOT NULL,
  `stock` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `reference`, `category`, `title`, `description`, `color`, `size`, `public`, `picture`, `price`, `stock`) VALUES
(1, '11-d-23', 'tshirt', 'Tshirt Col V', 'Tee-shirt en coton flammé liseré contrastant pour femme', 'bleu foncé', 'M', 'f', '11-d-23_bleu.jpg', 2011, 101),
(2, '66-f-15', 'tshirt', 'Tshirt Col V rouge', 'c\'est vraiment un super tshirt en soir&eacute;e !', 'rouge', 'S', 'f', '66-f-15_rouge.png', 151, 21),
(3, '88-g-77', 'tshirt', 'Tshirt Col rond vert', 'Il vous faut ce tshirt Made In France !!!', 'vert', 'L', 'm', '88-g-77_vert.png', 29, 56),
(4, '55-b-38', 'tshirt', 'Tshirt jaune', 'le jaune reviens &agrave; la mode, non? :-)', 'jaune', 'S', 'm', '55-b-38_jaune.png', 20, -4),
(5, '31-p-33', 'tshirt', 'Tshirt noir original', 'voici un tshirt noir tr&egrave;s original :p', 'noir', 'XL', 'm', '31-p-33_noir.jpg', 25, 73),
(6, '56-a-65', 'chemise', 'Chemise Blanche', 'Les chemises c\'est bien mieux que les tshirts', 'blanc', 'L', 'm', '56-a-65_chemiseblanchem.jpg', 49, 66),
(7, '63-s-63', 'chemise', 'Chemise Noir', 'Comme vous pouvez le voir c\'est une chemise noir...', 'noir', 'M', 'm', '63-s-63_chemisenoirm.jpg', 59, 113),
(8, '77-p-79', 'pull', 'Pull gris', 'Pull gris pour l\'hiver', 'gris', 'XL', 'f', '77-p-79_pullgrism2.jpg', 79, 92);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`);
