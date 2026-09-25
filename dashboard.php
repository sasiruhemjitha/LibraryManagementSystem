<?php
session_start();
include 'Includes\db.php'; 

// check if admin is logged in
if (!isset($_SESSION['admin'])) { 
    header("Location: index.php"); 
    exit(); 
}

// get total number of books
$book_count = $conn->query("SELECT SUM(quantity) AS total FROM books")->fetch_assoc()['total'];
if($book_count == null) $book_count = 0; 

// get total borrowed books that are not returned
$borrow_count = $conn->query("SELECT COUNT(*) AS total FROM borrow WHERE actual_return_date IS NULL")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Dashboard</title>
    <link rel="stylesheet" href="CSS\Style.css">
</head>
<body>
    <div class="nav">
        <a href="dashboard.php" class="nav-brand">
            <img src="Images\OC.jpg" alt="Orion Logo" class="nav-logo">
            <h2>Orion Library</h2>
        </a>
        <div class="nav-links">
            <!-- Button to switch between Dark and Light mode -->
            <button id="darkModeToggle" class="btn dark-mode-btn">🌙 Dark Mode</button>
            
            <a href="add_admin.php" style="color: #4da6ff;">+ Add Admin</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="books.php">Manage Books</a>
            <a href="borrows.php">Manage Borrows</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <!-- Main title -->
        <h2>🙏 Welcome to Admin Dashboard</h2>
        
        <!-- Box container -->
        <div class="dashboard-boxes">
            
            <!-- Box 1: Total Books -->
            <a href="books.php" class="stat-box blue-box">
                <h3>📚 Total Books in Library</h3>
                <h1 class="blue-text"><?php echo $book_count; ?></h1>
            </a>
            
            <!-- Box 2: Borrowed Books -->
            <a href="borrows.php" class="stat-box red-box">
                <h3>🧾 Currently Borrowed</h3>
                <h1 class="red-text"><?php echo $borrow_count; ?></h1>
            </a>
            
        </div>
    </div>

    <!-- JavaScript for Dark Mode -->
<script>
    // Get the button and the whole page (body)
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;

    // 1. Check if the user already chose dark mode before (using Local Storage)
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode'); // Turn on dark mode
        darkModeToggle.innerText = '☀️ Light Mode'; // Change button text
    }

    // 2. What happens when we click the button
    darkModeToggle.addEventListener('click', () => {
        
        // Switch the dark mode class ON or OFF
        body.classList.toggle('dark-mode');
        
        // Check if the page is currently dark
        if (body.classList.contains('dark-mode')) {
            // Save the choice so it stays dark even if we refresh the page
            localStorage.setItem('theme', 'dark');
            // Change button text to Light Mode
            darkModeToggle.innerText = '☀️ Light Mode';
        } else {
            // Save the choice as light mode
            localStorage.setItem('theme', 'light');
            // Change button text back to Dark Mode
            darkModeToggle.innerText = '🌙 Dark Mode';
        }
    });
</script>
</body>
</html>