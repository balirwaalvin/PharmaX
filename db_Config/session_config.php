<?php
// Session-based Database Simulation for Pharmacy-X
// This replaces the MySQL database with PHP sessions for demo purposes

session_start();

// Initialize session data if not exists
if (!isset($_SESSION['pharmacy_data'])) {
    $_SESSION['pharmacy_data'] = [
        'users' => [
            'admin01' => [
                'user_name' => 'admin01',
                'first_name' => 'Moditha',
                'last_name' => 'Marasingha',
                'email' => 'moditha2003@gmail.com',
                'phone_no' => '0716899555',
                'password' => 'mod123',
                'profilepic_url' => null,
                'acc_status' => 'Active',
                'user_type' => 'Admin'
            ],
            'user01' => [
                'user_name' => 'user01',
                'first_name' => 'Kulanya',
                'last_name' => 'Lisaldi',
                'email' => 'alice.johnson@gmail.com',
                'phone_no' => '1234567891',
                'password' => 'userPass01',
                'profilepic_url' => null,
                'acc_status' => 'Active',
                'user_type' => 'Customer'
            ],
            'user02' => [
                'user_name' => 'user02',
                'first_name' => 'Deshan',
                'last_name' => 'GGD',
                'email' => 'bob.williams@gmail.com',
                'phone_no' => '0774545787',
                'password' => 'userPass02',
                'profilepic_url' => null,
                'acc_status' => 'Active',
                'user_type' => 'Customer'
            ]
        ],
        'products' => [
            1 => [
                'product_id' => 1,
                'product_name' => 'Paracetamol',
                'product_description' => 'Effective pain relief for headaches and fever. 500mg tablets.',
                'price' => 150.00,
                'stock_quantity' => 100,
                'image_url' => 'paracetamol.jpg',
                'expire_date' => '2025-12-31'
            ],
            2 => [
                'product_id' => 2,
                'product_name' => 'Ibuprofen',
                'product_description' => 'Anti-inflammatory pain reliever',
                'price' => 200.00,
                'stock_quantity' => 75,
                'image_url' => 'ibuprofen.jpg',
                'expire_date' => '2025-06-30'
            ],
            3 => [
                'product_id' => 3,
                'product_name' => 'Amoxicillin',
                'product_description' => 'Antibiotic used to treat bacterial infections. 250mg capsules.',
                'price' => 600.00,
                'stock_quantity' => 50,
                'image_url' => 'amoxicillin.jpg',
                'expire_date' => '2025-06-30'
            ],
            4 => [
                'product_id' => 4,
                'product_name' => 'Vitamin C Tablets',
                'product_description' => 'Boost your immune system with Vitamin C. 1000mg tablets.',
                'price' => 200.00,
                'stock_quantity' => 100,
                'image_url' => 'vitamin-c.jpg',
                'expire_date' => '2026-03-15'
            ],
            5 => [
                'product_id' => 5,
                'product_name' => 'First Aid Kit',
                'product_description' => 'Complete first aid emergency kit',
                'price' => 4500.00,
                'stock_quantity' => 20,
                'image_url' => 'first-aid-kit.jpg',
                'expire_date' => '2025-12-31'
            ]
        ],
        'orders' => [],
        'messages' => [],
        'payments' => [],
        'counters' => [
            'order_id' => 1,
            'message_id' => 1,
            'payment_id' => 1,
            'product_id' => 6
        ]
    ];
}

// Simulate database connection for compatibility
$Connection = true;
$Database = "Session Storage";
$Server = "localhost";

// Helper functions to simulate database operations

function session_query($sql) {
    global $_SESSION;
    
    // Simple SQL parsing for common operations
    $sql = trim($sql);
    
    // SELECT operations
    if (stripos($sql, 'SELECT') === 0) {
        return handle_select($sql);
    }
    
    // INSERT operations
    if (stripos($sql, 'INSERT') === 0) {
        return handle_insert($sql);
    }
    
    // UPDATE operations
    if (stripos($sql, 'UPDATE') === 0) {
        return handle_update($sql);
    }
    
    // DELETE operations
    if (stripos($sql, 'DELETE') === 0) {
        return handle_delete($sql);
    }
    
    // SHOW TABLES (for compatibility)
    if (stripos($sql, 'SHOW TABLES') === 0) {
        return new SessionResult(['User_info', 'Products', 'Orders', 'Messages', 'Payment']);
    }
    
    return true;
}

function handle_select($sql) {
    global $_SESSION;
    
    $data = $_SESSION['pharmacy_data'];
    
    // User authentication queries
    if (stripos($sql, 'User_info') !== false && stripos($sql, 'WHERE') !== false) {
        if (preg_match('/user_name\s*=\s*[\'"]([^\'"]+)[\'"]/', $sql, $matches)) {
            $username = $matches[1];
            if (isset($data['users'][$username])) {
                return new SessionResult([$data['users'][$username]]);
            }
        }
        if (preg_match('/email\s*=\s*[\'"]([^\'"]+)[\'"]/', $sql, $matches)) {
            $email = $matches[1];
            foreach ($data['users'] as $user) {
                if ($user['email'] === $email) {
                    return new SessionResult([$user]);
                }
            }
        }
        return new SessionResult([]);
    }
    
    // Products queries
    if (stripos($sql, 'Products') !== false) {
        if (stripos($sql, 'WHERE') !== false && preg_match('/product_id\s*=\s*(\d+)/', $sql, $matches)) {
            $product_id = (int)$matches[1];
            if (isset($data['products'][$product_id])) {
                return new SessionResult([$data['products'][$product_id]]);
            }
            return new SessionResult([]);
        } else {
            return new SessionResult(array_values($data['products']));
        }
    }
    
    // Orders queries
    if (stripos($sql, 'Orders') !== false) {
        if (stripos($sql, 'WHERE') !== false && preg_match('/user_name\s*=\s*[\'"]([^\'"]+)[\'"]/', $sql, $matches)) {
            $username = $matches[1];
            $user_orders = array_filter($data['orders'], function($order) use ($username) {
                return $order['user_name'] === $username;
            });
            return new SessionResult(array_values($user_orders));
        } else {
            return new SessionResult(array_values($data['orders']));
        }
    }
    
    // Messages queries
    if (stripos($sql, 'Messages') !== false) {
        return new SessionResult(array_values($data['messages']));
    }
    
    // Count queries
    if (stripos($sql, 'COUNT(*)') !== false) {
        $count = 0;
        if (stripos($sql, 'User_info') !== false) $count = count($data['users']);
        if (stripos($sql, 'Products') !== false) $count = count($data['products']);
        if (stripos($sql, 'Orders') !== false) $count = count($data['orders']);
        if (stripos($sql, 'Messages') !== false) $count = count($data['messages']);
        return new SessionResult([['count' => $count]]);
    }
    
    return new SessionResult([]);
}

function handle_insert($sql) {
    global $_SESSION;
    
    // User registration
    if (stripos($sql, 'User_info') !== false) {
        if (preg_match('/VALUES\s*\((.*?)\)/i', $sql, $matches)) {
            $values = str_getcsv($matches[1], ',', "'");
            $values = array_map('trim', $values);
            $user = [
                'user_name' => trim($values[0], "'\""),
                'first_name' => trim($values[1], "'\""),
                'last_name' => trim($values[2], "'\""),
                'email' => trim($values[3], "'\""),
                'phone_no' => trim($values[4], "'\""),
                'password' => trim($values[5], "'\""),
                'profilepic_url' => $values[6] === 'NULL' ? null : trim($values[6], "'\""),
                'acc_status' => 'Active',
                'user_type' => 'Customer'
            ];
            $_SESSION['pharmacy_data']['users'][$user['user_name']] = $user;
            return true;
        }
    }
    
    // Order insertion
    if (stripos($sql, 'Orders') !== false) {
        $order_id = $_SESSION['pharmacy_data']['counters']['order_id']++;
        // Parse order data from SQL (simplified)
        $order = [
            'order_id' => $order_id,
            'user_name' => $_SESSION['current_user'] ?? 'user01',
            'order_status' => 'Pending',
            'order_type' => 'General',
            'qty' => 1,
            'receiver_name' => 'Demo User',
            'street' => 'Demo Street',
            'city' => 'Demo City',
            'postal_code' => '12345',
            'prescription_url' => null,
            'product_id' => 1,
            'Order_total' => 150.00,
            'order_date' => date('Y-m-d H:i:s')
        ];
        $_SESSION['pharmacy_data']['orders'][$order_id] = $order;
        return true;
    }
    
    return true;
}

function handle_update($sql) {
    // Handle UPDATE queries (simplified)
    return true;
}

function handle_delete($sql) {
    // Handle DELETE queries (simplified)
    return true;
}

// Session Result class to simulate mysqli_result
class SessionResult {
    private $data;
    private $position = 0;
    
    public function __construct($data) {
        $this->data = is_array($data) ? $data : [];
    }
    
    public function fetch_assoc() {
        if ($this->position < count($this->data)) {
            return $this->data[$this->position++];
        }
        return null;
    }
    
    public function fetch_array() {
        return $this->fetch_assoc();
    }
    
    public function num_rows() {
        return count($this->data);
    }
}

// Compatibility functions
function mysqli_query($connection, $sql) {
    return session_query($sql);
}

function mysqli_fetch_assoc($result) {
    return $result ? $result->fetch_assoc() : null;
}

function mysqli_fetch_array($result) {
    return $result ? $result->fetch_array() : null;
}

function mysqli_num_rows($result) {
    return $result ? $result->num_rows() : 0;
}

function mysqli_connect_error() {
    return "Session storage active";
}

function mysqli_error($connection) {
    return "";
}

function mysqli_close($connection) {
    return true;
}

function mysqli_set_charset($connection, $charset) {
    return true;
}

// Set current user for session tracking
if (isset($_SESSION['user_name'])) {
    $_SESSION['current_user'] = $_SESSION['user_name'];
}

error_log("Session-based storage initialized successfully");
?>
