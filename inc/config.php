<?php
// Condition for Localhost data
if ($_SERVER['SERVER_NAME'] == 'localhost') {
  $host = 'localhost';
  $username = 'root';
  $password = '';
} else {
  $host = 'tmjpwmd-dbinstance.cjw9rloocgwb.ap-northeast-1.rds.amazonaws.com';
  $username = 'ad_tmjpwmd';
  $password = 'fUzfC6I9zDBofoeHQ7Yl';
}

//Connecting to database
$connection = mysqli_connect($host, $username, $password);

// Check connection
if($connection === false){
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Attempt create database query execution
$sql = "CREATE DATABASE IF NOT EXISTS demo";
//Proceed the execution
mysqli_query($connection, $sql);


/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$connection = mysqli_connect($host, $username, $password, "demo");

// Check connection
if($connection === false){
  die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Attempt create table query execution
$sql = "CREATE TABLE IF NOT EXISTS loginform(
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  fname VARCHAR(50) NOT NULL,
  lname VARCHAR(50) NOT NULL,
  gender VARCHAR(50) NOT NULL,
  email VARCHAR(70) NOT NULL UNIQUE,
  Pass VARCHAR(50) NOT NULL,
  picture VARCHAR(50) NOT NULL,
  status BOOLEAN,
  active BOOLEAN
)";
//Proceed the execution
mysqli_query($connection, $sql);

// Attempt create default data query execution
$sql = "INSERT INTO  loginform (fname, lname, gender, email, Pass, picture, status, active)
SELECT * FROM (SELECT 'TMJ', 'Philippines', 'male', '0a11ed7ec76b598775260c4539d0110e', 'add4975cdca13639aa973de26aa98ca8', 'default.png', '1' , '0') AS tmp
WHERE NOT EXISTS ( SELECT email from loginform WHERE email= '0a11ed7ec76b598775260c4539d0110e' ) ";
//Proceed the execution
mysqli_query($connection, $sql);

// Close connection
// mysqli_close($connection);
?>
