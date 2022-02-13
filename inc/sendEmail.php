<?php
debbuger;
// Replace this with your own email address
$siteOwnersEmail = 'henrique@gracaevidaportugal.com';


if($_POST) {

   $fname = trim(stripslashes($_POST['contactFname']));
   $lname = trim(stripslashes($_POST['contactLname']));
   $email = trim(stripslashes($_POST['contactEmail']));
   $phone = trim(stripslashes($_POST['contactPhone']));
   $contact_message = trim(stripslashes($_POST['contactMessage']));

   // Check First Name
	if (strlen($fname) < 2) {
		$error['fname'] = "Please enter your first name.";
	}
	// Check Last Name
	if (strlen($lname) < 2) {
		$error['lname'] = "Please enter your last name.";
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
		$error['email'] = "Please enter a valid email address.";
	}
	// Check Message
	if (strlen($contact_message) < 1) {
		$error['message'] = "Please enter your message. It should have at least 15 characters.";
	}
   // Set Subject
	$subject = "Novo formulário preenchido"; 

	// Set Name
	$name = $fname . " " . $lname;

   // Set Message
   $message .= "Nome: " . $name . "<br />";
	$message .= "E-mail: " . $email . "<br />";
	$message .= "Telefone: " . $phone . "<br />";
   $message .= "Mensagem: ";
   $message .= $contact_message;

   // Set From: header
   $from =  $name . " <" . $email . ">";

   // Email Headers
	$headers = "From: " . $from . "\r\n";
	$headers .= "Reply-To: ". $email . "\r\n";
 	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";


   if (!$error) {

      ini_set("sendmail_from", $siteOwnersEmail); // for windows server
      $mail = mail($siteOwnersEmail, $subject, $message, $headers);

		if ($mail) { echo "OK"; }
      else { echo "Something went wrong. Please try again."; }
		
	} # end if - no validation error

	else {

		$response = (isset($error['fname'])) ? $error['fname'] . "<br /> \n" : null;
		$response .= (isset($error['lname'])) ? $error['lname'] . "<br /> \n" : null;
		$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
		$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
		
		echo $response;

	} # end if - there was a validation error

}

?>