DELIMITER //
-- Tạo thủ tục Insert thông tin khách hàng 
CREATE PROCEDURE InsertCustomer(
    IN p_account_id INT,
    IN p_first_name VARCHAR(255),
    IN p_last_name VARCHAR(255),
    IN p_phone_number VARCHAR(10),
    IN p_email VARCHAR(255),
    IN p_points INT
)
BEGIN
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
END //




-- Tạo thủ tục Update thông tin khách hàng 
CREATE PROCEDURE UpdateCustomer(
    IN p_account_id INT,
    IN p_first_name VARCHAR(255),
    IN p_last_name VARCHAR(255),
    IN p_phone_number VARCHAR(10),
    IN p_email VARCHAR(255),
    IN p_points INT
)
BEGIN
    -- Kiểm tra account_id có tồn tại trong bảng account
    IF NOT EXISTS (SELECT 1 FROM account WHERE account_id = p_account_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Account ID does not exist in account table.';
    END IF;

    -- Kiểm tra account_id có tồn tại trong bảng customer
    IF NOT EXISTS (SELECT 1 FROM customer WHERE customer_id = p_account_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Customer with this Account ID does not exist.';
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

    -- Cập nhật thông tin khách hàng
    UPDATE customer
    SET customer_first_name = p_first_name,
        customer_last_name = p_last_name,
        phone_number = p_phone_number,
        email = p_email,
        points = COALESCE(p_points, 0)
    WHERE customer_id = p_account_id;
END //





-- Tạo thủ tục Delete thông tin khách hàng 
CREATE PROCEDURE DeleteCustomer(
    IN p_account_id INT
)
BEGIN
    -- Kiểm tra account_id có tồn tại trong bảng account
    IF NOT EXISTS (SELECT 1 FROM account WHERE account_id = p_account_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Account ID does not exist in account table.';
    END IF;

    -- Kiểm tra account_id có tồn tại trong bảng customer
    IF NOT EXISTS (SELECT 1 FROM customer WHERE customer_id = p_account_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Customer with this Account ID does not exist.';
    END IF;

    -- Xóa khách hàng khỏi bảng customer
    DELETE FROM customer
    WHERE customer_id = p_account_id;
END //





-- Tạo thủ tục hiển thị các khách hàng đánh giá "x" sao cho món ăn
CREATE PROCEDURE GetCustomersByRating(
    IN p_rating INT
)
BEGIN
    -- Kiểm tra giá trị x sao có hợp lệ không (1 đến 5)
    IF p_rating NOT BETWEEN 1 AND 5 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Số sao nhập vào phải trong khoảng từ 1 đến 5';
    END IF;

    -- Hiển thị thông tin khách hàng đã đánh giá x sao
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
END //





-- Tạo thủ tục hiển thị các món ăn dưới 1 mức giá được nhập
CREATE PROCEDURE GetFoodsBelowPrice(
    IN p_price INT
)
BEGIN
    -- Kiểm tra giá trị nhập vào có hợp lệ không (phải là số dương)
    IF p_price <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Giá tìm kiếm phải lớn hơn 0.';
    END IF;

    -- Hiển thị danh sách món ăn có giá dưới giá trị nhập vào
    SELECT 
        food_id,
        food_name,
        food_price,
        food_description
    FROM 
        food
    WHERE 
        food_price < p_price;
END //

DELIMITER ;






