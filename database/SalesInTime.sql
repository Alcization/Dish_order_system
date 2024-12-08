DELIMITER //

CREATE FUNCTION SaleInTime(id INT, start_day DATE, end_day DATE)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE total_sale INT DEFAULT 0;
    DECLARE done INT DEFAULT FALSE;
    DECLARE current_sale INT;

    IF id < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'The food_id must be a non-negative integer';
    END IF;

    -- Check if start_day and end_day are valid dates
    IF start_day IS NULL OR end_day IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'The start_day and end_day must be valid dates';
    END IF;

    -- Check if start_day is before or equal to end_day
    IF start_day > end_day THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'The start_day must be before or equal to the end_day';
    END IF;

    -- Khai báo con trỏ để duyệt qua bảng creat_order
    DECLARE sale_cursor CURSOR FOR 
        SELECT temp_price FROM creat_order WHERE food_id = id AND creat_date BETWEEN start_day AND end_day;

    -- Khai báo handler để xử lý khi không còn dữ liệu
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Mở con trỏ
    OPEN sale_cursor;

    read_loop: LOOP
        FETCH sale_cursor INTO current_sale;
        IF done THEN
            LEAVE read_loop;
        END IF;

        SET total_sale = total_sale + current_sale;
    END LOOP;

    -- Đóng con trỏ
    CLOSE sale_cursor;

    RETURN total_sale;
END

DELIMITER ;


