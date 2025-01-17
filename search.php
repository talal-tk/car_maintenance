<?php
$q = $_REQUEST["q"];

$con = mysqli_connect('localhost','root','12345678','maintenance');
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"ajax_demo");
$sql="SELECT * FROM customer WHERE car_plate = '".$q."'";
$result = mysqli_query($con,$sql);

echo "<table>
<tr>
<th>Car plate</th>
<th>Car name</th>
<th>Customer name</th>
<th>Phone</th>


</tr>";
while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $row['car_plate'] . "</td>";
  echo "<td>" . $row['car_name'] . "</td>";
  echo "<td>" . $row['customer_name'] . "</td>";
  echo "<td>" . $row['phone'] . "</td>";
  
  
  echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>