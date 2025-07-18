<?php 

//Sankalpa M M G H C
//IT23538832

session_start();

//to check if the user is logged in
if(isset($_SESSION['username']))
{
    require_once './db_Config/config.php';
}
else
{
    header('location: ./signin.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="./CSS/index.css">
    <link rel="stylesheet" href="./CSS/products.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="./Images/Pharmacy X Icon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./Images/Pharmacy X Icon.png">
</head>

<body>
    <?php include("./header.php"); ?>
    <div class="main-image-container">
        <div class="main-heading-container">
            <h1 class="main-heading">Quick, Easy, and Affordable <br> Healthcare Solutions.</h1>
           <a href="#prducts"><button class="main-heading-button" >Shop Now</button></a>
        </div>
        
        <div id="image-container">
            <img src="./Images/product-image-slider-1.jpg" alt="" id="main-image">
        </div>
            
        
        
    </div>

    <div class="products-section-container">
        <div class="products-heading-container">
            <div class="product-heading" id="prducts" >Products</div>
        </div>
         <div class="product-content-container">

        <?php

            $sql = "SELECT * FROM products";

            $result = mysqli_query($Connection,$sql);

                while($row = $result -> fetch_assoc()){

                    $productName = $row['product_name'];
                    $productDescription = $row['product_description'];
                    $price = $row['price'];
                    $product_id = $row['product_id'];
                    $img = $row['image_url'];
                    $image_URL= "./Images/product-icons/".$img;

                    echo "<div class='products-container'>
                            <div class='product-image-container'>
                                <img src='$image_URL' alt='' class='item-image' >
                            </div>
                            <div class='product-details-container'>
                                <p class='item-name'>$productName</p>
                                <p class = 'product-discription'>$productDescription</p>
                                <p class='item-price'>$price</p>
                                <form action='./order_product.php' method='POST' >
                                    <input type='hidden' name='prdct_id' value='$product_id'>
                                    <button class='item-buynow-button' name='buynowbtn' >Buy Now</button>
                                </form>
                            </div>
                          </div>";
                }
        ?>

        </div>
    </div>

    <?php include("./footer.php"); ?>

    <script src="./JS/products.js"></script>
    
</body>

</html>