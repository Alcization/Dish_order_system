DELIMITER //

CREATE FUNCTION CountCustomersWithPointsAbove(min_points INT)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE customer_count INT;

    SELECT COUNT(*) INTO customer_count
    FROM customer
    WHERE points > min_points;

    RETURN customer_count;
END //

DELIMITER ;
SELECT CountCustomersWithPointsAbove(100);