CREATE TABLE IF NOT EXISTS `customer`(
    cust_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    cust_email VARCHAR(50) NOT NULL,
    password VARCHAR(20) NOT NULL,
    cust_name VARCHAR(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS `admin`(
    admin_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS `menu_item`(
    item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    item_name VARCHAR(50) NOT NULL,
    item_desc VARCHAR(100) NOT NULL,
    item_price DECIMAL(6,2) NOT NULL,
    item_cost DECIMAL(6,2) NOT NULL,
    item_time DECIMAL(6,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS `table`(
    table_num INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    reservations VARCHAR(50) NOT NULL,
    numOfSeats INT NOT NULL
);

CREATE TABLE IF NOT EXISTS `order`(
    order_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    table_id INT UNSIGNED NULL,
    type VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    items VARCHAR(100) NOT NULL,
    customer_id INT UNSIGNED NOT NULL,
    FOREIGN KEY(table_id) REFERENCES `table`(table_num),
    FOREIGN KEY(customer_id) REFERENCES `customer`(cust_id)
);

CREATE TABLE IF NOT EXISTS `reservation`(
    reservation_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    customer_id INT UNSIGNED NULL,
    reservation_date  date NOT NULL,
    reservation_time TIME NOT NULL,
    table_id INT UNSIGNED NOT NULL,
    FOREIGN KEY(table_id) REFERENCES `table`(table_num),
    FOREIGN KEY(customer_id) REFERENCES `customer`(cust_id)
);

INSERT INTO `reservation` (`reservation_id`, `customer_id`, `reservation_date`, `reservation_time`, `table_id`) VALUES ('0001', '1001', '2022-06-08', '15:00:00', '3');

INSERT INTO `reservation` (`reservation_id`, `customer_id`, `reservation_date`, `reservation_time`, `table_id`) VALUES ('0002', '1001', '2022-06-08', '16:00:00', '2');

INSERT INTO `reservation` (`reservation_id`, `customer_id`, `reservation_date`, `reservation_time`, `table_id`) VALUES ('0003', '1001', '2022-06-08', '15:00:00', '1');

INSERT INTO `customer`(`cust_id`, `cust_email`, `password`, `cust_name`) VALUES ('1001','johndoe@gmail.com','123456','John Doe');

INSERT INTO `customer`(`cust_email`, `password`, `cust_name`) VALUES ('johndoe2@gmail.com','123456','John Doe2');

INSERT INTO `admin`(`admin_id`, `username`, `password`) VALUES ('2001','admin','admin');

INSERT INTO `menu_item`(`item_id`, `item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('3001','Puff-Puff','Traditional Nigerian donut ball, rolled in sugar',4.99,3,10);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('SCOTCH EGG','Boiled egg wrapped in a ground meat mixture, coated in breadcrumbs, and deep-fried.',2,1,9);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('ATA RICE','Small pieces of beef, goat, stipe, and tendon sautéed in crushed green Jamaican pepper.',12,10,8);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('RICE AND DODO','(plantains) w/chicken, fish, beef or goat',11.99,9,8);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('Special Shrimp Deluxe','Fresh shrimp sautéed in blended mixture of tomatoes, onion, peppers over choice of rice',12.99,10,8);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('African Salad','With baked beans, egg, tuna, onion, tomatoes , green peas and carrot with your choice of dressing.',8.99,5,3);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('Whole catfish with rice and vegetables','Whole catfish slow cooked in tomatoes, pepper and onion sauce with seasoning to taste',13.99,10,7);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('Seafood Salad','With shrimp, egg and imitation crab meat',5.99,5,3);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('EBA','Grated cassava',11.99,9,8);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('AMALA','Yam flour',11.99,8,8);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('YAM PORRIDGE','in tomatoes sauce',9.99,6,7);

INSERT INTO `menu_item`(`item_name`, `item_desc`, `item_price`, `item_cost`, `item_time`) VALUES ('Boiled Plantain','w/spinach soup',9.99,7,5);

INSERT INTO `table`(`table_num`, `reservations`, `numOfSeats`) VALUES (1,'-','4');

INSERT INTO `table`(`table_num`, `reservations`, `numOfSeats`) VALUES (2,'6001,6002','2');

INSERT INTO `table`(`reservations`, `numOfSeats`) VALUES ('',2);

INSERT INTO `order`(`order_id`, `table_id`, `type`, `date`, `time`, `customer_id`) VALUES (8001,1,'Take In','2022-45-12','11:37:57',1001);

INSERT INTO `order`(`type`, `date`, `time`, `customer_id`) VALUES ('Take Out','2022-05-12','10:37:57',1001);

INSERT INTO `order`(`type`, `date`, `time`, `customer_id`) VALUES ('Take Out','2022-05-12','15:37:57',1002);

UPDATE `order` SET `items`='3001,3006' WHERE `customer_id`='1001';

UPDATE `order` SET `items`='3008,3010' WHERE `order_id`='8002';

UPDATE `order` SET `items`='3001,3010' WHERE `order_id`='8003';

