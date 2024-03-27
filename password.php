<?php
session_start();
//connection to DB
require_once './inc/config.php';
require_once './inc/validation.php';

//Redirection
if (!isset($_GET['activate'])) {
  header('Location: index.php');
}

//null validation
$validation = '';

if (isset($_POST['submit'])) {
  $email = $_GET['activate'];
  $password = $_POST['password'];
  $cpassword = $_POST['cpassword'];

  //Condition for not match password
  if (pwdMatch($password, $cpassword) !== false) {
    $validation = '*Password is not match!';

  } else {

    $sql = "SELECT * FROM loginform where email = '".$email."' AND active = '1'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_array($result);

    //Condition for submitting same password
    if (is_array($row) && ($row['Pass'] == md5($password))) {

      $validation = '*Please set a new password!';

    } else {

      //condition for empty input field (validation for backend)
      if (!empty($password)) {

        //Condition for result
        if ($result->num_rows > 0) {
          $sql = "UPDATE loginform SET Pass = '".md5($password)."', active = '0' WHERE email = '".$email."'";
          $result = mysqli_query($connection, $sql);
          header('Location: password.php?activate=260ca9dd8a4577fc00b7bd5810298076');
        } else {
          $validation = '*Session is already expired!';
        }

      } else {
        $validation = '*Fields must be filled out!';
      }

    }

  }

}

//Condition for redirection to smtp
if (isset($_GET['activate']) && $_GET['activate'] == '260ca9dd8a4577fc00b7bd5810298076') {
  echo '<meta http-equiv="refresh" content="1; url= index.php" />';
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
    <div class="password-page login-bg">
  		<div class="container">
  			<div class="row bg-form py-5 py-lg-0">
  				<div class="col-md-6 px-4">
            <a href="./" class="hvr-icon-wobble-horizontal text-gray"><i class="fas fa-chevron-left hvr-icon"></i><span class="pl-1">Back</span></a>
            <form class="login-form" action="" method="post">
              <h2 class="text-gray mb-0">Create new Password</h2>
              <p class="text-gray mb-4">Your new password must be different from previous used password</p>
              <?php //Success Message
              if (isset($_GET['activate']) && $_GET['activate'] == '260ca9dd8a4577fc00b7bd5810298076') { ?>
                <div class="alert alert-success">
                  <strong>Password Changed!</strong>
                </div>
              <?php } ?>
              <?php if (!empty($validation)): ?>
                <p class="font-12 text-danger mb-1"><?=$validation;?></p>
              <?php endif; ?>
              <div class="input-container mb-2">
								<input id="new-pw" class="input-text pl-5 px-3" type="password" name="password" placeholder="New Password" required>
                <span class="icon-symbol"><i class="fas fa-lock"></i></span>
	              <span toggle="#new-pw" class="fa fa-fw fa-eye field-icon new-pw"></span>
              </div>
              <div class="input-container mb-1">
                <input id="confirm-pw" class="input-text pl-5 px-3" type="password" name="cpassword" placeholder="Confirm Password" required>
                <span class="icon-symbol"><i class="fas fa-lock"></i></span>
              </div>
              <button class="btnn mt-3 mb-3" name="submit">
                  <span class="btn-text"><a class="">Reset Password</a></span>
              </button>
            </form>
  				</div>
					<div class="col-md-6 bg-full d-none d-md-block">
					</div>
  			</div>
  		</div>
		</div>
		<script src="assets/bs4/js/jquery.slim.min.js"></script>
		<script src="assets/bs4/js/bootstrap.min.bundle.min.js"></script>
		<script>
      //Display Password
			$(".new-pw").click(function() {
			  $(this).toggleClass("fa-eye fa-eye-slash");
			  var input = $($(this).attr("toggle"));
			  if (input.attr("type") == "password") {
			    input.attr("type", "text");
			  } else {
			    input.attr("type", "password");
			  }
			});
		</script>
	</body>
</html>
