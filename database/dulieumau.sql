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
    ADD CONSTRAINT check_length_password CHECK (LENGTH(password) >= 8);
-- ! Rang buoc ve password phai la co it nhat 1 chu in hoa, 1 chu in thuong va 1 so
ALTER TABLE `account`
    ADD CONSTRAINT check_valid_password CHECK (password REGEXP '^[a-zA-Z0-9]+$');

INSERT INTO account (`user_name`, `password`)
    VALUES 
    ('minhnguyen', 'minh1234' ),
    ('lannguyenthi324', 'lann1234' ),
    ('cuongchau0122', 'cuon1234' ),
    ('lannguyenthi999', 'lann1234' ),
    ('cuongdola', 'cuon1234' ),
    ('locdeptrai', 'locd1234' ),
    ('kietnguyen2286', 'kiet1234' ),
    ('khoatheluong', 'khoa1234' ),
    ('hungkhanh', 'hung1234' ),
    ('phanphiphung', 'phan1234' ),
    ('tientanngo', 'tien1234' ),
    ('thayongnoi', 'thay1234' );

-- Table structure for table `nha hang` 
CREATE TABLE `restaurant` (
    `restaurant_id` int(10) NOT NULL,
    `restaurant_name` varchar(255) NOT NULL,
    `restaurant_address` varchar(255) NOT NULL,
    `restaurant_description` varchar(255),
    CONSTRAINT fk_restaurant_account FOREIGN KEY (`restaurant_id`) REFERENCES `account`(`account_id`) ON DELETE CASCADE,
    PRIMARY KEY (`restaurant_id`)
);

INSERT INTO `restaurant` ( `restaurant_id`, `restaurant_name`, `restaurant_address`, `restaurant_description`)
 VALUES 
 (1112223331, 'Cát biển', 'Phú Quốc', 'Khám phá các loại pizza độc đáo, tươi ngon từ hải sản, đầy hương vị và sáng tạo'),
 (1112223332, 'Sao biển', 'Phú Quốc', 'Nhà hàng pizza lý tưởng cho cả gia đình với thực đơn đa dạng, món ngon cho mọi lứa tuổi'),
 (1112223333, 'Hữu duyên', 'Phú Quốc', 'Thưởng thức pizza Ý với đế bánh mỏng giòn và nguyên liệu tươi ngon, nướng trong lò củi'),
 (1112223334, 'Mị châu', 'Phú Quốc', 'Nhiều topping'),
 (1112223335, 'Ngô quyền', 'Phú Quốc', 'Vị cay nồng từ các loại ớt nổi tiếng'),
 (1112223336, 'Jangnam', 'Ktx khu B', 'Tham gia khuyến mãi hấp dẫn với combo pizza giảm giá và ưu đãi cho nhóm lớn'),
 (1112223337, 'Landmark81', 'Bình Thạnh', 'Nguyên liệu thượng hạng'),
 (1112223338, 'Pizza sinh viên', 'Làng đại học', 'Ngon bổ rẻ, giá sinh viên');

-- Table structure for table `anh nha hang`
CREATE TABLE `restaurant_image` (
    `restaurant_image_url` varchar(255) NOT NULL,
    `restaurant_id` int(10) NOT NULL,
    CONSTRAINT fk_restaurantimage_restaurant FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant`(`restaurant_id`) ON DELETE CASCADE,
    PRIMARY KEY (`restaurant_id`, `restaurant_image_url`)
);


INSERT INTO `restaurant_image` ( `restaurant_image_url`, `restaurant_id`)
 VALUES 
 ('https://www.hian.com.vn/wp-content/uploads/2023/09/Hian.com_.vn-thiet-ke-nha-hang-pizza-rr452.jpg', '11112223331'),

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

INSERT INTO `customer` (`customer_id`, `customer_first_name`, `customer_last_name`, `phone_number`, `email`, `points`)
VALUES
    (001, 'Nguyễn', 'Minh', 1234567890, 'minh.nguyen@gmail.com', 100),
    (002, 'Trần', 'Lan', 0987654321, 'lan.tran@gmail.com', 50),
    (003, 'Phạm', 'Hùng', 0123456789, 'hung.pham@gmail.com', 0),
    (004, 'Lê', 'Thảo', 0876543210, 'thao.le@gmail.com', 20),
    (005, 'Đỗ', 'Nam', 0234567891, 'nam.do@gmail.com', 10);

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

INSERT INTO `food` (`food_name`, `food_price`, `food_description`)
VALUES
    ('Pizza Hải Sản', 150000, 'Pizza với tôm, mực và cá hồi tươi ngon.'),
    ('Mì Ý Sốt Bolognese', 120000, 'Mì Ý truyền thống với sốt thịt bò và gia vị đặc trưng.'),
    ('Salad Trái Cây', 80000, 'Salad tươi ngon với nhiều loại trái cây mùa hè.'),
    ('Sushi Cá Hồi', 200000, 'Sushi tươi với cá hồi và cơm dẻo, cuộn với rong biển.'),
    ('Bánh Mì Thịt Nướng', 50000, 'Bánh mì giòn với thịt nướng và rau sống tươi mát.'),
    ('Pizza Margherita', 120000, 'Pizza truyền thống với sốt cà chua, phô mai mozzarella và lá húng quế.'),
    ('Pizza Pepperoni', 150000, 'Pizza với lớp xúc xích pepperoni thơm ngon, phô mai và sốt cà chua.'),
    ('Pizza Hải Sản đặt biệt', 180000, 'Pizza đặc biệt với tôm, mực và cá hồi tươi ngon, phủ phô mai.'),
    ('Pizza Thập Cẩm', 200000, 'Pizza với đầy đủ các loại nhân như thịt, xúc xích, nấm và rau củ.'),
    ('Pizza Trứng Muối', 170000, 'Pizza sáng tạo với trứng muối, phô mai và rau củ tươi mát.'),
    ('Pizza Chay', 110000, 'Pizza dành cho người ăn chay với rau củ tươi ngon và sốt cà chua tự nhiên.'),
    ('Pizza BBQ Gà', 160000, 'Pizza với gà nướng và sốt BBQ thơm lừng, kèm theo hành tây và ớt chuông.'),
    ('Pizza Quattro Stagioni', 190000, 'Pizza bốn mùa với các loại nhân khác nhau: nấm, giăm bông, ô liu và artichoke.'),
    ('Pizza Phô Mai Đặc Biệt', 140000, 'Pizza với nhiều loại phô mai khác nhau, dành cho tín đồ phô mai.'),
    ('Pizza Trái Cây', 130000, 'Pizza ngọt với trái cây tươi như dứa, kiwi và dâu tây, phù hợp cho bữa tráng miệng.');

-- Table structure for table `anh mon an`
CREATE TABLE `food_image` (
    `food_image_url` varchar(255) NOT NULL,
    `food_id` int (10) NOT NULL,
    CONSTRAINT fk_foodimage_food FOREIGN KEY (`food_id`) REFERENCES `food`(`food_id`) ON DELETE CASCADE,
    PRIMARY KEY (`food_id`, `food_image_url`)
);

INSERT INTO `food_image` (`food_image_url`, `food_id`)
VALUES
    ('https://images.pexels.com/photos/27583268/pexels-photo-27583268/free-photo-of-a-pizza-with-a-slice-missing-from-it-on-a-wooden-board.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load.jpg', 1),
    ('https://images.pexels.com/photos/14737/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=600.jpg', 2),
    ('https://images.pexels.com/photos/1437270/pexels-photo-1437270.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1.jpg', 3),
    ('https://images.pexels.com/photos/2098085/pexels-photo-2098085.jpeg?auto=compress&cs=tinysrgb&w=600.jpg', 4),
    ('https://images.pexels.com/photos/9101561/pexels-photo-9101561.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load.jpg', 5),
    ('https://images.pexels.com/photos/5175513/pexels-photo-5175513.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load.jpg', 6),
    ('https://images.pexels.com/photos/1435903/pexels-photo-1435903.jpeg?auto=compress&cs=tinysrgb&w=600.jpg', 7),
    ('https://www.pexels.com/photo/a-colorful-sliced-pizza-2909822/.jpg', 8),
    ('https://images.pexels.com/photos/19786231/pexels-photo-19786231/free-photo-of-carrying-a-cutting-board-with-slices-of-hot-dog-pizza.jpeg?auto=compress&cs=tinysrgb&w=4001', 9),
    ('https://images.pexels.com/photos/162744/tomatoes-tomato-quiche-red-yellow-162744.jpeg?auto=compress&cs=tinysrgb&w=600.jpg', 10),
    ('https://images.pexels.com/photos/5640015/pexels-photo-5640015.jpeg?auto=compress&cs=tinysrgb&w=600.png', 11),
    ('https://images.pexels.com/photos/5639547/pexels-photo-5639547.jpeg?auto=compress&cs=tinysrgb&w=600.jpg', 12),
    ('https://images.pexels.com/photos/28308002/pexels-photo-28308002/free-photo-of-a-pizza-with-greens-and-other-toppings-on-a-table.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load.jpg', 13),
    ('https://images.pexels.com/photos/774487/pexels-photo-774487.jpeg?auto=compress&cs=tinysrgb&w=400.jpg', 14),
    ('https://images.pexels.com/photos/578009/pexels-photo-578009.jpeg?auto=compress&cs=tinysrgb&w=400.jpg', 15);
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

INSERT INTO `manage_food` (`restaurant_id`, `food_id`, `price_sell`)
VALUES
    (1112223331, 1, 160000),
    (1112223331, 4, 220000),
    (1112223331, 8, 190000),
    (1112223332, 1, 170000),
    (1112223332, 3, 90000),
    (1112223332, 6, 130000),
    (1112223332, 9, 210000),
    (1112223333, 2, 130000),
    (1112223333, 5, 60000),
    (1112223333, 7, 160000),
    (1112223333, 10, 180000),
    (1112223334, 1, 180000),
    (1112223334, 4, 230000),
    (1112223334, 8, 200000),
    (1112223335, 2, 140000),
    (1112223335, 6, 140000),
    (1112223335, 11, 120000),
    (1112223336, 3, 100000),
    (1112223336, 5, 70000),
    (1112223336, 12, 170000),
    (1112223337, 4, 240000),
    (1112223337, 7, 180000),
    (1112223337, 13, 210000),
    (1112223338, 2, 110000),
    (1112223338, 5, 55000),
    (1112223338, 6, 120000);

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


INSERT INTO `user_review` (`customer_id`, `food_id`, `rating`, `review_description`)
VALUES
    (1, 1, 5, 'Pizza hải sản rất ngon, đầy đủ các loại hải sản tươi ngon.'),
    (2, 2, 4, 'Mì Ý Sốt Bolognese rất hấp dẫn, ăn cực kỳ thích.'),
    (3, 3, 3, 'Salad trái cây tươi mát, nhưng một chút ngọt nhạt.'),
    (4, 4, 5, 'Sushi cá hồi tuyệt vời, tươi ngon và cách trình bày rất đẹp.'),
    (5, 5, 4, 'Bánh mì thịt nướng rất giòn và đầy vị.'),
    (1, 6, 5, 'Pizza Margherita ngon như ở Italia, cực kỳ hài lòng.'),
    (2, 7, 4, 'Pizza Pepperoni rất thơm ngon, sốt cà chua và phô mai ăn rất hài hòa.'),
    (3, 8, 5, 'Pizza hải sản đặc biệt quá tuyệt, nhiều hải sản và phô mai rất ngon.'),
    (4, 9, 4, 'Pizza Thập Cẩm đủ các loại nhân, rất đa dạng và ngon.'),
    (5, 10, 3, 'Pizza trứng muối khá ngon, nhưng một chút quá mặn.'),
    (1, 11, 4, 'Pizza chay rất tươi ngon, sốt cà chua cũng rất hấp dẫn.'),
    (2, 12, 5, 'Pizza BBQ gà rất thơm ngon, vị cay nồng của ớt và hành tây rất hài hòa.'),
    (3, 13, 4, 'Pizza Quattro Stagioni rất đa dạng nhân, mỗi loại đều ngon.'),
    (4, 14, 5, 'Pizza phô mai đặc biệt quá tuyệt, các loại phô mai hòa quyện rất ngon.'),
    (5, 15, 4, 'Pizza trái cây khá ngon, vị ngọt của trái cây rất hợp khẩu vị.');

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


INSERT INTO discount (discount_name, restaurant_id, status_use)
VALUES
    ('XMAS2024', 1112223331, 0),
    ('SUMMER2024', 1112223332, 1),
    ('STUDENTDISCOUNT', 1112223336, 0),
    ('NEWCUSTOMER', 1112223338, 1);


-- Table structure for table `ma giam gia theo phan tram`
CREATE TABLE `discount_on_percent` (
    `discount_id` int(10) NOT NULL,
    `percent` int(2) NOT NULL,
    CONSTRAINT fk_discountpercent_discount FOREIGN KEY (`discount_id`) REFERENCES `discount`(`discount_id`) ON DELETE CASCADE,
    PRIMARY KEY (`discount_id`)
);

INSERT INTO discount_on_percent (discount_id, percent)
VALUES
    (1, 20),
    (2, 15),
    (3, 10),
    (4, 25);


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


INSERT INTO discount_on_number (discount_id, discount_number)
VALUES
    (1, 50000),
    (2, 30000),
    (3, 20000),
    (4, 40000);


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


INSERT INTO customer_discounts (customer_id, discount_id, type_discount, expired_date)
VALUES
    (001, 1, 'percent', '2024-12-31'),
    (002, 2, 'number', '2024-08-31'),
    (003, 3, 'percent', '2024-05-31'),
    (004, 4, 'number', '2024-02-28'),
    (005, 1, 'percent', '2024-12-31');

    

-- Table structure for table `tao ma giam gia`
CREATE TABLE `discount_creator` (
    `restaurant_id` int(10) NOT NULL,
    `discount_id` int(10) NOT NULL,
    CONSTRAINT fk_discountcreator_restaurant FOREIGN KEY (`restaurant_id`) REFERENCES `restaurant`(`restaurant_id`) ON DELETE CASCADE,
    CONSTRAINT fk_discountcreator_discount FOREIGN KEY (`discount_id`) REFERENCES `discount`(`discount_id`) ON DELETE CASCADE,
    PRIMARY KEY (`restaurant_id`,`discount_id`)
);


INSERT INTO discount_creator (restaurant_id, discount_id)
VALUES
    (1112223331, 1),
    (1112223332, 2),
    (1112223336, 3),
    (1112223338, 4);


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

INSERT INTO `order` (`customer_id`, `order_status`, `address_delivery`)
VALUES
    (1, 0, '123 Nguyễn Văn Cừ, Quận 5, TP.HCM'),
    (2, 1, '456 Lê Văn Sỹ, Quận 3, TP.HCM'),
    (3, 2, '789 Nguyễn Trãi, Quận 1, TP.HCM'),
    (4, 3, '321 Trần Hưng Đạo, Quận 5, TP.HCM'),
    (5, 0, '654 Lê Đại Hành, Quận 11, TP.HCM'),
    (1, 1, '987 Phan Đình Phùng, Quận Phú Nhuận, TP.HCM'),
    (2, 2, '159 Nguyễn Đình Chiểu, Quận 3, TP.HCM'),
    (3, 3, '753 Hoàng Diệu, Quận 4, TP.HCM'),
    (4, 0, '852 Lê Lợi, Quận 1, TP.HCM'),
    (5, 1, '369 Trường Chinh, Quận Tân Bình, TP.HCM'),
    (1, 2, '258 Võ Văn Ngân, Quận Thủ Đức, TP.HCM'),
    (2, 3, '741 Bà Huyện Thanh Quan, Quận 3, TP.HCM'),
    (3, 0, '963 Trần Quang Khải, Quận 1, TP.HCM'),
    (4, 1, '147 Lê Văn Việt, Quận 9, TP.HCM'),
    (5, 2, '852 Nguyễn Hữu Thọ, Quận 7, TP.HCM');


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



INSERT INTO `shipping_employee` (`employee_name`, `employee_phone`, `order_id`)
VALUES
    ('Nguyễn Văn Hiền', 0987654321, 1),
    ('Trần Thị Hoa', 0123456789, 2),
    ('Lê Hoàng Công', 0456789012, 3),
    ('Phạm Minh Danh', 0789012345, 4),
    ('Võ Thị Ngân', 0159753951, 5);


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



INSERT INTO `receiver` (`receiver_name`, `receiver_phone`, `receiver_address`, `order_id`)
VALUES
    ('Nguyễn Văn Minh', 0987654321, '123 Nguyễn Văn Cừ, Quận 5, TP.HCM', 1),
    ('Trần Thị Lan', 0123456789, '456 Lê Văn Sỹ, Quận 3, TP.HCM', 2),
    ('Lê Hoàng Công', 0456789012, '789 Nguyễn Trãi, Quận 1, TP.HCM', 3),
    ('Phạm Minh Tiến', 0789012345, '321 Trần Hưng Đạo, Quận 5, TP.HCM', 4),
    ('Võ Thị Chi', 0159753951, '654 Lê Đại Hành, Quận 11, TP.HCM', 5),
    ('Ngô Thanh Thảo', 0951357913, '987 Phan Đình Phùng, Quận Phú Nhuận, TP.HCM', 6);

-- Table structure for table `hoa don thanh toan`
CREATE TABLE `payment_bill` (
    `bill_id` int(10) NOT NULL AUTO_INCREMENT,
    `payment_status` int(1) NOT NULL, -- ! 0: unpaid, 1: paid
    PRIMARY KEY (`bill_id`)
);
-- ! Rang buoc ve trang thai cua hoa don phai la 0 hoac 1
ALTER TABLE `payment_bill`
    ADD CONSTRAINT check_paymentstatus CHECK (payment_status = 0 OR payment_status = 1);


INSERT INTO `payment_bill` (`payment_status`)
VALUES
    (0),  -- Bill 1: unpaid
    (1),  -- Bill 2: paid
    (0),  -- Bill 3: unpaid
    (1),  -- Bill 4: paid
    (1),  -- Bill 5: paid
    (0),  -- Bill 6: unpaid
    (1),  -- Bill 7: paid
    (0),  -- Bill 8: unpaid
    (1),  -- Bill 9: paid
    (0);  -- Bill 10: unpaid

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
    

INSERT INTO `creat_bill` (`order_id`, `bill_id`, `food_id`, `discount_id`, `total_price`, `create_date`)
VALUES
    (1, 1, 1, 1, 150000, '2024-12-02'),
    (2, 2, 3, 2, 80000, '2024-12-03'),
    (3, 3, 4, 1, 200000, '2024-12-04'),
    (4, 4, 2, 2, 120000, '2024-12-05'),
    (5, 5, 5, 1, 50000, '2024-12-06'),
    (1, 6, 6, 2, 120000, '2024-12-07'),
    (2, 7, 7, 1, 150000, '2024-12-08'),
    (3, 8, 8, 2, 180000, '2024-12-09'),
    (4, 9, 9, 1, 200000, '2024-12-10'),
    (5, 10, 10, 2, 170000, '2024-12-11');