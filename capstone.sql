-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2026 at 02:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstone`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(20) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `postal` varchar(10) DEFAULT NULL,
  `full_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `delivery_method` varchar(50) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `delivery_fee` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'To Ship',
  `postal_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `first_name`, `last_name`, `email`, `phone`, `province`, `city`, `barangay`, `postal`, `full_address`, `payment_method`, `delivery_method`, `subtotal`, `delivery_fee`, `total`, `created_at`, `status`, `postal_code`) VALUES
(1, '02242601', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1245.00, '2026-02-24 14:29:22', 'To Ship', NULL),
(2, '02242602', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1260.00, '2026-02-24 14:50:22', 'To Ship', NULL),
(3, '02242603', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 575.00, '2026-02-24 14:52:10', 'To Ship', NULL),
(4, '02242604', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1260.00, '2026-02-24 14:53:13', 'To Ship', NULL),
(5, '02242605', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 590.00, '2026-02-24 14:54:49', 'To Ship', NULL),
(6, '02242606', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 575.00, '2026-02-24 14:55:18', 'Process', NULL),
(7, '02242607', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 575.00, '2026-02-24 14:56:11', 'Shipping', NULL),
(8, '02242608', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 575.00, '2026-02-24 14:58:34', 'Shipping', NULL),
(9, '02242609', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1260.00, '2026-02-24 15:02:56', 'Shipped', NULL),
(10, '02242610', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1260.00, '2026-02-25 02:22:07', 'Shipping', NULL),
(11, '02242611', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 590.00, '2026-02-25 02:47:10', 'Shipping', NULL),
(12, '02242612', 'bea', 'son', 'lilbeemail88@gmail.com', '11111', 'Bulacan', 'San Ildefonso', 'mat', NULL, 'mataas na parang ', 'COD', 'Express Delivery (2 - 3days)', NULL, NULL, 420.00, '2026-02-25 05:38:48', 'Cancel', '3010');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `price`, `qty`, `size`, `color`, `image`, `product_id`) VALUES
(2, 2, ' Plain Cotton Brassiere Cleavage W/ Adjustable Strap', 250.00, 2, '32', 'NUDE', '1770003932_var_NUDE_A115 0.webp', NULL),
(3, 3, ' Plain Cotton Brassiere Cleavage W/ Adjustable Strap', 250.00, 1, '32', 'BLACK', '1770003932_var_BLACK_A115 0.webp', NULL),
(4, 3, ' Plain Cotton Brassiere Cleavage W/ Adjustable Strap', 250.00, 2, '32', 'NUDE', '1770003932_var_NUDE_A115 0.webp', NULL),
(5, 4, ' Intimate Forever A3031 Cup A Seamless Sports Bra in Racerback Ribbed Fabric Bralette Style 3 colors', 530.00, 2, '34A', 'BLACK', '1770008270_var_BLACK_A3031 BK.webp', NULL),
(6, 4, ' Intimate Forever A3031 Cup A Seamless Sports Bra in Racerback Ribbed Fabric Bralette Style 3 colors', 540.00, 4, '38A', 'BLACK', '1770008270_var_BLACK_A3031 BK.webp', NULL),
(7, 5, 'Intimate Forever Epf Black Dozen Full Panty Cotton Underwear In Embroidery Design with Piping Garter', 1200.00, 1, 'SMALL', 'BLACK', '1770002184_p_0_epf1.webp', NULL),
(8, 1, 'Intimate Forever Epf Black Dozen Full Panty Cotton Underwear In Embroidery Design with Piping Garter', 1200.00, 1, 'SMALL', 'BLACK', '1770002184_p_0_epf1.webp', NULL),
(9, 2, 'Intimate Forever Epf Black Dozen Full Panty Cotton Underwear In Embroidery Design with Piping Garter', 1200.00, 1, 'SMALL', 'BLACK', '1770002184_p_0_epf1.webp', NULL),
(10, 3, ' Intimate Forever A3031 Cup A Seamless Sports Bra in Racerback Ribbed Fabric Bralette Style 3 colors', 530.00, 1, '32A', 'BLACK', '1770008270_var_BLACK_A3031 BK.webp', NULL),
(11, 4, 'Intimate Forever Epf Black Dozen Full Panty Cotton Underwear In Embroidery Design with Piping Garter', 1200.00, 1, 'MEDIUM', 'BLACK', '1770002184_p_0_epf1.webp', NULL),
(12, 5, ' Intimate Forever A3031 Cup A Seamless Sports Bra in Racerback Ribbed Fabric Bralette Style 3 colors', 530.00, 1, '32A', 'BLACK', '1770008270_var_BLACK_A3031 BK.webp', NULL),
(13, 6, ' Intimate Forever A3031 Cup A Seamless Sports Bra in Racerback Ribbed Fabric Bralette Style 3 colors', 530.00, 1, '32A', 'BLACK', '1770008270_var_BLACK_A3031 BK.webp', NULL),
(14, 7, ' Intimate Forever A3031 Cup A Seamless Sports Bra in Racerback Ribbed Fabric Bralette Style 3 colors', 530.00, 1, '32A', 'BLACK', '1770008270_var_BLACK_A3031 BK.webp', NULL),
(15, 8, ' Intimate Forever A3031 Cup A Seamless Sports Bra in Racerback Ribbed Fabric Bralette Style 3 colors', 530.00, 1, '32A', 'BLACK', '1770008270_var_BLACK_A3031 BK.webp', NULL),
(16, 9, 'Intimate Forever Epf Black Dozen Full Panty Cotton Underwear In Embroidery Design with Piping Garter', 1200.00, 1, 'MEDIUM', 'BLACK', '1770002184_p_0_epf1.webp', NULL),
(17, 10, 'Intimate Forever Epf Black Dozen Full Panty Cotton Underwear In Embroidery Design with Piping Garter', 1200.00, 1, 'SMALL', 'BLACK', '1770002184_p_0_epf1.webp', NULL),
(18, 11, ' Intimate Forever A3031 Cup A Seamless Sports Bra in Racerback Ribbed Fabric Bralette Style 3 colors', 530.00, 1, '32A', 'BLACK', '1770008270_var_BLACK_A3031 BK.webp', NULL),
(19, 12, 'Intimate Forever Egbw 6 In 1 Wear Adult Underwear Bikini Panty All White with Embroidery Design', 360.00, 1, 'SMALL', 'WHITE', '1769956931_var_WHITE_egbw2.webp', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `category` enum('BRA','PANTY','PANTYLET','PANTYSHORT','SANDO','SLEEPWEAR') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_2` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `image_3` varchar(255) DEFAULT NULL,
  `image_4` varchar(255) DEFAULT NULL,
  `image_5` varchar(255) DEFAULT NULL,
  `image_6` varchar(255) DEFAULT NULL,
  `size_chart` varchar(255) DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `category`, `description`, `price`, `size`, `color`, `qty`, `sku`, `image`, `image_2`, `status`, `length`, `width`, `height`, `weight`, `image_3`, `image_4`, `image_5`, `image_6`, `size_chart`, `original_price`, `sale_price`, `featured`) VALUES
(27, 'Intimate Forever Egbw 6 In 1 Wear Adult Underwear Bikini Panty All White with Embroidery Design', 'Intimate Forever Egbw 6 In 1', 'PANTY', 'Forever’s Adult Bikini Underwear\r\n\r\nTimeless Quality & Comfort\r\nCause We’ve got you covered!\r\nProudly made in the Philippines.\r\n\r\n\r\n\r\nEGB W\r\n\r\n•Material: Cotton\r\n\r\n•Description: Bikini in Outside Leg Garter \r\n\r\n•Design: Embroidery\r\n\r\n•Sizes: Small – 2XL\r\n\r\n•Particulars: 6 in 1\r\n\r\n•Colors: All White\r\n\r\n\r\n\r\nFOREVER is a fashion retail company specializing in undergarments and sleepwear for females, from teens to \r\n\r\nadults. What started out in 1997 as a manufacturer of expertly crafted garments is now one of the major players in \r\n\r\nthe world of intimate apparel. \r\n\r\nFOREVER will be the fashion solution for every Filipina who wants to look and feel her best, inside and out\r\n\r\n\r\n\r\nIMPORTANT REMINDER:\r\n\r\n• You are purchasing an authentic product directly from the manufacturer.\r\n\r\n• For quality control and hygiene purposes, strictly NO RETURN & EXCHANGE “WHITE & INTIMATE WEAR”\r\n\r\n• Please refer to our policies, terms, and conditions for further details.\r\n\r\n• Please note that due to the nature of the manufacturing process, from time-to-time product sizing may vary.\r\n\r\n• Fabric colors represented on computer monitors / MOBILE PHONES may vary due to variations in computer \r\n\r\nscreens, resolution, color, tone or any other display settings. We cannot guarantee that the color you see \r\n\r\naccurately portrays the true color of the garments\r\n\r\n\r\n\r\n#panty #underwear #forever #intimateforever', 360.00, '', '', 11, '', '1769956931_p_0_egbw1.webp', '', 'active', 25.00, 10.00, 25.00, 1.00, NULL, NULL, NULL, NULL, '1769956931_sc_sizechart bikini.webp', NULL, NULL, 0),
(28, 'Intimate Forever Epf Black Dozen Full Panty Cotton Underwear In Embroidery Design with Piping Garter', 'Intimate Forever Epf Black Dozen Full Panty ', 'PANTY', 'Forever’s Adult Full Underwear\r\n\r\nTimeless Quality & Comfort\r\n\r\nCause We’ve got you covered!\r\n\r\nProudly made in the Philippines.\r\n\r\n\r\n\r\nEPF B\r\n\r\n• Material: Cotton\r\n\r\n• Description: Full in Piping Leg Garter\r\n\r\n• Design: Embroidery\r\n\r\n• Sizes: Small – 3XL\r\n\r\n• Particulars: Dozen\r\n\r\n• Colors: All Black\r\n\r\n\r\n\r\nFOREVER is a fashion retail company specializing in undergarments and sleepwear for females, from teens to\r\n\r\nadults. What started out in 1997 as a manufacturer of expertly crafted garments is now one of the major players in\r\n\r\nthe world of intimate apparel.\r\n\r\n\r\n\r\nFOREVER will be the fashion solution for every Filipina who wants to look and feel her best, inside and out\r\n\r\n\r\n\r\nIMPORTANT REMINDER:\r\n\r\n• You are purchasing an authentic product directly from the manufacturer.\r\n\r\n• For quality control and hygiene purposes, strictly NO RETURN & EXCHANGE “WHITE & INTIMATE WEAR”\r\n\r\n• Please refer to our policies, terms, and conditions for further details.\r\n\r\n• Please note that due to the nature of the manufacturing process, from time-to-time product sizing may vary.\r\n\r\n• Fabric colors represented on computer monitors / MOBILE PHONES may vary due to variations in computer\r\n\r\nscreens, resolution, color, tone or any other display settings. We cannot guarantee that the color you see\r\n\r\naccurately portrays the true color of the garments.\r\n\r\n\r\n\r\n\r\n\r\n#panty #underwear #forever #intimateforever', 1200.00, '', '', 10, '', '1770002184_p_0_epf1.webp', '', 'active', 30.00, 3.00, 20.00, 60.00, NULL, NULL, NULL, NULL, '1770002184_sc_size chart full.webp', NULL, NULL, 0),
(29, 'Intimate Forever Smb Per Piece Wear Underwear Black and Nude Garter Bikini Seamless Panty', 'Intimate Forever Smb Per Piece ', 'PANTY', 'Forever’s Adult Bikini Underwear\r\n\r\nTimeless Quality & Comfort\r\n\r\nCause We’ve got you covered!\r\n\r\nProudly made in the Philippines.\r\n\r\n\r\n\r\nSMB\r\n\r\n•Material: Cotton Lycra\r\n\r\n•Description: Bikini Seamless\r\n\r\n•Design: Plain\r\n\r\n•Sizes: Small – 2XL\r\n\r\n•Particulars: Per Piece\r\n\r\n•Colors: Black & Nude\r\n\r\n\r\n\r\nFOREVER is a fashion retail company specializing in undergarments and sleepwear for females, from teens to\r\n\r\nadults. What started out in 1997 as a manufacturer of expertly crafted garments is now one of the major players\r\n\r\nin the world of intimate apparel.\r\n\r\n\r\n\r\nFOREVER will be the fashion solution for every Filipina who wants to look and feel her best, inside and out\r\n\r\n\r\n\r\nIMPORTANT REMINDER:\r\n\r\n• You are purchasing an authentic product directly from the manufacturer.\r\n\r\n• For quality control and hygiene purposes, strictly NO RETURN & EXCHANGE “WHITE & INTIMATE WEAR”\r\n\r\n• Please refer to our policies, terms, and conditions for further details.\r\n\r\n• Please note that due to the nature of the manufacturing process, from time-to-time product sizing may\r\n\r\nvary.\r\n\r\n• Fabric colors represented on computer monitors / MOBILE PHONES may vary due to variations in\r\n\r\ncomputer screens, resolution, color, tone or any other display settings. We cannot guarantee that the\r\n\r\ncolor you see accurately portrays the true color of the garments.\r\n\r\n\r\n\r\n\r\n\r\n#panty #underwear #forever #intimateforever\r\n\r\n', 160.00, '', '', 135, '', '1770002827_p_0_SMB1.webp', '', 'active', 25.00, 1.00, 20.00, 20.00, NULL, NULL, NULL, NULL, '1770002827_sc_sizechart bikini.webp', NULL, NULL, 0),
(30, ' Plain Cotton Brassiere Cleavage W/ Adjustable Strap', 'Intimate Forever A115 Cup A', 'BRA', 'Forever’s Brassiere\r\n\r\nTimeless Quality & Comfort\r\n\r\nCause We’ve got you covered!\r\n\r\nProudly made in the Philippines.\r\n\r\n\r\n\r\nA115\r\n\r\n• Material: Cotton \r\n\r\n• Description: 3/4 Cup Non Wired Plain Cotton Bra\r\n\r\n• Design: Plain with ribbon\r\n\r\n•Cup: A\r\n\r\n• Sizes: 32-40\r\n\r\n• Particulars: Per Piece\r\n\r\n• Colors: Black, Gray, Nude & Darkgray\r\n\r\n\r\n\r\nFOREVER is a fashion retail company specializing in undergarments and sleepwear for females, from teens to adults. \r\n\r\nWhat started out in 1997 as a manufacturer of expertly crafted garments is now one of the major players in the \r\n\r\nworld of intimate apparel. \r\n\r\nFOREVER will be the fashion solution for every Filipina who wants to look and feel her best, inside and out\r\n\r\n\r\nIMPORTANT REMINDER:\r\n\r\n• You are purchasing an authentic product directly from the manufacturer.\r\n\r\n• For quality control and hygiene purposes, strictly NO RETURN & EXCHANGE “WHITE & INTIMATE WEAR”\r\n\r\n• Please refer to our policies, terms, and conditions for further details.\r\n\r\n• Please note that due to the nature of the manufacturing process, from time-to-time product sizing may vary.\r\n\r\n• Fabric colors represented on computer monitors / MOBILE PHONES may vary due to variations in computer \r\n\r\nscreens, resolution, color, tone or any other display settings. We cannot guarantee that the color you see \r\n\r\naccurately portrays the true color of the garments.\r\n\r\n#panty #underwear #forever #intimateforever', 250.00, '', '', 44, '', '1770003932_p_0_A115.webp', '', 'active', 40.00, 22.00, 33.00, 55.00, NULL, NULL, NULL, NULL, '1770003932_sc_SIZE CHART BRA.webp', NULL, NULL, 0),
(31, ' Intimate Forever A3031 Cup A Seamless Sports Bra in Racerback Ribbed Fabric Bralette Style 3 colors', 'Intimate Forever A3031 Cup A', 'BRA', 'Forever’s Adult Boyleg Underwear\r\n\r\nTimeless Quality & Comfort\r\n\r\nCause We’ve got you covered!\r\n\r\nProudly made in the Philippines.\r\n\r\n\r\n\r\nA3031\r\n\r\n•Material: Seamless Ribbed Fabric\r\n\r\n•Description: Sports Bra\r\n\r\n•Design: Plain Bralette\r\n\r\n•Cup: A\r\n\r\n•Sizes: 32 - 38\r\n\r\n•Particulars: Per Piece\r\n\r\n•Colors: Black, Brown & Cream\r\n\r\n\r\n\r\nFOREVER is a fashion retail company specializing in undergarments and sleepwear for females, from teens to adults. What started out in 1997 as a manufacturer of expertly crafted garments is now one of the major players in the world of intimate apparel.\r\n\r\n\r\n\r\nFOREVER will be the fashion solution for every Filipina who wants to look and feel her best, inside and out\r\n\r\n\r\n\r\nIMPORTANT REMINDER:\r\n\r\n• You are purchasing an authentic product directly from the manufacturer.\r\n\r\n• For quality control and hygiene purposes, strictly NO RETURN & EXCHANGE “WHITE & INTIMATE WEAR”\r\n\r\n• Please refer to our policies, terms, and conditions for further details.\r\n\r\n• Please note that due to the nature of the manufacturing process, from time-to-time product sizing may vary.\r\n\r\n• Fabric colors represented on computer monitors / MOBILE PHONES may vary due to variations in computer screens, resolution, color, tone or any other display settings. We cannot guarantee that the color you see accurately portrays the true color of the garments.\r\n\r\n\r\n\r\n\r\n\r\n#panty #underwear #forever #intimateforever\r\n\r\n', 530.00, '', '', 29, '', '1770008270_p_0_A3031.webp', '', 'active', 44.00, 44.00, 44.00, 44.00, NULL, NULL, NULL, NULL, '1770008270_sc_SIZE CHART BRA.webp', NULL, NULL, 0),
(32, 'Intimate Forever 3102 ZIELLE Night Dress Cotton Fabric Printed Design Button Down Top in 3 Colors', 'Intimate Forever 3102 ZIELLE ', 'SLEEPWEAR', 'Forever’s Adult Sleepwear\r\n\r\nTimeless Quality & Comfort\r\n\r\nCause We’ve got you covered!\r\n\r\nProudly made in the Philippines.\r\n\r\n\r\n\r\n3102 ZIELLE\r\n\r\n•Material: Cotton Fabric\r\n\r\n•Description: Button Down Dress\r\n\r\n•Design: Printed Design\r\n\r\n•Sizes: Medium – 2XLarge\r\n\r\n•Particulars: Per Piece\r\n\r\n•Colors: Pink, Blue & Orange\r\n\r\n \r\n\r\nFOREVER is a fashion retail company specializing in undergarments and sleepwear for females, from teens to\r\n\r\nadults. What started out in 1997 as a manufacturer of expertly crafted garments is now one of the major players\r\n\r\nin the world of intimate apparel.\r\n\r\nFOREVER will be the fashion solution for every Filipina who wants to look and feel her best, inside and out\r\n\r\n\r\n\r\nIMPORTANT REMINDER:\r\n\r\n• You are purchasing an authentic product directly from the manufacturer.\r\n\r\n• For quality control and hygiene purposes, strictly NO RETURN & EXCHANGE “WHITE & INTIMATE WEAR”\r\n\r\n• Please refer to our policies, terms, and conditions for further details.\r\n\r\n• Please note that due to the nature of the manufacturing process, from time-to-time product sizing may\r\n\r\nvary.\r\n\r\n• Fabric colors represented on computer monitors / MOBILE PHONES may vary due to variations in\r\n\r\ncomputer screens, resolution, color, tone or any other display settings. We cannot guarantee that the\r\n\r\ncolor you see accurately portrays the true color of the garments.\r\n\r\n\r\n\r\n#panty #underwear #forever #intimateforever #sleepwear\r\n\r\n', 450.00, '', '', 22, '', '1770008843_p_0_3012 1.webp', '', 'active', 33.00, 33.00, 43.00, 44.00, NULL, NULL, NULL, NULL, '1770008843_sc_DRESS SIZE CHART.webp', NULL, NULL, 0),
(33, 'Intimate Forever 4301 NARI Cotton Fabric Spaghetti Strap Top Shorts Set Sleepwear Loungewear', 'Intimate Forever 4301 NARI', 'SLEEPWEAR', 'Forever’s Adult Sleepwear\r\n\r\nTimeless Quality & Comfort\r\n\r\nCause We’ve got you covered!\r\n\r\nProudly made in the Philippines.\r\n\r\n\r\n\r\n4301 NARI\r\n\r\n•Material: Cotton Fabric\r\n\r\n•Description: Spaghetti Strap Top Shorts Set\r\n\r\n•Design: Panda Painting\r\n\r\n•Sizes: Medium – 2XLarge\r\n\r\n•Particulars: Set\r\n\r\n•Colors: Black, Green & Peach Pink\r\n\r\n\r\n\r\nFOREVER is a fashion retail company specializing in undergarments and sleepwear for females, from teens to adults. What started out in 1997 as a manufacturer of expertly crafted garments is now one of the major players in the world of intimate apparel. FOREVER will be the fashion solution for every Filipina who wants to look and feel her best, inside and out\r\n\r\n\r\n\r\nIMPORTANT REMINDER:\r\n\r\n• You are purchasing an authentic product directly from the manufacturer.\r\n\r\n• For quality control and hygiene purposes, strictly NO RETURN & EXCHANGE “WHITE & INTIMATE WEAR”\r\n\r\n• Please refer to our policies, terms, and conditions for further details.\r\n\r\n• Please note that due to the nature of the manufacturing process, from time-to-time product sizing may vary.\r\n\r\n• Fabric colors represented on computer monitors / MOBILE PHONES may vary due to variations in computer screens, resolution, color, tone or any other display settings. We cannot guarantee that the color you see accurately portrays the true color of the garments.\r\n\r\n\r\n\r\n#panty #underwear #forever #intimateforever #sleepwear', 380.00, '', '', 18, '', '1770009597_p_0_4301 1.webp', '', 'inactive', 32.00, 33.00, 22.00, 33.00, NULL, NULL, NULL, NULL, '1770009597_sc_SHORT SIZE CHART.webp', NULL, NULL, 0),
(35, 'Intimate Forever Ls69 Per Piece Adult Ladies Female Teens Black and White Spaghetti Strap Sando', 'Intimate Forever Ls69 Per Piece Adult Ladies Sando', 'SANDO', 'Forever’s Adult Spaghetti Strap Sando\r\n\r\nTimeless Quality & Comfort\r\n\r\nCause We’ve got you covered!\r\n\r\nProudly made in the Philippines.\r\n\r\n\r\n\r\nLS69\r\n\r\n•Material: Cotton\r\n\r\n•Description: Adult Spaghetti Strap Sando\r\n\r\n•Design: Plain White\r\n\r\n•Sizes: Small – 2XL\r\n\r\n•Particulars: Per Piece\r\n\r\n•Colors: White & Black\r\n\r\n\r\n\r\n\r\n\r\nFOREVER is a fashion retail company specializing in undergarments and sleepwear for females, from teens to\r\n\r\nadults. What started out in 1997 as a manufacturer of expertly crafted garments is now one of the major players\r\n\r\nin the world of intimate apparel.\r\n\r\n\r\n\r\nFOREVER will be the fashion solution for every Filipina who wants to look and feel her best, inside and out\r\n\r\n\r\n\r\nIMPORTANT REMINDER:\r\n\r\n• You are purchasing an authentic product directly from the manufacturer.\r\n\r\n• For quality control and hygiene purposes, strictly NO RETURN & EXCHANGE “WHITE & INTIMATE WEAR”\r\n\r\n• Please refer to our policies, terms, and conditions for further details.\r\n\r\n• Please note that due to the nature of the manufacturing process, from time-to-time product sizing may\r\n\r\nvary.\r\n\r\n• Fabric colors represented on computer monitors / MOBILE PHONES may vary due to variations in\r\n\r\ncomputer screens, resolution, color, tone or any other display settings. We cannot guarantee that the\r\n\r\ncolor you see accurately portrays the true color of the garments.\r\n\r\n\r\n\r\n\r\n\r\n#panty #underwear #forever #intimateforever', 165.00, '', '', 536, '', '1770478223_p_0_sando 1.webp', '', 'active', 22.00, 22.00, 22.00, 22.00, NULL, NULL, NULL, NULL, '1770478223_sc_sando 5.webp', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `created_at`) VALUES
(56, 27, '1769956931_p_0_egbw1.webp', '2026-02-01 14:42:11'),
(57, 27, '1769956931_p_1_egbw2.webp', '2026-02-01 14:42:11'),
(58, 27, '1769956931_p_2_egbw3.webp', '2026-02-01 14:42:11'),
(59, 27, '1769956931_p_3_egbw4.webp', '2026-02-01 14:42:11'),
(60, 27, '1769956931_p_4_sizechart bikini.webp', '2026-02-01 14:42:11'),
(61, 27, '1769956931_p_5_policy.webp', '2026-02-01 14:42:11'),
(62, 28, '1770002184_p_0_epf1.webp', '2026-02-02 03:16:24'),
(63, 28, '1770002184_p_1_epf2.webp', '2026-02-02 03:16:24'),
(64, 28, '1770002184_p_2_epf3.webp', '2026-02-02 03:16:24'),
(65, 28, '1770002184_p_3_epf4.webp', '2026-02-02 03:16:24'),
(66, 28, '1770002184_p_4_policy.webp', '2026-02-02 03:16:24'),
(67, 28, '1770002184_p_5_size chart full.webp', '2026-02-02 03:16:24'),
(68, 29, '1770002827_p_0_SMB1.webp', '2026-02-02 03:27:07'),
(69, 29, '1770002827_p_1_SMB2.webp', '2026-02-02 03:27:07'),
(70, 29, '1770002827_p_2_SMB3.webp', '2026-02-02 03:27:07'),
(71, 29, '1770002827_p_3_SMB5.webp', '2026-02-02 03:27:07'),
(72, 29, '1770002827_p_4_SMB6.webp', '2026-02-02 03:27:07'),
(73, 29, '1770002827_p_5_policy.webp', '2026-02-02 03:27:07'),
(74, 30, '1770003932_p_0_A115.webp', '2026-02-02 03:45:32'),
(75, 30, '1770003932_p_1_A115 2.webp', '2026-02-02 03:45:32'),
(76, 30, '1770003932_p_2_A115 3.webp', '2026-02-02 03:45:32'),
(77, 30, '1770003932_p_3_A155 4.webp', '2026-02-02 03:45:32'),
(78, 30, '1770003932_p_4_A115 5.webp', '2026-02-02 03:45:32'),
(79, 30, '1770003932_p_5_policy.webp', '2026-02-02 03:45:32'),
(80, 31, '1770008270_p_0_A3031.webp', '2026-02-02 04:57:50'),
(81, 31, '1770008270_p_1_A3031 1.webp', '2026-02-02 04:57:50'),
(82, 31, '1770008270_p_2_A3031 2.webp', '2026-02-02 04:57:50'),
(83, 31, '1770008270_p_3_A3031 3.webp', '2026-02-02 04:57:50'),
(84, 31, '1770008270_p_4_A3031 4.webp', '2026-02-02 04:57:50'),
(85, 31, '1770008270_p_5_policy.webp', '2026-02-02 04:57:50'),
(86, 32, '1770008843_p_0_3012 1.webp', '2026-02-02 05:07:23'),
(87, 32, '1770008843_p_1_3102 2.webp', '2026-02-02 05:07:23'),
(88, 32, '1770008843_p_2_3102 3.webp', '2026-02-02 05:07:23'),
(89, 32, '1770008843_p_3_3102 4.webp', '2026-02-02 05:07:23'),
(90, 32, '1770008843_p_4_policy.webp', '2026-02-02 05:07:23'),
(91, 32, '1770008843_p_5_SIZE CHART BRA.webp', '2026-02-02 05:07:23'),
(92, 33, '1770009597_p_0_4301 1.webp', '2026-02-02 05:19:57'),
(93, 33, '1770009597_p_1_4301 2.webp', '2026-02-02 05:19:57'),
(94, 33, '1770009597_p_2_4301 3.webp', '2026-02-02 05:19:57'),
(95, 33, '1770009597_p_3_4301 4.webp', '2026-02-02 05:19:57'),
(96, 33, '1770009597_p_4_policy.webp', '2026-02-02 05:19:57'),
(97, 33, '1770009597_p_5_SHORT SIZE CHART.webp', '2026-02-02 05:19:57'),
(98, 34, '1770010561_p_0_sizechart bikini.webp', '2026-02-02 05:35:17'),
(99, 34, '1770010517_p_2_3102 3.webp', '2026-02-02 05:35:17'),
(100, 34, '1770010517_p_3_3102 2.webp', '2026-02-02 05:35:17'),
(101, 34, '1770010517_p_4_4301 1.webp', '2026-02-02 05:35:17'),
(102, 34, '1770010517_p_5_4301 G.webp', '2026-02-02 05:35:17'),
(103, 34, '1770010543_p_5_4301 1.webp', '2026-02-02 05:35:43'),
(104, 35, '1770478223_p_0_sando 1.webp', '2026-02-07 15:30:23'),
(105, 35, '1770478223_p_1_sando 2.webp', '2026-02-07 15:30:23'),
(106, 35, '1770478223_p_2_sando 3.webp', '2026-02-07 15:30:23'),
(107, 35, '1770478223_p_3_sando 4.webp', '2026-02-07 15:30:23'),
(108, 35, '1770478223_p_4_sando 5.webp', '2026-02-07 15:30:23'),
(109, 35, '1770478223_p_5_policy.webp', '2026-02-07 15:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variations`
--

CREATE TABLE `product_variations` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variations`
--

INSERT INTO `product_variations` (`id`, `product_id`, `image`, `price`, `size`, `color`, `qty`, `sku`, `created_at`) VALUES
(109, 27, '1769956931_var_WHITE_egbw2.webp', 360.00, 'SMALL', 'WHITE', 0, 'EGBW - 6IN1 - SMALL', '2026-02-01 14:42:11'),
(110, 27, '1769956931_var_WHITE_egbw2.webp', 360.00, 'MEDIUM', 'WHITE', 3, 'EGBW - 6IN1 - MEDIUM', '2026-02-01 14:42:11'),
(111, 27, '1769956931_var_WHITE_egbw2.webp', 360.00, 'LARGE', 'WHITE', 0, 'EGBW - 6IN1 - LARGE', '2026-02-01 14:42:11'),
(112, 27, '1769956931_var_WHITE_egbw2.webp', 370.00, 'XLARGE', 'WHITE', 2, 'EGBW - 6IN1 - XLARGE', '2026-02-01 14:42:11'),
(113, 27, '1769956931_var_WHITE_egbw2.webp', 380.00, '2XLARGE', 'WHITE', 5, 'EGBW - 6IN1 - 2XLARGE', '2026-02-01 14:42:11'),
(114, 28, NULL, 1200.00, 'SMALL', 'BLACK', 0, 'EGFB - DOZEN - SMALL', '2026-02-02 03:16:24'),
(115, 28, NULL, 1200.00, 'MEDIUM', 'BLACK', 0, 'EGFB - DOZEN - MEDIUM', '2026-02-02 03:16:24'),
(116, 28, NULL, 1200.00, 'LARGE', 'BLACK', 1, 'EGFB - DOZEN - LARGE', '2026-02-02 03:16:24'),
(117, 28, NULL, 1300.00, 'XLARGE', 'BLACK', 1, 'EGFB - DOZEN - XLARGE', '2026-02-02 03:16:24'),
(118, 28, NULL, 1400.00, '2XLARGE', 'BLACK', 0, 'EGFB - DOZEN - 2XLARGE', '2026-02-02 03:16:24'),
(119, 28, NULL, 1500.00, '3XLARGE', 'BLACK', 4, 'EGFB - DOZEN - 3XLARGE', '2026-02-02 03:16:24'),
(120, 29, '1770002827_var_BLACK_SMB4.webp', 160.00, 'SMALL-MEDIUM', 'BLACK', 24, 'SMB - SMALL/MEDIUM-BLACK', '2026-02-02 03:27:07'),
(121, 29, '1770002827_var_BLACK_SMB4.webp', 170.00, 'LARGE-XLARGE', 'BLACK', 20, 'SMB - LARGE/XLARGE -BLACK', '2026-02-02 03:27:07'),
(122, 29, '1770002827_var_BLACK_SMB4.webp', 180.00, '2XLARGE', 'BLACK', 30, 'SMB - 2XLARGE-BLACK', '2026-02-02 03:27:07'),
(123, 29, '1770002827_var_NUDE_SMB3.webp', 160.00, 'SMALL-MEDIUM', 'NUDE', 21, 'SMB - SMALL/MEDIUM-NUDE', '2026-02-02 03:27:07'),
(124, 29, '1770002827_var_NUDE_SMB3.webp', 170.00, 'LARGE-XLARGE', 'NUDE', 18, 'SMB - LARGE/XLARGE -NUDE', '2026-02-02 03:27:07'),
(125, 29, '1770002827_var_NUDE_SMB3.webp', 180.00, '2XLARGE', 'NUDE', 22, 'SMB - 2XLARGE-BLACK', '2026-02-02 03:27:07'),
(126, 30, '1770003932_var_BLACK_A115 0.webp', 250.00, '32', 'BLACK', 2, 'A115 - BLACK - 32', '2026-02-02 03:45:32'),
(127, 30, '1770003932_var_BLACK_A115 0.webp', 250.00, '34', 'BLACK', 6, 'A115 - BLACK - 34', '2026-02-02 03:45:32'),
(128, 30, '1770003932_var_BLACK_A115 0.webp', 250.00, '36', 'BLACK', 4, 'A115 - BLACK - 36', '2026-02-02 03:45:32'),
(129, 30, '1770003932_var_BLACK_A115 0.webp', 260.00, '38', 'BLACK', 2, 'A115 - BLACK - 38', '2026-02-02 03:45:32'),
(130, 30, '1770003932_var_BLACK_A115 0.webp', 270.00, '40', 'BLACK', 1, 'A115 - BLACK - 40', '2026-02-02 03:45:32'),
(131, 30, '1770003932_var_DARKGRAY_A115 0.webp', 250.00, '32', 'DARKGRAY', 2, 'A115 - DARKGRAY  - 32 ', '2026-02-02 03:45:32'),
(132, 30, '1770003932_var_DARKGRAY_A115 0.webp', 250.00, '34', 'DARKGRAY', 2, 'A115 - DARKGRAY  - 34', '2026-02-02 03:45:32'),
(133, 30, '1770003932_var_DARKGRAY_A115 0.webp', 250.00, '36', 'DARKGRAY', 2, 'A115 - DARKGRAY  - 36', '2026-02-02 03:45:32'),
(134, 30, '1770003932_var_DARKGRAY_A115 0.webp', 260.00, '38', 'DARKGRAY', 2, 'A115 - DARKGRAY  - 38', '2026-02-02 03:45:32'),
(135, 30, '1770003932_var_DARKGRAY_A115 0.webp', 270.00, '40', 'DARKGRAY', 2, 'A115 - DARKGRAY  - 40', '2026-02-02 03:45:32'),
(136, 30, '1770003932_var_GRAY_A115 0.webp', 250.00, '32', 'GRAY', 2, 'A115 - GRAY - 32 ', '2026-02-02 03:45:32'),
(137, 30, '1770003932_var_GRAY_A115 0.webp', 250.00, '34', 'GRAY', 3, 'A115 - GRAY - 34', '2026-02-02 03:45:32'),
(138, 30, '1770003932_var_GRAY_A115 0.webp', 250.00, '36', 'GRAY', 0, 'A115 - GRAY - 36', '2026-02-02 03:45:32'),
(139, 30, '1770003932_var_GRAY_A115 0.webp', 260.00, '38', 'GRAY', 3, 'A115 - GRAY - 38', '2026-02-02 03:45:32'),
(140, 30, '1770003932_var_GRAY_A115 0.webp', 270.00, '40', 'GRAY', 2, 'A115 - GRAY - 40', '2026-02-02 03:45:32'),
(141, 30, '1770003932_var_NUDE_A115 0.webp', 250.00, '32', 'NUDE', 2, 'A115 - NUDE- 32', '2026-02-02 03:45:32'),
(142, 30, '1770003932_var_NUDE_A115 0.webp', 250.00, '34', 'NUDE', 0, 'A115 - NUDE- 34', '2026-02-02 03:45:32'),
(143, 30, '1770003932_var_NUDE_A115 0.webp', 250.00, '36', 'NUDE', 2, 'A115 - NUDE- 36', '2026-02-02 03:45:32'),
(144, 30, '1770003932_var_NUDE_A115 0.webp', 260.00, '38', 'NUDE', 2, 'A115 - NUDE- 38', '2026-02-02 03:45:32'),
(145, 30, '1770003932_var_NUDE_A115 0.webp', 270.00, '40', 'NUDE', 3, 'A115 - NUDE- 40', '2026-02-02 03:45:32'),
(146, 31, '1770008270_var_BLACK_A3031 BK.webp', 530.00, '32A', 'BLACK', -1, 'A3031 - BLACK - 32A', '2026-02-02 04:57:50'),
(147, 31, '1770008270_var_BLACK_A3031 BK.webp', 530.00, '34A', 'BLACK', 2, 'A3031 - BLACK - 34A', '2026-02-02 04:57:50'),
(148, 31, '1770008270_var_BLACK_A3031 BK.webp', 530.00, '36A', 'BLACK', 0, 'A3031 - BLACK - 36A', '2026-02-02 04:57:50'),
(149, 31, '1770008270_var_BLACK_A3031 BK.webp', 540.00, '38A', 'BLACK', 4, 'A3031 - BLACK - 38A', '2026-02-02 04:57:50'),
(150, 31, '1770008270_var_BROWN_A3031 B.webp', 530.00, '32A', 'BROWN', 4, 'A3031 - BROWN - 32A', '2026-02-02 04:57:50'),
(151, 31, '1770008270_var_BROWN_A3031 B.webp', 530.00, '34A', 'BROWN', 5, 'A3031 - BROWN - 34A', '2026-02-02 04:57:50'),
(152, 31, '1770008270_var_BROWN_A3031 B.webp', 530.00, '36A', 'BROWN', 3, 'A3031 - BROWN - 36A', '2026-02-02 04:57:50'),
(153, 31, '1770008270_var_BROWN_A3031 B.webp', 540.00, '38A', 'BROWN', 1, 'A3031 - BROWN - 38A', '2026-02-02 04:57:50'),
(154, 31, '1770008270_var_CREAM_A3031 C.webp', 530.00, '32A', 'CREAM', 2, 'A3031 - CREAM - 32A', '2026-02-02 04:57:50'),
(155, 31, '1770008270_var_CREAM_A3031 C.webp', 530.00, '34A', 'CREAM', 2, 'A3031 - CREAM - 34A', '2026-02-02 04:57:50'),
(156, 31, '1770008270_var_CREAM_A3031 C.webp', 530.00, '36A', 'CREAM', 2, 'A3031 - CREAM - 36A', '2026-02-02 04:57:50'),
(157, 31, '1770008270_var_CREAM_A3031 C.webp', 540.00, '38A', 'CREAM', 2, 'A3031 - CREAM - 38A', '2026-02-02 04:57:50'),
(158, 32, '1770008843_var_BLUE_3012 1.webp', 450.00, 'MEDIUM', 'BLUE', 0, '3102-BLUE-MEDIUM', '2026-02-02 05:07:23'),
(159, 32, '1770008843_var_BLUE_3012 1.webp', 450.00, 'LARGE', 'BLUE', 3, '3102-BLUE-LARGE', '2026-02-02 05:07:23'),
(160, 32, '1770008843_var_BLUE_3012 1.webp', 460.00, 'XLARGE', 'BLUE', 4, '3102-BLUE-XLARGE', '2026-02-02 05:07:23'),
(161, 32, '1770008843_var_BLUE_3012 1.webp', 470.00, '2XLARGE', 'BLUE', 2, '3102-BLUE-2XLARGE', '2026-02-02 05:07:23'),
(162, 32, '1770008843_var_ORANGE_3102.webp', 450.00, 'MEDIUM', 'ORANGE', 4, '3102-ORANGE-MEDIUM', '2026-02-02 05:07:23'),
(163, 32, '1770008843_var_ORANGE_3102.webp', 450.00, 'LARGE', 'ORANGE', 0, '3102-ORANGE-LARGE', '2026-02-02 05:07:23'),
(164, 32, '1770008843_var_ORANGE_3102.webp', 460.00, 'XLARGE', 'ORANGE', 1, '3102-ORANGE-XLARGE', '2026-02-02 05:07:23'),
(165, 32, '1770008843_var_ORANGE_3102.webp', 470.00, '2XLARGE', 'ORANGE', 2, '3102-ORANGE-2XLARGE', '2026-02-02 05:07:23'),
(166, 32, '1770008843_var_PINK_3012 1.webp', 450.00, 'MEDIUM', 'PINK', 1, '3102-PINK-MEDIUM', '2026-02-02 05:07:23'),
(167, 32, '1770008843_var_PINK_3012 1.webp', 450.00, 'LARGE', 'PINK', 1, '3102-PINK-LARGE', '2026-02-02 05:07:23'),
(168, 32, '1770008843_var_PINK_3012 1.webp', 460.00, 'XLARGE', 'PINK', 1, '3102-PINK-XLARGE', '2026-02-02 05:07:23'),
(169, 32, '1770008843_var_PINK_3012 1.webp', 470.00, '2XLARGE', 'PINK', 3, '3102-PINK-2XLARGE', '2026-02-02 05:07:23'),
(170, 33, '1770009597_var_BLACK_4301 2.webp', 380.00, 'MEDIUM', 'BLACK', 2, '4301-BLACK-MEDIUM', '2026-02-02 05:19:57'),
(171, 33, '1770009597_var_BLACK_4301 2.webp', 380.00, 'LARGE', 'BLACK', 2, '4301-BLACK-LARGE', '2026-02-02 05:19:57'),
(172, 33, '1770009597_var_BLACK_4301 2.webp', 390.00, 'XLARGE', 'BLACK', 1, '4301-BLACK-XLARGE', '2026-02-02 05:19:57'),
(173, 33, '1770009597_var_BLACK_4301 2.webp', 400.00, '2XLARGE', 'BLACK', 0, '4301-BLACK-2XLARGE', '2026-02-02 05:19:57'),
(174, 33, '1770009597_var_GREEN_4301 G.webp', 380.00, 'MEDIUM', 'GREEN', 2, '4301-GREEN-MEDIUM', '2026-02-02 05:19:57'),
(175, 33, '1770009597_var_GREEN_4301 G.webp', 380.00, 'LARGE', 'GREEN', 2, '4301-GREEN-LARGE', '2026-02-02 05:19:57'),
(176, 33, '1770009597_var_GREEN_4301 G.webp', 390.00, 'XLARGE', 'GREEN', 1, '4301-GREEN-XLARGE', '2026-02-02 05:19:57'),
(177, 33, '1770009597_var_GREEN_4301 G.webp', 400.00, '2XLARGE', 'GREEN', 0, '4301-GREEN-2XLARGE', '2026-02-02 05:19:57'),
(178, 33, '1770009597_var_PEACH PINK_4301 1.webp', 380.00, 'MEDIUM', 'PEACH PINK', 2, '4301-PEACH PINK-MEDIUM', '2026-02-02 05:19:57'),
(179, 33, '1770009597_var_PEACH PINK_4301 1.webp', 380.00, 'LARGE', 'PEACH PINK', 2, '4301-PEACH PINK-LARGE', '2026-02-02 05:19:57'),
(180, 33, '1770009597_var_PEACH PINK_4301 1.webp', 390.00, 'XLARGE', 'PEACH PINK', 3, '4301-PEACH PINK-XLARGE', '2026-02-02 05:19:57'),
(181, 33, '1770009597_var_PEACH PINK_4301 1.webp', 400.00, '2XLARGE', 'PEACH PINK', 1, '4301-PEACH PINK-2XLARGE', '2026-02-02 05:19:57'),
(182, 34, '1770010517_var_BLACK_3102 3.webp', 222.00, 'SMALL', 'BLACK', 22, 'A115 - BLACK - 32', '2026-02-02 05:35:17'),
(183, 34, '1770010517_var_NUDE_4301 1.webp', 33.00, 'SMALL', 'NUDE', 3, 'ASS', '2026-02-02 05:35:17'),
(184, 35, '1770478223_var_BLACK_sando 2.webp', 165.00, 'SMALL', 'BLACK', 50, 'LS69B-BLACK-SMALL', '2026-02-07 15:30:23'),
(185, 35, '1770478223_var_BLACK_sando 2.webp', 165.00, 'MEDIUM', 'BLACK', 50, 'LS69B-BLACK-MEDIUM', '2026-02-07 15:30:23'),
(186, 35, '1770478223_var_BLACK_sando 2.webp', 165.00, 'LARGE', 'BLACK', 60, 'LS69B-BLACK-LARGE', '2026-02-07 15:30:23'),
(187, 35, '1770478223_var_BLACK_sando 2.webp', 170.00, 'XLARGE', 'BLACK', 50, 'LS69B-BLACK-XLARGE', '2026-02-07 15:30:23'),
(188, 35, '1770478223_var_BLACK_sando 2.webp', 175.00, '2XLARGE', 'BLACK', 60, 'LS69B-BLACK-2XLARGE', '2026-02-07 15:30:23'),
(189, 35, '1770478223_var_WHITE_sando 2.webp', 125.00, 'SMALL', 'WHITE', 60, 'LS69W-WHITE-SMALL', '2026-02-07 15:30:23'),
(190, 35, '1770478223_var_WHITE_sando 2.webp', 125.00, 'MEDIUM', 'WHITE', 50, 'LS69W-WHITE-MEDIUM', '2026-02-07 15:30:23'),
(191, 35, '1770478223_var_WHITE_sando 2.webp', 125.00, 'LARGE', 'WHITE', 66, 'LS69W-WHITE-LARGE', '2026-02-07 15:30:23'),
(192, 35, '1770478223_var_WHITE_sando 2.webp', 130.00, 'XLARGE', 'WHITE', 40, 'LS69W-WHITE-XLARGE', '2026-02-07 15:30:23'),
(193, 35, '1770478223_var_WHITE_sando 2.webp', 135.00, '2XLARGE', 'WHITE', 50, 'LS69W-WHITE-2XLARGE', '2026-02-07 15:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `product_variation_images`
--

CREATE TABLE `product_variation_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_images`
--

CREATE TABLE `site_images` (
  `id` int(11) NOT NULL,
  `image_key` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_images`
--

INSERT INTO `site_images` (`id`, `image_key`, `image_path`) VALUES
(1, 'home_main', 'uploads/home/1770704546_holliday.jpg'),
(2, 'shop_main', 'uploads/shop/1770704546_FRONT SHOP.jpg'),
(3, 'cat_1', 'uploads/category/1770704546_UNDERWEAR.jpg'),
(4, 'cat_2', 'uploads/category/1770704546_sando.jpg'),
(5, 'cat_3', 'uploads/category/1770704546_SLEEPWEAR.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('footer_address', '64 J.P Bautista Caloocan, Caloocan, Philippines'),
('footer_email', 'intimateforevergarments@gmail.com'),
('footer_phone', '0939 819 6120'),
('pin_action', '2026'),
('pin_withdraw', '1234'),
('shop_name', 'Intimate Forever');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variation_images`
--
ALTER TABLE `product_variation_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_images`
--
ALTER TABLE `site_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `image_key` (`image_key`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variations`
--
ALTER TABLE `product_variations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `product_variation_images`
--
ALTER TABLE `product_variation_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_images`
--
ALTER TABLE `site_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
