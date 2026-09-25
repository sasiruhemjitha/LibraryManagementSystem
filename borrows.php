<?php
session_start();
include 'Includes\db.php';

// check login
if (!isset($_SESSION['admin'])) { header("Location: index.php"); exit(); }

// process auto-date book return
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_book'])) {
    
    $borrow_id = $_POST['borrow_id'];
    $book_title = $conn->real_escape_string($_POST['book_title']); 
    $actual_return_date = date('Y-m-d'); // auto get today's date for actual return
    
    // update actual return date
    $conn->query("UPDATE borrow SET actual_return_date='$actual_return_date' WHERE id=$borrow_id");
    
    // increase book quantity by 1 in books table
    $conn->query("UPDATE books SET quantity = quantity + 1 WHERE title='$book_title'");
    
    echo "<script>alert('Book marked as returned today! Inventory increased.'); window.location.href='borrows.php';</script>";
}

// fetch records
$result = $conn->query("SELECT * FROM borrow ORDER BY id DESC");
$today = date('Y-m-d'); // get today's date for overdue check
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Borrows</title>
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
            
            <a href="dashboard.php">Dashboard</a>
            <a href="books.php">Manage Books</a>
            <a href="borrows.php">Manage Borrows</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Borrowed Books List</h2>
        <a href="issue_book.php" class="btn" style="margin-bottom: 15px;">+ Issue New Book</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Book Title</th>
                <th>Borrowed On</th>
                <th>Expected Return</th>
                <th>Actual Return / Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                <td><?php echo $row['borrow_date']; ?></td>
                
                <!-- expected return date -->
                <td>
                    <?php echo $row['expected_return_date']; ?>
                    
                    <!-- Overdue logic: if not returned and today is past the expected date -->
                    <?php 
                    if(!$row['actual_return_date'] && $today > $row['expected_return_date']) {
                        echo "<br><span style='color:#dc3545; font-size:12px; font-weight:bold;'>⚠️ Overdue!</span>";
                    }
                    ?>
                </td>
                
                <td>
                    <!-- if not returned yet, show auto-return button -->
                    <?php if(!$row['actual_return_date']): ?>
                        <form method="POST" action="" style="margin:0;">
                            <input type="hidden" name="borrow_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="book_title" value="<?php echo htmlspecialchars($row['book_title']); ?>">
                            
                            <!-- clicking this button automatically sets today as the return date -->
                            <button type="submit" name="return_book" class="btn" style="background:#28a745; padding:6px 12px;" onclick="return confirm('Confirm book is returned today?');">Return Now</button>
                        </form>
                    <?php else: ?>
                        <!-- show actual returned date -->
                        <strong style="color:#28a745;">Returned: <?php echo $row['actual_return_date']; ?></strong>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
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