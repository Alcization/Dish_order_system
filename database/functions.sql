-- ! Hàm áp dụng mã giảm giá 

DROP FUNCTION IF EXISTS apply_discount;

DELIMITER //

CREATE FUNCTION apply_discount(
    total INT(10),
    customerId INT(10),
    discountId INT(10)
)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE discountType VARCHAR(20);
    DECLARE discountValue DECIMAL(10,2);
    DECLARE discountValid BOOLEAN;

    IF discountID IS NOT NULL THEN
        -- Kiểm tra mã giảm giá hợp lệ
        SELECT COUNT(*)
        INTO discountValid
        FROM customer_discounts
        WHERE discount_id = discountId 
          AND customer_id = customerId 
          AND expiry_date >= CURRENT_DATE();

        IF discountValid = 1 THEN
            -- Lấy loại mã giảm giá
            SELECT 
                type_discount
            INTO 
                discountType
            FROM customer_discounts
            WHERE discount_id = discountId;

            -- Áp dụng giảm giá
            IF discountType = 'percent' THEN
                -- Lấy giá trị mã giảm giá
                SELECT 
                    `percent`
                INTO 
                    discountValue
                FROM discount_on_percent
                WHERE discount_id = discountId;
                -- Giảm giá theo phần trăm
                SET total = total - (total * discountValue / 100);
                -- Làm tròn xuống hàng nghìn
                SET total = FLOOR(total / 1000) * 1000;
                
            ELSEIF discountType = 'number' THEN
                -- Lấy giá trị mã giảm giá
                SELECT 
                    `number`
                INTO 
                    discountValue
                FROM discount_on_percent
                WHERE discount_id = discountId;
                -- Giảm giá theo số tiền cụ thể
                SET total = total - discountValue;
            END IF;
        END IF;
    END IF;
    RETURN (total);
END //
DELIMITER ;


-- ! Thủ tục thanh toán đơn hàng sau khi khách hàng đã chọn món và nhập mã giảm giá
DELIMITER //

CREATE PROCEDURE ProcessOrder(IN orderId INT, IN customerId INT, IN discountID INT)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE price INT;
    DECLARE total INT DEFAULT 0;
    DECLARE discountType VARCHAR(20);
    DECLARE discountValue DECIMAL(10,2);
    DECLARE discountValid BOOLEAN;

    -- Con trỏ để lấy giá từng sản phẩm trong đơn hàng
    DECLARE cur CURSOR FOR 
        SELECT price FROM creat_order WHERE order_id = orderId;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Tính tổng giá trị đơn hàng
    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO price;
        IF done THEN
            LEAVE read_loop;
        END IF;
        SET total = total + price;
    END LOOP;
    CLOSE cur;
    
    -- Áp dụng mã giảm giá
    SET total = apply_discount(total, customerId, discountID)

    -- Đảm bảo tổng không âm
    IF total < 0 THEN
        SET total = 0;
    END IF;

    -- Cập nhật giá trị cuối cùng vào bảng 'order'
    UPDATE `order`
    SET final_price = total,
        discount_id = discountID
    WHERE order_id = orderId;
END //

DELIMITER ;


