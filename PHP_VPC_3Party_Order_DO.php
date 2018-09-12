<?php

include('VPCPaymentConnection.php');
$conn = new VPCPaymentConnection();


// This is secret for encoding the SHA256 hash
// This secret will vary from merchant to merchant

$secureSecret = "171EF6E7E40A6F46CA2D0581CF6B2CB8";

// Set the Secure Hash Secret used by the VPC connection object
$conn->setSecureSecret($secureSecret);


// *******************************************
// START OF MAIN PROGRAM
// *******************************************
// Sort the POST data - it's important to get the ordering right
ksort ($_POST);

// add the start of the vpcURL querystring parameters
$vpcURL = $_POST["virtualPaymentClientURL"];

// This is the title for display
$title  = $_POST["Title"];


// Remove the Virtual Payment Client URL from the parameter hash as we 
// do not want to send these fields to the Virtual Payment Client.
unset($_POST["virtualPaymentClientURL"]); 
unset($_POST["SubButL"]);
unset($_POST["Title"]);

// Add VPC post data to the Digital Order
foreach($_POST as $key => $value) {
	if (strlen($value) > 0) {
		$conn->addDigitalOrderField($key, $value);
	}
}

// Add original order HTML so that another transaction can be attempted.
$conn->addDigitalOrderField("AgainLink", $againLink);

// Obtain a one-way hash of the Digital Order data and add this to the Digital Order
$secureHash = $conn->hashAllFields();
$conn->addDigitalOrderField("Title", $title);
$conn->addDigitalOrderField("vpc_SecureHash", $secureHash);
$conn->addDigitalOrderField("vpc_SecureHashType", "SHA256");

// Obtain the redirection URL and redirect the web browser
$vpcURL = $conn->getDigitalOrder($vpcURL);

header("Location: ".$vpcURL);
//echo "<a href=$vpcURL>$vpcURL</a>";

?>