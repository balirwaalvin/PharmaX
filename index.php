<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vita-Care</title>
    <link rel="stylesheet" href="./CSS/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="./Images/Pharmacy X Icon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./Images/Pharmacy X Icon.png">
</head>
<body>
    <?php include ("./header.php"); ?>

    <div class="slider">
        <div class="slides">
            <img class="slide" src="Images\slide01.jpg" alt="">
            <img class="slide" src="images/slide02.jpg" alt="">
            <img class="slide" src="images/slide03.jpg" alt="">
        </div>
        <button class="prev" onclick="prevSlide()">&#10094</button>
        <button class="next" onclick="nextSlide()">&#10095</button>
    </div>

    <section class="popular-products">
        <h2><b>Featured Products</b></h2>
        <div class="products">
            <div class="product-content">
                <img class="item" src="./Images\product-icons\Pharmacy-Isometric-Icons-8.png" alt="">
                <h3>Multi Vitamin</h3>
                <p>Multivitamins provide essential vitamins and minerals to support overall health. </p>
                <h6>UGX.20,000</h6>
                <ul>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star"></i></li>
                </ul>
            </div>
            <div class="product-content">
                <img class="item" src="./Images\product-icons\Pharmacy-Isometric-Icons-1.png" alt="">
                <h3>Skin care</h3>
                <p>Skincare involves routines and products to cleanse, protect, and nourish the skin. </p>
                <h6>UGX.35,000</UGX></h6>
                <ul>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                </ul>
            </div>
            <div class="product-content">
                <img class="item" src="./Images\product-icons\Pharmacy-Isometric-Icons-2.png" alt="">
                <h3>Pain Killers</h3>
                <p>Painkillers relieve pain by reducing inflammation or blocking pain signals.</p>
                <h6>UGX.10,000</h6>
                <ul>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                </ul>
            </div>
            <div class="product-content">
                <img class="item" src="./Images\product-icons\Pharmacy-Isometric-Icons-9.png" alt="">
                <h3>Paracetamol</h3>
                <p>Effective pain relief for headaches and fever  </p>
                <h6>UGX.5000</h6>
                <ul>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                </ul>
            </div>
        </div>
        <button class="buy-now-button"><a href="products.php">Buy Now</a></button>
    </section>
    <section class="popular-products">
        <h2><b>Popular Products</b></h2>
        <div class="products">
            <div class="product-content">
                <img class="item" src="./Images\product-icons\Pharmacy-Isometric-Icons-6.png" alt="">
                <h3>Vitamin C Tablets</h3>
                <p>Boost your immune system with Vitamin C. </p>
                <h6>UGX.15,000</h6>
                <ul>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                </ul>
            </div>
            <div class="product-content">
                <img class="item" src="./Images\product-icons\Pharmacy-Isometric-Icons-4.png" alt="">
                <h3>Insulin Syringe</h3>
                <p>Disposable insulin syringes with fine needles. </p>
                <h6>UGX.8000</h6>
                <ul>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                </ul>
            </div>
            <div class="product-content">
                <img class="item" src="./Images\product-icons\Pharmacy-Isometric-Icons-5.png" alt="">
                <h3>Blood Pressure Monitor</h3>
                <p>Accurate and easy-to-use blood pressure monitor. </p>
                <h6>UGX.250000</h6>
                <ul>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                </ul>
            </div>
            <div class="product-content">
                <img class="item" src="./Images\product-icons\Pharmacy-Isometric-Icons-2.png" alt="">
                <h3>Amoxicillin</h3>
                <p>Antibiotic used to treat bacterial infections.  </p>
                <h6>UGX.20000</h6>
                <ul>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star checked"></i></li>
                    <li><i class="fa fa-star"></i></li>
                </ul>
            </div>
        </div>
        <button class="buy-now-button"><a href="products.php">Buy Now</a></button>
    </section>
    
    <?php include ("./footer.php"); ?>
    <script src="./js/index.js"></script>
    
</body>
</html>