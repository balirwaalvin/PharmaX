<?php
// Simple Database Import Script
// Upload this file to your DigitalOcean app and visit it in browser

require_once 'db_Config/config.php';

echo "<h1>Database Import Tool</h1>";

// Check if connection is working
if (!$Connection) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<p>✅ Connected to database: $Database</p>";

// Import SQL commands
$sql_commands = [
    // Drop existing tables
    "DROP TABLE IF EXISTS Payment",
    "DROP TABLE IF EXISTS Orders", 
    "DROP TABLE IF EXISTS Messages",
    "DROP TABLE IF EXISTS Products",
    "DROP TABLE IF EXISTS User_info",
    
    // Create User_info table
    "CREATE TABLE `User_info` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
    
    // Insert admin user
    "INSERT INTO `User_info` (`user_name`, `first_name`, `last_name`, `email`, `phone_no`, `password`, `profilepic_url`, `acc_status`, `user_type`) VALUES
    ('admin01', 'Moditha', 'Marasingha', 'moditha2003@gmail.com', '0716899555', 'mod123', NULL, 'Active', 'Admin')",
    
    // Create Products table
    "CREATE TABLE `Products` (
        `product_id` int(11) NOT NULL AUTO_INCREMENT,
        `product_name` varchar(255) NOT NULL,
        `product_description` text DEFAULT NULL,
        `price` decimal(10,2) NOT NULL,
        `stock_quantity` int(11) NOT NULL,
        `image_url` varchar(255) DEFAULT NULL,
        `expire_date` date NOT NULL,
        PRIMARY KEY (`product_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",
    
    // Insert sample products
    "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES
    ('Paracetamol', 'Effective pain relief for headaches and fever. 500mg tablets.', 150.00, 100, 'paracetamol.jpg', '2025-12-31'),
    ('Ibuprofen', 'Anti-inflammatory pain reliever', 200.00, 75, 'ibuprofen.jpg', '2025-06-30')"
];

// Execute each command
foreach ($sql_commands as $sql) {
    echo "<p>Executing: " . substr($sql, 0, 50) . "...</p>";
    
    if (mysqli_query($Connection, $sql)) {
        echo "<p style='color: green;'>✅ Success</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: " . mysqli_error($Connection) . "</p>";
    }
}

// Verify tables were created
echo "<h2>Verification</h2>";
$result = mysqli_query($Connection, "SHOW TABLES");
echo "<p><strong>Tables created:</strong></p><ul>";
while ($row = mysqli_fetch_array($result)) {
    echo "<li>" . $row[0] . "</li>";
}
echo "</ul>";

echo "<p><strong>⚠️ Important: Delete this file after use for security!</strong></p>";

mysqli_close($Connection);
?>
