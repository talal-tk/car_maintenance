<?php   
    include("connect.php");
    $note = "";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $sql = "SELECT * FROM employee WHERE username='$username' AND password='$password' AND employee_type='$role'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);

        if($count > 0){
            if($role === "manager"){
                header("Location: index1lv.php?username='$username'");
                exit();
            } elseif($role === "employee"){
                header("Location: index2.php?username='$username'");
                exit();
            }
        } else {
            $note = "You do not exist in the system.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="loginf.css"> <!-- Link to the CSS file -->

    <style>
       #note {
           display: block;
           margin: 10px 0;
           padding: 10px;
           color: white;
           background-color: red;
           border-radius: 5px;
       }
   </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="loginf.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label>Role:</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="role" value="manager" required> Manager
                </label>
                <label>
                    <input type="radio" name="role" value="employee"> Employee
                </label>
            </div>

            <button type="submit">Submit</button>
        </form>

        <!-- Display the note if it exists -->
        <?php if ($note !== ""): ?>
            <div id="note">
                <?php echo $note; ?>
            </div>
        <?php endif; ?>

        <script>
            window.onload = function() {
                var note = document.getElementById('note');
                
                // Check if the note exists before setting the timeout
                if (note) {
                    setTimeout(() => {
                        note.style.display = 'none';
                    }, 3000); // Hide the note after 3 seconds
                }
            };
        </script>
    </div>
</body>
</html>
