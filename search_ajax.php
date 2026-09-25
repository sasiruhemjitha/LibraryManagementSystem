<?php
include 'Includes\db.php';

if (isset($_POST['query'])) {
    $search = $conn->real_escape_string($_POST['query']);
    
    // Show about 5 books as suggestions.
    $sql = "SELECT title FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%' LIMIT 5";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            // Pass the book name to a JavaScript function when clicked
            $title = htmlspecialchars($row['title']);
            $escaped_title = addslashes($row['title']);
            
            echo "<div class='suggestion-item' onclick='selectSuggestion(\"$escaped_title\")'>$title</div>";
        }
    } else {
        echo "<div class='suggestion-item' style='color:red;'>No books found...</div>";
    }
}
?>