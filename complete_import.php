<?php
// Complete Database Import Script for DigitalOcean
// Upload this file and visit it in your browser to import the database

require_once 'db_Config/config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy-X Database Import</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        .warning { background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>";

echo "<h1>🏥 Pharmacy-X Database Import Tool</h1>";

// Check connection first
if (!$Connection) {
    die("<p class='error'>❌ Connection failed: " . mysqli_connect_error() . "</p>");
}

echo "<p class='success'>✅ Connected to database: $Database</p>";
echo "<p class='info'>🔗 Host: $Server:$Port</p>";

// Function to execute SQL and show results
function executeSQL($connection, $sql, $description) {
    echo "<h3>$description</h3>";
    echo "<p><code>" . substr($sql, 0, 100) . "...</code></p>";
    
    if (mysqli_query($connection, $sql)) {
        echo "<p class='success'>✅ Success</p>";
        return true;
    } else {
        echo "<p class='error'>❌ Error: " . mysqli_error($connection) . "</p>";
        return false;
    }
}

// Start import process
echo "<h2>🚀 Starting Database Import</h2>";

// Step 1: Drop existing tables
echo "<h2>Step 1: Cleaning existing tables</h2>";
$dropTables = [
    "DROP TABLE IF EXISTS Payment",
    "DROP TABLE IF EXISTS Orders", 
    "DROP TABLE IF EXISTS Messages",
    "DROP TABLE IF EXISTS Products",
    "DROP TABLE IF EXISTS User_info"
];

foreach ($dropTables as $sql) {
    executeSQL($Connection, $sql, "Dropping table");
}

// Step 2: Create User_info table
echo "<h2>Step 2: Creating User_info table</h2>";
$createUserInfo = "CREATE TABLE `User_info` (
    `user_name` varchar(100) NOT NULL,
    `first_name` varchar(100) NOT NULL,
    `last_name` varchar(100) NOT NULL,
    `email` varchar(255) NOT NULL,
    `phone_no` varchar(15) DEFAULT NULL,
    `password` varchar(255) NOT NULL,
    `profilepic_url` varchar(255) DEFAULT NULL,
    `acc_status` enum('Active','Inactive') DEFAULT 'Active',
    `user_type` enum('Admin','Manager','Customer') DEFAULT NULL,
    PRIMARY KEY (`user_name`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

executeSQL($Connection, $createUserInfo, "Creating User_info table");

// Step 3: Insert users
echo "<h2>Step 3: Adding users</h2>";
$insertUsers = [
    "INSERT INTO `User_info` (`user_name`, `first_name`, `last_name`, `email`, `phone_no`, `password`, `profilepic_url`, `acc_status`, `user_type`) VALUES
    ('admin01', 'Moditha', 'Marasingha', 'moditha2003@gmail.com', '0716899555', 'mod123', NULL, 'Active', 'Admin')",
    
    "INSERT INTO `User_info` (`user_name`, `first_name`, `last_name`, `email`, `phone_no`, `password`, `profilepic_url`, `acc_status`, `user_type`) VALUES
    ('user01', 'Kulanya', 'Lisaldi', 'alice.johnson@gmail.com', '1234567891', 'userPass01', NULL, 'Active', 'Customer')",
    
    "INSERT INTO `User_info` (`user_name`, `first_name`, `last_name`, `email`, `phone_no`, `password`, `profilepic_url`, `acc_status`, `user_type`) VALUES
    ('user02', 'Deshan', 'GGD', 'bob.williams@gmail.com', '0774545787', 'userPass02', NULL, 'Active', 'Customer')"
];

foreach ($insertUsers as $sql) {
    executeSQL($Connection, $sql, "Adding user");
}

// Step 4: Create Products table
echo "<h2>Step 4: Creating Products table</h2>";
$createProducts = "CREATE TABLE `Products` (
    `product_id` int(11) NOT NULL AUTO_INCREMENT,
    `product_name` varchar(255) NOT NULL,
    `product_description` text DEFAULT NULL,
    `price` decimal(10,2) NOT NULL,
    `stock_quantity` int(11) NOT NULL,
    `image_url` varchar(255) DEFAULT NULL,
    `expire_date` date NOT NULL,
    PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

executeSQL($Connection, $createProducts, "Creating Products table");

// Step 5: Insert products
echo "<h2>Step 5: Adding products</h2>";
$insertProducts = [
    "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES
    ('Paracetamol', 'Effective pain relief for headaches and fever. 500mg tablets.', 150.00, 100, 'paracetamol.jpg', '2025-12-31')",
    
    "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES
    ('Ibuprofen', 'Anti-inflammatory pain reliever', 200.00, 75, 'ibuprofen.jpg', '2025-06-30')",
    
    "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES
    ('Amoxicillin', 'Antibiotic used to treat bacterial infections. 250mg capsules.', 600.00, 50, 'amoxicillin.jpg', '2025-06-30')",
    
    "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES
    ('Vitamin C Tablets', 'Boost your immune system with Vitamin C. 1000mg tablets.', 200.00, 100, 'vitamin-c.jpg', '2026-03-15')",
    
    "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES
    ('First Aid Kit', 'Complete first aid emergency kit', 4500.00, 20, 'first-aid-kit.jpg', '2025-12-31')"
];

foreach ($insertProducts as $sql) {
    executeSQL($Connection, $sql, "Adding product");
}

// Step 6: Create remaining tables
echo "<h2>Step 6: Creating remaining tables</h2>";

// Orders table
$createOrders = "CREATE TABLE `Orders` (
    `order_id` int(11) NOT NULL AUTO_INCREMENT,
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
    `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`order_id`),
    KEY `user_name` (`user_name`),
    KEY `product_id` (`product_id`),
    FOREIGN KEY (`user_name`) REFERENCES `User_info` (`user_name`),
    FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

executeSQL($Connection, $createOrders, "Creating Orders table");

// Messages table
$createMessages = "CREATE TABLE `Messages` (
    `message_id` int(11) NOT NULL AUTO_INCREMENT,
    `user_name` varchar(100) DEFAULT NULL,
    `name` varchar(100) DEFAULT NULL,
    `message_text` text NOT NULL,
    `contact_no` varchar(15) DEFAULT NULL,
    `email` varchar(255) NOT NULL,
    `Uploads_url` varchar(255) DEFAULT NULL,
    `response_text` text DEFAULT NULL,
    `message_date` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`message_id`),
    KEY `user_name` (`user_name`),
    FOREIGN KEY (`user_name`) REFERENCES `User_info` (`user_name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

executeSQL($Connection, $createMessages, "Creating Messages table");

// Payment table
$createPayment = "CREATE TABLE `Payment` (
    `payment_id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) DEFAULT NULL,
    `amount` decimal(10,2) DEFAULT NULL,
    `bank` varchar(50) DEFAULT NULL,
    `remark` text DEFAULT NULL,
    `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
    `receipt_url` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`payment_id`),
    KEY `order_id` (`order_id`),
    FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

executeSQL($Connection, $createPayment, "Creating Payment table");

// Step 7: Verify import
echo "<h2>Step 7: Verification</h2>";

$result = mysqli_query($Connection, "SHOW TABLES");
echo "<h3>📋 Tables created:</h3><ul>";
while ($row = mysqli_fetch_array($result)) {
    echo "<li><strong>" . $row[0] . "</strong></li>";
}
echo "</ul>";

// Count records
$tables = ['User_info', 'Products', 'Orders', 'Messages', 'Payment'];
echo "<h3>📊 Record counts:</h3><ul>";
foreach ($tables as $table) {
    $result = mysqli_query($Connection, "SELECT COUNT(*) as count FROM `$table`");
    $count = mysqli_fetch_array($result)['count'];
    echo "<li><strong>$table:</strong> $count records</li>";
}
echo "</ul>";

// Show admin login details
echo "<div class='warning'>";
echo "<h3>🔑 Login Credentials for Testing:</h3>";
echo "<p><strong>Admin Login:</strong><br>";
echo "Username: <code>admin01</code><br>";
echo "Password: <code>mod123</code></p>";
echo "<p><strong>Customer Login:</strong><br>";
echo "Username: <code>user01</code><br>";
echo "Password: <code>userPass01</code></p>";
echo "</div>";

echo "<div class='warning'>";
echo "<h3>⚠️ IMPORTANT SECURITY NOTE</h3>";
echo "<p><strong>DELETE THIS FILE IMMEDIATELY AFTER USE!</strong></p>";
echo "<p>This file contains database operations and should not remain accessible in production.</p>";
echo "</div>";

echo "<h2>🎉 Database Import Complete!</h2>";
echo "<p class='success'>Your Pharmacy-X database has been successfully set up. You can now test your application.</p>";

mysqli_close($Connection);

echo "</body></html>";
?>
