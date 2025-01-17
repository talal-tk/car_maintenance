<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "maintenance";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$id=$_POST['employee_id'];
// sql to delete a record
$sql = "DELETE FROM employee WHERE employee_id='$id' ";

if (mysqli_query($conn, $sql)) {
  echo "Record deleted successfully";
  echo "<br> <a href='index1lv.php'>go back</a>";
} else {
  echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>