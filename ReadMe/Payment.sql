CREATE TABLE IF NOT EXISTS `payment`(
    paymentID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    orderId INT UNSIGNED NULL,
    customerId INT UNSIGNED NULL,
    paymentDate DATE NOT NULL,
    paymentTime TIME NOT NULL,
    paymentAmount DECIMAL(20,2) NOT NULL,
    paymentPaid DECIMAL(20,2) NOT NULL,
    paymentChanges DECIMAL(20,2) NOT NULL,
    FOREIGN KEY(orderID) REFERENCES `order`(order_id),
    FOREIGN KEY(customerId) REFERENCES `customer`(cust_id)
);
