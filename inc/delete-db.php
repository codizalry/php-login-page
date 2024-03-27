<?php
require_once './config.php';
session_start();

//Redirection
if (!isset($_SESSION['email'])) {
  header('location: ./../index.php');
} else {
  if ($_SESSION['email'] == 'ab02ee727c1644d299abc4c8ca17045b') {
    $connection->query("Drop Table loginform");
    printf("Table Loginform dropped successfully.");
  } else {
    header('location: ./../content.php');
  }
}


 ?>
