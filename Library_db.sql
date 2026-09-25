-- Create database
CREATE DATABASE library_db;

-- Users table for Admin Login 
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);
-- Password: 123
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$QO2HkQ37g8/2vK8wZ6/e/O.E/nL6lV0a/O/oZ1Yw7J5L6W9R3lC/u');

-- Books table with Image Upload support
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    cover_image VARCHAR(255) DEFAULT NULL
);

-- Borrow table for Borrow Management
CREATE TABLE borrow (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    book_title VARCHAR(150) NOT NULL,
    borrow_date DATE NOT NULL,
    expected_return_date DATE NOT NULL,
    actual_return_date DATE DEFAULT NULL
);