<?php

if (isset($_POST['emailAddress'])) {

    // $email_to = 'mandy.ohaire@yahoo.co.uk';
    $email_to = 'stephen.j.learmonth@gmail.com';
    $email_subject = "You have a cleaning enquiry!";
    $email_dev = 'mandyohaireenquiries@gmail.com';

    // Validate that expected data actually exists
    if (
        !isset($_POST['firstName']) ||
        !isset($_POST['lastName']) ||
        !isset($_POST['enquiryMessage'])
    ) {
        $error = "Error: One or more fields are empty. Please go back and fill in the missing fields.";
        echo "script type='text/javascript'>alert(" + $error + ");window.location.href='/index.html';</script>";
    }

    $firstName = $_POST['firstName']; // required
    $lastName = $_POST['lastName']; // required
    $emailAddress = $_POST['emailAddress']; // required
    $enquiryMessage = $_POST['enquiryMessage']; // required

    $error_message = "";

    $string_exp = "/^[A-Za-z .'-]+$/";

    if (!preg_match($string_exp, $firstName)) {
        $error_message .= 'The first name that you entered does not appear to be valid.<br>';
    }

    if (!preg_match($string_exp, $lastName)) {
        $error_message .= 'The last name that you entered does not appear to be valid.<br>';
    }

    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
 
    if (!preg_match($email_exp, $emailAddress)) {
        $error_message .= 'The email address that you entered does not appear to be valid.<br>';
    }

    if (strlen($enquiryMessage) < 2) {
        $error_message .= 'The message that you entered does not appear to be valid.<br>';
    }

    if (strlen($error_message) > 0) {
        echo "script type='text/javascript'>alert(\"Error: One or more fields have invalid data. Please go back and try again.\");window.location.href='/index.html';</script>";
    }

    // Fetch phone number
    $phoneNumber = $_POST['phoneNumber']; // required

    // Build regex expression for phone number match
    $number_exp = '/^[0-9]+$/';

    // Check if phone number is valid
    if (preg_match($number_exp, $phoneNumber) && substr($phoneNumber, 0, 2) == '07' && strlen($phoneNumber) == 11) {

        // Base URL and send PHP script
        $url = "https://api-mapper.clicksend.com/http/v2/send.php";
        
        // Format phone number for API call
        $phoneNumber = '44' . substr($phoneNumber, 1);
    
        // Build sender ID for SMS message
        $senderid = "Unknown";
    
        // Build SMS message body
        $sms_message = "You have a message from a potential client. Here are the details.". "\n\n";
        $sms_message .= "First Name: " . $firstName . "\n";
        $sms_message .= "Last Name: " . $lastName . "\n";
        $sms_message .= "Email Address: " . $emailAddress . "\n";
        $sms_message .= "Phone Number: " . '+' . $phoneNumber . "\n";
        $sms_message .= "Enquiry Message: " . $enquiryMessage;
    
        // Build array for API call
        $data = array("username" => "stephen.j.learmonth@gmail.com", "key" => "8C32B75C-35A6-C906-5A04-A6CD05141B11", "to" => $phoneNumber, "senderid" => $senderid, "message" => $sms_message);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        // Build the email content and headers
        $email_message = "You have a message from a potential client. Here are the details.". "<br /><br />";
        $email_message .= "First Name: " . $firstName . "<br /><br />";
        $email_message .= "Last Name: " . $lastName . "<br /><br />";
        $email_message .= "Email Address: " . $emailAddress . "<br /><br />";
        $email_message .= "Phone Number: " . $phoneNumber . "<br /><br />";
        $email_message .= "Enquiry Message: " . $enquiryMessage;
    
        $headers = "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=iso-8859-1";
    
        // Send the email message
        $email_sent_successfully = mail($email_to, $email_subject, $email_message, $headers) && mail($email_dev, $email_subject, $email_message, $headers);

        // Send the SMS message
        $response = curl_exec($ch);
        curl_close($ch);
        $sms_sent_successfully = strpos($response, "Success");

        // Check if they have both been sent successfully
        if ($email_sent_successfully && $sms_sent_successfully) {
            echo "<script type='text/javascript'>alert('Thank you. Your message has been sent.');window.location.href='/index.html';</script>";
        } else {
            echo "script type='text/javascript'>alert(" + $error_message + ");window.location.href='/index.html';</script>";
        }
    } else {

        if (!$phoneNumber == "") {

            // Print out error message as phone number is invalid or missing
            $error_message .= 'The phone number that you entered does not appear to be valid.<br>';

        } else {

            // Build the email message content and headers
            $email_message = "You have a message from a potential client. Here are the details.". "<br /><br />";
            $email_message .= "First Name: " . $firstName . "<br /><br />";
            $email_message .= "Last Name: " . $lastName . "<br /><br />";
            $email_message .= "Email Address: " . $emailAddress . "<br /><br />";
            $email_message .= "Phone Number: " . $phoneNumber . "<br /><br />";
            $email_message .= "Enquiry Message: " . $enquiryMessage;
        
            $headers = "X-Mailer: PHP/" . phpversion() . "\r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type: text/html; charset=iso-8859-1";
        
            // Send the email message
            $email_sent_successfully = mail($email_to, $email_subject, $email_message, $headers) && mail($email_dev, $email_subject, $email_message, $headers);

            // Check if email message has been sent successfully
            if ($email_sent_successfully) {
                echo "<script type='text/javascript'>alert('Thank you. Your message has been sent.');window.location.href='/index.html';</script>";
            } else {
                echo "script type='text/javascript'>alert(" + $error_message + ");window.location.href='/index.html';</script>";        
            }
        }
    }
} else {
    
    // No email address, so prompt user to enter one
    echo "script type='text/javascript'>alert('Error. Please enter an email address and try again.');window.location.href='/index.html';</script>";            
}
?>
