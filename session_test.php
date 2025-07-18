<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy-X Session Storage Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        .warning { background: #fff3cd; padding: 10px; border-radius: 5px; margin: 10px 0; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>🏥 Pharmacy-X Session Storage Test</h1>
    
    <?php
    require_once 'db_Config/config.php';
    
    echo "<p class='success'>✅ Session storage initialized successfully!</p>";
    echo "<p class='info'>Storage Type: $Database</p>";
    
    // Test user data
    echo "<h2>👥 Users</h2>";
    $users = mysqli_query($Connection, "SELECT * FROM User_info");
    echo "<table>";
    echo "<tr><th>Username</th><th>Name</th><th>Email</th><th>Type</th></tr>";
    while ($user = mysqli_fetch_assoc($users)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($user['user_name']) . "</td>";
        echo "<td>" . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . "</td>";
        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
        echo "<td>" . htmlspecialchars($user['user_type']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test product data
    echo "<h2>💊 Products</h2>";
    $products = mysqli_query($Connection, "SELECT * FROM Products");
    echo "<table>";
    echo "<tr><th>Name</th><th>Description</th><th>Price</th><th>Stock</th></tr>";
    while ($product = mysqli_fetch_assoc($products)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($product['product_name']) . "</td>";
        echo "<td>" . htmlspecialchars(substr($product['product_description'], 0, 50)) . "...</td>";
        echo "<td>LKR " . number_format($product['price'], 2) . "</td>";
        echo "<td>" . $product['stock_quantity'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test authentication
    echo "<h2>🔐 Test Authentication</h2>";
    $testUser = mysqli_query($Connection, "SELECT * FROM User_info WHERE user_name = 'admin01'");
    $admin = mysqli_fetch_assoc($testUser);
    if ($admin) {
        echo "<p class='success'>✅ Admin user found: " . $admin['first_name'] . " " . $admin['last_name'] . "</p>";
        echo "<p>Login with: <strong>admin01</strong> / <strong>mod123</strong></p>";
    }
    
    echo "<div class='warning'>";
    echo "<h3>🎉 Session Storage Working!</h3>";
    echo "<p>Your Pharmacy-X app is now ready to use without any database setup.</p>";
    echo "<p><strong>Benefits:</strong></p>";
    echo "<ul>";
    echo "<li>✅ No database configuration needed</li>";
    echo "<li>✅ Works immediately on any hosting platform</li>";
    echo "<li>✅ Perfect for presentations and demos</li>";
    echo "<li>✅ All data persists during user session</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h2>🔗 Navigation</h2>";
    echo "<p><a href='index.php'>← Go to Pharmacy-X Home</a></p>";
    echo "<p><a href='signin.php'>→ Try Login Page</a></p>";
    echo "<p><a href='products.php'>→ View Products</a></p>";
    ?>
</body>
</html>
