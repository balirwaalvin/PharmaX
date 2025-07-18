<?php 
// IT23569522 Medhani W A P
session_start();

require_once './db_Config/config.php';

// get order details from order_product.php
if(isset($_POST['placeorder']))
{

    $product_id = $_POST['product_id'];
    $username = $_SESSION['username'];
    $fullname = $_POST['firstname']." ".$_POST['Lastname'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $quantity = $_POST['quantity'];
    $order_type = "General";
    
    $sql = "SELECT * FROM Products WHERE product_id = '$product_id'";
    $result = mysqli_query($Connection,$sql);
    $row = mysqli_fetch_assoc($result);

    //calculate total price
    $total_price = $row['price'] * $quantity;

    //insert order details into orders table

    $sql = "INSERT INTO Orders (user_name, order_type, qty, receiver_name, street, city, postal_code, product_id, Order_total) 
            VALUES ('$username', '$order_type', '$quantity', '$fullname', '$street', '$city', '$postal_code', '$product_id', '$total_price')";
    $result = mysqli_query($Connection,$sql);

    if($result)
    {
        echo "<script>alert('Order Placed Successfully!')</script>";
    }
    else
    {
        echo "<script>alert('Failed to Place Order!')</script>";
    }
}

if(isset($_POST['Updateandorder']))
{
    $order_id_updte = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $username = $_SESSION['username'];
    $fullname = $_POST['firstname']." ".$_POST['Lastname'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $quantity = $_POST['quantity'];
    $order_type = "General";

    //update order details
    $sql = "UPDATE Orders SET qty = '$quantity', receiver_name = '$fullname', street = '$street', city = '$city', postal_code = '$postal_code' WHERE order_id = '$order_id_updte'";
    $result = mysqli_query($Connection,$sql);
    if($result)
    {
        echo "<script>alert('Order Updated Successfully!')</script>";
    }
    else
    {
        echo "<script>alert('Failed to Update Order!')</script>";
    }
    
    //get product price
    $sql = "SELECT * FROM Products WHERE product_id = '$product_id'";
    $result = mysqli_query($Connection,$sql);
    $row = mysqli_fetch_assoc($result);

    //calculate total price
    $total_price = $row['price'] * $quantity;
}

if(isset($_POST['Submit_payment']))
{
    $username = $_SESSION['username'];
    $paid_amount = $_POST['deposited_amount'];
    $bank = $_POST['bank'];
    $payment_remark = $_POST['payment_remark'];
    $payslipurl = $_FILES['payment_slip']['name'];
    $target_directory = "./Images/PaymentSlips/";

    //get order id
    $sql = "SELECT order_id FROM Orders WHERE user_name = '$username' ORDER BY order_id DESC LIMIT 1";
    $result = mysqli_query($Connection,$sql);
    if ($result) 
    {
        if (mysqli_num_rows($result) > 0) 
        {
            $row = mysqli_fetch_assoc($result);
            $order_id = $row['order_id']; // Extract 'order_id' from the row
        } 
        else 
        {
            echo "<script>alert('No orders found for this user!');</script>";
            $order_id = null;  
        }
    }

    // Move file
    move_uploaded_file($_FILES['payment_slip']['tmp_name'],$target_directory . $payslipurl);

    //add payment details to payments table
    $sql = "INSERT INTO Payment (order_id, amount,bank,remark, receipt_url)
            VALUES ('$order_id', '$paid_amount',' $bank ','$payment_remark','$payslipurl')";
    $result = mysqli_query($Connection,$sql);
    if($result)
    {
        echo "<script>alert('Payment Submitted Successfully!')</script>";
        header('location: ./index.php');
    }
    else
    {
        echo "<script>alert('Failed to Submit Payment!')</script>";
    }
}
if(isset($_POST['Delete_order']))
{
    $username = $_SESSION['username'];

    //get order id
    $sql = "SELECT order_id FROM Orders WHERE user_name = '$username' ORDER BY order_id DESC LIMIT 1";
    $result = mysqli_query($Connection,$sql);
    $row = mysqli_fetch_assoc($result);
    $order_id = $row['order_id'];

    //delete order
    $sql = "DELETE FROM Orders WHERE order_id = '$order_id'";
    $result = mysqli_query($Connection,$sql);
    if($result)
    {
        echo "<script>alert('Order Deleted Successfully!')</script>";
        sleep(1);
        header('location: ./index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title> 
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

    <div class="container" >
        <form action="paymentpage.php" method="POST" enctype="multipart/form-data">
            
            <hr>

            <div class="step">
                <h3>Step 2</h3>
                <label for="calculated-amount">Calculated Amount</label>
                <input type="text" id="calculated-amount" name="calculated_amount" value="<?php echo $total_price; ?>" readonly>
            </div>

            <hr>
                    
            <div class="step">
                <h3>Step 3</h3>
                <label for="deposited-amount">Deposited Amount</label>
                <input type="number" id="deposited-amount" name="deposited_amount" required >
                
                <label for="bank">Bank</label>
                <input type="text" id="bank" name="bank" required>

                <label for="payment-slip">Add Your Payment Slip</label>
                <input type="file" id="payment-slip" name="payment_slip" accept=".pdf" required>

                <label for="payment-remark">Payment Remark</label>
                <textarea id="payment-remark" name="payment_remark" placeholder="Type your comments" required></textarea>
                    

                <button type="submit" name="Submit_payment" id="place-order-btn"> Proceed Payment </button>

            </div>
        </form>
        <br>

        <form action="paymentpage.php" method="POST">
            <button type="submit" name="Delete_order" id="delete-order-btn" > Delete Order </button>
        </form>

        <br>
        

        <form action="Update_order.php" method="POST">
            <input type="hidden" id="product_id" name="product_id" value="<?php echo $product_id; ?>">
            <button type="submit" name="Edit_order" id="Edit_order" > Edit Order </button>
        </form>
    </div>

    <?php include ("./footer.php"); ?>
</body>
</html>