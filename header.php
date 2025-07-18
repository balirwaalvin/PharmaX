<!-- By Marasingha MAMN IT23539990 -->

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./CSS/partials.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/> <!--For dropdown menu-->
    <script src="./js/partials.js"></script>

   <style>
  .main_header {
    min-height: 200px;
    padding: 30px;
  }

  .main_header img.logo {
    height: 150px !important;
    width: auto !important;
    border: 3px solid lightblue;
    display: block;
  }
</style>


</head>
<body>
    <header>
        <div class="main_header">
        <a href="index.php"><img class="logo" src="Images/pharmacy logo.png" alt="PharmacyX Logo">
</a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="aboutUs.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>

            <?php 
                if(!isset($_SESSION['username']))
                {
                    // signin button for users who are not signed in
                    echo "<button onclick='window.location.href=\"signin.php\"'>Sign in</button>";
                }
                else
                {
                    //check if user is admin manager or customer and display DB links accordingly
                    if($_SESSION['user_type']== 'Admin')
                    {
                        $DBLink = "<a href='./admin_DB.php'>Admin DB</a>";
                    }
                    else if($_SESSION['user_type']== 'Manager')
                    {
                        $DBLink = "<a href='./manager_DB.php'>Manager DB</a>";
                    }
                    else if($_SESSION['user_type']== 'Customer')
                    {
                        $DBLink = "<a href='./my_orders.php'>My Orders</a>";
                    }

                    // assign profile picture name to a variable
                    $baseProfilePicUrl = './Images/Profile_Pics/'; // base derecotry of profile pictures
                    $profilePicUrl = isset($_SESSION['profilePic_url']) ? htmlspecialchars($baseProfilePicUrl.$_SESSION['profilePic_url']) : './Images/Profile_Pics/student-avatar-illustratio.jpg'; // Default profile picture

                    // user profile for users who are signed in
                    echo "<div class='user_profile' onclick='dropdownmenu()'>
                            <img src='".$profilePicUrl."' alt='User Profile'>
                            <h3 class='username no-select'>Hello ".$_SESSION['firstname']." <i class='fas fa-caret-down'></i></h3>
                            <div class='Profile_dropdown' id='prof_dropdown'>
                                <div class='dropdown_items'>
                                    <a href='my_account.php'>My Profile</a>
                                    ".$DBLink."
                                    <a onclick='return confirm(\"Do you want to sign out now?\")' href='logout.php'>Sign Out</a>
                                </div>
                            </div>
                        </div>";
                }
            
            ?>
        </div>
    </header>
</body>
</html>