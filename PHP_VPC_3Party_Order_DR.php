<?php


// Initialisation
// ==============

// 
include('VPCPaymentConnection.php');
$conn = new VPCPaymentConnection();


// This is secret for encoding the SHA256 hash
// This secret will vary from merchant to merchant

$secureSecret = "";

// Set the Secure Hash Secret used by the VPC connection object
$conn->setSecureSecret($secureSecret);


// Set the error flag to false
$errorExists = false;



// *******************************************
// START OF MAIN PROGRAM
// *******************************************


// This is the title for display
$title  = $_GET["Title"];


// Add VPC post data to the Digital Order
foreach($_GET as $key => $value) {
	if (($key!="vpc_SecureHash") && ($key != "vpc_SecureHashType") && ((substr($key, 0,4)=="vpc_") || (substr($key,0,5) =="user_"))) {
		$conn->addDigitalOrderField($key, $value);
	}
}

// Obtain a one-way hash of the Digital Order data and
// check this against what was received.
$serverSecureHash	= array_key_exists("vpc_SecureHash", $_GET)	? $_GET["vpc_SecureHash"] : "";
$secureHash = $conn->hashAllFields();
if ($secureHash==$serverSecureHash) {
	$hashValidated = "<font color='#00AA00'><strong>CORRECT</strong></font>";
} else {
	$hashValidated = "<font color='#FF0066'><strong>INVALID HASH</strong></font>";
	$errorsExist = true;
}



    
/*  If there has been a merchant secret set then sort and loop through all the
    data in the Virtual Payment Client response. while we have the data, we can
    append all the fields that contain values (except the secure hash) so that
    we can create a hash and validate it against the secure hash in the Virtual
    Payment Client response.

    NOTE: If the vpc_TxnResponseCode in not a single character then
    there was a Virtual Payment Client error and we cannot accurately validate
    the incoming data from the secure hash. 

    // remove the vpc_TxnResponseCode code from the response fields as we do not 
    // want to include this field in the hash calculation
    
    if (secureSecret != null && secureSecret.length() > 0 && 
        (fields.get("vpc_TxnResponseCode") != null || fields.get("vpc_TxnResponseCode") != "No Value Returned")) {
        
        // create secure hash and append it to the hash map if it was created
        // remember if secureSecret = "" it wil not be created
        String secureHash = vpc3conn.hashAllFields(fields);
    
        // Validate the Secure Hash (remember  hashes are not case sensitive)
        if (vpc_Txn_Secure_Hash.equalsIgnoreCase(secureHash)) {
            // Secure Hash validation succeeded, add a data field to be 
            // displayed later.
            hashValidated = "<font color='#00AA00'><strong>CORRECT</strong></font>";
        } else {
            // Secure Hash validation failed, add a data field to be
            // displayed later.
            errorExists = true;
            hashValidated = "<font color='#FF0066'><strong>INVALID HASH</strong></font>";
        }
    } else {
        // Secure Hash was not validated, 
        hashValidated = "<font color='orange'><strong>Not Calculated - No 'SECURE_SECRET' present.</strong></font>";
    }
*/

    // Extract the available receipt fields from the VPC Response
    // If not present then let the value be equal to 'Unknown'
    // Standard Receipt Data


$Title 				= array_key_exists("Title", $_GET) 						? $_GET["Title"] 				: "";
$againLink 			= array_key_exists("AgainLink", $_GET) 					? $_GET["AgainLink"] 			: "";
$amount 			= array_key_exists("vpc_Amount", $_GET) 				? $_GET["vpc_Amount"] 			: "";
$locale 			= array_key_exists("vpc_Locale", $_GET) 				? $_GET["vpc_Locale"] 			: "";
$batchNo 			= array_key_exists("vpc_BatchNo", $_GET) 				? $_GET["vpc_BatchNo"] 			: "";
$command 			= array_key_exists("vpc_Command", $_GET) 				? $_GET["vpc_Command"] 			: "";
$message 			= array_key_exists("vpc_Message", $_GET) 				? $_GET["vpc_Message"]			: "";
$version  			= array_key_exists("vpc_Version", $_GET) 				? $_GET["vpc_Version"] 			: "";
$cardType   		= array_key_exists("vpc_Card", $_GET) 					? $_GET["vpc_Card"] 			: "";
$orderInfo 			= array_key_exists("vpc_OrderInfo", $_GET) 				? $_GET["vpc_OrderInfo"] 		: "";
$receiptNo 			= array_key_exists("vpc_ReceiptNo", $_GET) 				? $_GET["vpc_ReceiptNo"] 		: "";
$merchantID  		= array_key_exists("vpc_Merchant", $_GET) 				? $_GET["vpc_Merchant"] 		: "";
$merchTxnRef 		= array_key_exists("vpc_MerchTxnRef", $_GET) 			? $_GET["vpc_MerchTxnRef"]		: "";
$authorizeID 		= array_key_exists("vpc_AuthorizeId", $_GET) 			? $_GET["vpc_AuthorizeId"] 		: "";
$transactionNo  	= array_key_exists("vpc_TransactionNo", $_GET) 			? $_GET["vpc_TransactionNo"] 	: "";
$acqResponseCode 	= array_key_exists("vpc_AcqResponseCode", $_GET) 		? $_GET["vpc_AcqResponseCode"] 	: "";
$txnResponseCode 	= array_key_exists("vpc_TxnResponseCode", $_GET) 		? $_GET["vpc_TxnResponseCode"] 	: "";
$riskOverallResult	= array_key_exists("vpc_RiskOverallResult", $_GET) 		? $_GET["vpc_RiskOverallResult"]: "";

		// Obtain the 3DS response
$vpc_3DSECI				= array_key_exists("vpc_3DSECI", $_GET) 			? $_GET["vpc_3DSECI"] : "";
$vpc_3DSXID				= array_key_exists("vpc_3DSXID", $_GET) 			? $_GET["vpc_3DSXID"] : "";
$vpc_3DSenrolled 		= array_key_exists("vpc_3DSenrolled", $_GET) 		? $_GET["vpc_3DSenrolled"] : "";
$vpc_3DSstatus 			= array_key_exists("vpc_3DSstatus", $_GET) 			? $_GET["vpc_3DSstatus"] : "";
$vpc_VerToken 			= array_key_exists("vpc_VerToken", $_GET) 			? $_GET["vpc_VerToken"] : "";
$vpc_VerType 			= array_key_exists("vpc_VerType", $_GET) 			? $_GET["vpc_VerType"] : "";
$vpc_VerStatus			= array_key_exists("vpc_VerStatus", $_GET) 			? $_GET["vpc_VerStatus"] : "";
$vpc_VerSecurityLevel	= array_key_exists("vpc_VerSecurityLevel", $_GET) 	? $_GET["vpc_VerSecurityLevel"] : "";


    // CSC Receipt Data
$cscResultCode 	= array_key_exists("vpc_CSCResultCode", $_GET)  			? $_GET["vpc_CSCResultCode"] : "";
$ACQCSCRespCode = array_key_exists("vpc_AcqCSCRespCode", $_GET) 			? $_GET["vpc_AcqCSCRespCode"] : "";
    
// Get the descriptions behind the QSI, CSC and AVS Response Codes
    // Only get the descriptions if the string returned is not equal to "No Value Returned".
    
$txnResponseCodeDesc = "";
$cscResultCodeDesc = "";
$avsResultCodeDesc = "";
    
    if ($txnResponseCode != "No Value Returned") {
        $txnResponseCodeDesc = getResultDescription($txnResponseCode);
    }
    
    if ($cscResultCode != "No Value Returned") {
        $cscResultCodeDesc = getCSCResultDescription($cscResultCode);
    }
    
    
		$error = "";
    // Show this page as an error page if error condition
    if ($txnResponseCode=="7" || $txnResponseCode=="No Value Returned" || $errorExists) {
        $error = "Error ";
    }
        
    // FINISH TRANSACTION - Process the VPC Response Data
    // =====================================================
    // For the purposes of demonstration, we simply display the Result fields on a
    // web page.
?> <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
    <html>
    <head><title><?php echo($title) ?> - VPC Response <?php echo($error) ?>Page</title>
        <meta http-equiv='Content-Type' content='text/html, charset=iso-8859-1'>
        <style type='text/css'>
            <!--
            h1       { font-family:Arial,sans-serif; font-size:20pt; font-weight:600; margin-bottom:0.1em; color:#08185A;}
            h2       { font-family:Arial,sans-serif; font-size:14pt; font-weight:100; margin-top:0.1em; color:#08185A;}
            h2.co    { font-family:Arial,sans-serif; font-size:24pt; font-weight:100; margin-top:0.1em; margin-bottom:0.1em; color:#08185A}
            h3       { font-family:Arial,sans-serif; font-size:16pt; font-weight:100; margin-top:0.1em; margin-bottom:0.1em; color:#08185A}
            h3.co    { font-family:Arial,sans-serif; font-size:16pt; font-weight:100; margin-top:0.1em; margin-bottom:0.1em; color:#FFFFFF}
            body     { font-family:Verdana,Arial,sans-serif; font-size:10pt; background-color:#FFFFFF; color:#08185A}
            th       { font-family:Verdana,Arial,sans-serif; font-size:8pt; font-weight:bold; background-color:#CED7EF; padding-top:0.5em; padding-bottom:0.5em;  color:#08185A}
            tr       { height:25px; }
            .shade   { height:25px; background-color:#CED7EF }
            .title   { height:25px; background-color:#0074C4 }
            td       { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#08185A }
            td.red   { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#FF0066 }
            td.green { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#008800 }
            p        { font-family:Verdana,Arial,sans-serif; font-size:10pt; color:#FFFFFF }
            p.blue   { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#08185A }
            p.red    { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#FF0066 }
            p.green  { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#008800 }
            div.bl   { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#0074C4 }
            div.red  { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#FF0066 }
            li       { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#FF0066 }
            input    { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#08185A; background-color:#CED7EF; font-weight:bold }
            select   { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#08185A; background-color:#CED7EF; font-weight:bold; }
            textarea { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#08185A; background-color:#CED7EF; font-weight:normal; scrollbar-arrow-color:#08185A; scrollbar-base-color:#CED7EF }
            -->
        </style>
    </head>
    <body>
    
	<!-- Start Branding Table -->
	<table width="100%" border="2" cellpadding="2" class="title">
		<tr>
			<td class="shade" width="90%"><h2 class="co">&nbsp;MasterCard Virtual Payment Client Example</h2></td>
		</tr>
	</table>
	<!-- End Branding Table -->
    
    <center><h1><?php echo($title); ?> <?php echo($error); ?>Response Page</H1></center>
    
    <table width="85%" align='center' cellpadding='5' border='0'>
      
        <tr class='title'>
            <td colspan="2" height="25"><p><strong>&nbsp;Standard Transaction Fields</strong></p></td>
        </tr>
        <tr>
            <td align='right' width='50%'><strong><i>VPC API Version: </i></strong></td>
            <td width='50%'><?php echo($version); ?></td>
        </tr>
        <tr class='shade'>                  
            <td align='right'><strong><i>Command: </i></strong></td>
            <td><?php echo($command); ?></td>
        </tr>
        <tr>
            <td align='right'><strong><i>Merchant Transaction Reference: </i></strong></td>
            <td><?php echo($merchTxnRef); ?></td>
        </tr>
        <tr class='shade'>
            <td align='right'><strong><i>Merchant ID: </i></strong></td>
            <td><?php echo($merchantID); ?></td>
        </tr>
        <tr>                  
            <td align='right'><strong><i>Order Information: </i></strong></td>
            <td><?php echo($orderInfo); ?></td>
        </tr>
        <tr class='shade'>
            <td align='right'><strong><i>Transaction Amount: </i></strong></td>
            <td><?php echo($amount); ?></td>
        </tr>
        <tr>                  
            <td align='right'><strong><i>Locale: </i></strong></td>
            <td><?php echo($locale); ?></td>
        </tr>
      
        <tr>
            <td colspan='2' align='center'><font color='#0074C4'>Fields above are the primary request values.<br/></font><hr/>
            </td>
        </tr>

        <tr class='shade'>                  
            <td align='right'><strong><i>VPC Transaction Response Code: </i></strong></td>
            <td><?php echo($txnResponseCode); ?></td>
        </tr>
        <tr>
            <td align='right'><strong><i>Transaction Response Code Description: </i></strong></td>
            <td><?php echo($txnResponseCodeDesc); ?></td>
        </tr>
        <tr class='shade'>                  
            <td align='right'><strong><i>Message: </i></strong></td>
            <td><?php echo($message); ?></td>
        </tr>
<?php
// only display the following fields if not an error condition
if ($txnResponseCode!="7" && $txnResponseCode!="No Value Returned") { 
?>
        <tr>
            <td align='right'><strong><i>Receipt Number: </i></strong></td>
            <td><?php echo($receiptNo); ?></td>
        </tr>
        <tr class='shade'>                  
            <td align='right'><strong><i>Transaction Number: </i></strong></td>
            <td><?php echo($transactionNo); ?></td>
        </tr>
        <tr>
            <td align='right'><strong><i>Acquirer Response Code: </i></strong></td>
            <td><?php echo($acqResponseCode); ?></td>
        </tr>
        <tr class='shade'>                  
            <td align='right'><strong><i>Bank Authorization ID: </i></strong></td>
            <td><?php echo($authorizeID); ?></td>
        </tr>
        <tr>
            <td align='right'><strong><i>Batch Number: </i></strong></td>
            <td><?php echo($batchNo); ?></td>
        </tr>
        <tr class='shade'>                  
            <td align='right'><strong><i>Card Type: </i></strong></td>
            <td><?php echo($cardType); ?></td>
        </tr>
		<tr>                  
            <td align='right'><strong><i>Risk Overall Result: </i></strong></td>
            <td><?php echo($riskOverallResult); ?></td>
        </tr>
      
        <tr>
            <td colspan='2' align='center'><font color='#0074C4'>Fields above are for a standard transaction.<br/><hr/>
                Fields below are additional fields for extra functionality.</font><br/></td>
        </tr>

        <tr class='title'>
            <td colspan="2" height="25"><p><strong>&nbsp;Card Security Code Fields</strong></p></td>
        </tr>
        <tr class='shade'>
            <td align='right'><strong><i>CSC Acquirer Response Code: </i></strong></td>
            <td><?php echo($ACQCSCRespCode); ?></td>
        </tr>
        <tr>                    
            <td align='right'><strong><i>CSC QSI Result Code: </i></strong></td>
            <td><?php echo($cscResultCode); ?></td>
        </tr>
        <tr class='shade'>
            <td align='right'><strong><i>CSC Result Description: </i></strong></td>
            <td><?php echo($cscResultCodeDesc); ?></td>
        </tr>
        				<tr class="title">
            <td colspan="2" height="25"><P><strong>&nbsp;3-D Secure Fields</strong></P></td>
        </tr>
        <tr>
            <td align="right"><strong><i>Unique 3DS transaction identifier (xid): </i></strong></td>
            <td class="red"><?php echo($vpc_3DSXID); ?></td>
        </tr>
        <tr class="shade">
            <td align="right"><strong><i>3DS Authentication Verification Value: </i></strong></td>
            <td class="red"><?php echo($vpc_VerToken); ?></td>
        </tr>
        <tr>
            <td align="right"><strong><i>3DS Electronic Commerce Indicator (ECI): </i></strong></td>
            <td class="red"><?php echo($vpc_3DSECI); ?></td>
        </tr>
        <tr class="shade">
            <td align="right"><strong><i>3DS Authentication Scheme: </i></strong></td>
            <td class="red"><?php echo($vpc_VerType); ?></td>
        </tr>
        <tr>
            <td align="right"><strong><i>3DS Security level used in the AUTH message: </i></strong></td>
            <td class="red"><?php echo($vpc_VerSecurityLevel); ?></td>
        </tr>
        <tr class="shade">
            <td align="right">
                <strong><i>3DS CardHolder Enrolled: </strong>
                <br>
                <font size="1">Takes values: <strong>Y</strong> - Yes <strong>N</strong> - No</i></font>
            </td>
            <td class="red"><?php echo($vpc_3DSenrolled); ?></td>
        </tr>
        <tr>
            <td align="right">
                <i><strong>Authenticated Successfully: </strong><br>
                <font size="1">Only returned if CardHolder Enrolled = <strong>Y</strong>. Takes values:<br>
                <strong>Y</strong> - Yes <strong>N</strong> - No <strong>A</strong> - Attempted to Check <strong>U</strong> - Unavailable for Checking</font></i>
            </td>
            <td class="red"><?php echo($vpc_3DSstatus); ?></td>
        </tr>
        <tr class="shade">
            <td align="right"><strong><i>Payment Server 3DS Authentication Status Code: </i></strong></td>
            <td class="green"><?php echo($vpc_VerStatus); ?></td>
        </tr>
        <tr>
            <td colspan="2" class="red" align="center">
                <br>The 3-D Secure values shown in red are those values that are important values to store in case of future transaction repudiation.
            </td>
        </tr>
        <tr>
            <td colspan="2" class="green" align="center">
                The 3-D Secure values shown in green are for information only and are not required to be stored.
            </td>
        </tr>
        <tr><td colspan = '2'><hr/></td></tr>
        <tr>
            <td colspan = '2'><hr/></td>
        </tr>
        <tr class='title'>
            <td colspan="2" height="25"><p><strong>&nbsp;Hash Validation</strong></p></td>
        </tr>
        <tr>
            <td align="right"><strong><i>Hash Validated Correctly: </i></strong></td>
            <td><?php echo($hashValidated); ?></td>
        </tr>

<?php } ?></table><br/>
    
    <center><P><A HREF='PHP_VPC_3Party_Order.html'>New Transaction</A></P></center>
    
    </body>
    </html>