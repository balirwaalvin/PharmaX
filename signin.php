<?php 
session_start();

require_once './db_Config/config.php';

if(isset($_POST['signin']))
{
    $user_name = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM User_info WHERE user_name = '{$user_name}' AND password = '{$password}' LIMIT 1";
    $result = mysqli_query($Connection, $query);

    if(mysqli_num_rows($result) == 1)
    {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['username'] = $row['user_name'];
        $_SESSION['firstname'] = $row['first_name'];
        $_SESSION['profilePic_url'] = $row['profilepic_url'];
        $_SESSION['user_type'] = $row['user_type'];

        $errors = '';
        
        if($row['acc_status'] == 'Active')
        {
            if($_SESSION['user_type'] == 'Admin')
            {
                header('location: ./admin_DB.php');
            }
            else if($_SESSION['user_type'] == 'Customer')
            {
                header('location: ./products.php');
            }
            else if($_SESSION['user_type'] == 'Manager')
            {
                header('location: ./manager_DB.php');
            }
            else
            {
                $errors = 'Error Invalid User Type';
            }
        }
        else
        {
            $errors = 'Account is Deactivated. Contact <a href="./contact.php">HERE</a>';
        }
    }
    else
    {
        $errors = 'Invalid Username or Password';
    }
}
else
{
    $errors = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In</title>
  <link rel="stylesheet" href="./CSS/signin.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="32x32" href="./Images/Pharmacy X Icon.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./Images/Pharmacy X Icon.png">
</head>
<body>
  <div class="container-top">
    <div class="main-container">
      <div class="container">
        <div class="login-container">
          <div class="login-image-container">
            <img src="./Images/Login-image.jpg" alt="" class="login-image">
          </div>
          <div class="login-form">
            <form action="" method="post">
              <h3 class="login-heading">Log In</h3>
              <div class="login-inputs-box">
                <input type="text" class="username-box" name="username" placeholder="Username" required>
    
                <input type="password" class="username-box" name="password" placeholder="Password" id="password" required>
              </div>
              <div class="special-tasks">
                <div class="remember-me">
                  <input type="checkbox" class="remember-me" id="showpw" onclick="showPassword()" > <h4 class="remember-me-content">Show Password</h4>
                </div>
              </div>
              <div class="login-button-container">
                <button type="submit" name="signin" class="login-button">Login</button>
              </div>
    
              <div class="signup-container">
                <h3 class="signup-heading">Still don't have an account?</h3>
    
                <div class="signup-button-container">
                  <a href="./register.php" class="signup-button">Sign Up</a>
                </div>

                <p class="error"> <?php echo $errors; ?> </p>
    
                <div class="pages-container">
                  <a href="./t&c.php" class="page-links">Terms & Conditions</a>
                  <h4 class="middle-section">||</h4>
                  <a href="./privacyPolicy.php" class="page-links">Privacy Policy</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="./JS/signin.js"></script>
</body>
</html>