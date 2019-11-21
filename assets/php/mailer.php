<?php
if($_POST)
{
	$to_email   	= "sdesigncba@gmail.com"; //Recipient email, Replace with own email here
	
	//check if its an ajax request, exit if not
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		
		$output = json_encode(array( //create JSON data
			'type'=>'error', 
			'text' => 'Sorry Request must be Ajax POST'
		));
		die($output); //exit script outputting json data
	} 
	
	//Sanitize input data using PHP filter_var().
	$user_name		= filter_var($_POST["name"], FILTER_SANITIZE_STRING);
	$user_last		= filter_var($_POST["last"], FILTER_SANITIZE_STRING);
	$user_phone		= filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
	$user_email		= filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
	$message		= filter_var($_POST["message"], FILTER_SANITIZE_STRING);
	
	//additional php validation
	if(strlen($user_name) < 2){ // If length is less than 4 it will output JSON error.
		$output = json_encode(array('type'=>'error', 'text' => 'El nombre es muy corto o está vacío!'));
		die($output);
	}
	if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ //email validation
		$output = json_encode(array('type'=>'error', 'text' => 'Ingrese un mail válido!'));
		die($output);
	}
	// if(strlen($message)<3){ //check emtpy message
	// 	$output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));
	// 	die($output);
	// }
	
	//email body
	$message_body = $message."\r\n\r\n-"."\r\nNombre: ".$user_name."\r\nApellido: ".$user_last."\r\nEmail: ".$user_email."\r\nTeléfono: ". $user_phone ;
	
	//proceed with PHP email.
	$headers = 'From: '. $user_email .'' . "\r\n" .
	'Reply-To: '.$user_email.'' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	
	$send_mail = mail($to_email, $subject, $message_body, $headers);
	
	if(!$send_mail)
	{
		//If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
		$output = json_encode(array('type'=>'error', 'text' => 'No se pudo enviar el mensaje. Revise los campos e intente nuevamente.'));
		die($output);
	}else{
		$output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_name .' Gracias por su mensaje'));
		die($output);
	}
}
?>