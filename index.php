<?php
//connection to DB
require_once './inc/config.php';
require_once './inc/validation.php';

//Deletion of image on uploads
$files = array_diff(scandir('./uploads'), array('.', '..'));

foreach($files as $file){
  $sql = "SELECT * FROM loginform WHERE picture ='$file'";
  $result = mysqli_query($connection, $sql);

  if (!$result->num_rows > 0 && $file != 'male.webp' && $file != 'female.jpg' && $file != 'default.png') {
    unlink($_SERVER['DOCUMENT_ROOT'] . "/wmd-login/uploads/".$file);
  }
}

//Session Start
session_start();

// set cookies
setcookie('admin', 'tmj', time()+60);
//Null variable
$username = $email_error = $password_error = '';

//Remove $_POST empty session
error_reporting(0);

if (isset($_POST['submit'])) {
  $email = input_validation(md5($_POST['email']));
  $password = input_validation(md5($_POST['password']));

  //Validations
  if (empty($_POST['email'])) {
    $email_error = "*Email address is Required!";
    $boolean = false;
  } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $email_error = 'Invalid Email';
    $boolean = false;
  } else {
    $boolean = true;
  }

  if (empty($_POST['password'])) {
    $password_error = "*Password is Required!";
    $boolean = false;
  } else {
    $boolean = true;
  }

  //Condition for empty Captcha
  if ($_POST['g-recaptcha-response'] != "") {
    $secret = '6LeJ1fofAAAAAOKH-hVnZxTuiPxsHwAKc3XDi1uJ';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
      $responseData = json_decode($verifyResponse);

      //Condition for success response of captcha
      if ($responseData->success) {

        $sql = "SELECT * FROM loginform where email = '".$email."' AND Pass = '".$password."'";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_array($result);

        //Condition for empty result
        if (is_array($row)) {

          $sql = "SELECT * FROM loginform WHERE email ='$email' AND status = '1'";
          $result = mysqli_query($connection, $sql);

          //Condition for activated account
          if ($result->num_rows > 0) {

            //Condition for success login
            if ($row['email'] === $email && $row['Pass'] === $password) {
              $_SESSION['email'] = $row['email'];
              $_SESSION['picture'] = $row['picture'];
              $_SESSION['firstname'] = $row['fname'];
              $_SESSION['lastname'] = $row['lname'];
              header('location: content.php');
            }

          } else {
            $validation = '*Account must be activated!';
          }

        } else {
          $validation = '*Invalid Email Address/Password!';
        }

      }

    } else {
      $validation = '*Captcha is Required!';
    }

    //Condition for empty fields
    if (empty_login($_POST['email'], $_POST['password']) !== false) {
      $empty_fields = '*Email address and Password is Empty!';
    }

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
    <div class="login-bg">
  		<div class="container">
  			<div class="row bg-form">
          <div class="col-12">
            <h2 class="d-block d-md-none font-KaushanScript text-center text-gray my-4">TMJP Login</h2>
          </div>
  				<div class="col-md-6 px-4 py-3 py-md-5 order-1 order-md-0">
            <form class="login-form" action="" method="POST">
              <h2 class="d-none d-md-block font-KaushanScript text-center text-gray mb-4">TMJP Login</h2>
              <?php if (!empty($empty_fields)): ?>
                <p class="font-12 text-danger mb-1"><?=$empty_fields;?></p>
              <?php endif; ?>
              <?php if (!empty($email_error) && empty($empty_fields)): ?>
                <p class="font-12 text-danger mb-1">*Email address is Required!</p>
              <?php endif; ?>
              <div class="input-container mb-2">
                <input class="email-field input-text pl-5 px-3" type="email" name="email" placeholder="Email" value="<?=$_POST['email'];?>" required>
                <span class="icon-symbol">
                  <i class="fad fa-envelope-open-text"></i>
                </span>
              </div>
              <?php if (!empty($password_error) && empty($empty_fields)): ?>
                <p class="font-12 text-danger mb-1"><?=$password_error;?></p>
              <?php endif; ?>
              <div class="input-container mb-1">
                <input id="password-field" class="input-text pl-5 px-3" type="password" name="password" placeholder="Password" required>
                <span class="icon-symbol">
                  <i class="fas fa-lock"></i>
                </span>
	              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
              </div>
							<div class="forget-section text-right mb-4">
								<span class="">Forgot</span>
								<a class="" href="email.php">Password?</a>
							</div>
              <button class="btnn mb-3" name="submit">
                  <span class="btn-text"><a class="">LOGIN</a></span>
              </button>
              <?php if (!empty($validation)): ?>
                <p class="font-12 text-center text-danger mb-1"><?=$validation;?></p>
              <?php endif; ?>
              <div class="d-flex justify-content-around">
                <div class="g-recaptcha" data-sitekey="6LeJ1fofAAAAAAUUtNsR8HXncgIg8F8ZT38NPySH"></div>
              </div>
            </form>
						<div class="sign-up-section text-center my-4">
							<span class="text-center">or Sign in using</span>
							<ul class="list-unstyled mt-1 mb-0">
								<li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
								<li><a href="#"><i class="fab fa-twitter"></i></a></li>
								<li><a href="#"><i class="fas fa-envelope"></i></a></li>
							</ul>
						</div>
						<div class="forget-section text-center">
							<span class="">Not a member?</span>
							<a class="text-blue font-weight-bold" href="signup.php">Sign up now!</a>
						</div>
  				</div>
					<div class="profile-image col-md-6 bg-full">
						<div class="img-section">
							<img class="img-fluid" src="./assets/img/default-img.png">
						</div>
            <h3 class="text-gray text-center mt-3 mb-0">Welcome <span class="font-weight-bold">User<span> !</span></span></h3>
					</div>
  			</div>
  		</div>
		</div>
		<script src="assets/bs4/js/jquery.slim.min.js"></script>
		<script src="assets/bs4/js/bootstrap.min.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<script>
      //Display Password
			$(".toggle-password").click(function() {
			  $(this).toggleClass("fa-eye fa-eye-slash");
			  var input = $($(this).attr("toggle"));
			  if (input.attr("type") == "password") {
			    input.attr("type", "text");
			  } else {
			    input.attr("type", "password");
			  }
			});

      //Ajax for profile image
      $(document).ready(function () {
        $('body').on('keyup', '.email-field',function () {
          var email = $(this).val();
          $.ajax({
            url: 'inc/ajax-image.php', //This is the current doc
            type: "POST",
            data: ({email : email}),
            success: function(data){
              $(".profile-image").html(data);
            }
          });
        });
      });
		</script>
	</body>
</html>
