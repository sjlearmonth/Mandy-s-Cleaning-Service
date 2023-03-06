<?php

if (isset($_POST['emailAddress'])) {

//     // EDIT THE FOLLOWING TWO LINES:
    $email_to = 'stephen.j.learmonth@gmail.com';
    $email_subject = "You have a cleaning enquiry!";

    function problem($error)
    {
        echo "We are sorry, but there were error(s) found with the form you submitted. ";
        echo "These errors appear below.<br><br>";
        echo $error . "<br><br>";
        echo "Please go back and fix these errors.<br><br>";
        die();
    }

    // validation expected data exists
    if (
        !isset($_POST['firstName']) ||
        !isset($_POST['lastName']) ||
        !isset($_POST['phoneNumber']) ||
        !isset($_POST['emailAddress']) ||
        !isset($_POST['enquiryMessage'])
    ) {
        problem('We are sorry, but there appears to be a problem with the form you submitted.');
    }

    $firstName = $_POST['firstName']; // required
    $lastName = $_POST['lastName']; // required
    $phoneNumber = $_POST['phoneNumber']; // required
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

    $number_exp = '/^[0-9]+$/';

    if (!preg_match($number_exp, $phoneNumber)) {
        $error_message .= 'The phone number that you entered does not appear to be valid.<br>';
    }

    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if (!preg_match($email_exp, $emailAddress)) {
        $error_message .= 'The email address that you entered does not appear to be valid.<br>';
    }

    if (strlen($enquiryMessage) < 2) {
        $error_message .= 'The message that you entered do not appear to be valid.<br>';
    }

    if (strlen($error_message) > 0) {
        problem($error_message);
    }

    $email_message = "You have a message from a potential client. Here are the details.". "<br /><br />";
    $email_message .= "First Name: " . $firstName . "<br /><br />";
    $email_message .= "Last Name: " . $lastName . "<br /><br />";
    $email_message .= "Email Address: " . $emailAddress . "<br /><br />";
    $email_message .= "Phone Number: " . $phoneNumber . "<br /><br />";
    $email_message .= "Enquiry Message: " . $enquiryMessage;

    $headers = "X-Mailer: PHP/" . phpversion() . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=iso-8859-1";

    if (mail($email_to, $email_subject, $email_message, $headers)) {
        echo "<script type='text/javascript'>alert('Thank you. Your message has been sent.');window.location.href='/index.html';</script>";
    } else {
        echo "script type='text/javascript'>alert('Error. Your message could not be sent. Please try again.');window.location.href='/index.html';</script>";
    }

    

 }
?>
