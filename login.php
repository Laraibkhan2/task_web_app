<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $msg= "Please fill in all fields";
    } else {
        $sql = "SELECT * FROM registeration WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $ispassword_verify=password_verify($password, $user['password']);
            
            if ($ispassword_verify) {
                print_r("ispassword_verify");
                print_r($ispassword_verify);
                $_SESSION['valid'] = true;
                $_SESSION['timeout'] = time();
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $user['id'];
                header('Location: read.php');
                exit;
            } else {
                $msg= "Incorrect password";
            }
        } else {
            $msg= "You are not registered , register first then login";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size:15px;
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
        
        #login {
            text-align:center;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 6px;
            width: 280px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            text-decoration:none;
        }

    </style>
</head>
<body>
    <form method="post">
    <?php if(isset($msg)){ echo '<p style="color:red;">'.$msg.'</p>'; } ?>
        <h2>Login!</h2>
        <div class="input-container">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" placeholder="Enter your email" name="email" >
        </div>
        
        <div class="input-container">
            <i class="fa-solid fa-lock"></i>
            <input type="password" placeholder="Enter your password" name="password" >
        </div><div id="login">
        <button type="submit" value="login" class="button" id="login" name="submit">Login</button>
        </div>
        <div>
        <p style="text-align:center;">If you dont have registered <a href="/task_web_app/register.php" target="_blank" name="register">Sign up</a></p>
        </div>
    </form>
</body>
</html>
