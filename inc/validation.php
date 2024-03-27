<?php

// Empty inputs-register
function empty_reg_input($firstname, $lastname, $email , $password, $cpassword) {
  $result;
  if ( empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($cpassword) ) {
    // $result.= (empty($username)) ? '<h5 class=" text-danger"><small>Empty Username</small> </h5>' : '';
    // $result.= (empty($email)) ? '<h5 class=" text-danger"><small>Empty Email</small> </h5>' : '';
    // $result.= (empty($password)) ? '<h5 class=" text-danger"><small>Empty Password</small> </h5>' : '';
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}


// Empty inputs-login
function empty_login($email, $password) {
  $result;
  if ( empty($email) && empty($password) ) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

// Invalid Email
function invalidEmail($email) {
  $result;
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

// confirm password
function pwdMatch($password, $cpassword) {
  $result;
  if ($password !== $cpassword) {
    $result = true;
  } else {
    $result = false;
  }
  return $result;
}

//For security purpose
function input_validation($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);

  return $data;
}
?>
