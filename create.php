<?php
include 'authentication.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])){

$t_des =$_POST['t_des']; 
$t_detail =$_POST['t_detail']; 
$start_d =$_POST['start_d']; 
$end_d =$_POST['end_d'];
$priority =$_POST['priority']; 
$days =isset($_POST['days']) ? $_POST['days'] : ''; 
$task =isset($_POST['task']) ? $_POST['task'] : '';  
$id=$_SESSION['id'];
if(!empty($t_des && $t_detail && $start_d && $end_d && $priority && $days && $task)){
$sql="INSERT INTO tasks(task_description, task_detail, start_date , end_date , priority_id , days_id,task_id , registeration_id) VALUES ('$t_des', '$t_detail', '$start_d', '$end_d','$priority', '$days', '$task', '$id')";
    if ($conn->query($sql) === TRUE) {
    echo "New record saved successfully";
    header('Location: read.php'); 
    exit;
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }
    

        
} }       
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
    <style>
        body {
            font-family: Arial;
            font-size: 25px;
            /*background-image:radial-gradient(circle,#A9F1DF , #FFBBBB);*/
        }
        .text {
            border: 1px solid #ccc;
            padding: 12px 20px;
            margin: 8px 0;
            border-radius: 4px;
            box-sizing: border-box;
            resize: none;
            width: 100%;
        }
        #submit, #reset , #cancel{
            font-size: 16px;
            width: 30%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 8px;
            box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
        }
        #reset{
            background-color: #ff3333;
        }
        #cancel{
            background-color: #003380;
            text-decoration:none;
            text-align:center;
        }
        #one {
            background-color: black;
            color: white;
            padding: 1px 30px;
            border-radius: 8px;
            text-align: center;
        }
        .flex-container {
            display: flex;
            justify-content: space-between;
            
            margin-bottom: 16px; 
            
        }
        .flex-container > div {
            width: 250px;
            margin: 0 5px; 
            flex:1;
        }
        .container {
            display: flex;
            justify-content: center; 
            transition-duration: 0.4s;
            -webkit-transition-duration: 0.4s;
            gap: 10px;  
        }
        .department{
            height:35px;
            font-size:20px;

        }
        .text{
            font-size:20px;
            text-decoration:none;
        }
        .status{
            height: 20px;
            width: 20px;
            text-decoration:none;
            
        }
        .checkbox{
            
            height: 20px;
            width: 20px;
            text-decoration:none;
        }
        .priority{
            height:35px;
            font-size:20px;

        }
        .text{
            font-size:20px;
            text-decoration:none;
        }
        .days{
            height: 20px;
            width: 20px;
            text-decoration:none;
            
        }
        .required::after{
            content:"*";
            color:red;
            font-size:20px;
        }
        .empty{
            color:red;
            font-size:15px;
        }
    </style>
</head>
<body>
    <form method="post">
        <div id="one">
            <h1>  CREATE TASK </h1>
        </div>
        <br>
        <div class="flex-container">
            <div>
                <label class="required" for="t_des">Task Description:</label>
                <input type="text" id="t_des" name="t_des" class="text" value="<?php echo !empty($_POST['t_des']) ? $_POST['t_des'] : ''; ?>">
                <div class="empty"> <?php 
                if(isset($_POST['submit'])){
                    if(empty($_POST["t_des"])){
                        $des_err="Input required";
                        echo $des_err;
                    } 
                }
                 ?></div>
            </div>
            <div>
                <label class="required" for="t_detail">Task Detail:</label>
                <textarea name="t_detail" rows="4" cols="35" class="text"><?php echo !empty($_POST['t_detail']) ? $_POST['t_detail'] : ''; ?></textarea>
                <div class="empty"> <?php
                if(isset($_POST['submit'])){
                    if(empty($_POST["t_detail"])){
                        $t_err="Input required";
                        echo $t_err;
                    } 
                }
                ?></div>
            </div>
        </div>
        <div class="flex-container">
            <div>
                <label class="required" for="start_d">Starting date:</label>
                <input type="date" id="start_d" name="start_d"  class="text" value="<?php echo !empty($_POST['start_d']) ? $_POST['start_d'] : ''; ?>">
                <div class="empty"> <?php
                if(isset($_POST['submit'])){
                    if(empty($_POST["start_d"])){
                        $sd_err="Input required";
                        echo $sd_err;
                    } 
                }
                ?></div>
            </div>
            <div>
                <label class="required" for="end_d">Ending date:</label>
                <input type="date" id="end_d" name="end_d" class="text" value="<?php echo !empty($_POST['end_d']) ? $_POST['end_d'] : ''; ?>">
                <div class="empty"> <?php
                if(isset($_POST['submit'])){
                    if(empty($_POST["end_d"])){
                        $ed_err="Input required";
                        echo $ed_err;
                    } 
                }
                ?></div>
            </div></div><br>
            <div class="flex-container">
            <div>
            <?php 
                $sql_dropdown="SELECT id, priority FROM priority";
                $result =$conn->query($sql_dropdown);?>
                <label class="required" for="Priority">Select your priority :</label>
                <select name="priority" class="priority">
                <option class="priority" value="">--Select--</option>
                <?php foreach ($result as $prow ) {  ?>
                <option class="priority"  value="<?php echo $prow['id']; ?>" <?php echo (!empty($_POST['priority']) && $_POST['priority'] == $prow['id']) ? 'selected' : ''; ?>>
                <?php echo $prow['priority']?>
                </option>
                <?php } ?>
                </select>
                <div class="empty"> <?php 
                if(isset($_POST['submit'])){
                    if(empty($_POST["priority"])){
                        $p_err="Input required";
                        echo $p_err;
                        
                    } 
                }
                ?></div>    
            </div>
            <div>
             <?php 
                $sql_radio="SELECT id, days FROM days";
                $result =$conn->query($sql_radio);?>
                <label class="required" for="days">Select your days </label><br>
                <?php foreach ($result as $drow ) { ?>
                <input type="radio" class="days" name="days" value="<?php echo $drow['id'] ?>"<?php echo (!empty($_POST['days']) && $_POST['days'] == $drow['id']) ? 'checked' : ''; ?>>
                <label for="days"><?php echo $drow['days'] ?></label>
                <?php } ?><div class="empty"> <?php 
                if(isset($_POST['submit'])){
                    if(empty($_POST["days"])){
                        $d_err="Input required";
                        echo $d_err;
                    } 
                }
                ?></div>
        </div><div>
        <?php $sql_checkbox="SELECT id, task FROM Category";
                $result =$conn->query($sql_checkbox);?>
                <label class="required" for="task">Select your task category <br>
                <?php foreach ($result as $crow ) { ?>
                <input type="checkbox" class="checkbox" name="task" value="<?php echo $crow['id'] ?>"<?php echo (!empty($_POST['task']) && $_POST['task'] == $crow['id']) ? 'checked' : ''; ?>>
                
            <label for="task"> <?php echo $crow['task'] ?></label>
            <?php } ?>
            <div class="empty"> <?php 
                if(isset($_POST['submit'])){
                    if(empty($_POST["task"])){
                        $t_err="Input required";
                        echo $t_err;
                    } 
                }
                ?></div>
        </div></div>
        <div class="container">
            <input type="submit" value="Submit" id="submit" name="submit">
            <input type="reset" value="Reset" id="reset">
            <a href="read.php" target="blank" value="Cancel" id="cancel" name="cancel">Cancel</a>
        </div>
    </form>
    
</body>
</html>