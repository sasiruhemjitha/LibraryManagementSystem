<?php
session_start();

// Check if user is logged in. If not, send them back to login page
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

include 'Includes\db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get the new username and password from the form
    $new_username = $conn->real_escape_string($_POST['new_username']);
    $new_password = $_POST['new_password'];

    // MAKE IT SAFE: Hash the new password automatically!
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // SQL command to save the new admin in the database
    $sql = "INSERT INTO users (username, password) VALUES ('$new_username', '$hashed_password')";

    // Check if it was saved successfully
    if ($conn->query($sql) === TRUE) {
        // Show success message
        echo "<script>
                alert('New Admin added successfully!');
                window.location.href='dashboard.php';
              </script>";
    } else {
        // Show error message if something went wrong
        $error = "Error: Could not add new admin.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Admin</title>
    <link rel="stylesheet" href="CSS\Style.css">
</head>
<body>

    <!-- Navigation Bar -->
    <div class="nav">
        <a href="dashboard.php" class="nav-brand">
            <img src="Images\OC.jpg" alt="Logo" class="nav-logo">
            <h2>Orion Library</h2>
        </a>
        <div class="nav-links">
            <button id="darkModeToggle" class="btn dark-mode-btn">🌙 Dark Mode</button>
            <a href="dashboard.php">Dashboard</a>
            <a href="books.php">Manage Books</a>
            <a href="borrows.php">Manage Borrows</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Add Admin Form Box -->
    <div class="container" style="max-width: 500px;">
        <h2 style="text-align: center;">👤 Add New Admin</h2>
        
        <!-- Show error message if there is one -->
        <?php if(isset($error)) echo "<p style='color:red; text-align:center;'>$error</p>"; ?>

        <!-- Form -->
        <form method="POST" action="">
            
            <label>New Username:</label>
            <input type="text" name="new_username" required>
            
            <label>New Password:</label>
            <input type="password" name="new_password" required>
            
            <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">Save New Admin</button>
            
        </form>
    </div>

    <!-- JavaScript for Dark Mode -->
    <script>
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;

        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode'); 
            if(darkModeToggle) darkModeToggle.innerText = '☀️ Light Mode'; 
        }

        if(darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                body.classList.toggle('dark-mode');
                
                if (body.classList.contains('dark-mode')) {
                    localStorage.setItem('theme', 'dark');
                    darkModeToggle.innerText = '☀️ Light Mode';
                } else {
                    localStorage.setItem('theme', 'light');
                    darkModeToggle.innerText = '🌙 Dark Mode';
                }
            });
        }
    </script>
</body>
</html>