<?php
//include config
include "config.php";

//Fetching the data from input field
$email = md5($_POST['email']);

//Query for searching the username with picture.
$query = "SELECT * from loginform WHERE email = '" . $email . "'";
$result = mysqli_query($connection,$query);
$content = mysqli_fetch_array($result);

//condition for what to display on the image
if($content) {
  if (!empty($content['picture'])) {
    $profile_image = "uploads/".$content['picture']."";
    $profile_name = $content['fname'];
  } else {
    $profile_image = "./assets/img/default-img.png";
    $profile_name = 'User';
  }
} else {
  $profile_image = "./assets/img/default-img.png";
  $profile_name = 'User';
}

//Displaying to the index (real-time)
echo '
<div class="img-section">
  <img class="img-fluid" src="'.$profile_image.'">
</div>
<h3 class="text-gray mt-3 mb-0">Welcome <span class="font-weight-bold">'.$profile_name.'!</span></h3>
';

?>
