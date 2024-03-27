<?php
session_start();
//Redirection
if (!isset($_SESSION['location']) && !isset($_GET['activate'])) {
  header('Location: ./../index.php');
}

//URL Tag
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
   $url = "https://".$_SERVER['HTTP_HOST'];
} else {
   $url = "http://".$_SERVER['HTTP_HOST'];
}

//Condition for displaying the output of email
if ($_SESSION['location'] == 'forgot') {
  $subject = 'Setup new Password';
  $body = 'It seems like you forgot your password for TMJP Site. If this is true, please click on the button (this will allow you to change your password).
  <br/>If you did not forget your password, please disregard this email..';
  $link = 'href="'.$url.'/wmd-login/password.php?activate='.md5($_SESSION['email']).'" target="_blank">SET NEW PASSWORD';
  $button = 'Please click the "set new password" button to set the new password for you account.';
  $image = 'https://cdn-icons-png.flaticon.com/512/159/159069.png';
} else {
  $subject = 'Account Activation';
  $body = 'Thank you for registering to TMJP, To activate your account, please click on the button (this will confirm your account).
  <br/>If you did not register to TMJP Site, please disregard this email..';
  $link = 'href="'.$url.'/wmd-login/activation.php?activate='.md5($_SESSION['email']).'" target="_blank">ACTIVATE';
  $button = 'Please click the "Activate" button to finish your account activation.';
  $image = 'https://www.pngmart.com/files/21/Account-Avatar-Profile-PNG-Photo.png';
}

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './../PHPMailer/Exception.php';
require './../PHPMailer/PHPMailer.php';
require './../PHPMailer/SMTP.php';

$mail = new PHPMailer;

$mail->isSMTP();                      // Set mailer to use SMTP
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers
$mail->SMTPAuth = true;               // Enable SMTP authentication
$mail->Username = 'emailnotificationtesting123@gmail.com';   // SMTP username (Google Email address)
$mail->Password = 'ntqpmtngquqkdiac';   // SMTP password (Google Password)
$mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                    // TCP port to connect to
$mail->SMTPDebug = 0  ;               // Debug mode

// Sender info
$mail->setFrom($_SESSION['email'], 'TMJP');
// Add a recipient
$mail->addAddress($_SESSION['email']);

//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

// Set email format to HTML
$mail->isHTML(true);

// Mail subject
$mail->Subject = $subject;

$firstname = (isset($_SESSION['firstname'])) ? ' '.$_SESSION['firstname'] : '';
$lastname = (isset($_SESSION['lastname'])) ? ' '.$_SESSION['lastname'] : '';

// Mail body content

$test = '    <div id="alert-container" class="alert-bottom-right" aria-live="polite" role="alert">
        <div class="alert alert-warning" style="">
            <div class="alert-progress" style="width: 73.32%;"></div><button type="button" class="alert-close-button" role="button">×</button>
            <div class="alert-message">
                <h5>Due in 23 Hours 59 Minutes.</h5>
                <ul>
                    <li class="post-type">
                        <p class="list-title article-list">Article List</p>
                        <ul>
                            <li class="post-title">— お役立ち情報まとめ</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li class="post-type">
                        <p class="list-title slider-list">Slider Banner</p>
                        <ul><img class="slider-alert" src="http://localhost/tmj-times-tsr/wp-content/uploads/2020/03/200228_MyStory_岡崎さん.png"> </ul>
                    </li>
                </ul>
                <ul>
                    <li class="post-type">
                        <p class="list-title attachment-list">Side Banner</p>
                        <ul><img class="side-alert" src="http://localhost/tmj-times-tsr/wp-content/uploads/2022/09/pexels-pixabay-38519.jpg"></ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>';
$mail->Body =
'
<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title></title>
  <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <![endif]-->
  <style>
    .post-title { color:red; }
  </style>
</head>
  <body>
  '.$test.'
  </body>
</html>
';
?>

<?php
// Send email
if(!$mail->send()) {
  echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
} else {
  header('Location: ./../index.php');
}
?>
