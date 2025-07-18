<?php
//By Moditha IT23539990
    session_start();

    if(!isset($_SESSION['username']))
    {
        header("Location: ./signin.php");
    }
    else
    {
        require_once './db_Config/config.php';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="./CSS/index.css">
    <link rel="stylesheet" href="./CSS/my_orders.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="./Images/Pharmacy X Icon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./Images/Pharmacy X Icon.png">
</head>
<body>
    <?php include ("./header.php"); ?>

    <div class="container1">
        <h2>My Orders</h2>

        <div class="table_container">
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Product</th>
                    <th>Order Status</th>
                    <th>Order Total</th>
                </tr>

                <?php 
                    $query = "SELECT o.*, p.product_name
                              FROM Orders o
                              INNER JOIN Products p ON o.product_id = p.product_id
                              WHERE o.user_name = '".$_SESSION['username']."';
                             ";

                    $result = mysqli_query($Connection, $query);

                    if(mysqli_num_rows($result) > 0)
                    {
                        while($row = mysqli_fetch_array($result))
                        {
                            echo "<tr>
                                    <td>{$row['order_id']}</td>
                                    <td>{$row['order_date']}</td>
                                    <td>{$row['product_name']}</td>
                                    <td><span class=\"status\">{$row['order_status']}</span></td>
                                    <td>Rs. {$row['Order_total']}</td>
                                </tr>";
                        }
                    }
                    else
                    {
                        echo "<tr>";
                        echo "<td colspan='5'>No Orders Found</td>";
                        echo "</tr>";
                    }
                ?>
                
            </table>
        </div>
    </div>


    <?php include ("./footer.php"); ?>
</body>
</html>