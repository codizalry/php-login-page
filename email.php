<?php
session_start();
//include config
require_once './inc/config.php';

//Null variable
$validation = '';

if (isset($_POST['submit'])) {
  //Query for searching the email with status 1.
  $sql = "SELECT * FROM loginform where email = '".md5($_POST['email'])."' AND status ='1'";
  $result = mysqli_query($connection, $sql);

  if ($result->num_rows > 0) {
    //Query for updating the email with status 1.
    $sql = "UPDATE loginform SET active = 1 WHERE email = '".md5($_POST['email'])."'";
    $result = mysqli_query($connection, $sql);
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['location'] = 'forgot';
    header('Location: email.php?success=260ca9dd8a4577fc00b7bd5810298076');

  } else {
    $validation = '*Invalid Email address!';
  }

}

//Condition for redirection to smtp
if (isset($_GET['success']) && $_GET['success'] == '260ca9dd8a4577fc00b7bd5810298076') {
  echo '<meta http-equiv="refresh" content="1; url= ./inc/smtp.php" />';
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
    <div class="email-page login-bg">
  		<div class="container">
  			<div class="row bg-form py-5 py-lg-0">
  				<div class="col-lg-6 px-4">
              <a href="./" class="hvr-icon-wobble-horizontal text-gray"><i class="fas fa-chevron-left hvr-icon"></i><span class="pl-1">Back</span></a>
            <form class="login-form" action="" method="post">
              <h2 class="text-gray mb-0">Reset Password</h2>
              <p class="text-gray mb-4">Enter the email associated with your account and we'll send an email with instructions to reset your password.</p>
              <?php //Success Message
              if (isset($_GET['success']) && $_GET['success'] == '260ca9dd8a4577fc00b7bd5810298076') { ?>
                <div class="alert alert-success">
                  <strong>Email has been sent!</strong>
                </div>
              <?php } ?>
              <?php if (!empty($validation)): ?>
                <p class="font-12 text-danger mb-1"><?=$validation;?></p>
              <?php endif; ?>
              <div class="input-container mb-2">
                <input class="email-field input-text pl-5 px-3" type="email" name="email" placeholder="Email Address" required>
                <span class="icon-symbol">
                  <i class="fad fa-envelope-open-text"></i>
                </span>
              </div>
              <button class="btnn mt-3 mb-3" name="submit">
                <span class="btn-text"><a class="">Send Instructions</a></span>
              </button>
            </form>
  				</div>
					<div class="col-lg-6 bg-full d-none d-lg-block">
					</div>
  			</div>
  		</div>
		</div>
		<script src="assets/bs4/js/jquery.slim.min.js"></script>
		<script src="assets/bs4/js/bootstrap.min.bundle.min.js"></script>
	</body>
</html>
