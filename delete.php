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

$sql="DELETE FROM tasks WHERE id=".$_GET['id'];

$result=$conn->query($sql);
if (!$result ) {
  echo "Error: " . $sql . "<br>" . $conn->error;
} else {
  header('Location: read.php?delete_msg=One Record Deleted successfully');
  exit;
}

$conn->close();

?>