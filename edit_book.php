<?php
session_start();
include 'Includes\db.php';

// check login
if (!isset($_SESSION['admin'])) { header("Location: index.php"); exit(); }

// get current book details from url id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM books WHERE id=$id");
    $book = $result->fetch_assoc();
}

// if update form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $category = $conn->real_escape_string($_POST['category']);
    $quantity = $_POST['quantity'];

    // check if a new image is selected
    if (!empty($_FILES['cover_image']['name'])) {
        $cover_image = time() . "_" . basename($_FILES["cover_image"]["name"]);
        $target_file = "Uploads\Books" . $cover_image;
        move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file);
        
        // update everything including image
        $sql = "UPDATE books SET title='$title', author='$author', category='$category', quantity='$quantity', cover_image='$cover_image' WHERE id=$id";
    } else {
        // update without changing the old image
        $sql = "UPDATE books SET title='$title', author='$author', category='$category', quantity='$quantity' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Book details updated!'); window.location.href='books.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <link rel="stylesheet" href="CSS\Style.css">
</head>
<body>
    <div class="nav">
        <a href="dashboard.php" class="nav-brand">
            <img src="Images\OC.jpg" alt="Orion Logo" class="nav-logo">
            <h2>Orion Library</h2>
        </a>
    </div>

    <div class="container">
        <h2>Edit Book Details</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <label>Book Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
            
            <label>Author</label>
            <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
            
            <label>Category</label>
            <select name="category" required>
                <option value="<?php echo htmlspecialchars($book['category']); ?>"><?php echo htmlspecialchars($book['category']); ?></option>
                <option value="Fiction">Fiction</option>
                <option value="Non-Fiction">Non-Fiction</option>
                <option value="Science & Technology">Science & Technology</option>
                <option value="History & Geography">History & Geography</option>
                <option value="Reference">Reference</option>
            </select>
            
            <label>Quantity</label>
            <input type="number" name="quantity" value="<?php echo $book['quantity']; ?>" required>

            <label>Change Cover Image (Optional)</label>
            <?php if($book['cover_image']) { ?>
                <img src="Uploads\Books<?php echo $book['cover_image']; ?>" width="60" height="80" style="margin-bottom:10px; display:block; border-radius:4px;">
            <?php } ?>
            <input type="file" name="cover_image" accept="image/*">
            
            <button type="submit" class="btn">Update Book</button>
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