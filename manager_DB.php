<?php

//Sankalpa M M G H C 
//IT23538832

session_start();


//validate user
if(isset($_SESSION['username'])){

  //check if user is manager
  if($_SESSION['user_type'] === 'Manager'){
    require_once './db_Config/config.php';
  }else if($_SESSION['user-type'] === 'Admin'){
    header('location: ./admin_DB.php');
  }else{
    header('location: ./index.php');
  }
}

//insert admin details
if(isset($_POST['addAdmin'])){
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $userpassword = $_POST["userpassword"];
    $usertype = "Admin";
  
    //Insert data into the database
    $sql_user_info = "INSERT INTO user_info (first_name,last_name,user_name,email,password,user_type) VALUES ('$firstname','$lastname','$username','$email','$userpassword','$usertype')";
  
    //check whether the data was insert successfully
    if(($Connection->query($sql_user_info) === TRUE)){
      echo "<script>alert('Add admin successfully')</script>";
    }else{
      echo "Error: " . $sql . "<br>" > $Connection->error;
    }
  }
}
//check admin status
$AdminStatus = '----';
$inputValue = '';

if(isset($_POST['check'])){

  $username = $_POST['adminInput'];
  $inputValue = $username;

  //check username container is empty or not
  if(empty($username)){
    $AdminStatus = 'Empty';
  }else{
    $sql = "SELECT acc_status FROM User_info WHERE user_name = '$username' AND user_type= 'Admin'";
    $result = mysqli_query($Connection,$sql);

    if(mysqli_num_rows($result) > 0 ){
      $row = mysqli_fetch_assoc($result);
      $AdminStatus = $row['acc_status'];
    }
    else{
      $AdminStatus = 'Invalid UN';
    }
  }
}

//Activate admin function
if(isset($_POST['activate'])){
  $username = $_POST['adminInput'];

  if(empty($username)){
    $AdminStatus = 'Empty';
  }else{
    $sql = "UPDATE User_info SET acc_status = 'Active' WHERE user_name = '$username' AND user_type = 'Admin'";
    $result = mysqli_query($Connection,$sql);

    if($result && mysqli_affected_rows($Connection) > 0){
      $AdminStatus = 'Activated';
    }else{
      $AdminStatus = 'Failed';
    }
  }
}

//Deactivate adin function
if(isset($_POST['deactivate'])){
  $username = $_POST['adminInput'];

  if(empty($username)){
    $AdminStatus = 'Empty';
  }else{
    $sql = "UPDATE User_info SET acc_status ='Inactive' WHERE user_name = '$username' AND user_type = 'Admin'";
    $result = mysqli_query($Connection, $sql);

    if($result && mysqli_affected_rows($Connection) > 0){
      $AdminStatus = "Deactivated";
    }else{
      $AdminStatus = "Failed";
    }
  }
}

if(isset($_POST['delete'])){
  $username = $_POST['adminInput'];

  if(empty($username)){
    $AdminStatus = 'Empty';
  }else{
    $sql = "DELETE FROM User_info WHERE user_name = '$username' AND user_type = 'Admin'";

    $result = mysqli_query($Connection, $sql);

    if($result && mysqli_affected_rows($Connection) > 0){
      $AdminStatus = 'Deleted';
    }else{
      $AdminStatus = 'Failed';
    }
  }
}

//Count total amount
$sql = "SELECT SUM(Order_total) AS totalAmount FROM orders";
$result = mysqli_query($Connection, $sql);

if(mysqli_num_rows($result) > 0){
  $row = mysqli_fetch_assoc($result);
  $totalAmount = $row['totalAmount'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Dashboard</title>
  <link rel="stylesheet" href="./CSS/index.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" sizes="32x32" href="./Images/Pharmacy X Icon.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./Images/Pharmacy X Icon.png">
  <link rel="stylesheet" href="./CSS/manager_DB.css">
</head>

<body>
  <?php include("./header.php"); ?>

  <div class="manager-dashboard-container">
    <div class="orders-container">
        <h1 class="manager-headings">Orders</h1>
      <div class="order-table-container">
          <table class="order-table">
            <tr>
              <th class="order-table-heading">Order ID</th>
              <th class="order-table-heading">Date</th>
              <th class="order-table-heading">Status</th>
              <th class="order-table-heading">Amount</th>
            </tr>

            <?php
              $sql = "SELECT * FROM orders;";

              $result = mysqli_query($Connection, $sql);
            

              if(mysqli_num_rows($result) > 0){

                while($row = $result -> fetch_assoc()){
                  $orderId = $row['order_id'];
                  $date = $row['order_date'];
                  $status = $row['order_status'];
                  $total = $row['Order_total'];

                  echo "<tr>
                        <td>$orderId</td>
                        <td>$date</td>
                        <td class=status-result>$status</td>
                        <td>$total</td>
                      </tr>";
                }
            }else{
              echo "<tr>
                      <td colspan = '8'>No Orders Found</td>
                    </tr>";
            }
            
            ?>

          </table>
      </div>
    </div>

    <div class="total-amount-container">
      <h2 class="total-amount-heading">Total Earned Amount: </h2>
      <div  class="total-amount-show-box">
        <h3 class="total-amount"><?php echo $totalAmount ?></h3>
      </div>
    </div>

    <div class="manage-admin-container">
      <div class="add-admin-container">
        <h1 class="manager-headings">Add Admin</h1>

        <div class="admin-form-container">
          <form action="manager_DB.php" method="POST">
            <div class="admin-form">
              <div class="form-rows">
                <input type="text" class="form-input-box" placeholder="Firt Name" name="firstname">
                <input type="text" class="form-input-box-right" placeholder="Last Name" name="lastname">
              </div>
              <div class="form-rows">
                <input type="text" class="form-input-box" placeholder="Username" name="username">
                <input type="text" class="form-input-box-right " placeholder="E mail" name="email">
              </div>
              <div class="form-rows">
                <input type="password" class="form-input-box enter-password" placeholder="Enter Password" name="userpassword">
                <input type="password" class="form-input-box-right re-enter-password" placeholder="Re-Enter Password" onkeyup= "checkPassword();">
              </div>
              <div class="display-error"> </div>
              <div class="add-admin-button-container">
                <button type="submit" class="add-admin-button" name="addAdmin">Add Admin</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="control-admins">
        <h1 class="check-admin-heading">Manage Admins</h1>

        <div class="control-admin-form">
          <form action="manager_DB.php" method="POST">
            <div class="check-admin">
                <input type="text" class="check-admin-input" placeholder="UserName" name="adminInput" value= "<?php echo $inputValue; ?>">
                <button type="submit" class="check-admin-button" name="check">Check</button> 
            </div>

            <div class="admin-satus-container">
              <h3 class="status-heading">Status : </h3>
              <div class="admin-status"><?php echo $AdminStatus ?></div>
            </div>

            <div class="admin-control-buttons">
              <button class="activate" name="activate">Activate</button>
              <button class="deactivate" name="deactivate">Deactivate</button>
              <button class="delete" name="delete">Delete</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include("./footer.php"); ?>
  <script src="./js/manager_DB.js"></script>
</body>

</html>