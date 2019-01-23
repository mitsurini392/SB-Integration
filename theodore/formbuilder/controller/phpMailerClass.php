<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';


$email = new PHPMailer;

//$email->ClearAddresses();

// Tell PHPMailer to use SMTP
$email->isSMTP();

// Replace smtp_username with your Amazon SES SMTP user name.
$email->Username = 'AKIAIUWJVSU5B3RZQ2CA';

// Replace smtp_password with your Amazon SES SMTP password.
$email->Password = 'AjxMu+mAt8Ydsc9ru3D2fObuqmYdmZOIzjWvGiwJBlOm';
     
// endpoint in the appropriate region.
$email->Host = 'email-smtp.us-east-1.amazonaws.com';

// Tells PHPMailer to use SMTP authentication
$email->SMTPAuth = true;

// Enable TLS encryption over port 587
$email->SMTPSecure = 'tls';
$email->Port = 587;

// Add BCC's from Internal
$email->AddBCC('it@smallbuilders.com.au', 'ITLOP');

$email -> IsHTML(true);

?>