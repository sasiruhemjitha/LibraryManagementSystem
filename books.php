<?php
session_start();
include 'Includes\db.php';

// check login
if (!isset($_SESSION['admin'])) { header("Location: index.php"); exit(); }

$search_query = "";

// check if admin is searching for a book
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
    // search in title or author
    $sql = "SELECT * FROM books WHERE title LIKE '%$search_query%' OR author LIKE '%$search_query%'";
} else {
    // get all books if no search
    $sql = "SELECT * FROM books ORDER BY id DESC";
}

$result = $conn->query($sql); // run the query
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Books</title>
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
        <h2>Book List</h2>
        
        <!-- Search Bar with Live Suggestions -->
        <form method="GET" action="" style="display: flex; gap: 10px; margin-bottom: 20px;">
            <div class="search-container">
                <!-- autocomplete="off" -->
                <input type="text" name="search" id="search-box" placeholder="Search by Title or Author..." value="<?php echo htmlspecialchars($search_query); ?>" style="margin:0;" autocomplete="off">
                
                <!-- Suggestions -->
                <div id="suggestions-box" class="suggestions-box"></div>
            </div>
            <button type="submit" class="btn">Search</button>
        </form>

        <a href="add_book.php" class="btn" style="margin-bottom: 15px;">➕ Add New Book</a>

        <table>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if($row['cover_image']): ?>
                        <img src="Uploads\Books<?php echo $row['cover_image']; ?>" width="80" height="120" style="border-radius: 4px;">
                    <?php else: ?> 
                        <span style="color: gray;">No Image</span> 
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['author']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>
                    <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="btn" style="padding: 5px 10px;">Edit</a>
                    <a href="delete_book.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" style="padding: 5px 10px;" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- JavaScript for Live Search -->
<script>
    const searchBox = document.getElementById('search-box');
    const suggestionsBox = document.getElementById('suggestions-box');

    // keyup event
    searchBox.addEventListener('keyup', function() {
        let query = this.value;
        
        if (query.length > 0) {
            // AJAX Request 
            fetch('search_ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'query=' + encodeURIComponent(query)
            })
            .then(response => response.text())
            .then(data => {
                suggestionsBox.innerHTML = data;
                suggestionsBox.style.display = 'block'; 
            });
        } else {
            suggestionsBox.style.display = 'none'; 
        }
    });

    function selectSuggestion(val) {
        searchBox.value = val; 
        suggestionsBox.style.display = 'none'; 
        
        
    }

    document.addEventListener('click', function(e) {
        if (e.target !== searchBox && e.target !== suggestionsBox) {
            suggestionsBox.style.display = 'none';
        }
    });
</script>

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