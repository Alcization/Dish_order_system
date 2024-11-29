INSERT INTO `account` (user_name, password) VALUES 
('nguyenanh', 'MatKhau1'),
('tranminh', 'MatKhau2'),
('lethuy', 'MatKhau3'),
('phamquang', 'MatKhau4'),
('hoanglan', 'MatKhau5'),
('minhhoang', 'MatKhau6'),
('thanhvan', 'MatKhau7'),
('quanghuy', 'MatKhau8'),
('huyentrang', 'MatKhau9'),
('tuananh', 'MatKhau10'),
('vananh', 'MatKhau11'),
('nhutdo', 'MatKhau12');


INSERT INTO `restaurant` (`restaurant_id`, `restaurant_name`, `restaurant_address`, `restaurant_description`)
VALUES
    (1, 'Nhà Hàng Hải Sản Tươi Sống', '123 Đường Biển, Quận 1, TP.HCM', 'Chuyên phục vụ hải sản tươi sống và món ăn đặc sản.'),
    (2, 'Pizza Italia', '456 Đường Pizzaiolo, Quận 3, TP.HCM', 'Pizza kiểu Ý với nguyên liệu nhập khẩu, hương vị đích thực.');



INSERT INTO restaurant_image (`restaurant_image_url`, `restaurant_id`)
VALUES 
('https://www.shutterstock.com/image-illustration/front-view-pizza-shop-restaurant-design-modern-1575914101.jpg', 1),
('https://www.shutterstock.com/image-vector/italian-pizza-ingredients-food-menu-design-2086137538.jpg', 2);





INSERT INTO `customer` (customer_id, customer_first_name, customer_last_name, phone_number, email, points) VALUES 
(3, 'John', 'Doe', 1234567890, 'john.doe@example.com', 100),
(4, 'Jane', 'Smith', 2345678901, 'jane.smith@example.com', 200),
(5, 'Alice', 'Johnson', 3456789012, 'alice.johnson@example.com', 50),
(6, 'Bob', 'Brown', 4567890123, 'bob.brown@example.com', 0),
(7, 'Charlie', 'Wilson', 5678901234, 'charlie.wilson@example.com', 150),
(8, 'Diana', 'Moore', 6789012345, 'diana.moore@example.com', 75),
(9, 'Ethan', 'Taylor', 7890123456, 'ethan.taylor@example.com', 300),
(10, 'Fiona', 'Anderson', 8901234567, 'fiona.anderson@example.com', 20),
(11, 'George', 'Thomas', 9012345678, 'george.thomas@example.com', 10),
(12, 'Hannah', 'Jackson', 1023456789, 'hannah.jackson@example.com', 5);







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
    ('https://images.pexels.com/photos/19786231/pexels-photo-19786231/free-photo-of-carrying-a-cutting-board-with-slices-of-hot-dog-pizza.jpeg?auto=compress&cs=tinysrgb&w=41', 9),
    ('https://images.pexels.com/photos/162744/tomatoes-tomato-quiche-red-yellow-162744.jpeg?auto=compress&cs=tinysrgb&w=600.jpg', 10),
    ('https://images.pexels.com/photos/5640015/pexels-photo-5640015.jpeg?auto=compress&cs=tinysrgb&w=600.png', 11),
    ('https://images.pexels.com/photos/5639547/pexels-photo-5639547.jpeg?auto=compress&cs=tinysrgb&w=600.jpg', 12),
    ('https://images.pexels.com/photos/28308002/pexels-photo-28308002/free-photo-of-a-pizza-with-greens-and-other-toppings-on-a-table.jpeg?auto=compress&cs=tinysrgb&w=600&lazy=load.jpg', 13),
    ('https://images.pexels.com/photos/774487/pexels-photo-774487.jpeg?auto=compress&cs=tinysrgb&w=400.jpg', 14),
    ('https://images.pexels.com/photos/578009/pexels-photo-578009.jpeg?auto=compress&cs=tinysrgb&w=400.jpg', 15);




    INSERT INTO `manage_food` (`restaurant_id`, `food_id`, `price_sell`)
VALUES
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


    
INSERT INTO `user_review` (`customer_id`, `food_id`, `rating`, `review_description`)
VALUES
    (3, 1, 5, 'Pizza hải sản rất ngon, đầy đủ các loại hải sản tươi ngon.'),
    (6, 2, 4, 'Mì Ý Sốt Bolognese rất hấp dẫn, ăn cực kỳ thích.'),
    (3, 3, 3, 'Salad trái cây tươi mát, nhưng một chút ngọt nhạt.'),
    (6, 4, 5, 'Sushi cá hồi tuyệt vời, tươi ngon và cách trình bày rất đẹp.'),
    (5, 5, 4, 'Bánh mì thịt nướng rất giòn và đầy vị.'),
    (6, 6, 5, 'Pizza Margherita ngon như ở Italia, cực kỳ hài lòng.'),
    (5, 7, 4, 'Pizza Pepperoni rất thơm ngon, sốt cà chua và phô mai ăn rất hài hòa.'),
    (3, 8, 5, 'Pizza hải sản đặc biệt quá tuyệt, nhiều hải sản và phô mai rất ngon.'),
    (8, 9, 4, 'Pizza Thập Cẩm đủ các loại nhân, rất đa dạng và ngon.'),
    (5, 10, 3, 'Pizza trứng muối khá ngon, nhưng một chút quá mặn.'),
    (7, 11, 4, 'Pizza chay rất tươi ngon, sốt cà chua cũng rất hấp dẫn.'),
    (6, 12, 5, 'Pizza BBQ gà rất thơm ngon, vị cay nồng của ớt và hành tây rất hài hòa.'),
    (9, 13, 4, 'Pizza Quattro Stagioni rất đa dạng nhân, mỗi loại đều ngon.'),
    (4, 14, 5, 'Pizza phô mai đặc biệt quá tuyệt, các loại phô mai hòa quyện rất ngon.'),
    (11, 15, 4, 'Pizza trái cây khá ngon, vị ngọt của trái cây rất hợp khẩu vị.');





INSERT INTO discount (discount_name, restaurant_id, status_use)
VALUES
    ('XMAS2024', 1, 0),
    ('SUMMER2024', 2, 1),
    ('STUDENTDISCOUNT', 2, 0),
    ('NEWCUSTOMER', 2, 1);




INSERT INTO discount_on_percent (discount_id, percent)
VALUES
    (1, 20),
    (2, 15),
    (3, 10),
    (4, 25);





INSERT INTO discount_on_number (discount_id, discount_number)
VALUES
    (1, 50000),
    (2, 30000),
    (3, 20000),
    (4, 40000);





INSERT INTO customer_discounts (customer_id, discount_id, type_discount, expired_date)
VALUES
    (3, 1, 'percent', '2024-12-31'),  -- Hợp lệ
    (4, 2, 'number', '2025-01-15'),   -- Sửa thành hợp lệ
    (5, 3, 'percent', '2025-01-15'),  -- Sửa thành hợp lệ
    (7, 4, 'number', '2025-01-15'),    -- Sửa thành hợp lệ
    (8, 1, 'percent', '2024-12-31');   -- Hợp lệ
    



    INSERT INTO discount_creator (restaurant_id, discount_id)
VALUES
    (1, 1),
    (2, 2),
    (2, 3),
    (1, 4);

    
INSERT INTO `payment_order` (`payment_status`)
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



INSERT INTO `order` (`customer_id`, `order_status`, `bill_id`, `address_delivery`, `final_price`, `order_date`) VALUES
(3, 0, 1, '123 Main St, Anytown USA', 100000, '2023-05-01'),
(4, 1, 2, '456 Oak Rd, Othertown USA', 150000, '2023-06-15'),
(5, 0, 3, '789 Elm St, Someplace USA', 80000, '2023-07-20'),
(6, 1, 4, '321 Maple Ave, Nowhere USA', 200000, '2023-08-05'),
(7, 2, 5, '654 Pine Rd, Everytown USA', 120000, '2023-09-10'),
(8, 0, 6, '987 Birch Blvd, Anotherton USA', 90000, '2023-10-01'),
(9, 1, 7, '159 Oak St, Somewhereville USA', 180000, '2023-11-15'),
(10, 0, 8, '753 Elm Ave, Nowhere USA', 70000, '2023-12-01'),
(11, 2, 9, '258 Maple Dr, Everytown USA', 150000, '2024-01-05'),
(12, 0, 10, '456 Pine St, Anotherton USA', 110000, '2024-02-10');
    


INSERT INTO `shipping_employee` (`employee_name`, `employee_phone`, `order_id`)
VALUES
    ('Nguyễn Văn Hiền', 9876543210, 1), 
    ('Trần Thị Hoa', 2345678930, 2),    
    ('Lê Hoàng Công', 4567890123, 3),   
    ('Phạm Minh Danh', 7890123456, 4), 
    ('Võ Thị Ngân', 1597539514, 5);  


    
INSERT INTO `receiver` (`receiver_name`, `receiver_phone`, `receiver_address`, `order_id`)
VALUES
    ('Nguyễn Văn Minh', 9876543210, '123 Nguyễn Văn Cừ, Quận 5, TP.HCM', 1),
    ('Trần Thị Lan', 1234567890, '456 Lê Văn Sỹ, Quận 3, TP.HCM', 2),
    ('Lê Hoàng Công', 4567890123, '789 Nguyễn Trãi, Quận 1, TP.HCM', 3),
    ('Phạm Minh Tiến', 7890123456, '321 Trần Hưng Đạo, Quận 5, TP.HCM', 4),
    ('Võ Thị Chi', 1597539514, '654 Lê Đại Hành, Quận 11, TP.HCM', 5),
    ('Ngô Thanh Thảo', 9513579130, '987 Phan Đình Phùng, Quận Phú Nhuận, TP.HCM', 6);
     


 


     
INSERT INTO `creat_order` (`order_id`, `food_id`, `quantity`, `temp_price`, `create_date`) 
VALUES
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