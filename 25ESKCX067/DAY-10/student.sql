CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    branch VARCHAR(50) NOT NULL,
    cgpa DECIMAL(3,1) NOT NULL CHECK (cgpa >= 0.0 AND cgpa <= 10.0),
    photo VARCHAR(255) DEFAULT NULL,
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


INSERT INTO students (name, email, branch, cgpa, status) VALUES
('Narpat Gahlot', 'narpatgahlot@gmail.com', 'Computer Science', 8.7, 'Active'),
('Nikhil tak', 'nikhiltak@gmail.com.com', 'electrical', 7.2, 'Active'),
(' parth saraswat', 'parthsaraswat@gmail.com', 'data science', 9.1, 'Inactive'),
('prashant jain', 'prashant@gmail.com', 'Computer Science', 6.8, 'Active');
