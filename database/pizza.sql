-- phpMyAdmin SQL Dump
-- Host: localhost

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Creat database: `pizza`
CREATE DATABASE IF NOT EXISTS `pizza` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `pizza`;

-- Create tables in database

-- Table structure for table `tai khoan`
CREATE TABLE `account` (
    `account_id` int(10) NOT NULL AUTO_INCREMENT,
    `user_name` varchar(255) NOT NULL,
    `password`varchar(32) NOT NULL,
    PRIMARY KEY (`account_id`)
);
-- ! Rang buoc ve do dai cua password phai lon hon hoac bang 8, gom ca chu va so
ALTER TABLE `account`
    ADD CONSTRAINT check_password CHECK (LENGTH(password) >= 8);

-- Table structure for table `nha hang` 
CREATE TABLE `restaurant` (
    `restaurant_id` int(10) NOT NULL,
    `restaurant_name` varchar(255) NOT NULL,
    `restaurant_address` varchar(255) NOT NULL,
    `restaurant_description` varchar(255),
    CONSTRAINT fk_restaurant_account FOREIGN KEY (`restaurant_id`) REFERENCES `account`(`account_id`) ON DELETE CASCADE,
    PRIMARY KEY (`restaurant_id`)
);

-- Table structure for table `anh nha hang`
CREATE TABLE `restaurant_image` (
    `restaurant_image_url` varchar(255) NOT NULL,
    `restaurant_id` int(10) NOT NULL,
    CONSTRAINT fk_restaurantimage_restaurant FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant`(`restaurant_id`) ON DELETE CASCADE,
    PRIMARY KEY (`restaurant_id`, `restaurant_image_url`)
);

-- Table structure for table `khach hang`
CREATE TABLE `customer` (
    `customer_id` int(10) NOT NULL,
    `customer_first_name` varchar(255) NOT NULL,
    `customer_last_name` varchar(255) NOT NULL,
    `phone_number` int(10) NOT NULL,
    `email` varchar(255) NOT NULL,
    `points` int(10),
    CONSTRAINT fk_customer_account FOREIGN KEY (`customer_id`) REFERENCES `account`(`account_id`) ON DELETE CASCADE,
    PRIMARY KEY (`customer_id`) 
);
-- ! Rang buoc ve diem tich luy cua khach hang phai lon hon hoac bang 0
ALTER TABLE `customer`
    ADD CONSTRAINT check_point CHECK (points >= 0);
-- ! Rang buoc ve so dien thoai cua khach hang phai dung dinh dang
ALTER TABLE `customer`
    ADD CONSTRAINT check_valid_phonenumber CHECK (LENGTH(phone_number) = 10);
-- ! Rang buoc ve email cua khach hang phai dung dinh dang
ALTER TABLE `customer`
    ADD CONSTRAINT check_valid_email CHECK (email LIKE '%_@_%._%');

-- Table structure for table `mon an`
CREATE TABLE `food` (
    `food_id` int(10) NOT NULL AUTO_INCREMENT,
    `food_name` varchar(255) NOT NULL,
    `food_price` int(10) NOT NULL,
    `food_description` varchar(255) NOT NULL,
    PRIMARY KEY (`food_id`)
);
-- ! Rang buoc ve gia cua mon an phai lon hon 0
ALTER TABLE `food`
    ADD CONSTRAINT check_foodprice CHECK (food_price > 0);

-- Table structure for table `anh mon an`
CREATE TABLE `food_image` (
    `food_image_url` varchar(255) NOT NULL,
    `food_id` int (10) NOT NULL,
    CONSTRAINT fk_foodimage_food FOREIGN KEY (`food_id`) REFERENCES `food`(`food_id`) ON DELETE CASCADE,
    PRIMARY KEY (`food_id`, `food_image_url`)
);

-- Table structure for table `quan ly mon an`
CREATE TABLE `manage_food` (
    `restaurant_id` int(10) NOT NULL,
    `food_id` int(10) NOT NULL,
    `price_sell` int(10),
    CONSTRAINT fk_managefood_restaurant FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant`(`restaurant_id`) ON DELETE CASCADE,
    CONSTRAINT fk_managefood_food FOREIGN KEY (`food_id`) REFERENCES `food`(`food_id`) ON DELETE CASCADE,
    PRIMARY KEY (`restaurant_id`,`food_id`) 
);
-- ! Rang buoc ve gia ban cua mon an phai lon hon 0
ALTER TABLE `manage_food`
    ADD CONSTRAINT check_pricesell CHECK (price_sell > 0);

-- Table structure for table `danh gia mon an`
CREATE TABLE `user_review` (
    `user_review_id` int(10) NOT NULL AUTO_INCREMENT,
    `customer_id` int(10) NOT NULL,
    `food_id` int(10) NOT NULL,
    `rating` char(1) NOT NULL,
    `review_description` varchar(255),
    CONSTRAINT fk_userreview_customer FOREIGN KEY (`customer_id`) REFERENCES `customer`(`customer_id`) ON DELETE CASCADE,
    CONSTRAINT fk_userreview_food FOREIGN KEY (`food_id`) REFERENCES `food`(`food_id`) ON DELETE CASCADE,
    PRIMARY KEY (`user_review_id`)
);
-- ! Rang buoc rating cua danh gia mon an phai la 1, 2, 3, 4 hoac 5 sao
ALTER TABLE `user_review`
    ADD CONSTRAINT check_rating CHECK (rating = 1 OR rating = 2 OR rating = 3 OR rating = 4 OR rating = 5);

-- Table structure for table `ma giam gia`
CREATE TABLE `discount` (
    `discount_id` int(10) NOT NULL AUTO_INCREMENT,
    `discount_name` varchar(255),
    `restaurant_id` int(10) NOT NULL,
    `status_use` int(1) NOT NULL, -- ! 0: not used, 1: used
    CONSTRAINT fk_discount_restaurant FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant`(`restaurant_id`) ON DELETE CASCADE,
    PRIMARY KEY (`discount_id`)
);
-- ! Rang buoc ve trang thai cua ma giam gia phai la 0 hoac 1
ALTER TABLE `discount`
    ADD CONSTRAINT check_statususe CHECK (status_use = 0 OR status_use = 1);

-- Table structure for table `ma giam gia theo phan tram`
CREATE TABLE `discount_on_percent` (
    `discount_id` int(10) NOT NULL,
    `percent` int(2) NOT NULL,
    CONSTRAINT fk_discountpercent_discount FOREIGN KEY (`discount_id`) REFERENCES `discount`(`discount_id`) ON DELETE CASCADE,
    PRIMARY KEY (`discount_id`)
);
-- ! Rang buoc muc giam gia toi da cua ma giam gia theo phan tram la 40%
ALTER TABLE `discount_on_percent`
    ADD CONSTRAINT check_percent CHECK (percent >= 0 AND percent <= 40);

-- Table structure for table `ma giam gia theo so tien`
CREATE TABLE `discount_on_number` (
    `discount_id` int(10) NOT NULL,
    `discount_number` int(10) NOT NULL,
    CONSTRAINT fk_discountnumber_discount FOREIGN KEY (`discount_id`) REFERENCES `discount`(`discount_id`) ON DELETE CASCADE,
    PRIMARY KEY (`discount_id`)
);
-- ! Rang buoc gia tri giam gia toi thieu cua ma giam gia theo so tien lon hon 0
ALTER TABLE `discount_on_number`
    ADD CONSTRAINT check_discountnumber CHECK (discount_number > 0);

-- Table structure for table `ma giam gia hien co`
CREATE TABLE `customer_discounts` (
    `customer_id` int(10) NOT NULL,
    `discount_id` int(10) NOT NULL,
    `type_discount` varchar(255) NOT NULL, -- ! percent or number
    `expired_date` date,
    CONSTRAINT fk_customerdiscount_customer FOREIGN KEY (`customer_id`) REFERENCES `customer`(`customer_id`) ON DELETE CASCADE,
    CONSTRAINT fk_customerdiscount_discount FOREIGN KEY (`discount_id`) REFERENCES `discount`(`discount_id`) ON DELETE CASCADE,
    PRIMARY KEY (`customer_id`,`discount_id`)
);
-- ! Rang buoc ve loai ma giam gia phai la percent hoac number
ALTER TABLE `customer_discounts`
    ADD CONSTRAINT check_typediscount CHECK (type_discount = 'percent' OR type_discount = 'number');
-- ! Rang buoc ve ngay het han cua ma giam gia phai lon hon ngay 01/12/2024
ALTER TABLE `customer_discounts`
    ADD CONSTRAINT check_expireddate CHECK (expired_date > '2024-12-01');

-- Table structure for table `tao ma giam gia`
CREATE TABLE `discount_creator` (
    `restaurant_id` int(10) NOT NULL,
    `discount_id` int(10) NOT NULL,
    CONSTRAINT fk_discountcreator_restaurant FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant`(`restaurant_id`) ON DELETE CASCADE,
    CONSTRAINT fk_discountcreator_discount FOREIGN KEY (`discount_id`) REFERENCES `discount`(`discount_id`) ON DELETE CASCADE,
    PRIMARY KEY (`restaurant_id`,`discount_id`)
);

-- Table structure for table `don hang`
CREATE TABLE `order` (
    `order_id` int(10) NOT NULL AUTO_INCREMENT,
    `customer_id` int(10) NOT NULL,
    `order_status` int(1) NOT NULL, -- ! 0: waiting, 1: processing, 2: delivering, 3: delivered
    `address_delivery` varchar(255) NOT NULL,
    CONSTRAINT fk_order_customer FOREIGN KEY (`customer_id`) REFERENCES `customer`(`customer_id`) ON DELETE CASCADE,
    PRIMARY KEY (`order_id`)
);
-- ! Rang buoc ve trang thai cua don hang phai la 0, 1, 2 hoac 3
ALTER TABLE `order`
    ADD CONSTRAINT check_orderstatus CHECK (order_status = 0 OR order_status = 1 OR order_status = 2 OR order_status = 3);

-- Table structure for table `nhan vien giao hang`
CREATE TABLE `shipping_employee` (
    `employee_id` int(10) NOT NULL AUTO_INCREMENT,
    `employee_name` varchar(255) NOT NULL,
    `employee_phone` int(10) NOT NULL,
    `order_id` int(10) NOT NULL,
    CONSTRAINT fk_shippingemployee_order FOREIGN KEY (`order_id`) REFERENCES `order`(`order_id`) ON DELETE CASCADE,
    PRIMARY KEY (`employee_id`)
);
-- ! Rang buoc ve so dien thoai cua nhan vien giao hang phai dung dinh dang
ALTER TABLE `shipping_employee`
    ADD CONSTRAINT check_valid_phonenumber CHECK (LENGTH(employee_phone) = 10);

-- Table structure for table `nguoi nhan`
CREATE TABLE `receiver` (
    `receiver_name` varchar(255) NOT NULL,
    `receiver_phone` int(10) NOT NULL,
    `receiver_address` varchar(255) NOT NULL,
    `order_id` int(10) NOT NULL,
    CONSTRAINT fk_receiver_order FOREIGN KEY (`order_id`) REFERENCES `order`(`order_id`) ON DELETE CASCADE,
    PRIMARY KEY (`receiver_name`, `order_id`)
);
-- ! Rang buoc ve so dien thoai cua nguoi nhan phai dung dinh dang
ALTER TABLE `receiver`
    ADD CONSTRAINT check_valid_phonenumber CHECK (LENGTH(receiver_phone) = 10);

-- Table structure for table `hoa don thanh toan`
CREATE TABLE `payment_bill` (
    `bill_id` int(10) NOT NULL AUTO_INCREMENT,
    `payment_status` int(1) NOT NULL, -- ! 0: unpaid, 1: paid
    PRIMARY KEY (`bill_id`)
);
-- ! Rang buoc ve trang thai cua hoa don phai la 0 hoac 1
ALTER TABLE `payment_bill`
    ADD CONSTRAINT check_paymentstatus CHECK (payment_status = 0 OR payment_status = 1);

-- Table structure for table `tao hoa don`
CREATE TABLE `creat_bill` (
    `order_id` int(10) NOT NULL,
    `bill_id` int(10) NOT NULL,
    `food_id` int(10) NOT NULL,
    `discount_id` int(10) NOT NULL,
    `total_price` int(10) NOT NULL,
    `create_date` date NOT NULL,
    CONSTRAINT fk_createbill_order FOREIGN KEY (`order_id`) REFERENCES `order`(`order_id`) ON DELETE CASCADE,
    CONSTRAINT fk_createbill_bill FOREIGN KEY (`bill_id`) REFERENCES `payment_bill`(`bill_id`) ON DELETE CASCADE,
    CONSTRAINT fk_createbill_food FOREIGN KEY (`food_id`) REFERENCES `food`(`food_id`) ON DELETE CASCADE,
    CONSTRAINT fk_createbill_discount FOREIGN KEY (`discount_id`) REFERENCES `discount`(`discount_id`) ON DELETE CASCADE,
    PRIMARY KEY (`order_id`, `bill_id`, `food_id`, `discount_id`)
);
-- ! Rang buoc ve gia tri thanh toan phai lon hon 0
ALTER TABLE `creat_bill`
    ADD CONSTRAINT check_totalprice CHECK (total_price > 0);
-- ! Rang buoc ve ngay tao hoa don phai lon hon ngay 01/12/2024
ALTER TABLE `creat_bill`
    ADD CONSTRAINT check_createdate CHECK (create_date > '2024-12-01');