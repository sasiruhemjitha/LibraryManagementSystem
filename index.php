<?php
session_start(); 
include 'Includes/db.php'; 

// Check if user clicked the login button
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get the typed username and password
    $username = $conn->real_escape_string($_POST['username']);
    $entered_password = $_POST['password'];

    // Find this username in the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    // If username is found
    if ($result->num_rows > 0) {
        
        // Get user details from the database
        $row = $result->fetch_assoc();
        $hashed_password_in_db = $row['password'];

        // Check if entered password is correct
        if (password_verify($entered_password, $hashed_password_in_db)) {
            
            // Login is successful
            $_SESSION['admin'] = $username;
        
            // Show success message and go to dashboard
            echo "<script>
                    alert('Login Successful! 🤗 Welcome to Orion Library.');
                    window.location.href='dashboard.php';
                  </script>";
            exit();
            
        } else {
            // Password is wrong
            $error = "Invalid Username or Password!"; 
        }
    } else {
        // Username is wrong
        $error = "Invalid Username or Password!"; 
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Login</title>
    <link rel="stylesheet" href="CSS/Style.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        
        <!-- Login Form -->
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn" style="width: 100%;">Login</button>
            
            <!-- Show error message here if login fails -->
            <?php if(isset($error)) echo "<p style='color:red; margin-top:10px;'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>