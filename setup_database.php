<?php
// Pharmacy-X Database Setup - Single File Solution
// Copy this entire file content and create it on your DigitalOcean app

require_once 'db_Config/config.php';

// Simple HTML header
echo "<!DOCTYPE html><html><head><title>Pharmacy-X Setup</title><style>body{font-family:Arial;max-width:800px;margin:20px auto;padding:20px}.success{color:green}.error{color:red}.warning{background:#fff3cd;padding:10px;margin:10px 0;border-radius:5px}</style></head><body>";

echo "<h1>🏥 Pharmacy-X Database Setup</h1>";

// Check connection
if (!$Connection) {
    die("<p class='error'>❌ Connection failed: " . mysqli_connect_error() . "</p></body></html>");
}

echo "<p class='success'>✅ Connected to database: $Database</p>";

// Check if already setup
$check = mysqli_query($Connection, "SHOW TABLES LIKE 'User_info'");
if (mysqli_num_rows($check) > 0) {
    echo "<div class='warning'><h3>⚠️ Database Already Setup</h3><p>Your database appears to already be configured. Admin login: <strong>admin01</strong> / <strong>mod123</strong></p></div>";
} else {
    echo "<h2>🚀 Setting up database...</h2>";
    
    // All SQL in one go
    $setupSQL = "
    DROP TABLE IF EXISTS Payment;
    DROP TABLE IF EXISTS Orders; 
    DROP TABLE IF EXISTS Messages;
    DROP TABLE IF EXISTS Products;
    DROP TABLE IF EXISTS User_info;
    
    CREATE TABLE User_info (
        user_name varchar(100) NOT NULL,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        email varchar(255) NOT NULL,
        phone_no varchar(15) DEFAULT NULL,
        password varchar(255) NOT NULL,
        profilepic_url varchar(255) DEFAULT NULL,
        acc_status enum('Active','Inactive') DEFAULT 'Active',
        user_type enum('Admin','Manager','Customer') DEFAULT NULL,
        PRIMARY KEY (user_name),
        UNIQUE KEY email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    INSERT INTO User_info VALUES 
    ('admin01', 'Moditha', 'Marasingha', 'moditha2003@gmail.com', '0716899555', 'mod123', NULL, 'Active', 'Admin'),
    ('user01', 'Kulanya', 'Lisaldi', 'alice.johnson@gmail.com', '1234567891', 'userPass01', NULL, 'Active', 'Customer'),
    ('user02', 'Deshan', 'GGD', 'bob.williams@gmail.com', '0774545787', 'userPass02', NULL, 'Active', 'Customer');
    
    CREATE TABLE Products (
        product_id int(11) NOT NULL AUTO_INCREMENT,
        product_name varchar(255) NOT NULL,
        product_description text DEFAULT NULL,
        price decimal(10,2) NOT NULL,
        stock_quantity int(11) NOT NULL,
        image_url varchar(255) DEFAULT NULL,
        expire_date date NOT NULL,
        PRIMARY KEY (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    INSERT INTO Products (product_name, product_description, price, stock_quantity, image_url, expire_date) VALUES
    ('Paracetamol', 'Effective pain relief for headaches and fever. 500mg tablets.', 150.00, 100, 'paracetamol.jpg', '2025-12-31'),
    ('Ibuprofen', 'Anti-inflammatory pain reliever', 200.00, 75, 'ibuprofen.jpg', '2025-06-30'),
    ('Amoxicillin', 'Antibiotic used to treat bacterial infections. 250mg capsules.', 600.00, 50, 'amoxicillin.jpg', '2025-06-30'),
    ('Vitamin C Tablets', 'Boost your immune system with Vitamin C. 1000mg tablets.', 200.00, 100, 'vitamin-c.jpg', '2026-03-15'),
    ('First Aid Kit', 'Complete first aid emergency kit', 4500.00, 20, 'first-aid-kit.jpg', '2025-12-31');
    
    CREATE TABLE Orders (
        order_id int(11) NOT NULL AUTO_INCREMENT,
        user_name varchar(100) DEFAULT NULL,
        order_status enum('Pending','Shipped','Delivered') DEFAULT 'Pending',
        order_type enum('Prescription','General') DEFAULT NULL,
        qty int(11) DEFAULT NULL,
        receiver_name varchar(255) DEFAULT NULL,
        street varchar(100) DEFAULT NULL,
        city varchar(100) DEFAULT NULL,
        postal_code varchar(10) DEFAULT NULL,
        prescription_url varchar(255) DEFAULT NULL,
        product_id int(11) DEFAULT NULL,
        Order_total decimal(10,2) DEFAULT NULL,
        order_date timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (order_id),
        KEY user_name (user_name),
        KEY product_id (product_id),
        FOREIGN KEY (user_name) REFERENCES User_info (user_name),
        FOREIGN KEY (product_id) REFERENCES Products (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE Messages (
        message_id int(11) NOT NULL AUTO_INCREMENT,
        user_name varchar(100) DEFAULT NULL,
        name varchar(100) DEFAULT NULL,
        message_text text NOT NULL,
        contact_no varchar(15) DEFAULT NULL,
        email varchar(255) NOT NULL,
        Uploads_url varchar(255) DEFAULT NULL,
        response_text text DEFAULT NULL,
        message_date timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (message_id),
        KEY user_name (user_name),
        FOREIGN KEY (user_name) REFERENCES User_info (user_name) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
    CREATE TABLE Payment (
        payment_id int(11) NOT NULL AUTO_INCREMENT,
        order_id int(11) DEFAULT NULL,
        amount decimal(10,2) DEFAULT NULL,
        bank varchar(50) DEFAULT NULL,
        remark text DEFAULT NULL,
        payment_date timestamp NOT NULL DEFAULT current_timestamp(),
        receipt_url varchar(255) DEFAULT NULL,
        PRIMARY KEY (payment_id),
        KEY order_id (order_id),
        FOREIGN KEY (order_id) REFERENCES Orders (order_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    
    // Execute setup
    if (mysqli_multi_query($Connection, $setupSQL)) {
        // Clear results
        while (mysqli_next_result($Connection)) {
            if ($result = mysqli_store_result($Connection)) {
                mysqli_free_result($result);
            }
        }
        echo "<p class='success'>✅ Database setup complete!</p>";
    } else {
        echo "<p class='error'>❌ Setup failed: " . mysqli_error($Connection) . "</p>";
    }
}

// Show login credentials
echo "<div class='warning'>";
echo "<h3>🔑 Login Credentials:</h3>";
echo "<p><strong>Admin:</strong> admin01 / mod123<br>";
echo "<strong>Customer:</strong> user01 / userPass01</p>";
echo "</div>";

echo "<div class='warning'>";
echo "<h3>⚠️ SECURITY WARNING</h3>";
echo "<p><strong>DELETE THIS FILE after setup!</strong></p>";
echo "</div>";

echo "<h2>🎉 Setup Complete!</h2>";
echo "<p><a href='index.php'>← Go to Pharmacy-X Home</a></p>";

mysqli_close($Connection);
echo "</body></html>";
?>
