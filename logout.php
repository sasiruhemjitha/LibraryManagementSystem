<?php
session_start();
session_destroy(); // clear session
header("Location:index.php"); // go back to login
exit();
?>