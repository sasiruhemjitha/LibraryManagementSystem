<?php
session_start();
include 'Includes\db.php';

// check login
if (!isset($_SESSION['admin'])) { header("Location: index.php"); exit(); }

// delete book by id
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM books WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Book deleted successfully!'); window.location.href='books.php';</script>";
    } else {
        echo "<script>alert('Error deleting book!'); window.location.href='books.php';</script>";
    }
}
?>