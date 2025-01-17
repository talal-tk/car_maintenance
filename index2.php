<?php
session_start(); // Start the session

include("connect.php");


?>




<?php
// add car 
    include("connect.php");

    if (isset($_POST['submit'])) {
        // استقبال البيانات من النموذج
        $carid= $_POST['car_id'];
        $car_plate = $_POST['car_plate'];
        $customer_name = $_POST['customer_name'];
        $phone_number = $_POST['phone_number'];
        $cost = $_POST['cost'];
        $time= $_POST['time'];
        // معالجة المشاكل (checkboxes)
        if (isset($_POST['issue'])) {
            $issues = $_POST['issue']; // مصفوفة المشاكل
            $all_issues = implode(', ', $issues); // تحويل المصفوفة إلى نص مفصول بفواصل
        } else {
            $all_issues = "No issues selected"; // في حالة عدم اختيار مشاكل
        }

        // إدخال البيانات في قاعدة البيانات
        $sql = "INSERT INTO maintenance_order (car_id ,car_plate,customer_name, phone, issue,cost,time)
                VALUES ( '$carid','$car_plate', '$customer_name', '$phone_number', '$all_issues', '$cost','$time')";

        if (mysqli_query($conn, $sql)) {
            $message = "Car issues added successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }

// إغلاق الاتصال بقاعدة البيانات
mysqli_close($conn);
?>


<?php
// add employee in manager page 

$employeeId = $_POST["employeeId"];
$employeeName = $_POST["employeeName"];
$employeePhone = $_POST["employeePhone"];
$employeeEmail = $_POST["employeeEmail"];
$username = $_POST["username"];
$password =$_POST["password"];
$role = $_POST["role"]; 
$pic = $_POST["fileToUpload"];


if (mysqli_num_rows($checkResult) > 0) {
    echo "Employee ID already exists. Please choose a different ID.";
} else {
    // Insert the employee if employeeId is unique

    $sql = "INSERT INTO employee (employee_id ,name, phone, email, username, password, employee_type, pic)
            VALUES ( '$employeeId', '$employeeName', '$employeePhone', '$employeeEmail', '$username', '$password','$role','$pic')";
    
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION['success_message'] = "Employee added successfully."; // Set success message in session
        $_SESSION['employee_id']= $employee_id;
    } else {
        $_SESSION['error_message'] = "Employee addition was not successful."; // Set error message in session
    }
}

?>



<?php 
    // --- schedule ---

    // استعلام SQL للحصول على البيانات
    $sql = "SELECT id,plate_number, entry_date, entry_time, finish_date FROM schedule";
    $result = $conn->query($sql);

    // تخزين الصفوف في متغير
    $rows = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    } else {
        $rows = null;
    }

    // إغلاق الاتصال بقاعدة البيانات
    //$conn->close();
?>

<?php 
    // --- show emplyee  ---
    
    // استعلام SQL للحصول على البيانات
    $sql = "SELECT employee_id , name,phone,email,username,password,employee_type FROM employee";
    $result = $conn->query($sql);

    // تخزين الصفوف في متغير
    $rows_e = [];
    if ($result->num_rows > 0) {
        while ($row_e = $result->fetch_assoc()) {
            $rows_e[] = $row_e;
        }
    } else {
        $rows_e = null;
    }

    // إغلاق الاتصال بقاعدة البيانات
    $conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Maintenance manager page </title>
 <link rel="stylesheet" href="index2.css">  

<!-- to show messge if insert emplyoee -->
    <style>
        .message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f0f8ff;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: none; /* Hide by default */
            z-index: 1000; /* Ensure it's above other content */
        }

        aside nav ul {
            height: 600px;
    overflow-y: scroll;
}
    </style>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.form-group {
  margin-bottom: 10px;
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

input[type="text"],
input[type="date"] {
  width: 50%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.checkbox-group {
  display: flex;
  flex-wrap: wrap;
}

.checkbox-group label {
  margin-right: 5px;
  margin-bottom: 5px;
}

#error-message {
  color: red;
  font-weight: bold;
}
    </style>

    
</head>
<body>
    <!-- Header -->
    <header>

        <form action="loginf.php" method="POST">
            <h1 id="ttt">Car Maintenance Dashboard - <ins>Employee</ins> </h1>
            <button style="top: 15 ;" class="logout" id="out" >Log Out</button>
        </form>

        
    </header>
  
    
    <!-- Display success or error message -->
    
    <div id="messageBox" style="display: none;"></div>


    <script>
        function showMessage() {
            var messageBox = document.getElementById('messageBox');
            if (messageBox && messageBox.innerText.trim() !== '') {
                messageBox.style.display = 'block';
                setTimeout(function() {
                    messageBox.style.display = 'none';
                }, 5000); // Hide after 5 seconds
            }
        }
    </script>











    <!-- Sidebar -->
    <aside>
        <nav>
            <ul>
                <li><a href="#Home" onclick="showSection('Home')">Home</a></li>
                <li><a href="#profile"onclick="showSection('profile')">My Profile</a></li>
                <hr>
                <li><a href="#addCar"onclick="showSection('addCar')">Add Car </a></li>
                <li><a href="#search"onclick="showSection('search')">Search for Car</a></li>
                <li><a href="#schedule"onclick="showSection('schedule')">Schedule</a></li>
                <hr>
                <li><a href="#addReport"onclick="showSection('addReport')">Add Maintenance Report</a></li>
                <li><a href="#reports"onclick="showSection('reports')">Reports and Statistics</a></li>
                
                

                
            </ul>
        </nav>
    </aside>
    
    <!-- home -->
    <main>
        <section style=" text-align: center;" id="Home" class="content-section">
            <h2>Maintenance Summary</h2>
            <p>Current Cars in Maintenance: <strong>5</strong></p>
            <p>Cars Completed Maintenance: <strong>10</strong></p>
            <p>Cars Needing Urgent Maintenance: <strong>2</strong></p>
        </section>


        <!-- addCar  -->
        <section  style=" text-align: center;" id="addCar" class="content-section">
            
            <form method="post" >
            <h2 style=" text-align: center;">Add Car</h2>
            <label>Car ID:</label>
            <input type="text" name="car_id" required><br><br>
            <label>Car Plate:</label>

            <input type="text" name="car_plate" required><br><br>

            <label>Customer Name:</label>
            <input type="text" name="customer_name" required><br><br>

            <label>Phone Number:</label>
            <input type="text" name="phone_number" required><br><br>

            
            <h3>Problem:</h3>
            <div id="issues_car">
                <div class="checkbox-group">
                    <label><input type="checkbox" name="issue[]" value="brakes"> Brakes</label>
                    <label><input type="checkbox" name="issue[]" value="wipers"> Windshield Wipers</label>
                    <label><input type="checkbox" name="issue[]" value="engine_oil"> Engine Oil</label>
                    <label><input type="checkbox" name="issue[]" value="battery"> Battery</label>
                    <label><input type="checkbox" name="issue[]" value="tires"> Tires</label>
                    <label><input type="checkbox" name="issue[]" value="coolant"> Coolant</label>
                </div>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="issue[]" value="suspension"> Suspension</label>
                    <label><input type="checkbox" name="issue[]" value="air_filter"> Air Filter</label>
                    <label><input type="checkbox" name="issue[]" value="fuel_filter"> Fuel Filter</label>
                    <label><input type="checkbox" name="issue[]" value="exhaust"> Exhaust System</label>
                    <label><input type="checkbox" name="issue[]" value="spark_plugs"> Spark Plugs</label>
                    <label><input type="checkbox" name="issue[]" value="belt"> Engine Belt</label>
                </div>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="issue[]" value="lights"> Car Lights</label>
                    <label><input type="checkbox" name="issue[]" value="ac"> Air Conditioning</label>
                    <label><input type="checkbox" name="issue[]" value="transmission"> Transmission System</label>
                    <label><input type="checkbox" name="issue[]" value="mirrors"> Car Mirrors</label>
                    <label><input type="checkbox" name="issue[]" value="steering"> Steering System</label>
                    <label><input type="checkbox" name="issue[]" value="leaks"> Fluid Leaks</label>
                </div>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="issue[]" value="gauges"> Dashboard Gauges</label>
                    <label><input type="checkbox" name="issue[]" value="seat_belts"> Seat Belts</label>
                </div>
            </div>
            <p id="error-message" style="color: red; display: none;">Please select at least one issue.</p>

            <br><br><br> 
            
            <label>Cost:</label>
            <input type="text" name="cost" required><br><br>
            <label>Date:</label>
            <input type="date" name="time" required><br><br>
           
            <br>
            <button type="submit" name="submit">Submit</button>
        </form>
    <div id="massage" >

<?php echo $massage; ?>

</div>
<script>
    <?php if (isset($message)) { ?>
        // عرض الرسالة بعد إرسال النموذج
        var messageDiv = document.createElement("div");
        messageDiv.innerHTML = "<?php echo $message; ?>";
        messageDiv.style.backgroundColor = "#4CAF50";
        messageDiv.style.color = "white";
        messageDiv.style.padding = "10px";
        messageDiv.style.marginTop = "10px";
        messageDiv.style.textAlign = "center";
        document.getElementById("massage").appendChild(messageDiv);

        // إخفاء الرسالة بعد 5 ثواني
        setTimeout(function() {
            messageDiv.style.display = "none";
        }, 5000);
    <?php } ?>
</script>

</section>


         <!-- search  -->
        <section style=" text-align: center;" id="search" class="content-section">
            <h2>Search for Car</h2>
            <script>
function showUser() {
  const userInput = document.getElementById("userInput").value;

  // Create an XMLHttpRequest object
  const xhr = new XMLHttpRequest();

  // Specify the URL and HTTP method
  xhr.open("GET", "search.php?q=" + userInput, true);

  // Handle the response
  xhr.onload = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("result").innerHTML = this.responseText;
    }
  };

  // Send the request
  xhr.send();
}
</script>



    <form id="userForm">
  <input type="text" id="userInput" name="user_name">
  <button type="button"  onclick="showUser()">Search</button>
</form>
<div id="result"></div>

<br>
        </section>


         <!-- addReport  -->
        <section style=" text-align: center;" id="addReport" class="content-section">
            <h2 >Add Maintenance Report</h2>
            <form>
                <label for="carId2">License Plate Number:</label>
                <input type="text" id="carId2" name="carId" required>
                
                <label for="workDone">Work Done:</label>
                <textarea id="workDone" name="workDone" required></textarea>
                

                <br><br>
                
                <button type="submit">Add Report</button>
            </form>
        </section>


         <!-- schedule  -->
         <section style=" text-align: center;" id="schedule" class="content-section">
            <h2 >Schedule order </h2>
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

$sql = "SELECT * FROM maintenance_order";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
     echo "<table> <tr> <th>Car id </th> <th>Car plate </th> <th>Issues </th> <th>Appointment date </th> </tr>";
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    echo "<tr> <td>" . $row["car_id"]. "</td> <td>" . $row["car_plate"]. "</td> <td>". $row["issue"].  "</td> <td>" . $row["time"]. "</td> </tr>";
  } 
  echo "</table>";
} else {
  echo "0 results";
}

mysqli_close($conn);
?>
        </section>


        <section style=" text-align: center;" id="postOnX" class="content-section">
        <h2>Post on Twitter</h2>
    
    // HTML form
    <form method="post">
        <textarea name="tweet_text"></textarea>
        <input type="submit" value="Post to Twitter">
    </form>

    <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-text="Hi im wroking on " data-related="Golden_Oil_KSA" data-lang="en" data-show-count="false">Tweet</a>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </section>


        <!-- reports  -->
        <section style=" text-align: center;" id="reports" class="content-section">
            <h2 >Reports and Statistics</h2>
            <p>Total Cars Serviced This Month: <strong>7</strong></p>
            <p>Best Maintenance Employee: <strong>Nasser Ahmed</strong></p>
        </section>


        <!-- profile  -->
        <section style=" text-align: center;" id="profile" class="content-section">
            <h2>My Profile</h2>
            <?php
            
            echo '<p>Username: <strong>'. $_GET['username'].'</strong></p>';
            ?>



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

$username1 = $_GET['username'];

$sql = "SELECT pic FROM employee WHERE username = $username1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<img src="uploads/' . $row['pic'] . '" alt="Profile Image" style="height: 200px; width: 200px; border-radius: 50%;">';
    }
} else {
    echo "0 results";
}

mysqli_close($conn);
?>


            

        </section>


        <!--AddEmployee-->
        <section id="AddEmployee" class="content-section">
         
        <div>

            <h2>Add Employee</h2>
            <form action="index1lv.php" method="post">
     

            <div id="container1" >
            <label for="employeeId">Employee Id:</label>
            <input type="text" id="employeeId" name="employeeId" required>
            



            <label for="employeeName">Employee Name:</label>
            <input type="text" id="employeeName" name="employeeName" required>
            </div>
            <br><br>

            <div id="container2" style="  margin-right: 90;">
            <label for="employeePhone">Phone Number:</label>
            <input type="text" id="employeePhone" name="employeePhone" required>
            

            <label for="employeeEmail">Email:</label>
            <input type="text" id="employeeEmail" name="employeeEmail" required>
            </div>
             <br><br>

             <div id = "container3" style="  margin-right: 20;">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            

            <label for="password">Password:</label>
            <input type="text" id="password" name="password" required>
            </div>
            <br><br>

            <label><input type="radio" name="role" value="manager"> manager </label>
            <label><input type="radio" name="role" value="employee"> employee </label>

             <br> <br><br> 

            <input type="file" id="fileInput" name="fileToUpload" style="display: none;">
            <label for="fileInput" style="
  border: 2px dashed #ccc; /* Thicker border */
  padding: 15px 30px; /* More padding */
  border-radius: 10px; /* Larger rounded corners */
  font-size: 16px; /* Larger font size */
  cursor: pointer;
">Select File</label>

            <br><br><br>
           
            <button type="submit" id="showMessageBtn" onclick="displayMessage()">Add Employee</button>
            </div>
             </form>



            

            </div>
         </section>

        <!-- showEmployee  -->
        <section id="showEmployee" class="content-section">
            <h2 style=" text-align: center;">showEmployee</h2>
            <table>
            <thead>
            <tr>
                <th>ID </th>
                <th>Name </th>
                <th>Phone</th>
                <th>Email</th>
                <th>Username</th>
                <th>Password</th>
                <th>employee_type</th>
                
            </tr>
        </thead>

        <tbody>
        <?php
       
       if ($rows_e) {
            // عرض الصفوف الديناميكية
            foreach ($rows_e as $row_e) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row_e["employee_id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row_e["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row_e["phone"]) . "</td>";
                echo "<td>" . htmlspecialchars($row_e["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row_e["username"]) . "</td>";
                echo "<td>" . htmlspecialchars($row_e["password"]) . "</td>";
                echo "<td>" . htmlspecialchars($row_e["employee_type"]) . "</td>";
                // إضافة أزرار الحذف والتعديل
                
                echo "</tr>";
            }
        } else {
            // عرض رسالة إذا لم توجد بيانات
            echo "<tr><td colspan='7'>No results found</td></tr>"; //<!-- تعديل العدد ليشمل عمود الأفعال -->
        }
        ?>
        
        </tbody>

            </table>

            <form action="delete_employee.php" method="post">
    <label for="employee_id">Employee ID:</label>
    <input type="text" id="employee_id" name="employee_id" required>
    <button type="submit">Delete Employee</button>
     </form>
        </section>
    </main>

    <!--for Page splitting ( sections )-->
    <script>
        function showSection(sectionId) {
            // Hide all sections
            var sections = document.querySelectorAll('.content-section');
            sections.forEach(section => {
                section.style.display = 'none';
            });

            // Show the selected section
            document.getElementById(sectionId).style.display = 'block';
        }

        // Show the dashboard by default
        showSection('AddEmployee');
    </script>
</body>
</html>
