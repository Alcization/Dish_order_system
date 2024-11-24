--- Trigger 1: Tính giá tạm tính trong creat_order khi tạo/update -------------------------------------------------
DELIMITER $$

CREATE TRIGGER before_insert_creat_order
BEFORE INSERT ON creat_order
FOR EACH ROW
BEGIN
    DECLARE item_price INT;

    -- Kiểm tra order_id có tồn tại trong bảng `order`
    IF NOT EXISTS (SELECT 1 FROM `order` WHERE `order_id` = NEW.order_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Không có đơn hàng đó!';
    END IF;

    -- Kiểm tra food_id có tồn tại trong bảng `food`
    IF NOT EXISTS (SELECT 1 FROM `food` WHERE `food_id` = NEW.food_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Không có món ăn đó!';
    END IF;

    -- Tính giá tạm tính
    SELECT food_price INTO item_price
    FROM food
    WHERE food_id = NEW.food_id;

    SET NEW.temp_price = NEW.quantity * item_price;
END;
$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER before_update_creat_order
BEFORE UPDATE ON creat_order
FOR EACH ROW
BEGIN
    DECLARE item_price INT;

    -- Kiểm tra order_id có tồn tại trong bảng `order`
    IF NOT EXISTS (SELECT 1 FROM `order` WHERE `order_id` = NEW.order_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Không có đơn hàng đó!';
    END IF;

    -- Kiểm tra food_id có tồn tại trong bảng `food`
    IF NOT EXISTS (SELECT 1 FROM `food` WHERE `food_id` = NEW.food_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Không có món ăn đó!';
    END IF;

    -- Tính lại giá tạm tính khi cập nhật số lượng
    SELECT food_price INTO item_price
    FROM food
    WHERE food_id = NEW.food_id;

    SET NEW.temp_price = NEW.quantity * item_price;
END;
$$

DELIMITER ;



--- Trigger 2: Tính giá final_price trong 'order' sau khi insert trong 'creat_order' -------------------------------------------------
DELIMITER $$

-- Trigger for AFTER INSERT
CREATE TRIGGER after_insert_creat_order
AFTER INSERT ON creat_order
FOR EACH ROW
BEGIN
    DECLARE temp_final INT DEFAULT 0;
    DECLARE discount_type VARCHAR(255);
    DECLARE discount_value INT;
    DECLARE discount_percent INT;
    
    -- Kiểm tra order_id có tồn tại trong bảng `order`
    IF NOT EXISTS (SELECT 1 FROM `order` WHERE `order_id` = NEW.order_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: order_id does not exist in `order` table';
    END IF;

    -- Kiểm tra food_id có tồn tại trong bảng `food`
    IF NOT EXISTS (SELECT 1 FROM `food` WHERE `food_id` = NEW.food_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: food_id does not exist in `food` table';
    END IF;

    -- Tính tổng khi chưa áp mã giảm giá của tất cả các món trong đơn
    SELECT SUM(temp_price) INTO temp_final
    FROM creat_order
    WHERE order_id = NEW.order_id;

    -- Kiểm tra xem đơn hàng có áp mã giảm giá không, mã giảm giá có thuộc về khách hàng đặt đơn không
    IF EXISTS (SELECT 1 FROM `order` WHERE `order_id` = NEW.order_id AND `discount_id` IS NOT NULL) THEN
        -- Xác định loại mã giảm giá và giá trị
        SELECT d.discount_id, dc.type_discount, dp.percent, dn.discount_number
        INTO @discount_id, @type_discount, @percent, @discount_number
        FROM `discount` d
        LEFT JOIN `discount_on_percent` dp ON d.discount_id = dp.discount_id
        LEFT JOIN `discount_on_number` dn ON d.discount_id = dn.discount_id
        LEFT JOIN `customer_discounts` dc ON d.discount_id = dc.discount_id
        WHERE d.discount_id = (SELECT discount_id FROM `order` WHERE `order_id` = NEW.order_id);

        -- Áp mã giảm giá
        IF @type_discount = 'percent' THEN
            SET temp_final = temp_final - (temp_final * @percent / 100);
        ELSEIF @type_discount = 'number' THEN
            SET temp_final = temp_final - @discount_number;
        END IF;
    END IF;

    -- Không nhận giá trị < 0 -> set temp_final = 1
    IF temp_final < 0 THEN
        SET temp_final = 1;
    END IF;

    -- Update final_price in the order table
    UPDATE `order`
    SET final_price = temp_final
    WHERE order_id = NEW.order_id;
END $$




--- Trigger 3: Cập nhập điểm cho khách hàng đánh giá 5* -------------------------------------------------
DELIMITER $$

CREATE TRIGGER update_customer_points
AFTER INSERT ON user_review 
FOR EACH ROW
BEGIN
    -- Kiểm tra customer_id có tồn tại trong bảng `customer`
    IF EXISTS (SELECT 1 FROM customer WHERE customer_id = NEW.customer_id) THEN
        -- Kiểm tra food_id có tồn tại trong bảng `food`
        IF EXISTS (SELECT 1 FROM food WHERE food_id = NEW.food_id) THEN
            -- Check if the rating is 5
            IF NEW.rating = 5 THEN
                -- Update customer's points
                UPDATE customer
                SET points = points + 10
                WHERE customer_id = NEW.customer_id;
            END IF;
        END IF;
    END IF;
END $$

DELIMITER ;







