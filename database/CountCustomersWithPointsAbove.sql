DELIMITER //

CREATE FUNCTION CountCustomersWithPointsAbove(min_points INT)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE customer_count INT DEFAULT 0;
    DECLARE done INT DEFAULT FALSE;
    DECLARE current_points INT;

    -- Khai báo con trỏ để duyệt qua bảng customer
    DECLARE customer_cursor CURSOR FOR 
        SELECT points FROM customer;

    -- Khai báo handler để xử lý khi không còn dữ liệu
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Mở con trỏ
    OPEN customer_cursor;

    -- Vòng lặp để duyệt qua từng khách hàng
    read_loop: LOOP
        FETCH customer_cursor INTO current_points;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Kiểm tra điểm và tăng biến đếm nếu điểm lớn hơn min_points
        IF current_points > min_points THEN
            SET customer_count = customer_count + 1;
        END IF;
    END LOOP;

    -- Đóng con trỏ
    CLOSE customer_cursor;

    RETURN customer_count;
END //

DELIMITER ;