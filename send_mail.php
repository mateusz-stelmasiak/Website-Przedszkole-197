<?php
/*
This bit sets the URLs of the supporting pages.
If you change the names of any of the pages, you will need to change the values here.
*/
$contactPage = "index.html?section=kontakt";
$successPage = "index.html?section=kontakt&success=true";
$error_page = "index.html?section=kontakt&success=false";

/*
This next bit loads the form field data into variables.
If you add a form field, you will need to add it here.
*/
$email_address = 'biuro@przedszkole197.pl';
$fname =$_REQUEST['firstname'] ;
$lname =$_REQUEST['lastname'] ;
$subject =$_REQUEST['subject'] ;
$mesg =$_REQUEST['content'] ;
/*
The following function checks for email injection.
Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
*/
function isInjected($str) {
	$injections = array(
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

// If the user tries to access this script directly, redirect them to the feedback form,
if (!isset($_REQUEST['content'])) {
    header( "Location: $contactPage" );
}

// If the form fields are empty, redirect to the error page.
elseif (empty($mesg) ||empty($fname) ||empty($lname) ||empty($subject) ) {
    header( "Location: $error_page" );
}

/* 
If email injection is detected, redirect to the error page.
If you add a form field, you should add it here.
*/
elseif ( isInjected($mesg)||isInjected($fname)||isInjected($lname)||isInjected($subject)) {
    header( "Location: $error_page" );
}

// If we passed all previous tests, send the email then redirect to the thank you page.
else 
{
  mail( "$email_address","$subject","\nWiadomość od: $fname $lname\n\n $mesg");
  header( "Location: $successPage" );
}


?>