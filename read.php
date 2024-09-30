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
if (isset($_POST['export'])){
    $t_des =$_POST['t_des']; 
    $t_det =$_POST['t_det']; 
    $s_date =$_POST['s_date']; 
    $e_date =$_POST['e_date'];
    $priority =$_POST['priority'];
    $days =$_POST['days'];
    $category =$_POST['category'];

    $sql = "SELECT * FROM tasks WHERE registeration_id='".$_SESSION['id']."'";

    if (!empty($t_des)) {
        $sql .= " AND t_des= '".$t_des."'";
    }
    if (!empty($t_det)) {
        $sql .= " AND t_det = '".$t_det."'";
    }
    if (!empty($s_date)) {
        $sql .= " AND s_date = '".$s_date."'";
    }
    if (!empty($e_date)) {
        $sql .= " AND e_date = '".$e_date."'";
    }
    if (!empty($priority) && $priority != 'Select') { 
        $sql .= " AND priority_id = '".$priority."'";
    }
    if (!empty($days) && $days != 'Select') { 
        $sql .= " AND days_id = '".$days."'";
    }

    if (!empty($category) && $category != 'Select') { 
        $sql .= " AND task_id = '".$category."'";
    }
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        ob_end_clean(); 
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="tasks.csv"');
        echo " ID , Description , Detail , Start date , End date , Priority ,Days , Category\n";
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            $priority_id = $row['priority_id'];
            $days_id = $row['days_id'];
            $task_id = $row['task_id'];

            $sql_p = "SELECT priority FROM priority WHERE id = $priority_id";
            $priority_result = $conn->query($sql_p);
            $priority_row = $priority_result->fetch_assoc();

            
            $sql_d = "SELECT days FROM days WHERE id = $days_id";
            $days_result = $conn->query($sql_d);
            $days_row = $days_result->fetch_assoc();

            $sql_s = "SELECT task FROM category WHERE id = $task_id";
            $category_result = $conn->query($sql_s);
            $category_row = $category_result->fetch_assoc();

            $task_description = utf8_encode($row['task_description']);
            $task_detail = utf8_encode($row['task_detail']);

            echo $counter.",".$task_description.",".$task_detail.",".$row['start_date'].",".$row['end_date'].",".$priority_row['priority'].",".$days_row['days'].",".$category_row['task']."\n";
            $counter++; 
            }
        exit;
    } else {
        echo "No data";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read</title>
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.css">
    <?php 
        if(isset($_GET['delete_msg'])){
            echo "<h2 style='color:red;'>".$_GET['delete_msg']."</h2>";
        }  
    ?>
    <style>
        
        body {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            max-width:100%;

        }

        table {
            background-color: white;
            border-radius: 20px;
            /*margin: 20px 10px 10px 10px;*/
            width: 100%;
            border-collapse: collapse;
            font-size: 18px;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color:  #000000;
            border-bottom: 2px solid #ddd;
            color:white;
        }

        td {
            border-bottom: 1px solid #ddd;
            word-wrap: break-word;
            white-space: normal;
        }

        .buttons i {
            margin: 0 5px;
        }

        .buttons {
            text-align: center;
        }

        .fa-trash {
            color: #ff3333;
        }

        .fa-pencil-square-o {
            color: #0099ff;
        }

        .create {
            background-color: #0066ff;
            color:white;
            width: 110px;
            height: 40px;
            font-size: 18px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin: 5px 10px 10px 0;
            right:130px;
        }
        .logout {
            background-color: #ff1a1a;
            color:white;
            margin: 5px 0px 10px 0;
            width: 110px;
            height: 40px;
            font-size: 18px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            float:right;
            
        }
        

        #filter_header {
            background-color: #e6f9ff;
            padding: 20px;
            border-radius: 10px;
            max-width: 100%;
        }

        #filter_header label {
            font-size: 20px;
            margin-right: 10px;
        }

        .filter {
            font-size: 16px;
            margin: 5px 30px 10px 0;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 460px;
            
        }
        .date, .dropdown {
            font-size: 16px;
            margin: 5px 30px 10px 0;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 250px;
        }
        .task_detail-column{
            word-wrap: break-word;
            max-width: 200px; 
            overflow-wrap: break-word;
        }
        .export {
            background-color: #009933;
            text-decoration:none;
            text-align:center;
            padding: 9px 24px 9px 24px;
            color:white;
            margin: 5px 0px 10px 0;
            font-size: 18px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            float:right;
            
        }
        #filter {
            background-color :#5c5c8a;
            color: white;
            font-size: 18px;
            border: none;
            cursor: pointer;
            margin: 5px 0px 10px 5px;
            width : 110px;
            height: 40px;
            border-radius: 5px;
            display:inline-block;
        }

        .pagination {
            text-align: center;
            margin: 20px 0;
        }

        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            color: black;
            font-size: 18px;
        }

        .pagination a.active {
            color: blue;
            font-weight: bold;
        }
        #submit{
            float:right;
        }
        #title{
            background-color: black;
            color: white;
            padding: 1px 30px;
            border-radius: 5px;
        }
        .flex-container {
        display: flex;
        background-color: black;
        }

        .flex-container > div {
        margin: 2px 0px 2px 320px;
        padding: 10px;
        }
               
    </style>
</head>
<body>
    <div class=flex-container>
        <div id=title>
            <h1> Welcome to the Task Web App</h1>
         </div><div>
            <a href="create.php" target="_blank">
                <input type="submit" value="Create" class="create">
            </a>
            <a href="logout.php">
                <input type="submit" value="Log out" class="logout">
            </a>
            </div></div>
    <div id="filter_header">
        <form action="" method="post">
            <label>Find Description</label>
            <input type="text" name="t_des" value="<?php echo isset($_POST['t_des']) ? $_POST['t_des'] : (isset($_GET['t_des']) ? $_GET['t_des'] : '') ?>" class="filter">
            <label>Find Detail</label>
            <input type="text" name="t_det" value="<?php echo isset($_POST['t_det']) ? $_POST['t_det'] : (isset($_GET['t_det']) ? $_GET['t_des'] : '') ?>" class="filter">
            <br><label>Find Start Date</label>
            <input type="date" name="s_date" value="<?php echo isset($_POST['s_date']) ? $_POST['s_date'] :  (isset($_GET['s_date']) ? $_GET['t_des'] : '')?>" class="date">
            <label>Find End Date</label>
            <input type="date" name="e_date" value="<?php echo isset($_POST['e_date']) ? $_POST['e_date'] :  (isset($_GET['e_date']) ? $_GET['t_des'] : '') ?>" class="date">
            <label>Find Priority</label>
            <?php 
                $sql_dropdown="SELECT id, priority FROM priority";
                $result =$conn->query($sql_dropdown);?>
                <select name="priority" class="dropdown">
                <option value="">--Select--</option>
                <?php foreach ($result as $prow ) {  
                $selected = ($prow['id'] == (isset($_POST['priority']) ? $_POST['priority'] : '')) ? 'selected' : '';
                ?>
                <option class="priority" value="<?php echo $prow['id']; ?>" <?php echo $selected; ?>><?php echo $prow['priority'];?></option>
                <?php } ?>
                </select>
            <br><label>Find Days</label>
            <?php 
                $sql_days="SELECT id, days FROM days";
                $result =$conn->query($sql_days);?>
                <select name="days" class="dropdown">
                <option value="">--Select--</option>
                <?php foreach ($result as $drow ) {  
                     $selected = ($drow['id'] == (isset($_POST['days']) ? $_POST['days'] : '')) ? 'selected' : '';?>
                <option class="dropdown"  value="<?php echo $drow['id']; ?>" <?php echo $selected; ?>> 
                <?php echo $drow['days']?>
                </option>
                <?php } ?>
                </select>
            <label>Find Category</label>
            <?php 
                $sql_category="SELECT id, task FROM category";
                $result =$conn->query($sql_category);?>
                <select name="category" class="dropdown">
                <option value="">--Select--</option>
                <?php foreach ($result as $crow ) {  
                $selected = ($crow['id'] == (isset($_POST['category']) ? $_POST['category'] : '')) ? 'selected' : '';?>
                <option class="dropdown"  value="<?php echo $crow['id']; ?>"<?php echo $selected; ?>>
                <?php echo $crow['task']?>
                </option>
                <?php } ?>
                </select>
            <div id=submit>
            <input type="submit" name="filter" value="Filter" id="filter">
            </div>
            <button type="submit" name="export" class="export">
                <i class="fa-solid fa-download"></i> Export</button>
            

        </form>
    </div><div style="overflow-x: auto; max-width:100%; table-layout:fixed;">
    <table>
        <tr id="header">
            <th>Serial no</th>
            <th>Task Description</th>
            <th>Task Detail</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Priority</th>
            <th>Days</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        <tbody>
            <?php
            $limit = 10; 
            $page = isset($_POST['page']) ? (int)$_POST['page'] : (isset($_GET['page']) ? $_GET['page'] : 1);
            $offset = ($page - 1) * $limit;
            if (isset($_POST['filter'])){
                $offset = ($page - 1) * $limit;
            }

            if (isset($_POST['filter']) ){
                $t_des=$_POST['t_des'];
                $t_det=$_POST['t_det'];
                $s_date = !empty($_POST['s_date']) ? date_format(date_create($_POST['s_date']), "Y-m-d") : '' ;
                $e_date = !empty($_POST['e_date']) ? date_format(date_create($_POST['e_date']), "Y-m-d") : '' ;
                $priority=$_POST['priority'];
                $days=$_POST['days'];
                $category=$_POST['category'];
                $sql = "SELECT id, task_description, task_detail, DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date, DATE_FORMAT(end_date, '%d/%m/%Y') AS end_date, priority_id, days_id, task_id 
                        FROM tasks WHERE registeration_id='".$_SESSION['id']."'"; 
                            if (!empty($t_des)) { 
                                $sql .= " AND task_description LIKE '%".$t_des."%'";
                            }
                            if (!empty($t_det)) { 
                                $sql .= " AND task_detail LIKE '%".$t_det."%'";
                            }
                            if (!empty($s_date)) { 
                                $sql .= " AND start_date = '".$s_date."'";
                            } 
                            if (!empty($e_date )) { 
                                $sql .= " AND end_date = '".$e_date."'";
                            }
                            if (!empty($priority )) { 
                                $sql .= " AND priority_id = '".$priority."'";
                            }
                            if (!empty($days)) {
                                $sql .= " AND days_id = '".$days."'";
                            }
                            if (!empty($category)) { 
                                $sql .= " AND task_id = '".$category."'";
                            }
                            $sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
                            }
                            else {
                    $sql = "SELECT id, task_description, task_detail, DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date, DATE_FORMAT(end_date, '%d/%m/%Y') AS end_date, priority_id, days_id, task_id 
                        FROM tasks WHERE registeration_id='".$_SESSION['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset";
                    }

                    $result = $conn->query($sql);
                    
                $counter = ($page - 1) * $limit; 
                if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $priority_id = $row['priority_id'];
                    $days_id = $row['days_id'];
                    $task_id = $row['task_id'];
                    $sql_p = "SELECT priority FROM priority WHERE id = $priority_id";
                    $priority_result = $conn->query($sql_p);
                    $priority_row = $priority_result->fetch_assoc();
                    $sql_d = "SELECT days FROM days WHERE id = $days_id";
                    $days_result = $conn->query($sql_d);
                    $days_row = $days_result->fetch_assoc();
                    $sql_c = "SELECT task FROM category WHERE id = $task_id";
                    $category_result = $conn->query($sql_c);
                    $category_row = $category_result->fetch_assoc();
                    ?>
                    <tr>
                        <td><?php  echo ++$counter ; ?></td>
                        <td><?php echo $row["task_description"]; ?></td>
                        <td  class="task-detail-column"><?php echo $row["task_detail"]; ?></td>
                        <td><?php echo $row["start_date"]; ?></td>
                        <td><?php echo $row["end_date"]; ?></td>
                        <td><?php echo $priority_row["priority"]; ?></td>
                        <td><?php echo $days_row["days"]; ?></td>
                        <td><?php echo $category_row["task"]; ?></td>
                        <td class="buttons">
                            <a href="update.php?id=<?php echo $row["id"]; ?>" target="_blank"><i class="fa fa-pencil-square-o" ></i></a>
                            <a href="delete.php?id=<?php echo $row["id"]; ?>"><i class="fa fa-trash" ></i></a>
                        </td>
                    </tr>
                    <?php
                }
            }
            if (isset($_POST['export'])){
                $t_des = $_POST['t_des'];
                $t_det = $_POST['t_det'];
                $s_date = $_POST['s_date'];
                $e_date = $_POST['e_date'];
                $priority = $_POST['priority'];
                $days = $_POST['days'];
                $category = $_POST['category'];
                
                $sql = "SELECT * FROM tasks WHERE registeration_id='".$_SESSION['id']."'";

                if (!empty($t_des)) {
                    $sql .= " AND task_description LIKE '%".$t_des."%'";
                }
                if (!empty($t_det)) {
                    $sql .= " AND task_detail LIKE '%".$t_det."%'";
                }
                if (!empty($s_date)) {
                    $sql .= " AND start_date = '".$s_date."'";
                }
                if (!empty($e_date)) {
                    $sql .= " AND end_date = '".$e_date."'";
                }
                if (!empty($priority) && $priority != 'Select') { 
                    $sql .= " AND priority_id = '".$priority."'";
                }
                if (!empty($days) && $days != 'Select') { 
                    $sql .= " AND days_id = '".$days."'";
                }
                if (!empty($category) && $category != 'Select') { 
                    $sql .= " AND task_id = '".$category."'";
                }

                echo "t_des: ".$t_des."<br>";
                echo "t_det: ".$t_det."<br>";
                echo "s_date: ".$s_date."<br>";
                echo "e_date: ".$e_date."<br>";
                echo "priority: ".$priority."<br>";
                echo "days: ".$days."<br>";
                echo "category: ".$category."<br>";
                echo $sql;

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment; filename="tasks.csv"');
                    echo "Task ID,Task Description,Task Detail,Start Date,End Date,Priority,Days,Category\n";

                    while ($row = $result->fetch_assoc()) {
                        $priority_id = $row['priority_id'];
                        $days_id = $row['days_id'];
                        $task_id = $row['task_id'];

                        $sql_p = "SELECT priority FROM priority WHERE id = $priority_id";
                        $priority_result = $conn->query($sql_p);
                        $priority_row = $priority_result->fetch_assoc();

                        $sql_d = "SELECT days FROM days WHERE id = $days_id";
                        $days_result = $conn->query($sql_d);
                        $days_row = $days_result->fetch_assoc();

                        $sql_c = "SELECT task FROM category WHERE id = $task_id";
                        $category_result = $conn->query($sql_c);
                        $category_row = $category_result->fetch_assoc();

                        $task_description = utf8_encode($row['task_description']);
                        $task_detail = utf8_encode($row['task_detail']);

                        echo $row['id'].",".$task_description.",".$task_detail.",".$row['start_date'].",".$row['end_date'].",".$priority_row['priority'].",".$days_row['days'].",".$category_row['task']."\n";
                    }
                    exit;
                } else {
                    echo "No data";
                    exit;
                }
            }
            ?>
        </tbody>
    </table><div>
    <div class="pagination">
        <?php 
        $sql1 = "SELECT COUNT(*) AS total FROM tasks";
        $result1 = $conn->query($sql1);
        $total_records = $result1->fetch_assoc()['total'];
        $total_pages = ceil($total_records / $limit);
        echo "<span style='font-size:18px;'>Page </span>";
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? 'class="active"' : '';
            $t_des = isset($_POST['t_des']) ? $_POST['t_des'] : (isset($_GET['t_des']) ? $_GET['t_des'] : '');
            $t_det = isset($_POST['t_det']) ? $_POST['t_det'] : (isset($_GET['t_det']) ? $_GET['t_det'] : '');
            $s_date = isset($_POST['s_date']) ? $_POST['s_date'] : (isset($_GET['s_date']) ? $_GET['s_date'] : '');
            $e_date = isset($_POST['e_date']) ? $_POST['e_date'] : (isset($_GET['e_date']) ? $_GET['e_date'] : '');
            $priority = isset($_POST['priority']) ? $_POST['priority'] : (isset($_GET['priority']) ? $_GET['priority'] : '');
            $days = isset($_POST['days']) ? $_POST['days'] : (isset($_GET['days']) ? $_GET['days'] : '');
            $category = isset($_POST['category']) ? $_POST['category'] : (isset($_GET['category']) ? $_GET['category'] : '');
            $page_url = 'read.php?page='. $i. '&t_des=' . $t_des . '&t_det=' . $t_det . '&s_date=' . $s_date . '&e_date=' . $e_date . '&priority=' . $priority . '&days=' . $days . '&category=' . $category;
            echo '<a href="' . $page_url . '" ' . $active . '>' . $i . '</a>';
        } 
        ?>  
    </div>
</body>
</html>