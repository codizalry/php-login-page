<?php
require_once './inc/config.php';
session_start();

//null
$picture = $firstname = $lastname = "";
//Redirection
if (!isset($_SESSION['email'])) {
  header('location: index.php');
} else {
  $picture = $_SESSION['picture'];
  $firstname = $_SESSION['firstname'];
  $lastname = $_SESSION['lastname'];

  //Session destroy idle for user
  if (!isset($_COOKIE['admin'])) {
  header('location: index.php');
  }

  setcookie('admin', 'tmj', time()+60);
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
    <div class="content-page login-bg">
  		<div class="container">
  			<div class="row bg-form py-4">
  				<div class="col-lg-6 px-4">
              <div class="img-section text-center">
  							<img class="img-fluid" src="uploads/<?=$picture;?>">
  						</div>
              <h3 class="text-gray text-center mt-3 mb-0">WELCOME <span class="font-weight-bold"><?=$firstname." ".$lastname;?><span> !</span></span></h3>
              <button class="btnn mt-3">
                  <span class="btn-text"><a class="" data-toggle="modal" data-target="#staticBackdrop">LOG OUT</a></span>
              </button>
  				</div>
					<div class="col-lg-6 bg-full d-none d-lg-block">
					</div>
  			</div>
  		</div>
		</div>
    <!-- MODAL -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content py-4 px-5 ">
          <div class="modal-body text-center mb-2">
            Are you sure you want to Proceed?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary w-100" data-dismiss="modal">
            <p class="mb-0">Close</p>
            </button>
            <button class="btnn">
                <span class="btn-text"><a href="./logout.php" class="">Proceed</a></span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <script src="assets/bs4/js/jquery.slim.min.js"></script>
    <script src="assets/bs4/js/bootstrap.min.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	</body>
</html>
