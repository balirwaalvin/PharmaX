-- DigitalOcean Database Import Script
-- Copy and paste this entire script into your DigitalOcean database console

-- Disable foreign key checks for import
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Table structure for table `Messages`
CREATE TABLE `Messages` (
  `message_id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `message_text` text NOT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `Uploads_url` varchar(255) DEFAULT NULL,
  `response_text` text DEFAULT NULL,
  `message_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Orders`
CREATE TABLE `Orders` (
  `order_id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `order_status` enum('Pending','Shipped','Delivered') DEFAULT 'Pending',
  `order_type` enum('Prescription','General') DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `receiver_name` varchar(255) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `prescription_url` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `Order_total` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Payment`
CREATE TABLE `Payment` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('Card','Bank Transfer') DEFAULT NULL,
  `payment_status` enum('Pending','Completed','Failed') DEFAULT 'Pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_slip_url` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Products`
CREATE TABLE `Products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity_in_stock` int(11) DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `prescription_required` tinyint(1) DEFAULT 0,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `User_info`
CREATE TABLE `User_info` (
  `user_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_pic_url` varchar(255) DEFAULT NULL,
  `user_type` enum('customer','admin','manager') DEFAULT 'customer',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data for User_info
INSERT INTO `User_info` (`user_name`, `email`, `name`, `password`, `contact_no`, `address`, `profile_pic_url`, `user_type`, `registration_date`) VALUES
('admin', 'admin@pharmacyx.com', 'System Administrator', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0771234567', 'Main Office, Colombo', NULL, 'admin', '2024-09-17 06:01:22'),
('manager01', 'manager@pharmacyx.com', 'Pharmacy Manager', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0777654321', 'Branch Office, Kandy', NULL, 'manager', '2024-09-17 06:01:22'),
('user01', 'kulanya.lisaldi@gmail.com', 'Kulanya Lisaldi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0771234567', '123 Galle Road, Colombo', NULL, 'customer', '2024-09-17 06:01:22'),
('user02', 'deshan.ggd@gmail.com', 'Deshan GGD', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0777654321', '456 Marine Drive, Galle', NULL, 'customer', '2024-09-17 06:01:22');

-- Insert sample data for Products
INSERT INTO `Products` (`product_id`, `product_name`, `description`, `price`, `quantity_in_stock`, `category`, `image_url`, `prescription_required`, `date_added`) VALUES
(1, 'Paracetamol 500mg', 'Pain reliever and fever reducer', 150.00, 100, 'Pain Relief', 'paracetamol.jpg', 0, '2024-09-17 06:01:22'),
(2, 'Ibuprofen 200mg', 'Anti-inflammatory pain reliever', 200.00, 75, 'Pain Relief', 'ibuprofen.jpg', 0, '2024-09-17 06:01:22'),
(3, 'Amoxicillin 250mg', 'Antibiotic for bacterial infections', 350.00, 50, 'Antibiotics', 'amoxicillin.jpg', 1, '2024-09-17 06:01:22'),
(4, 'First Aid Kit', 'Complete first aid emergency kit', 4500.00, 20, 'First Aid', 'first aid kit.jpg', 0, '2024-09-17 06:01:22'),
(5, 'Digital Thermometer', 'Accurate digital temperature measurement', 1200.00, 30, 'Medical Devices', 'thermometer.jpg', 0, '2024-09-17 06:01:22'),
(6, 'Bandages Pack', 'Sterile adhesive bandages pack of 20', 450.00, 80, 'First Aid', 'bandage.jpg', 0, '2024-09-17 06:01:22'),
(7, 'Eye Drops', 'Lubricating eye drops for dry eyes', 850.00, 40, 'Eye Care', 'eye drops.jpg', 0, '2024-09-17 06:01:22'),
(8, 'Syringes Pack', 'Disposable syringes pack of 10', 300.00, 60, 'Medical Supplies', 'syringes.jpg', 1, '2024-09-17 06:01:22');

-- Insert sample data for Messages
INSERT INTO `Messages` (`message_id`, `user_name`, `name`, `message_text`, `contact_no`, `email`, `Uploads_url`, `response_text`, `message_date`) VALUES
(1, 'user01', 'kulanya', 'I want to know the availability of Paracetamol.', '0771234567', 'kulanya.lisaldi@gmail.com', NULL, NULL, '2024-09-17 06:01:22'),
(2, 'user01', 'kulanya', 'Here is my prescription for the required medicines.', '0777654321', 'deshan.ggd@gmail.com', 'Prescription2.jpeg', NULL, '2024-09-17 06:01:22'),
(3, 'user02', 'deshan', 'Can I get a discount on my next purchase?', '0712345678', 'group3@gmail.com', NULL, 'Yes, we are currently offering a 10% discount on all items.', '2024-09-17 06:01:22');

-- Insert sample data for Orders
INSERT INTO `Orders` (`order_id`, `user_name`, `order_status`, `order_type`, `qty`, `receiver_name`, `street`, `city`, `postal_code`, `prescription_url`, `product_id`, `Order_total`, `order_date`) VALUES
(1, 'user01', 'Pending', 'Prescription', 2, 'Kulanya Lisaldi', '123 Galle Road', 'Colombo', '00100', 'Prescription3.png', 2, 800.00, '2024-09-17 12:42:40'),
(2, 'user01', 'Pending', 'General', 1, 'Kulanya Lisaldi', '456 Marine Drive', 'Galle', '80000', NULL, 4, 4500.00, '2024-09-17 12:42:40'),
(3, 'user02', 'Shipped', 'General', 4, 'Deshan GGD', '25 Temple Road', 'Negombo', '11500', NULL, 1, 600.00, '2024-09-17 12:42:40');

-- Insert sample data for Payment
INSERT INTO `Payment` (`payment_id`, `order_id`, `amount`, `payment_method`, `payment_status`, `transaction_id`, `payment_slip_url`, `payment_date`) VALUES
(1, 1, 800.00, 'Card', 'Completed', 'TXN001234567', NULL, '2024-09-17 12:45:00'),
(2, 2, 4500.00, 'Bank Transfer', 'Pending', 'TXN001234568', 'payment_slip_001.jpg', '2024-09-17 12:50:00'),
(3, 3, 600.00, 'Card', 'Completed', 'TXN001234569', NULL, '2024-09-17 13:00:00');

-- Add indexes and constraints
ALTER TABLE `Messages` ADD PRIMARY KEY (`message_id`), ADD KEY `user_name` (`user_name`);
ALTER TABLE `Orders` ADD PRIMARY KEY (`order_id`), ADD KEY `user_name` (`user_name`), ADD KEY `product_id` (`product_id`);
ALTER TABLE `Payment` ADD PRIMARY KEY (`payment_id`), ADD KEY `order_id` (`order_id`);
ALTER TABLE `Products` ADD PRIMARY KEY (`product_id`);
ALTER TABLE `User_info` ADD PRIMARY KEY (`user_name`), ADD UNIQUE KEY `email` (`email`);

-- AUTO_INCREMENT settings
ALTER TABLE `Messages` MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `Orders` MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `Payment` MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `Products` MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

-- Add foreign key constraints
ALTER TABLE `Messages` ADD CONSTRAINT `fk_user_name` FOREIGN KEY (`user_name`) REFERENCES `User_info` (`user_name`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `Orders` ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`), ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_name`) REFERENCES `User_info` (`user_name`);
ALTER TABLE `Payment` ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`);

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Verification queries (optional - run these to verify import)
SELECT 'Import completed successfully!' as Status;
SELECT 'Tables created:' as Info;
SHOW TABLES;
SELECT 'Sample data counts:' as Info;
SELECT 'Users' as Table_Name, COUNT(*) as Row_Count FROM User_info
UNION ALL
SELECT 'Products', COUNT(*) FROM Products  
UNION ALL
SELECT 'Orders', COUNT(*) FROM Orders
UNION ALL
SELECT 'Messages', COUNT(*) FROM Messages
UNION ALL
SELECT 'Payments', COUNT(*) FROM Payment;
