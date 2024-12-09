-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2024 at 02:19 PM
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
-- Database: `pizza2`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteCustomer` (IN `p_account_id` INT)   BEGIN
    IF NOT EXISTS (SELECT 1 FROM account WHERE account_id = p_account_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Account ID does not exist in account table.';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM customer WHERE customer_id = p_account_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Customer with this Account ID does not exist.';
    END IF;

    DELETE FROM customer
    WHERE customer_id = p_account_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCustomersByRating` (IN `p_rating` INT)   BEGIN
    IF p_rating NOT BETWEEN 1 AND 5 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Số sao nhập vào phải trong khoảng từ 1 đến 5';
    END IF;

    SELECT 
        c.customer_id,
        CONCAT(c.customer_first_name, ' ', c.customer_last_name) AS full_name,
        c.phone_number,
        c.email,
        ur.food_id,
        ur.rating,
        ur.review_description
    FROM 
        customer c
    INNER JOIN 
        user_review ur ON c.customer_id = ur.customer_id
    WHERE 
        ur.rating = p_rating;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetFoodsBelowPrice` (IN `p_price` INT)   BEGIN
    IF p_price <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Giá tìm kiếm phải lớn hơn 0.';
    END IF;

    SELECT 
        food_id,
        food_name,
        food_price,
        food_description
    FROM 
        food
    WHERE 
        food_price < p_price;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertCustomer` (IN `p_account_id` INT, IN `p_first_name` VARCHAR(255), IN `p_last_name` VARCHAR(255), IN `p_phone_number` VARCHAR(10), IN `p_email` VARCHAR(255), IN `p_points` INT)   BEGIN
    -- Kiểm tra account_id có tồn tại trong bảng account
    IF NOT EXISTS (SELECT 1 FROM account WHERE account_id = p_account_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Account ID does not exist in account table.';
    END IF;

    -- Kiểm tra account_id đã tồn tại trong bảng customer chưa
    IF EXISTS (SELECT 1 FROM customer WHERE customer_id = p_account_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Customer with this Account ID already exists.';
    END IF;

    -- Kiểm tra số điện thoại hợp lệ
    IF CHAR_LENGTH(p_phone_number) != 10 OR p_phone_number NOT REGEXP '^0[0-9]{9}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid phone number format.';
    END IF;

    -- Kiểm tra email hợp lệ
    IF p_email NOT LIKE '%_@_%._%' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email format.';
    END IF;

    -- Thêm bản ghi vào bảng customer
    INSERT INTO customer (customer_id, customer_first_name, customer_last_name, phone_number, email, points)
    VALUES (p_account_id, p_first_name, p_last_name, p_phone_number, p_email, COALESCE(p_points, 0));
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateCustomer` (IN `p_account_id` INT, IN `p_first_name` VARCHAR(255), IN `p_last_name` VARCHAR(255), IN `p_phone_number` VARCHAR(10), IN `p_email` VARCHAR(255), IN `p_points` INT)   BEGIN
    IF NOT EXISTS (SELECT 1 FROM account WHERE account_id = p_account_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Account ID does not exist in account table.';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM customer WHERE customer_id = p_account_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Customer with this Account ID does not exist.';
    END IF;

    IF CHAR_LENGTH(p_phone_number) != 10 OR p_phone_number NOT REGEXP '^0[0-9]{9}$' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid phone number format.';
    END IF;

    IF p_email NOT LIKE '%_@_%._%' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email format.';
    END IF;

    UPDATE customer
    SET customer_first_name = p_first_name,
        customer_last_name = p_last_name,
        phone_number = p_phone_number,
        email = p_email,
        points = COALESCE(p_points, 0)
    WHERE customer_id = p_account_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(10) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL
) ;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `user_name`, `password`) VALUES
(1, 'nguyenanh', 'MatKhau1'),
(2, 'tranminh', 'MatKhau2'),
(3, 'lethuy', 'MatKhau3'),
(4, 'phamquang', 'MatKhau4'),
(5, 'hoanglan', 'MatKhau5'),
(6, 'minhhoang', 'MatKhau6'),
(7, 'thanhvan', 'MatKhau7'),
(8, 'quanghuy', 'MatKhau8'),
(9, 'huyentrang', 'MatKhau9'),
(10, 'tuananh', 'MatKhau10'),
(11, 'vananh', 'MatKhau11'),
(12, 'nhutdo', 'MatKhau12'),
(13, 'username13', 'Password13');

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
(4, 1, 1, 150000, '2024-12-08'),
(4, 8, 1, 180000, '2025-03-05'),
(4, 9, 1, 200000, '2025-03-05'),
(5, 10, 1, 170000, '2025-04-10'),
(5, 11, 1, 110000, '2025-04-10'),
(6, 12, 1, 160000, '2025-05-01'),
(6, 13, 1, 190000, '2025-05-01'),
(7, 10, 3, 510000, '2024-12-08'),
(7, 14, 1, 140000, '2025-06-15'),
(7, 15, 1, 130000, '2025-06-15');

--
-- Triggers `creat_order`
--
DELIMITER $$
CREATE TRIGGER `after_delete_creat_order` AFTER DELETE ON `creat_order` FOR EACH ROW BEGIN
    DECLARE temp_final INT DEFAULT 0;
    DECLARE discount_type VARCHAR(255);
    DECLARE discount_value INT;
    DECLARE discount_percent INT;

    SELECT SUM(temp_price) INTO temp_final
    FROM creat_order
    WHERE order_id = OLD.order_id;

    IF EXISTS (SELECT 1 FROM `order` WHERE `order_id` = OLD.order_id AND `discount_id` IS NOT NULL) THEN
        SELECT d.discount_id, dc.type_discount, dp.percent, dn.discount_number
        INTO @discount_id, @type_discount, @percent, @discount_number
        FROM `discount` d
        LEFT JOIN `discount_on_percent` dp ON d.discount_id = dp.discount_id
        LEFT JOIN `discount_on_number` dn ON d.discount_id = dn.discount_id
        LEFT JOIN `customer_discounts` dc ON d.discount_id = dc.discount_id
        WHERE d.discount_id = (SELECT discount_id FROM `order` WHERE `order_id` = OLD.order_id);

        IF @type_discount = 'percent' THEN
            SET temp_final = temp_final - (temp_final * @percent / 100);
        ELSEIF @type_discount = 'number' THEN
            SET temp_final = temp_final - @discount_number;
        END IF;
    END IF;

    IF temp_final < 0 THEN
        SET temp_final = 1;
    END IF;

    UPDATE `order`
    SET final_price = temp_final
    WHERE order_id = OLD.order_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_creat_order` AFTER INSERT ON `creat_order` FOR EACH ROW BEGIN
    DECLARE temp_final INT DEFAULT 0;
    DECLARE discount_type VARCHAR(255);
    DECLARE discount_value INT;
    DECLARE discount_percent INT;
    
    IF NOT EXISTS (SELECT 1 FROM `order` WHERE `order_id` = NEW.order_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: order_id does not exist in `order` table';
    END IF;

    SELECT SUM(temp_price) INTO temp_final
    FROM creat_order
    WHERE order_id = NEW.order_id;

    IF EXISTS (SELECT 1 FROM `order` WHERE `order_id` = NEW.order_id AND `discount_id` IS NOT NULL) THEN
        SELECT d.discount_id, dc.type_discount, dp.percent, dn.discount_number
        INTO @discount_id, @type_discount, @percent, @discount_number
        FROM `discount` d
        LEFT JOIN `discount_on_percent` dp ON d.discount_id = dp.discount_id
        LEFT JOIN `discount_on_number` dn ON d.discount_id = dn.discount_id
        LEFT JOIN `customer_discounts` dc ON d.discount_id = dc.discount_id
        WHERE d.discount_id = (SELECT discount_id FROM `order` WHERE `order_id` = NEW.order_id);

        IF @type_discount = 'percent' THEN
            SET temp_final = temp_final - (temp_final * @percent / 100);
        ELSEIF @type_discount = 'number' THEN
            SET temp_final = temp_final - @discount_number;
        END IF;
    END IF;

    IF temp_final < 0 THEN
        SET temp_final = 1;
    END IF;

    UPDATE `order`
    SET final_price = temp_final
    WHERE order_id = NEW.order_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_creat_order` AFTER UPDATE ON `creat_order` FOR EACH ROW BEGIN
    DECLARE temp_final INT DEFAULT 0;
    DECLARE discount_type VARCHAR(255);
    DECLARE discount_value INT;
    DECLARE discount_percent INT;

    SELECT SUM(temp_price) INTO temp_final
    FROM creat_order
    WHERE order_id = NEW.order_id;

    IF EXISTS (SELECT 1 FROM `order` WHERE `order_id` = NEW.order_id AND `discount_id` IS NOT NULL) THEN
        SELECT d.discount_id, dc.type_discount, dp.percent, dn.discount_number
        INTO @discount_id, @type_discount, @percent, @discount_number
        FROM `discount` d
        LEFT JOIN `discount_on_percent` dp ON d.discount_id = dp.discount_id
        LEFT JOIN `discount_on_number` dn ON d.discount_id = dn.discount_id
        LEFT JOIN `customer_discounts` dc ON d.discount_id = dc.discount_id
        WHERE d.discount_id = (SELECT discount_id FROM `order` WHERE `order_id` = NEW.order_id);

        IF @type_discount = 'percent' THEN
            SET temp_final = temp_final - (temp_final * @percent / 100);
        ELSEIF @type_discount = 'number' THEN
            SET temp_final = temp_final - @discount_number;
        END IF;
    END IF;

    IF temp_final < 0 THEN
        SET temp_final = 1;
    END IF;

    UPDATE `order`
    SET final_price = temp_final
    WHERE order_id = NEW.order_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_creat_order` BEFORE INSERT ON `creat_order` FOR EACH ROW BEGIN
    DECLARE item_price INT;

    IF NOT EXISTS (SELECT 1 FROM `order` WHERE `order_id` = NEW.order_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: order_id does not exist in `order` table';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM `food` WHERE `food_id` = NEW.food_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: food_id does not exist in `food` table';
    END IF;

    SELECT food_price INTO item_price
    FROM food
    WHERE food_id = NEW.food_id;

    SET NEW.temp_price = NEW.quantity * item_price;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_creat_order` BEFORE UPDATE ON `creat_order` FOR EACH ROW BEGIN
    DECLARE item_price INT;

    IF NOT EXISTS (SELECT 1 FROM `order` WHERE `order_id` = NEW.order_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: order_id does not exist in `order` table';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM `food` WHERE `food_id` = NEW.food_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: food_id does not exist in `food` table';
    END IF;

    SELECT food_price INTO item_price
    FROM food
    WHERE food_id = NEW.food_id;

    SET NEW.temp_price = NEW.quantity * item_price;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(10) NOT NULL,
  `customer_first_name` varchar(255) NOT NULL,
  `customer_last_name` varchar(255) NOT NULL,
  `phone_number` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `points` int(10) DEFAULT NULL
) ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_first_name`, `customer_last_name`, `phone_number`, `email`, `points`) VALUES
(3, 'John', 'Doe', '1234567890', 'john.doe@example.com', 100),
(4, 'Jane', 'Smith', '2345678901', 'jane.smith@example.com', 200),
(5, 'Alice', 'Johnson', '3456789012', 'alice.johnson@example.com', 50),
(6, 'Bob', 'Brown', '4567890123', 'bob.brown@example.com', 10),
(7, 'Charlie', 'Wilson', '5678901234', 'charlie.wilson@example.com', 150),
(8, 'Diana', 'Moore', '6789012345', 'diana.moore@example.com', 75),
(9, 'Ethan', 'Taylor', '7890123456', 'ethan.taylor@example.com', 300),
(10, 'Fiona', 'Anderson', '8901234567', 'fiona.anderson@example.com', 20),
(11, 'George', 'Thomas', '9012345678', 'george.thomas@example.com', 10),
(12, 'Hannah', 'Jackson', '1023456789', 'hannah.jackson@example.com', 5);

-- --------------------------------------------------------

--
-- Table structure for table `customer_discounts`
--

CREATE TABLE `customer_discounts` (
  `customer_id` int(10) NOT NULL,
  `discount_id` int(10) NOT NULL,
  `type_discount` varchar(255) NOT NULL,
  `expired_date` date DEFAULT NULL
) ;

--
-- Dumping data for table `customer_discounts`
--

INSERT INTO `customer_discounts` (`customer_id`, `discount_id`, `type_discount`, `expired_date`) VALUES
(3, 1, 'percent', '2024-12-31'),
(4, 2, 'number', '2025-01-15'),
(5, 3, 'percent', '2025-01-15'),
(7, 4, 'number', '2025-01-15'),
(8, 1, 'percent', '2024-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `discount_id` int(10) NOT NULL,
  `discount_name` varchar(255) DEFAULT NULL,
  `restaurant_id` int(10) NOT NULL,
  `status_use` int(1) NOT NULL
) ;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`discount_id`, `discount_name`, `restaurant_id`, `status_use`) VALUES
(1, 'XMAS2024', 1, 0),
(2, 'SUMMER2024', 2, 1),
(3, 'STUDENTDISCOUNT', 2, 0),
(4, 'NEWCUSTOMER', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `discount_creator`
--

CREATE TABLE `discount_creator` (
  `restaurant_id` int(10) NOT NULL,
  `discount_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `discount_creator`
--

INSERT INTO `discount_creator` (`restaurant_id`, `discount_id`) VALUES
(1, 1),
(1, 4),
(2, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `discount_on_number`
--

CREATE TABLE `discount_on_number` (
  `discount_id` int(10) NOT NULL,
  `discount_number` int(10) NOT NULL
) ;

--
-- Dumping data for table `discount_on_number`
--

INSERT INTO `discount_on_number` (`discount_id`, `discount_number`) VALUES
(1, 50000),
(2, 30000),
(3, 20000),
(4, 40000);

-- --------------------------------------------------------

--
-- Table structure for table `discount_on_percent`
--

CREATE TABLE `discount_on_percent` (
  `discount_id` int(10) NOT NULL,
  `percent` int(2) NOT NULL
) ;

--
-- Dumping data for table `discount_on_percent`
--

INSERT INTO `discount_on_percent` (`discount_id`, `percent`) VALUES
(1, 20),
(2, 15),
(3, 10),
(4, 25);

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `food_id` int(10) NOT NULL,
  `food_name` varchar(255) NOT NULL,
  `food_price` int(10) NOT NULL,
  `food_description` varchar(255) NOT NULL
) ;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`food_id`, `food_name`, `food_price`, `food_description`) VALUES
(1, 'Pizza Hải Sản', 150000, 'Pizza với tôm, mực và cá hồi tươi ngon.'),
(2, 'Mì Ý Sốt Bolognese', 120000, 'Mì Ý truyền thống với sốt thịt bò và gia vị đặc trưng.'),
(3, 'Salad Trái Cây', 80000, 'Salad tươi ngon với nhiều loại trái cây mùa hè.'),
(4, 'Sushi Cá Hồi', 200000, 'Sushi tươi với cá hồi và cơm dẻo, cuộn với rong biển.'),
(5, 'Bánh Mì Thịt Nướng', 50000, 'Bánh mì giòn với thịt nướng và rau sống tươi mát.'),
(6, 'Pizza Margherita', 120000, 'Pizza truyền thống với sốt cà chua, phô mai mozzarella và lá húng quế.'),
(7, 'Pizza Pepperoni', 150000, 'Pizza với lớp xúc xích pepperoni thơm ngon, phô mai và sốt cà chua.'),
(8, 'Pizza Hải Sản đặt biệt', 180000, 'Pizza đặc biệt với tôm, mực và cá hồi tươi ngon, phủ phô mai.'),
(9, 'Pizza Thập Cẩm', 200000, 'Pizza với đầy đủ các loại nhân như thịt, xúc xích, nấm và rau củ.'),
(10, 'Pizza Trứng Muối', 170000, 'Pizza sáng tạo với trứng muối, phô mai và rau củ tươi mát.'),
(11, 'Pizza Chay', 110000, 'Pizza dành cho người ăn chay với rau củ tươi ngon và sốt cà chua tự nhiên.'),
(12, 'Pizza BBQ Gà', 160000, 'Pizza với gà nướng và sốt BBQ thơm lừng, kèm theo hành tây và ớt chuông.'),
(13, 'Pizza Quattro Stagioni', 190000, 'Pizza bốn mùa với các loại nhân khác nhau: nấm, giăm bông, ô liu và artichoke.'),
(14, 'Pizza Phô Mai Đặc Biệt', 140000, 'Pizza với nhiều loại phô mai khác nhau, dành cho tín đồ phô mai.'),
(15, 'Pizza Trái Cây', 130000, 'Pizza ngọt với trái cây tươi như dứa, kiwi và dâu tây, phù hợp cho bữa tráng miệng.');

-- --------------------------------------------------------

--
-- Table structure for table `food_image`
--

CREATE TABLE `food_image` (
  `food_image_url` varchar(255) NOT NULL,
  `food_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `food_image`
--

INSERT INTO `food_image` (`food_image_url`, `food_id`) VALUES
('https://images.pexels.com/photos/27583268/pexels-photo-27583268/free-photo-of-a-pizza-with-a-slice-missing-from-it-on-a-wooden-board.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load.jpg', 1),
('https://images.pexels.com/photos/14737/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=600.jpg', 2),
('https://images.pexels.com/photos/1437270/pexels-photo-1437270.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1.jpg', 3),
('https://images.pexels.com/photos/2098085/pexels-photo-2098085.jpeg?auto=compress&cs=tinysrgb&w=600.jpg', 4),
('https://images.pexels.com/photos/9101561/pexels-photo-9101561.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load.jpg', 5),
('https://images.pexels.com/photos/5175513/pexels-photo-5175513.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load.jpg', 6),
('https://images.pexels.com/photos/1435903/pexels-photo-1435903.jpeg?auto=compress&cs=tinysrgb&w=600.jpg', 7),
('https://www.pexels.com/photo/a-colorful-sliced-pizza-2909822/.jpg', 8),
('https://images.pexels.com/photos/19786231/pexels-photo-19786231/free-photo-of-carrying-a-cutting-board-with-slices-of-hot-dog-pizza.jpeg?auto=compress&cs=tinysrgb&w=41', 9),
('https://images.pexels.com/photos/162744/tomatoes-tomato-quiche-red-yellow-162744.jpeg?auto=compress&cs=tinysrgb&w=600.jpg', 10),
('https://images.pexels.com/photos/5640015/pexels-photo-5640015.jpeg?auto=compress&cs=tinysrgb&w=600.png', 11),
('https://images.pexels.com/photos/5639547/pexels-photo-5639547.jpeg?auto=compress&cs=tinysrgb&w=600.jpg', 12),
('https://images.pexels.com/photos/28308002/pexels-photo-28308002/free-photo-of-a-pizza-with-greens-and-other-toppings-on-a-table.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load.jpg', 13),
('https://images.pexels.com/photos/774487/pexels-photo-774487.jpeg?auto=compress&cs=tinysrgb&w=400.jpg', 14),
('https://images.pexels.com/photos/578009/pexels-photo-578009.jpeg?auto=compress&cs=tinysrgb&w=400.jpg', 15);

-- --------------------------------------------------------

--
-- Table structure for table `manage_food`
--

CREATE TABLE `manage_food` (
  `restaurant_id` int(10) NOT NULL,
  `food_id` int(10) NOT NULL,
  `price_sell` int(10) DEFAULT NULL
) ;

--
-- Dumping data for table `manage_food`
--

INSERT INTO `manage_food` (`restaurant_id`, `food_id`, `price_sell`) VALUES
(1, 1, 160000),
(1, 2, 140000),
(1, 4, 240000),
(1, 5, 60000),
(1, 6, 140000),
(1, 7, 180000),
(1, 8, 190000),
(1, 9, 130000),
(1, 10, 180000),
(1, 11, 120000),
(1, 13, 210000),
(2, 1, 170000),
(2, 2, 110000),
(2, 3, 90000),
(2, 4, 230000),
(2, 5, 70000),
(2, 6, 120000),
(2, 8, 200000),
(2, 9, 210000),
(2, 12, 170000);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(10) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `order_status` int(1) NOT NULL,
  `bill_id` int(10) NOT NULL,
  `discount_id` int(10) DEFAULT NULL,
  `address_delivery` varchar(255) NOT NULL DEFAULT 'N/A',
  `final_price` int(10) NOT NULL DEFAULT 0,
  `order_date` date NOT NULL
) ;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `customer_id`, `order_status`, `bill_id`, `discount_id`, `address_delivery`, `final_price`, `order_date`) VALUES
(1, 3, 0, 1, NULL, '123 Main St, Anytown USA', 100000, '2023-05-01'),
(2, 4, 1, 2, NULL, '456 Oak Rd, Othertown USA', 150000, '2023-06-15'),
(3, 5, 0, 3, NULL, '789 Elm St, Someplace USA', 80000, '2023-07-20'),
(4, 6, 1, 4, NULL, '321 Maple Ave, Nowhere USA', 530000, '2023-08-05'),
(5, 7, 2, 5, NULL, '654 Pine Rd, Everytown USA', 120000, '2023-09-10'),
(6, 8, 0, 6, NULL, '987 Birch Blvd, Anotherton USA', 90000, '2023-10-01'),
(7, 9, 1, 7, NULL, '159 Oak St, Somewhereville USA', 780000, '2023-11-15'),
(8, 10, 0, 8, NULL, '753 Elm Ave, Nowhere USA', 70000, '2023-12-01'),
(9, 11, 2, 9, NULL, '258 Maple Dr, Everytown USA', 150000, '2024-01-05'),
(10, 12, 0, 10, NULL, '456 Pine St, Anotherton USA', 110000, '2024-02-10');

-- --------------------------------------------------------

--
-- Table structure for table `payment_order`
--

CREATE TABLE `payment_order` (
  `bill_id` int(10) NOT NULL,
  `payment_status` int(1) NOT NULL
) ;

--
-- Dumping data for table `payment_order`
--

INSERT INTO `payment_order` (`bill_id`, `payment_status`) VALUES
(1, 0),
(2, 1),
(3, 0),
(4, 1),
(5, 1),
(6, 0),
(7, 1),
(8, 0),
(9, 1),
(10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `receiver`
--

CREATE TABLE `receiver` (
  `receiver_name` varchar(255) NOT NULL,
  `receiver_phone` int(10) NOT NULL,
  `receiver_address` varchar(255) NOT NULL,
  `order_id` int(10) NOT NULL
) ;

--
-- Dumping data for table `receiver`
--

INSERT INTO `receiver` (`receiver_name`, `receiver_phone`, `receiver_address`, `order_id`) VALUES
('Lê Hoàng Công', 2147483647, '789 Nguyễn Trãi, Quận 1, TP.HCM', 3),
('Ngô Thanh Thảo', 2147483647, '987 Phan Đình Phùng, Quận Phú Nhuận, TP.HCM', 6),
('Nguyễn Văn Minh', 2147483647, '123 Nguyễn Văn Cừ, Quận 5, TP.HCM', 1),
('Phạm Minh Tiến', 2147483647, '321 Trần Hưng Đạo, Quận 5, TP.HCM', 4),
('Trần Thị Lan', 1234567890, '456 Lê Văn Sỹ, Quận 3, TP.HCM', 2),
('Võ Thị Chi', 1597539514, '654 Lê Đại Hành, Quận 11, TP.HCM', 5);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `restaurant_id` int(10) NOT NULL,
  `restaurant_name` varchar(255) NOT NULL,
  `restaurant_address` varchar(255) NOT NULL,
  `restaurant_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`restaurant_id`, `restaurant_name`, `restaurant_address`, `restaurant_description`) VALUES
(1, 'Nhà Hàng Hải Sản Tươi Sống', '123 Đường Biển, Quận 1, TP.HCM', 'Chuyên phục vụ hải sản tươi sống và món ăn đặc sản.'),
(2, 'Pizza Italia', '456 Đường Pizzaiolo, Quận 3, TP.HCM', 'Pizza kiểu Ý với nguyên liệu nhập khẩu, hương vị đích thực.');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_image`
--

CREATE TABLE `restaurant_image` (
  `restaurant_image_url` varchar(255) NOT NULL,
  `restaurant_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `restaurant_image`
--

INSERT INTO `restaurant_image` (`restaurant_image_url`, `restaurant_id`) VALUES
('https://www.shutterstock.com/image-illustration/front-view-pizza-shop-restaurant-design-modern-1575914101.jpg', 1),
('https://www.shutterstock.com/image-vector/italian-pizza-ingredients-food-menu-design-2086137538.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_employee`
--

CREATE TABLE `shipping_employee` (
  `employee_id` int(10) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `employee_phone` int(10) NOT NULL,
  `order_id` int(10) NOT NULL
) ;

--
-- Dumping data for table `shipping_employee`
--

INSERT INTO `shipping_employee` (`employee_id`, `employee_name`, `employee_phone`, `order_id`) VALUES
(1, 'Nguyễn Văn Hiền', 2147483647, 1),
(2, 'Trần Thị Hoa', 2147483647, 2),
(3, 'Lê Hoàng Công', 2147483647, 3),
(4, 'Phạm Minh Danh', 2147483647, 4),
(5, 'Võ Thị Ngân', 1597539514, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_review`
--

CREATE TABLE `user_review` (
  `user_review_id` int(10) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `food_id` int(10) NOT NULL,
  `rating` char(1) NOT NULL,
  `review_description` varchar(255) DEFAULT NULL
) ;

--
-- Dumping data for table `user_review`
--

INSERT INTO `user_review` (`user_review_id`, `customer_id`, `food_id`, `rating`, `review_description`) VALUES
(1, 3, 1, '5', 'Pizza hải sản rất ngon, đầy đủ các loại hải sản tươi ngon.'),
(2, 6, 2, '4', 'Mì Ý Sốt Bolognese rất hấp dẫn, ăn cực kỳ thích.'),
(3, 3, 3, '3', 'Salad trái cây tươi mát, nhưng một chút ngọt nhạt.'),
(4, 6, 4, '5', 'Sushi cá hồi tuyệt vời, tươi ngon và cách trình bày rất đẹp.'),
(5, 5, 5, '4', 'Bánh mì thịt nướng rất giòn và đầy vị.'),
(6, 6, 6, '5', 'Pizza Margherita ngon như ở Italia, cực kỳ hài lòng.'),
(7, 5, 7, '4', 'Pizza Pepperoni rất thơm ngon, sốt cà chua và phô mai ăn rất hài hòa.'),
(8, 3, 8, '5', 'Pizza hải sản đặc biệt quá tuyệt, nhiều hải sản và phô mai rất ngon.'),
(9, 8, 9, '4', 'Pizza Thập Cẩm đủ các loại nhân, rất đa dạng và ngon.'),
(10, 5, 10, '3', 'Pizza trứng muối khá ngon, nhưng một chút quá mặn.'),
(11, 7, 11, '4', 'Pizza chay rất tươi ngon, sốt cà chua cũng rất hấp dẫn.'),
(12, 6, 12, '5', 'Pizza BBQ gà rất thơm ngon, vị cay nồng của ớt và hành tây rất hài hòa.'),
(13, 9, 13, '4', 'Pizza Quattro Stagioni rất đa dạng nhân, mỗi loại đều ngon.'),
(14, 4, 14, '5', 'Pizza phô mai đặc biệt quá tuyệt, các loại phô mai hòa quyện rất ngon.'),
(15, 11, 15, '4', 'Pizza trái cây khá ngon, vị ngọt của trái cây rất hợp khẩu vị.'),
(16, 6, 1, '5', 'Amazing Pizza!'),
(17, 4, 2, '3', 'So so');

--
-- Triggers `user_review`
--
DELIMITER $$
CREATE TRIGGER `update_customer_points` AFTER INSERT ON `user_review` FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM customer WHERE customer_id = NEW.customer_id) THEN
        IF EXISTS (SELECT 1 FROM food WHERE food_id = NEW.food_id) THEN
            IF NEW.rating = 5 THEN
                UPDATE customer
                SET points = points + 10
                WHERE customer_id = NEW.customer_id;
            END IF;
        END IF;
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `creat_order`
--
ALTER TABLE `creat_order`
  ADD PRIMARY KEY (`order_id`,`food_id`),
  ADD KEY `fk_createbill_food` (`food_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customer_discounts`
--
ALTER TABLE `customer_discounts`
  ADD PRIMARY KEY (`customer_id`,`discount_id`),
  ADD KEY `fk_customerdiscount_discount` (`discount_id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`discount_id`),
  ADD KEY `fk_discount_restaurant` (`restaurant_id`);

--
-- Indexes for table `discount_creator`
--
ALTER TABLE `discount_creator`
  ADD PRIMARY KEY (`restaurant_id`,`discount_id`),
  ADD KEY `fk_discountcreator_discount` (`discount_id`);

--
-- Indexes for table `discount_on_number`
--
ALTER TABLE `discount_on_number`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `discount_on_percent`
--
ALTER TABLE `discount_on_percent`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `food_image`
--
ALTER TABLE `food_image`
  ADD PRIMARY KEY (`food_id`,`food_image_url`);

--
-- Indexes for table `manage_food`
--
ALTER TABLE `manage_food`
  ADD PRIMARY KEY (`restaurant_id`,`food_id`),
  ADD KEY `fk_managefood_food` (`food_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_order_customer` (`customer_id`),
  ADD KEY `fk_order_bill` (`bill_id`),
  ADD KEY `fk_createbill_discount` (`discount_id`);

--
-- Indexes for table `payment_order`
--
ALTER TABLE `payment_order`
  ADD PRIMARY KEY (`bill_id`);

--
-- Indexes for table `receiver`
--
ALTER TABLE `receiver`
  ADD PRIMARY KEY (`receiver_name`,`order_id`),
  ADD KEY `fk_receiver_order` (`order_id`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`restaurant_id`);

--
-- Indexes for table `restaurant_image`
--
ALTER TABLE `restaurant_image`
  ADD PRIMARY KEY (`restaurant_id`,`restaurant_image_url`);

--
-- Indexes for table `shipping_employee`
--
ALTER TABLE `shipping_employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `fk_shippingemployee_order` (`order_id`);

--
-- Indexes for table `user_review`
--
ALTER TABLE `user_review`
  ADD PRIMARY KEY (`user_review_id`),
  ADD KEY `fk_userreview_customer` (`customer_id`),
  ADD KEY `fk_userreview_food` (`food_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `discount_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `food_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_order`
--
ALTER TABLE `payment_order`
  MODIFY `bill_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_employee`
--
ALTER TABLE `shipping_employee`
  MODIFY `employee_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_review`
--
ALTER TABLE `user_review`
  MODIFY `user_review_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `creat_order`
--
ALTER TABLE `creat_order`
  ADD CONSTRAINT `fk_createbill_food` FOREIGN KEY (`food_id`) REFERENCES `food` (`food_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_createbill_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_customer_account` FOREIGN KEY (`customer_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_discounts`
--
ALTER TABLE `customer_discounts`
  ADD CONSTRAINT `fk_customerdiscount_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_customerdiscount_discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`discount_id`) ON DELETE CASCADE;

--
-- Constraints for table `discount`
--
ALTER TABLE `discount`
  ADD CONSTRAINT `fk_discount_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`) ON DELETE CASCADE;

--
-- Constraints for table `discount_creator`
--
ALTER TABLE `discount_creator`
  ADD CONSTRAINT `fk_discountcreator_discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`discount_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_discountcreator_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`) ON DELETE CASCADE;

--
-- Constraints for table `discount_on_number`
--
ALTER TABLE `discount_on_number`
  ADD CONSTRAINT `fk_discountnumber_discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`discount_id`) ON DELETE CASCADE;

--
-- Constraints for table `discount_on_percent`
--
ALTER TABLE `discount_on_percent`
  ADD CONSTRAINT `fk_discountpercent_discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`discount_id`) ON DELETE CASCADE;

--
-- Constraints for table `food_image`
--
ALTER TABLE `food_image`
  ADD CONSTRAINT `fk_foodimage_food` FOREIGN KEY (`food_id`) REFERENCES `food` (`food_id`) ON DELETE CASCADE;

--
-- Constraints for table `manage_food`
--
ALTER TABLE `manage_food`
  ADD CONSTRAINT `fk_managefood_food` FOREIGN KEY (`food_id`) REFERENCES `food` (`food_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_managefood_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_createbill_discount` FOREIGN KEY (`discount_id`) REFERENCES `discount` (`discount_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_bill` FOREIGN KEY (`bill_id`) REFERENCES `payment_order` (`bill_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `receiver`
--
ALTER TABLE `receiver`
  ADD CONSTRAINT `fk_receiver_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD CONSTRAINT `fk_restaurant_account` FOREIGN KEY (`restaurant_id`) REFERENCES `account` (`account_id`) ON DELETE CASCADE;

--
-- Constraints for table `restaurant_image`
--
ALTER TABLE `restaurant_image`
  ADD CONSTRAINT `fk_restaurantimage_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant` (`restaurant_id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_employee`
--
ALTER TABLE `shipping_employee`
  ADD CONSTRAINT `fk_shippingemployee_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_review`
--
ALTER TABLE `user_review`
  ADD CONSTRAINT `fk_userreview_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_userreview_food` FOREIGN KEY (`food_id`) REFERENCES `food` (`food_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
