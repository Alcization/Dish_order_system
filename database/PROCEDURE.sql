DELIMITER $$
CREATE PROCEDURE InsertCustomer (
    IN inuser_name VARCHAR(255),
    IN inpassword VARCHAR(32),
    IN incustomer_first_name VARCHAR(255),
    IN incustomer_last_name VARCHAR(255),
    IN inphone_number INT(10),
    IN inemail VARCHAR(255),
    IN inpoints INT
)
BEGIN
    DECLARE new_account_id INT;
    -- Thêm tài khoản mới vào bảng account
    INSERT INTO account (user_name, password)
    VALUES (inuser_name, inpassword);
    -- Lấy account_id vừa tạo
    SET new_account_id = LAST_INSERT_ID();
    -- Thêm khách hàng mới vào bảng customer
    INSERT INTO customer (
        customer_id,
        customer_first_name,
        customer_last_name,
        phone_number,
        email,
        points
    )
    VALUES (
        new_account_id,
        incustomer_first_name,
        incustomer_last_name,
        inphone_number,
        inemail,
        inpoints
    );
END $$

CREATE PROCEDURE UpdateCustomer (
    IN in_customer_id INT,
    IN in_customer_first_name VARCHAR(255),
    IN in_customer_last_name VARCHAR(255),
    IN in_phone_number VARCHAR(10),
    IN in_email VARCHAR(255),
    IN in_points INT
)
BEGIN
    -- Cập nhật thông tin khách hàng trong bảng customer
    UPDATE customer
    SET 
        customer_first_name = in_customer_first_name,
        customer_last_name = in_customer_last_name,
        phone_number = in_phone_number,
        email = in_email,
        points = in_points
    WHERE customer_id = in_customer_id;
END $$

CREATE PROCEDURE DeleteCustomer (
    IN in_customer_id INT
)
BEGIN
    -- Xóa thông tin khách hàng trong bảng customer
    DELETE FROM customer WHERE customer_id = in_customer_id;
END $$

DELIMITER ;

-- CÁCH SỬ DỤNG CÁCH HÀM:

-- CALL DeleteCustomer(1);-- '1' là customer_id cần xóa

-- CALL UpdateCustomer(
--     5, -- customer_id
--     'Thanh', -- customer_first_name
--     'Nguyen', -- customer_last_name
--     '1987654321', -- phone_number
--     'thanh.nguyen140800d@example.com', -- email
--     150 -- points
-- );

-- CALL InsertCustomer(
--     'Thanhbe102', -- Tên tài khoản
--     'Password123', -- Mật khẩu
--     'Thanh', -- Tên khách hàng
--     'Nguyen', -- Họ khách hàng
--     '1234567890', -- Số điện thoại
--     'thanh.nguyen140800@example.com', -- Email
--     100 -- Điểm tích lũy
-- );
