<?php 
//Marasingha M A M N IT23539990

session_start();

//check if the user is logged in
if(isset($_SESSION['username']))
{
    //check if the user is admin
    if($_SESSION['user_type']=='Admin')
    {
        require_once './db_Config/config.php';
    }
    else if($_SESSION['user_type']=='Manager')
    {
        header('location: ./manager_DB.php');
    }
    else
    {
        header('location: ./index.php');
    }
}
else
{
    header('location: ./index.php');
}

// Initialize session storage if not exists
if (!isset($_SESSION['users'])) {
    require_once './db_Config/session_config.php';
}

// AJAX request handlers
if(isset($_POST['ajax_action'])) {
    header('Content-Type: application/json');
    
    switch($_POST['ajax_action']) {
        case 'update_user_status':
            $username = $_POST['username'];
            $action = $_POST['action'];
            $result = updateUserStatus($username, $action);
            echo json_encode($result);
            exit;
            
        case 'update_order_status':
            $orderId = $_POST['order_id'];
            $status = $_POST['status'];
            $result = updateOrderStatus($orderId, $status);
            echo json_encode($result);
            exit;
            
        case 'reply_message':
            $messageId = $_POST['message_id'];
            $reply = $_POST['reply'];
            $result = replyToMessage($messageId, $reply);
            echo json_encode($result);
            exit;
            
        case 'delete_message':
            $messageId = $_POST['message_id'];
            $result = deleteMessage($messageId);
            echo json_encode($result);
            exit;
    }
}

// Helper functions for AJAX actions
function updateUserStatus($username, $action) {
    if(empty($username)) {
        return ['success' => false, 'message' => 'Username is required'];
    }
    
    if(!isset($_SESSION['users'][$username])) {
        return ['success' => false, 'message' => 'User not found'];
    }
    
    if($_SESSION['users'][$username]['user_type'] !== 'Customer') {
        return ['success' => false, 'message' => 'Cannot modify admin users'];
    }
    
    switch($action) {
        case 'activate':
            $_SESSION['users'][$username]['acc_status'] = 'Active';
            logActivity("User $username activated by admin");
            return ['success' => true, 'message' => 'User activated successfully'];
            
        case 'deactivate':
            $_SESSION['users'][$username]['acc_status'] = 'Inactive';
            logActivity("User $username deactivated by admin");
            return ['success' => true, 'message' => 'User deactivated successfully'];
            
        case 'delete':
            unset($_SESSION['users'][$username]);
            logActivity("User $username deleted by admin");
            return ['success' => true, 'message' => 'User deleted successfully'];
            
        default:
            return ['success' => false, 'message' => 'Invalid action'];
    }
}

function updateOrderStatus($orderId, $status) {
    if(!isset($_SESSION['orders'][$orderId])) {
        return ['success' => false, 'message' => 'Order not found'];
    }
    
    $_SESSION['orders'][$orderId]['order_status'] = $status;
    logActivity("Order $orderId marked as $status by admin");
    return ['success' => true, 'message' => "Order marked as $status"];
}

function replyToMessage($messageId, $reply) {
    if(!isset($_SESSION['messages'][$messageId])) {
        return ['success' => false, 'message' => 'Message not found'];
    }
    
    $_SESSION['messages'][$messageId]['response_text'] = $reply;
    logActivity("Reply sent to message $messageId by admin");
    return ['success' => true, 'message' => 'Reply sent successfully'];
}

function deleteMessage($messageId) {
    if(!isset($_SESSION['messages'][$messageId])) {
        return ['success' => false, 'message' => 'Message not found'];
    }
    
    unset($_SESSION['messages'][$messageId]);
    logActivity("Message $messageId deleted by admin");
    return ['success' => true, 'message' => 'Message deleted successfully'];
}

function logActivity($activity) {
    if(!isset($_SESSION['activity_log'])) {
        $_SESSION['activity_log'] = [];
    }
    
    $_SESSION['activity_log'][] = [
        'timestamp' => date('Y-m-d H:i:s'),
        'activity' => $activity
    ];
    
    // Keep only last 100 activities
    if(count($_SESSION['activity_log']) > 100) {
        $_SESSION['activity_log'] = array_slice($_SESSION['activity_log'], -100);
    }
}

//check user status functionality
$UserStatus = '-----';
$inputValue = '';

if(isset($_POST['Check']))
{
    $username = $_POST['UserInput'];
    $inputValue = $username;

    //check username is empty or not
    if(empty($username))
    {
        $UserStatus = 'Empty';
    }
    else
    {
        if(isset($_SESSION['users'][$username])) {
            $UserStatus = $_SESSION['users'][$username]['acc_status'];
        } else {
            $UserStatus = 'Invalid UN';
        }
    }
}

//user activate, deactivate and delete functionality
if(isset($_POST['activate']))
{
    $username = $_POST['UserInput'];
    $result = updateUserStatus($username, 'activate');
    $UserStatus = $result['success'] ? 'Activated' : 'Failed';
}

if(isset($_POST['deactivate']))
{
    $username = $_POST['UserInput'];
    $result = updateUserStatus($username, 'deactivate');
    $UserStatus = $result['success'] ? 'Deactivated' : 'Failed';

}

if(isset($_POST['delete']))
{
    $username = $_POST['UserInput'];
    $result = updateUserStatus($username, 'delete');
    $UserStatus = $result['success'] ? 'Deleted' : 'Failed';
}

// Get user statistics from session
$TotalUsers = 0;
$ActiveUsers = 0;
$DeactivatedUsers = 0;

if(isset($_SESSION['users'])) {
    foreach($_SESSION['users'] as $user) {
        if($user['user_type'] === 'Customer') {
            $TotalUsers++;
            if($user['acc_status'] === 'Active') {
                $ActiveUsers++;
            } elseif($user['acc_status'] === 'Inactive') {
                $DeactivatedUsers++;
            }
        }
    }
}

//Order Requests section mark as shipped functionality
if(isset($_POST['Shipped']))
{
    $orderID = $_POST['order_id'];
    $result = updateOrderStatus($orderID, 'Shipped');
    if($result['success']) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

//Message Reply and Delete functionality
//submit reply
if(isset($_POST['submit_rply']))
{
    $messageID = $_POST['message_id'];
    $reply = $_POST['Reply_in'];
    $result = replyToMessage($messageID, $reply);
    if($result['success']) {
        header("Location: ".$_SERVER['PHP_SELF']);
    }
}

//delete message
if(isset($_POST['Delete_msg']))
{
    $messageID = $_POST['message_id'];

    $result = deleteMessage($messageID);
    if($result['success']) {
        header("Location: ".$_SERVER['PHP_SELF']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Real-time</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="./Images/Pharmacy X Icon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./Images/Pharmacy X Icon.png">
    <link rel="stylesheet" href="./CSS/admin_DB.css">
    <style>
        /* Real-time dashboard styles */
        .realtime-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            background-color: #4CAF50;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        
        .status-active { background-color: #4CAF50; }
        .status-inactive { background-color: #f44336; }
        .status-pending { background-color: #FF9800; }
        .status-shipped { background-color: #2196F3; }
        .status-paid { background-color: #4CAF50; }
        .status-unpaid { background-color: #f44336; }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            display: none;
        }
        
        .notification.success { background-color: #4CAF50; }
        .notification.error { background-color: #f44336; }
        
        .activity-feed {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-top: 10px;
        }
        
        .activity-item {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-time {
            color: #666;
            font-size: 12px;
        }
        
        .refresh-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .refresh-btn:hover {
            background-color: #45a049;
        }
        
        .auto-refresh-toggle {
            margin-top: 10px;
        }
        
        .auto-refresh-toggle label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .auto-refresh-toggle input[type="checkbox"] {
            margin-right: 8px;
        }
    </style>
    <script src="./JS/admin_DB.js"></script>
    <script src="./JS/admin_realtime.js"></script>
</head>
<body>
    <?php include ("./header.php"); ?>

    <!-- Notification container -->
    <div id="notification" class="notification"></div>

    <div class="Container1">
        <div class="user_box">
            <h2 class="user-heading">
                <span class="realtime-indicator"></span>
                Manage Users (Real-time)
            </h2>
            <div class="user_content">
                <div class="auto-refresh-toggle">
                    <label>
                        <input type="checkbox" id="autoRefreshToggle" checked>
                        Auto-refresh enabled
                    </label>
                    <button class="refresh-btn" onclick="adminDashboard.refreshData()">Refresh Now</button>
                </div>
                
                <form id="userManagementForm">
                    <div class="check-users"> 
                        <input type="text" class="check-user-input" placeholder="Username" name="UserInput" id="userInput" value="<?php echo $inputValue; ?>" >
                        <button type="button" class="check-user-button" onclick="adminDashboard.checkUserStatus()">Check</button>
                    </div>

                    <div class="user-satus-container">
                        <h3 class="status-heading">Status : </h3>
                        <div class="user-status" id="userStatus"><?php echo $UserStatus; ?></div>
                    </div>

                    <div class="user-control-buttons">
                        <button type="button" class="action_btn bg" onclick="adminDashboard.updateUserStatus('activate')">Activate</button>
                        <button type="button" class="action_btn bg" onclick="adminDashboard.updateUserStatus('deactivate')">Deactivate</button>
                        <button type="button" class="delete" onclick="adminDashboard.updateUserStatus('delete')">Delete</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="stat_box">
            <h2 class="user-heading">User Statistics Overview</h2>
            <div class="stat_content" id="userStats">
                <h3>Total Registered Users: <span class="stat_value" id="totalUsers"><?php echo $TotalUsers; ?></span></h3>
                <h3>Active Users: <span class="stat_value" id="activeUsers"><?php echo $ActiveUsers; ?></span></h3>
                <h3>Deactivated Users: <span class="stat_value" id="deactivatedUsers"><?php echo $DeactivatedUsers; ?></span></h3>
            </div>
            
            <div class="activity-feed">
                <h4>Recent Activity</h4>
                <div id="activityFeed">
                    <!-- Activity items will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <div class="Container2">
        <h2><span class="realtime-indicator"></span>Order Requests (Real-time)</h2>
        <div class="order_content">
            <table id="ordersTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(isset($_SESSION['orders'])) {
                            foreach($_SESSION['orders'] as $orderId => $order) {
                                if($order['order_status'] === 'Pending' || $order['order_status'] === 'Shipped') {
                                    $paymentStatus = 'Not Paid';
                                    if(isset($_SESSION['payments'])) {
                                        foreach($_SESSION['payments'] as $payment) {
                                            if($payment['order_id'] == $orderId) {
                                                $paymentStatus = 'Paid';
                                                break;
                                            }
                                        }
                                    }
                                    
                                    $orderType = $order['order_type'];
                                    $prescriptionLink = '';
                                    if($orderType === 'Prescription' && !empty($order['prescription_url'])) {
                                        $prescriptionLink = "./Images/PrescriptionOrders/{$order['prescription_url']}";
                                        $orderType = "<a href='$prescriptionLink' target='_blank'>Prescription</a>";
                                    }
                                    
                                    $statusClass = $order['order_status'] === 'Shipped' ? 'status-shipped' : 'status-pending';
                                    $paymentClass = $paymentStatus === 'Paid' ? 'status-paid' : 'status-unpaid';
                                    
                                    echo "<tr data-order-id='$orderId'>
                                        <td><span class='ordertb_title'>ID:</span> $orderId</td>
                                        <td><span class='ordertb_title'>Customer:</span> {$order['user_name']}</td>
                                        <td><span class='ordertb_title'>Date:</span> {$order['order_date']}</td>
                                        <td><span class='ordertb_title'>Status:</span> <span class='status-badge $statusClass'>{$order['order_status']}</span></td>
                                        <td><span class='ordertb_title'>Total:</span> {$order['Order_total']}</td>
                                        <td><span class='status-badge $paymentClass'>$paymentStatus</span></td>
                                        <td>$orderType</td>
                                        <td class='action-cell'>";
                                    
                                    if($order['order_status'] === 'Shipped') {
                                        echo "<button class='action_btn' disabled>Already Shipped</button>";
                                    } else {
                                        echo "<button class='action_btn bg' onclick='adminDashboard.updateOrderStatus(\"$orderId\", \"Shipped\")'>Mark as Shipped</button>";
                                    }
                                    
                                    echo "</td></tr>";
                                }
                            }
                        } else {
                            echo "<tr><td colspan='8'>No Orders Found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="Container2">
        <h2><span class="realtime-indicator"></span>Inbox (Real-time)</h2>
        <div class="message_content">
            <table id="messagesTable">
                <thead>
                    <tr>
                        <th>Message ID</th>
                        <th>Sender Username</th>
                        <th>Message</th>
                        <th>Attachments</th>
                        <th>Reply</th>
                        <th>Submit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(isset($_SESSION['messages'])) {
                            foreach($_SESSION['messages'] as $messageId => $message) {
                                if(empty($message['response_text'])) {
                                    $attachment = $message['Uploads_url'] ?? null;
                                    $uploadsUrl = $attachment ? "<a href='./Images/PrescriptionMessage/$attachment' target='_blank'>Link</a>" : "----";
                                    
                                    echo "<tr data-message-id='$messageId'>
                                        <td>$messageId</td>
                                        <td>{$message['user_name']}</td>
                                        <td>{$message['message_text']}</td>
                                        <td>$uploadsUrl</td>
                                        <td><input class='Reply_in' type='text' placeholder='Reply' id='reply_$messageId'></td>
                                        <td><button type='button' class='Submit_rply' onclick='adminDashboard.replyToMessage(\"$messageId\")'>Submit</button></td>
                                        <td><button type='button' class='Delete_rply' onclick='adminDashboard.deleteMessage(\"$messageId\")'>Delete</button></td>
                                    </tr>";
                                }
                            }
                        } else {
                            echo "<tr><td colspan='7'>No Messages Found</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <hr class="order_hr">

    <div class="manage_product">
        <button class="action_btn bg" onclick="manage_Product()">Manage Products</button>
    </div>

    <script>
        // Initialize the admin dashboard when page loads
        document.addEventListener('DOMContentLoaded', function() {
            adminDashboard.init();
        });
    </script>

    <?php include ("./footer.php"); ?>
</body>
</html>