<?php
session_start();
require_once './db_Config/config.php';
//IT23569522 Medhani W A P
//edit order function
if(isset($_POST['Edit_order']))
{
    //get order id
    $product_id = $_POST['product_id'];
    $username = $_SESSION['username'];
    $sql = "SELECT order_id FROM Orders WHERE user_name = '$username' ORDER BY order_id DESC LIMIT 1";
    $result = mysqli_query($Connection,$sql);
    if ($result) 
    {
        if (mysqli_num_rows($result) > 0) 
        {
            $row = mysqli_fetch_assoc($result);
            $order_id = $row['order_id']; // Extract 'order_id' from the row
            $sql = "SELECT * FROM Orders WHERE order_id = '$order_id'";
            $result = mysqli_query($Connection,$sql);
            $row = mysqli_fetch_assoc($result);

            $fullname = $row['receiver_name'];
            $street = $row['street'];
            $city = $row['city'];
            $postal_code = $row['postal_code'];
            $quantity = $row['qty'];

            $nameParts = explode(" ",$fullname);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1];
        } 
        else 
        {
            echo "<script>alert('No orders found for this user!');</script>";
            $order_id = null;  
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Now</title>
    <link rel="stylesheet" href="./CSS/index.css">
    <link rel="stylesheet" href="./CSS/order_product.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" >
    <link rel="icon" type="image/png" sizes="32x32" href="./Images/Pharmacy X Icon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./Images/Pharmacy X Icon.png">
   
</head>
<body>
    <?php include ("./header.php"); ?>

    <div class="container">
        
        <h2>Place Your Order</h2>
        <form action="./paymentpage.php" method="POST">
            <div class="step">
                <h3></h3>
                <label for="username">First Name</label>
                <input type="text" id="username" name="firstname" placeholder="Enter Your User Name" value="<?php echo $firstName ?>" required>

                <label for="fullname">Last Name</label>
                <input type="text" id="fullname" name="Lastname" placeholder="Enter Your Full Name" value="<?php echo $lastName ?>" required>

                <label for="name">Address</label>
                <input type="text" id="street" placeholder="street" name="street" placeholder="Street" value="<?php echo $street ?>" required>
                <input type="text" id="City"  placeholder="City" name="city" value="<?php echo $city ?>" >

                <label for="postal-code">Postal Code</label>
                <input type="text" id="postal-code" name="postal_code" placeholder="Enter Your Postal Code" value="<?php echo $postal_code ?>" required >

                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1" value="<?php echo $quantity ?>" >

                <br>

                <input type="hidden"  name="order_id" value="<?php echo $order_id; ?>">

                <input type="hidden" id="product_id" name="product_id" value="<?php echo $product_id; ?>">
                
                <button type="submit" id="place-order-btn" name="Updateandorder">Update And Proceed to Payment</button>
            </div>
        </form>
    </div>

    <?php include ("./footer.php"); ?>
</body>
</html>