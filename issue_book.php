<?php
session_start();
include 'Includes\db.php';

// check login
if (!isset($_SESSION['admin'])) { header("Location: index.php"); exit(); }

// get available books (quantity greater than 0)
$books_result = $conn->query("SELECT title FROM books WHERE quantity > 0");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = $conn->real_escape_string($_POST['student_name']);
    $book_title = $conn->real_escape_string($_POST['book_title']);
    $borrow_date = date('Y-m-d'); // auto set today's date for borrow date
    
    // getting the manual expected return date from form
    $expected_return_date = $_POST['expected_return_date']; 
    
    // insert borrow record with expected return date
    $sql = "INSERT INTO borrow (student_name, book_title, borrow_date, expected_return_date) 
            VALUES ('$student_name', '$book_title', '$borrow_date', '$expected_return_date')";
    
    if ($conn->query($sql) === TRUE) {
        // decrease book quantity in books table
        $conn->query("UPDATE books SET quantity = quantity - 1 WHERE title = '$book_title'");

        echo "<script>alert('Book issued successfully!'); window.location.href='borrows.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
    <link rel="stylesheet" href="CSS\Style.css">
</head>
<body>
    <div class="nav">
        <a href="dashboard.php" class="nav-brand">
            <img src="Images\OC.jpg" alt="Orion Logo" class="nav-logo">
            <h2>Orion Library</h2>
        </a>
        <div class="nav-links"><a href="borrows.php">Back to Borrows</a></div>
    </div>

    <div class="container">
        <h2>Issue a Book</h2>
        <form method="POST" action="">
            <label>Student Name</label>
            <input type="text" name="student_name" placeholder="Enter student name" required>
            
            <label>Select Book</label>
            <select name="book_title" required>
                <option value=""> Choose an Available Book </option>
                <?php while($row = $books_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['title']); ?>"><?php echo htmlspecialchars($row['title']); ?></option>
                <?php endwhile; ?>
            </select>
            
            <label>Expected Return Date</label>
            <!-- completely manual date picker, required to be filled  -->
            <input type="date" name="expected_return_date" required>
            
            <button type="submit" class="btn">Issue Book</button>
            <a href="borrows.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
    
    <!-- JavaScript for Dark Mode (Put this at the bottom of EVERY page) -->
<script>
    // Get the button and the whole page (body)
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;

    // Check if the user already chose dark mode before (using Local Storage)
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode'); 
        if(darkModeToggle) darkModeToggle.innerText = '☀️ Light Mode'; 
    }

    // What happens when we click the button
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