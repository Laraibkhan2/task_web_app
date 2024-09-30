<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])){
$name =$_POST['name']; 
$email =$_POST['email']; 
$age =$_POST['age']; 
$city =$_POST['city'];
$cnic =$_POST['cnic']; 
$contact = $_POST['contact']; 
$password =$_POST['password']; 
$confirm_password =$_POST['confirm_password']; 

$sql = "SELECT * FROM registeration WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
     $check1=false;
    if ($result->num_rows > 0) {
        $email_error_msg = "Email already exists";
    $check1=true;

    }
    $check2=false;
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $pass_error_msg = "Password must be at least 8 characters, contain at least one lowercase letter, one uppercase letter, one digit, and one special character";
    $check2=true;
    }
$check="false";
if($password==$confirm_password){
    $check="true";
}else{
    $error_msg= "Password does not match";
}

if (!empty($name) && !empty($email) && !empty($age) && !empty($city) && !empty($cnic) && !empty($contact) && !empty($password) && $check === "true"  && $check1 === false && $check2 === false) {
    $hash_password = password_hash($password, PASSWORD_DEFAULT);
$sql2 = "INSERT INTO registeration (name, email, age, city, cnic, contact, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("ssisiis", $name, $email, $age, $city, $cnic, $contact, $hash_password);
    if($stmt2->execute()){
        $msg="You have been registered suucessfully";
    }
}

}
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size:16px;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .input-container {
            margin-bottom: 15px;
            position: relative;
        }
        .input-container i {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            color: #4c4a4a;
        }
        .input-container input {
            width: 250px;
            padding: 10px 10px 10px 35px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
      
        .button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }

    </style>
</head>
<body>
    <form action="register.php" method="post">
    <?php if(isset($msg)){ echo '<p style="color:green;">'.$msg.'</p>'; } ?>
        <h2>Register!</h2>
        <div class="input-container">
            <i class="fa-solid fa-user"></i>
            <input type="text" placeholder="Enter your full name" name="name" value="<?php if(!isset($_POST['submit']) && !empty($name)) echo $name; ?>" required>
        </div>
        <div class="input-container">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" placeholder="Enter your email" name="email" value="<?php if(!isset($_POST['submit']) && !empty($email)) echo $email; ?>" required>
            <?php if (isset($email_error_msg)) { echo "<span style='color: red; font-size:12px;'>$email_error_msg</span>"; } ?>
        
        </div>
        
        <div class="input-container">
            <i class="fa-solid fa-calendar-days"></i>
            <input type="number" placeholder="Enter your age" name="age" value="<?php if(!isset($_POST['submit']) && !empty($age)) echo $age; ?>" required>
        </div>
        
        <div class="input-container">
            <i class="fa-solid fa-city"></i>
            <input type="text" placeholder="Enter your city" name="city" value="<?php if(!isset($_POST['submit']) && !empty($city)) echo $city; ?>" required>
        </div>
        <div class="input-container">
            <i class="fa-solid fa-id-card"></i>
            <input type="number" placeholder="Enter your CNIC no" name="cnic" value="<?php if(!isset($_POST['submit']) && !empty($cnic)) echo $cnic; ?>" required>
        </div>
        <div class="input-container">
            <i class="fa-solid fa-phone"></i>
            <input type="number" placeholder="Enter your contact no" name="contact" value="<?php if(!isset($_POST['submit']) && !empty($contact)) echo $contact; ?>" required>
        </div>
        <div class="input-container">
            <i class="fa-solid fa-lock"></i>
            <input type="password" placeholder="Enter your password" name="password" required>
            </div><?php if (isset($error_msg)) { echo "<span style='color: red; font-size:12px;'>$error_msg</span>"; } ?>
            <?php if (isset($pass_error_msg)) { echo "<span style='color: red; font-size:12px;'>$pass_error_msg</span>"; } ?>
        
        
        <div class="input-container">
            <i class="fa-solid fa-lock"></i>
            <input type="password" placeholder="Confirm password" name="confirm_password" required>
        </div>
        <button type="submit" value="submit" class="button" name="submit">Sign Up</button>
        <div>
            <p style="text-align:center">Already signed up ?
            <a href="/task_web_app/login.php" target="blank" value="login"  name="login">login</a></p>
        </div>
    </form>
</body>
</html>
