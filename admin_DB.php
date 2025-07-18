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
        $sql = "SELECT acc_status FROM User_info WHERE user_name='$username' ";
        $result = mysqli_query($Connection, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_assoc($result);
            $UserStatus = $row['acc_status'];
        }
        else
        {
            $UserStatus = 'Invalid UN';
        }
    }
}


//user activate, deactivate and delete functionality
if(isset($_POST['activate']))
{
    $username = $_POST['UserInput'];

    if(empty($username))
    {
        $UserStatus = 'Empty';
    }
    else
    {
        $sql = "UPDATE User_info SET acc_status='Active' WHERE user_name='$username' AND user_type='Customer' ";
        $result = mysqli_query($Connection, $sql);

        if($result && mysqli_affected_rows($Connection) > 0)
        {
            $UserStatus = 'Activated';
        }
        else
        {
            $UserStatus = 'Failed';
        }
    }
}

if(isset($_POST['deactivate']))
{
    $username = $_POST['UserInput'];

    if(empty($username))
    {
        $UserStatus = 'Empty';
    }
    else
    {
        $sql = "UPDATE User_info SET acc_status='Inactive' WHERE user_name='$username' AND user_type='Customer' ";
        $result = mysqli_query($Connection, $sql);

        if($result && mysqli_affected_rows($Connection) > 0)
        {
            $UserStatus = 'Deactivated';
        }
        else
        {
            $UserStatus = 'Failed';
        }
    }
}

if(isset($_POST['delete']))
{
    $username = $_POST['UserInput'];

    if(empty($username))
    {
        $UserStatus = 'Empty';
    }
    else
    {
        $sql = "DELETE FROM User_info WHERE user_name='$username' AND user_type='Customer'";
        $result = mysqli_query($Connection, $sql);

        if($result && mysqli_affected_rows($Connection) > 0)
        {
            $UserStatus = 'Deleted';
        }
        else
        {
            $UserStatus = 'Failed';
        }
    }
}


//User Analytics

//count all users and active users and deactivated users
$sql = "SELECT 
            COUNT(user_name) AS TotalUsers,
            COUNT(CASE WHEN acc_status = 'Active' THEN 1 END) AS ActiveUsers,
            COUNT(CASE WHEN acc_status = 'Inactive' THEN 1 END) AS DeactivatedUsers
        FROM 
            User_info
        WHERE 
            user_type = 'Customer';
        ";
$result = mysqli_query($Connection, $sql);

if(mysqli_num_rows($result) > 0)
{
    $row = mysqli_fetch_assoc($result);
    $TotalUsers = $row['TotalUsers'];
    $ActiveUsers = $row['ActiveUsers'];
    $DeactivatedUsers = $row['DeactivatedUsers'];
}
else
{
    $TotalUsers = 0;
    $ActiveUsers = 0;
    $DeactivatedUsers = 0;
}


//Order Requests section mark as shipped functionality
if(isset($_POST['Shipped']))
{
    $orderID = $_POST['order_id'];

    $sql = "UPDATE Orders SET order_status= 'Shipped' WHERE order_id='$orderID' ";
    $result = mysqli_query($Connection, $sql);

    if($result && mysqli_affected_rows($Connection) > 0)
    {
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

    $sql = "UPDATE Messages SET response_text='$reply' WHERE message_id='$messageID' ";
    $result = mysqli_query($Connection, $sql);

    if($result && mysqli_affected_rows($Connection) > 0)
    {
        header("Location: ".$_SERVER['PHP_SELF']);
    }
}
//delete message
if(isset($_POST['Delete_msg']))
{
    $messageID = $_POST['message_id'];

    $sql = "DELETE FROM Messages WHERE message_id='$messageID' ";
    $result = mysqli_query($Connection, $sql);

    if($result && mysqli_affected_rows($Connection) > 0)
    {
        header("Location: ".$_SERVER['PHP_SELF']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="./Images/Pharmacy X Icon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./Images/Pharmacy X Icon.png">
    <link rel="stylesheet" href="./CSS/admin_DB.css">
    <script src="./JS/admin_DB.js"></script>
</head>
<body>
    <?php include ("./header.php"); ?>

    <div class="Container1">
        <div class="user_box">
            <h2 class="user-heading">Manage Users</h2>
            <div class="user_content">
                <form action="admin_DB.php" method="POST">
                    <div class="check-users"> 
                        <input type="text" class="check-user-input" placeholder="Username" name="UserInput" value="<?php echo $inputValue; ?>" >
                        <button type="submit" class="check-user-button" name="Check">Check</button>
                    </div>

                    <div class="user-satus-container">
                        <h3 class="status-heading">Status : </h3>
                        <div class="user-status"><?php echo $UserStatus; ?></div>
                    </div>

                    <div class="user-control-buttons">
                        <button class="action_btn bg" name="activate" >Activate</button>
                        <button class="action_btn bg" name="deactivate" >Deactivate</button>
                        <button class="delete" name="delete" >Delete</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="stat_box">
            <h2 class="user-heading">User Statistics Overview</h2>
            <div class="stat_content">
                <h3>Total Registered Users:  <span class="stat_value"><?php echo $TotalUsers; ?></span></h3>
                <h3>Active Users: <span class="stat_value"><?php echo $ActiveUsers; ?></span></h3>
                <h3>Deactivated Users: <span class="stat_value"><?php echo $DeactivatedUsers; ?></span></h3>
            </div>
        </div>
    </div>

    <div class="Container2">
        <h2>Order Requests</h2>
        <div class="order_content">
            <table>
                <?php
                    $sql = "SELECT o.*, 
                                CASE 
                                    WHEN p.payment_id IS NOT NULL THEN 'Paid'
                                    ELSE 'Not Paid'
                                END AS payment_status
                            FROM Orders o
                            LEFT JOIN Payment p ON o.order_id = p.order_id
                            WHERE o.order_status IN ('Pending', 'Shipped');";

                    $result = mysqli_query($Connection, $sql);

                    if(mysqli_num_rows($result) > 0)
                    {
                        while($row = $result->fetch_assoc()) 
                        {

                            $ID = $row['order_id'];
                            $customer = $row['user_name'];
                            $date = $row['order_date'];
                            $status = $row['order_status'];
                            $total = $row['Order_total'];
                            $orderStat = $row['order_status'];
                            $orderType = $row['order_type'];
                            $paymentStatus = $row['payment_status'];
                            $PrescriptionLink = "./Images/PrescriptionOrders/{$row['prescription_url']}";

                            if($orderType == 'Prescription')
                            {
                                $orderType = "<a href='$PrescriptionLink' target='_blank'>Prescription</a>";
                            }

                            if($orderStat=='Shipped')
                            {
                                echo "<tr>
                                    <td><span class='ordertb_title'>ID:</span> $ID </td>
                                    <td><span class='ordertb_title'>Customer:</span> $customer</td>
                                    <td><span class='ordertb_title'>Date:</span> $date</td>
                                    <td><span class='ordertb_title'>Status:</span> <span class='order_stat_row'>$status</span> </td>
                                    <td><span class='ordertb_title'>Total:</span> $total </td>
                                    <td><span class='order_stat_row'>$paymentStatus</span></td>
                                    <td>$orderType</td>
                                    <td class='disable_box'>
                                        <input type='submit' value='Mark as Shipped' disabled>
                                    </td>
                                </tr>";
                            }
                            else
                            {
                                echo "<tr>
                                    <td><span class='ordertb_title'>ID:</span> $ID </td>
                                    <td><span class='ordertb_title'>Customer:</span> $customer</td>
                                    <td><span class='ordertb_title'>Date:</span> $date</td>
                                    <td><span class='ordertb_title'>Status:</span> <span class='order_stat_row'>$status</span> </td>
                                    <td><span class='ordertb_title'>Total:</span> $total </td>
                                    <td><span class='order_stat_row'>$paymentStatus</span></td>
                                    <td>$orderType</td>
                                    <td class='shipped_box'>
                                        <form action='admin_DB.php' method='POST'>
                                            <input type='hidden' value=\"$ID\" name='order_id'>
                                            <input type='submit' name='Shipped' value='Mark as Shipped'>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        }
                    }
                    else
                    {
                        echo "<tr><td colspan='8'>No Orders Found</td></tr>";
                    }
                ?>
            </table>
        </div>
    </div>

    <div class="Container2">
        <h2>Inbox</h2>
        <div class="message_content">
            <table>
                <tr>
                    <th>Message ID</th>
                    <th>Sender Username</th>
                    <th>Message</th>
                    <th>Attachments</th>
                    <th>Reply</th>
                    <th>Submit</th>
                    <th>Delete</th>
                </tr>

                <?php
                    $sql = "SELECT * FROM Messages WHERE response_text IS NULL;";
                    $result = mysqli_query($Connection, $sql);

                    if(mysqli_num_rows($result) > 0)
                    {
                        while($row = $result->fetch_assoc()) 
                        {
                            $messageID = $row['message_id'];
                            $senderusername = $row['user_name'];
                            $message = $row['message_text'];
                            $attachment = $row['Uploads_url'];

                            if($attachment===NULL)
                            {
                                $uploads_Url = "----";
                            }
                            else
                            {
                                $uploads_Url = "<a href='./Images/PrescriptionMessage/$attachment' target='_blank'>Link</a>";
                            }

                            echo "<tr>
                                    <td>$messageID</td>
                                    <td>$senderusername</td>
                                    <td>$message</td>
                                    <td>$uploads_Url</td>
                                        <form action='admin_DB.php' method='POST'>
                                            <input type='hidden' value='$messageID' name='message_id'>
                                            <td><input class=\"Reply_in\" type=\"text\" placeholder=\"Reply\" name='Reply_in'></td>
                                            <td><button type=\"submit\" class=\"Submit_rply\" name='submit_rply'>Submit</button></td>
                                            <td><button type=\"submit\" class=\"Delete_rply\" name='Delete_msg'>Delete</button></td>
                                        </form>
                                    </tr>";
                        }
                    }
                    else
                    {
                        echo "<tr><td colspan='7'>No Messages Found</td></tr>";
                    }
                
                ?>
            </table>
        </div>
    </div>

    <hr class="order_hr">

    <div class="manage_product">
        <button  class="action_btn bg" onclick="manage_Product()" >Manage Products</button>
    </div>

    <?php include ("./footer.php"); ?>
</body>
</html>