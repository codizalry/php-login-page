<?php
//connection to DB
require_once './inc/config.php';
require_once './inc/validation.php';

//Session Start
session_start();

//Remove $_POST empty session
error_reporting(0);

//Null variable
$validation = $picture = $img_name = $firstname = $lastname = $email = $new_img_name = '';

if (isset($_POST['submit'])) {
  //Validations
  if (empty($_POST['email'])) {
    $email_error = "*Email Address is Required!";
    $boolean = false;
  } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $email_error = '*Invalid Email Address!';
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

  if (empty($_POST['cpassword'])) {
    $cpassword_error = "*Confirm Password is Required!";
    $boolean = false;
  } elseif ($_POST['password'] != $_POST['cpassword']) {
    $cpassword_error = "*Password do not match!";
    $boolean = false;
  } else {
    $boolean = true;
  }

  if (empty($_POST['firstname']) || empty($_POST['lastname'])) {
    $fullname_error = "*First and Last Name is Required!";
    $boolean = false;
  } else {
    $boolean = true;
  }

  if (empty($_POST['gender'])) {
    $gender_error = "*Gender is Required!";
    $boolean = false;
  } else {
    $boolean = true;
  }


  //Set variables
  $email = $_POST['email'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $password = md5($_POST['password']);
  $cpassword = md5($_POST['cpassword']);
  $gender = $_POST['gender'];

  //condition for the empty profile
  if (!empty($_FILES["upload"]["name"])) {
    $img_name = $_FILES["upload"]["name"];
    $img_size = $_FILES["upload"]["size"];
    $tmp_name = $_FILES["upload"]["tmp_name"];

    //Check the image
    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
    $img_ex_lc = strtolower($img_ex);
    $allowed_exs = array("jpg","png","jpeg");
    if (in_array($img_ex_lc, $allowed_exs)) {
      if ($img_size >! 2000000) {
        $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
        $img_upload_path = 'uploads/'.$new_img_name;
      } else {
        $image_error = '*Image size is too large!';
      }
    }
  } else {
    $new_img_name = (isset($_POST['gender']) && $_POST['gender'] == "male") ? 'male.webp' : 'female.jpg';
  }


  //Check for empty fields ( backend validation )
  if (!empty($firstname && $lastname && $email && $_POST['password'] && $_POST['cpassword'] && $_POST['gender']) && $password == $cpassword) {
    $email = md5($email);
    $sql = "SELECT * FROM loginform WHERE email = '".$email."'";
    $result = mysqli_query($connection, $sql);
    //condition for wrong file upload  ( image only )
    if (!empty($new_img_name)) {
      //Condition for email if it's already exist
      if (!$result->num_rows > 0) {
        $sql = "INSERT INTO loginform (fname, lname, gender, email, Pass, picture) VALUES ('$firstname', '$lastname', '$gender', '$email', '$password', '$new_img_name')";
        $result = mysqli_query($connection, $sql);
        //Condition for sql injection
        if ($result) {
          move_uploaded_file($tmp_name, $img_upload_path);
          $username = $_POST['picture'] = $_POST['password'] = $_POST['cpassword'] = "";
          $_SESSION['email'] = $_POST['email'];
          $_SESSION['location'] = 'register';
          $_SESSION['firstname'] = $_POST['firstname'];
          $_SESSION['lastname'] = $_POST['lastname'];
          header('Location: signup.php?success=260ca9dd8a4577fc00b7bd5810298076');
        } else {
          $validation = '*Woops! Something Went Wrong. Field data is invalid!';
        }

      } else {
        $email_error = '*Email Address Already Exists!';
      }

    } else {
      $image_error = '*Wrong file uploaded!';
    }

  }

  $email = $_POST['email'];
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
    <div class="signup-page login-bg">
      <div class="container">
        <div class="row bg-form">
          <div class="d-block d-md-none p-3">
            <a href="./" class="hvr-icon-wobble-horizontal text-gray"><i class="fas fa-chevron-left hvr-icon"></i><span class="pl-1">Back</span></a>
            <h2 class="text-gray mb-0">Sign Up</h2>
            <p class="text-gray mb-4">We would love to be in touch with you!<br/> Please sign up to have an access to our site. Thank you!</p>
          </div>
          <div class="col-md-6 py-3 py-lg-5 order-1 order-md-0">
            <div class="d-none d-md-block">
              <a href="./" class="hvr-icon-wobble-horizontal text-gray"><i class="fas fa-chevron-left hvr-icon"></i><span class="pl-1">Back</span></a>
              <h2 class="text-gray mb-0">Sign Up</h2>
              <p class="text-gray mb-4">We would love to be in touch with you!<br/> Please sign up to have an access to our site. Thank you!</p>
            </div>
            <?php //Success Message
            if (isset($_GET['success']) && $_GET['success'] == '260ca9dd8a4577fc00b7bd5810298076') { ?>
              <div class="alert alert-success">
                <strong>Successfully Registered!</strong>
              </div>
            <?php } ?>
            <form class="login-form" action="" method="post" enctype="multipart/form-data">
              <?php if (!empty($fullname_error)): ?>
                <p class="font-12 text-danger mb-1"><?=$fullname_error;?></p>
              <?php endif; ?>
              <div class="name-section mb-2">
                <div class="input-container w-100 w-sm-50  mb-2 mb-sm-0 mr-0 mr-sm-1">
                    <input class="input-text pl-5 px-3" type="text" name="firstname" placeholder="First Name" value ="<?=$_POST['firstname']; ?>" required>
                    <span class="icon-symbol"><i class="fas fa-user"></i></span>
                  </div>
                <div class="input-container w-100 w-sm-50 ml-0 ml-sm-1">
                  <input class="input-text pl-5 px-3" type="text" name="lastname" placeholder="Last Name" value ="<?=$_POST['lastname']; ?>" required>
                  <span class="icon-symbol"><i class="fas fa-user"></i></span>
                </div>
                </div>
                <!-- Validation ( Error message) -->
                <?php if (!empty($gender_error)): ?>
                  <p class="font-12 text-danger mb-1"><?=$gender_error;?></p>
                <?php endif; ?>
                <!-- End here -->
                <!-- Gender input field -->
                <div class="input-container mb-2">
                  <p class="gender-label text-gray mb-0 mr-2">Select Gender</p>
                  <span class="icon-symbol"><i class="fas fa-venus-mars"></i></span>
                  <div class="gender-field-sec text-gray mr-2">
                    <input class="gender-field text-gray" type="radio" name="gender" value="male" <?=(isset($_POST['gender']) && $_POST['gender'] == "male") ? 'checked' : '';?> required>
                    <span>Male</span>
                  </div>
                  <div class="gender-field-sec text-gray">
                    <input class="gender-field text-gray" type="radio" name="gender" value="female" <?=(isset($_POST['gender']) && $_POST['gender'] == "female") ? 'checked' : '';?> required>
                    <span>Female</span>
                  </div>
                </div>
                <!-- End here -->
              <?php if (!empty($email_error)): ?>
                <p class="font-12 text-danger mb-1"><?=$email_error;?></p>
              <?php endif; ?>
              <div class="input-container mb-2">
                <input class="email-field input-text pl-5 px-3" type="email" name="email" placeholder="Email Address" value ="<?=$_POST['email']; ?>" required>
                <span class="icon-symbol">
                  <i class="fad fa-envelope-open-text"></i>
                </span>
              </div>
              <?php if (!empty($password_error)): ?>
                <p class="font-12 text-danger mb-1"><?=$password_error;?></p>
              <?php endif; ?>
              <div class="input-container mb-2">
                <input id="password-field" class="input-text pl-5 px-3" type="password" name="password" placeholder="Set Password" required>
                <span class="icon-symbol">
                  <i class="fas fa-lock"></i>
                </span>
                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
              </div>
              <?php if (!empty($cpassword_error)): ?>
                <p class="font-12 text-danger mb-1"><?=$cpassword_error;?></p>
              <?php endif; ?>
              <div class="input-container mb-2">
                <input class="input-text pl-5 px-3" type="password" name="cpassword" placeholder="Confirm Password" required>
                <span class="icon-symbol">
                  <i class="fas fa-lock"></i>
                </span>
              </div>
              <?php if (!empty($validation)): ?>
                <p class="font-12 text-danger mb-1"><?=$validation;?></p>
              <?php endif; ?>
              <button class="btnn mt-3 mb-3" name="submit">
                  <span class="btn-text"><a class="">Sign Up</a></span>
              </button>
            </div>
            <div class="col-md-6 bg-full">
              <div class="img-section" >
                <div class="display-image"></div>
              </div>
              <div class="file-upload btn btn-primary mt-4">
                <span>Upload Image</span>
                <input class="upload btn btn-secondary mt-3" type="file" accept="image/jpeg, image/png, image/jpg" name="upload">
              </div>
              <?php if (!empty($image_error)): ?>
                <p class="text-danger"><?=$image_error;?></p>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
		<script src="assets/bs4/js/jquery.slim.min.js"></script>
		<script src="assets/bs4/js/bootstrap.min.bundle.min.js"></script>
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

    //Displaying the image ( Real time display to img)
    var image_input = document.querySelector(".upload");
    image_input.addEventListener("change", function() {
      var reader = new FileReader();
      reader.addEventListener("load", () => {
        var uploaded_image = reader.result;
        document.querySelector(".display-image").style.backgroundImage = `url(${uploaded_image})`;
      });
      reader.readAsDataURL(this.files[0]);
    });

    if (jQuery('.upload').val().length == 0) {
      jQuery('.gender-field').on('change', function() {
        switch(jQuery(this).val()) {
          case "male":
            jQuery('.upload').val('');
            jQuery(".display-image").css("background-image","url('uploads/male.webp')");
          break;
          case "female":
            jQuery('.upload').val('');
            jQuery(".display-image").css("background-image","url('uploads/female.jpg')");
          break;
        };
      });
    };
		</script>
	</body>
</html>
