Steps to Test
Save your HTML file as index.html and PHP file as process.php in the same project folder (e.g., C:\wamp64\www\myproject).
Start WAMP Server.
Open a browser and go to http://localhost/myproject/index.html.
Fill in the form and click one of the buttons (Register, Search, or Update).
The process.php script will display the appropriate response.

DataBase Creation

CREATE DATABASE student_db;

USE student_db;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    city VARCHAR(50) NOT NULL
);



Default Login 
"admin"
"admin"