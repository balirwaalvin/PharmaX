<?php
// Database connection test and import tool for DigitalOcean deployment
// WARNING: Remove this file after testing for security reasons

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>PharmacyX Database Setup Tool</h1>";
echo "<p><strong>Environment:</strong> " . (isset($_ENV['DATABASE_URL']) ? 'DigitalOcean Production' : 'Local Development') . "</p>";

// Include database configuration
require_once 'db_Config/config.php';

// Use the connection variable from config.php
$conn = $Connection;

// Check for import action
$doImport = isset($_GET['import']) && $_GET['import'] === 'yes';

try {
    if (!$conn || mysqli_connect_error()) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
    
    if ($doImport) {
        echo "<h2>🚀 Starting Database Import</h2>";
        
        // Import function
        function executeSQL($connection, $sql, $description) {
            echo "<h3>$description</h3>";
            echo "<p><code>" . substr($sql, 0, 80) . "...</code></p>";
            
            if (mysqli_query($connection, $sql)) {
                echo "<p style='color: green;'>✅ Success</p>";
                return true;
            } else {
                echo "<p style='color: red;'>❌ Error: " . mysqli_error($connection) . "</p>";
                return false;
            }
        }
        
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
            executeSQL($conn, $sql, "Dropping table");
        }
        
        // Step 2: Create and populate User_info table
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
        
        executeSQL($conn, $createUserInfo, "Creating User_info table");
        
        // Insert users
        $insertUsers = [
            "INSERT INTO `User_info` VALUES ('admin01', 'Moditha', 'Marasingha', 'moditha2003@gmail.com', '0716899555', 'mod123', NULL, 'Active', 'Admin')",
            "INSERT INTO `User_info` VALUES ('user01', 'Kulanya', 'Lisaldi', 'alice.johnson@gmail.com', '1234567891', 'userPass01', NULL, 'Active', 'Customer')",
            "INSERT INTO `User_info` VALUES ('user02', 'Deshan', 'GGD', 'bob.williams@gmail.com', '0774545787', 'userPass02', NULL, 'Active', 'Customer')"
        ];
        
        foreach ($insertUsers as $sql) {
            executeSQL($conn, $sql, "Adding user");
        }
        
        // Step 3: Create and populate Products table
        echo "<h2>Step 3: Creating Products table</h2>";
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
        
        executeSQL($conn, $createProducts, "Creating Products table");
        
        $insertProducts = [
            "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES ('Paracetamol', 'Pain relief tablets 500mg', 150.00, 100, 'paracetamol.jpg', '2025-12-31')",
            "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES ('Ibuprofen', 'Anti-inflammatory pain reliever', 200.00, 75, 'ibuprofen.jpg', '2025-06-30')",
            "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES ('Amoxicillin', 'Antibiotic 250mg capsules', 600.00, 50, 'amoxicillin.jpg', '2025-06-30')",
            "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES ('Vitamin C', 'Immune system booster 1000mg', 200.00, 100, 'vitamin-c.jpg', '2026-03-15')",
            "INSERT INTO `Products` (`product_name`, `product_description`, `price`, `stock_quantity`, `image_url`, `expire_date`) VALUES ('First Aid Kit', 'Complete emergency kit', 4500.00, 20, 'first-aid.jpg', '2025-12-31')"
        ];
        
        foreach ($insertProducts as $sql) {
            executeSQL($conn, $sql, "Adding product");
        }
        
        // Step 4: Create remaining tables
        echo "<h2>Step 4: Creating remaining tables</h2>";
        
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
            PRIMARY KEY (`order_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        
        executeSQL($conn, $createOrders, "Creating Orders table");
        
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
            PRIMARY KEY (`message_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        
        executeSQL($conn, $createMessages, "Creating Messages table");
        
        // Payment table
        $createPayment = "CREATE TABLE `Payment` (
            `payment_id` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` int(11) DEFAULT NULL,
            `amount` decimal(10,2) DEFAULT NULL,
            `bank` varchar(50) DEFAULT NULL,
            `remark` text DEFAULT NULL,
            `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
            `receipt_url` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`payment_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        
        executeSQL($conn, $createPayment, "Creating Payment table");
        
        echo "<h2>🎉 Import Complete!</h2>";
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>✅ Database Setup Successful!</h3>";
        echo "<p><strong>Login Credentials:</strong></p>";
        echo "<ul>";
        echo "<li><strong>Admin:</strong> Username: <code>admin01</code>, Password: <code>mod123</code></li>";
        echo "<li><strong>Customer:</strong> Username: <code>user01</code>, Password: <code>userPass01</code></li>";
        echo "</ul>";
        echo "<p><strong>⚠️ IMPORTANT:</strong> Delete this db_test.php file after testing!</p>";
        echo "</div>";
        
    } else {
        // Show connection test and import option
        echo "<h2>Connection Test</h2>";
        echo "<p style='color: green;'>✅ Database connected successfully!</p>";
        echo "<p><strong>Database:</strong> " . $Database . "</p>";
        echo "<p><strong>Host:</strong> " . $Server . "</p>";
        
        // Test table existence and count records
        echo "<h2>Database Status</h2>";
        
        $tables = ['User_info', 'Products', 'Orders', 'Messages', 'Payment'];
        $allTablesExist = true;
        
        foreach ($tables as $table) {
            $sql = "SELECT COUNT(*) as count FROM `$table`";
            $result = mysqli_query($conn, $sql);
            
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                echo "<p>✅ Table <strong>$table</strong>: {$row['count']} records</p>";
            } else {
                echo "<p style='color: red;'>❌ Table <strong>$table</strong>: Not found</p>";
                $allTablesExist = false;
            }
        }
        
        if (!$allTablesExist) {
            echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3>🔧 Database Setup Required</h3>";
            echo "<p>Some tables are missing. Click the button below to automatically create and populate your database:</p>";
            echo "<p><a href='?import=yes' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>🚀 Import Database Schema</a></p>";
            echo "</div>";
        } else {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3>✅ Database Ready!</h3>";
            echo "<p>Your database is properly set up and ready to use.</p>";
            echo "<p><strong>Test Login Credentials:</strong></p>";
            echo "<ul>";
            echo "<li><strong>Admin:</strong> Username: <code>admin01</code>, Password: <code>mod123</code></li>";
            echo "<li><strong>Customer:</strong> Username: <code>user01</code>, Password: <code>userPass01</code></li>";
            echo "</ul>";
            echo "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h2 style='color: #721c24; margin-top: 0;'>❌ Database Error</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Troubleshooting:</strong></p>";
    echo "<ul>";
    echo "<li>Check if the database connection details are correct</li>";
    echo "<li>Verify DATABASE_URL environment variable is set correctly</li>";
    echo "<li>Check DigitalOcean database connection settings</li>";
    echo "<li>Review database logs in DigitalOcean console</li>";
    echo "</ul>";
    echo "</div>";
    
    // Show environment variables for debugging (safely)
    echo "<h3>Environment Debug Info</h3>";
    echo "<p><strong>DATABASE_URL set:</strong> " . (isset($_ENV['DATABASE_URL']) ? 'Yes' : 'No') . "</p>";
    echo "<p><strong>DB_HOST set:</strong> " . (isset($_ENV['DB_HOST']) ? 'Yes' : 'No') . "</p>";
    echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
} finally {
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f8f9fa;
}

h1, h2, h3 {
    color: #333;
}

.warning {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    padding: 10px;
    border-radius: 5px;
    margin: 10px 0;
    color: #856404;
}
</style>

<div class="warning">
    <strong>Security Warning:</strong> This file should be deleted after testing. It exposes database information and should not be accessible in production.
</div>
