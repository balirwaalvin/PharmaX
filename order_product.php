<?php
session_start();
//IT23569522 Medhani W A P
// get product id from products.php
if(isset($_POST['buynowbtn']))
{
    $product_id = $_POST['prdct_id'];
}
else
{
    header('location: ./index.php');
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
                <h3>Step 1</h3>
                <label for="username">First Name</label>
                <input type="text" id="username" name="firstname" placeholder="Enter Your User Name" required>

                <label for="fullname">Last Name</label>
                <input type="text" id="fullname" name="Lastname" placeholder="Enter Your Full Name" required>

                <label for="name">Address</label>
                <input type="text" id="street" placeholder="street" name="street" placeholder="Street" required>
                <input type="text" id="City"  placeholder="City" name="city" >

                <label for="postal-code">Postal Code</label>
                <input type="text" id="postal-code" name="postal_code" placeholder="Enter Your Postal Code" required >

                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" min="1" value="1">

                <br>

                <input type="hidden" id="product_id" name="product_id" value="<?php echo $product_id; ?>">
                
                
                <button type="submit" id="place-order-btn" name="placeorder">Place My Order</button>
                
                
            </div>
        </form>
    </div>

    <?php include ("./footer.php"); ?>
</body>
</html>