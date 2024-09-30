<?php
include 'authentication.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);
$t_des = $_POST['t_des'];
$t_det = $_POST['t_det'];
$s_date = $_POST['s_date'];
$e_date = $_POST['e_date'];
$priority = $_POST['priority'];
$days = $_POST['days'];
$category = $_POST['category'];
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
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


$result = $conn->query($sql);

if ($result->num_rows > 0) {
    ob_end_clean(); 
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
    echo "No data found for the applied filters.";
    exit;
}
?>