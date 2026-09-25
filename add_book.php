<?php
session_start();
include 'Includes\db.php';

// check login
if (!isset($_SESSION['admin'])) { header("Location:index.php"); exit(); }

// if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // get form data safely
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $category = $conn->real_escape_string($_POST['category']);
    $quantity = $_POST['quantity'];
    
    // simple validation check
    if (empty($title) || empty($author) || empty($category) || empty($quantity)) {
        echo "<script>alert('Please fill all required fields!');</script>";
    } else {
        
        // handle cover image upload
        $cover_image = "";
        if (!empty($_FILES['cover_image']['name'])) {
            $cover_image = time() . "_" . basename($_FILES["cover_image"]["name"]); 
            $target_file = "Uploads\Books" . $cover_image;
            move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file); 
        }

        // save to database
        $sql = "INSERT INTO books (title, author, category, quantity, cover_image) 
                VALUES ('$title', '$author', '$category', '$quantity', '$cover_image')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New book added successfully!'); window.location.href='books.php';</script>";
        } else {
            echo "<script>alert('Error saving the book.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="CSS\Style.css">
</head>
<body>
    <div class="nav">
        <a href="dashboard.php" class="nav-brand">
            <img src="Images\OC.jpg" alt="Orion Logo" class="nav-logo">
            <h2>Orion Library</h2>
        </a>
        <div class="nav-links"><a href="books.php">Back to Books</a></div>
    </div>

    <div class="container">
        <h2>📚 Add New Book</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <label>Book Title</label>
            <input type="text" name="title" placeholder="Enter book title" required>
            
            <label>Author</label>
            <input type="text" name="author" placeholder="Enter author name" required>
            
            <label>Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
                <option value="Science & Technology">Science & Technology</option>
                <option value="History & Geography">History & Geography</option>
                <option value="Reference">Reference</option>
            </select>
            
            <label>Quantity</label>
            <input type="number" name="quantity" min="1" value="1" required>

            <label>Cover Image (Optional)</label>
            <input type="file" name="cover_image" accept="image/*">
            
            <button type="submit" class="btn">Save Book</button>
            <a href="books.php" class="btn btn-danger">Cancel</a>
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