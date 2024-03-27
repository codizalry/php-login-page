<?php
//include config
require_once './inc/config.php';

//Email Activation
$email = $_GET['activate'];
if (isset($_GET['activate'])) {
  //Query for searching the email with status 1.
  $sql = "SELECT * FROM loginform WHERE email='$email' AND status = '1'";

  $result = mysqli_query($connection, $sql);

  //Validation if the query found a equal value
  if (!$result->num_rows > 0) {
    //Update the status in DB for email activation
    $sql = "UPDATE loginform SET status = 1 WHERE email = '$email'";
    $result = mysqli_query($connection, $sql);
    $message = 'Your Account has been activated!';
    $body = 'You may now login to the site using your chosen username and password. Thank you.';
  } else {
    $message = 'Account has already been Activated!';
    $body = 'The session is already used. Thank you.';
  }

} else {
  header('Location: index.php');
}
 ?>

 <!doctype html>
 <html lang="en">
 	<head>
 		<meta charset="utf-8">
 		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 		<link rel="stylesheet" href="assets/bs4/css/bootstrap.min.css">
     <link rel="stylesheet" href="assets/css/style.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
 		<title>TMJP Login</title>
 	</head>
   <body>
     <div class="activation-page login-bg">
   		<div class="container">
   			<div class="row bg-form py-4">
   				<div class="col-md-6 px-4">
             <a href="./" class="hvr-icon-wobble-horizontal text-gray"><i class="fas fa-chevron-left hvr-icon"></i><span class="pl-1">Back</span></a>
             <h2 class="text-gray mb-0"><?=$message;?></h2>
             <p class="text-gray mb-0"><?=$body;?></p>
   				</div>
 					<div class="col-md-6 bg-full d-none d-md-block">
 					</div>
   			</div>
   		</div>
 		</div>
 	</body>
 </html>
