-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 02:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pizza`
--

-- --------------------------------------------------------

--
-- Table structure for table `creat_order`
--

CREATE TABLE `creat_order` (
  `order_id` int(10) NOT NULL,
  `food_id` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `temp_price` int(10) NOT NULL,
  `creat_date` date NOT NULL
) ;

--
-- Dumping data for table `creat_order`
--

INSERT INTO `creat_order` (`order_id`, `food_id`, `quantity`, `temp_price`, `creat_date`) VALUES
(1, 1, 2, 300000, '2025-01-01'),
(1, 2, 1, 120000, '2025-01-01'),
(1, 3, 1, 80000, '2025-01-01'),
(2, 4, 1, 200000, '2025-01-15'),
(2, 5, 1, 50000, '2025-01-15'),
(3, 6, 1, 120000, '2025-02-20'),
(3, 7, 1, 150000, '2025-02-20'),
(4, 8, 1, 180000, '2025-03-05'),
(4, 9, 1, 200000, '2025-03-05'),
(5, 10, 1, 170000, '2025-04-10'),
(5, 11, 1, 110000, '2025-04-10'),
(6, 12, 1, 160000, '2025-05-01'),
(6, 13, 1, 190000, '2025-05-01'),
(7, 14, 1, 140000, '2025-06-15'),
(7, 15, 1, 130000, '2025-06-15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `creat_order`
--
ALTER TABLE `creat_order`
  ADD PRIMARY KEY (`order_id`,`food_id`),
  ADD KEY `fk_createbill_food` (`food_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `creat_order`
--
ALTER TABLE `creat_order`
  ADD CONSTRAINT `fk_createbill_food` FOREIGN KEY (`food_id`) REFERENCES `food` (`food_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_createbill_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
