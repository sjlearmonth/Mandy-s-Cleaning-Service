<?php
$message_sent = 'Not Sent';
if (isset($_POST['email'])) {

//     // EDIT THE FOLLOWING TWO LINES:
    $email_to = 'stephen.j.learmonth@gmail.com';
    $email_subject = "You have an enquiry!";

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
        !isset($_POST['name']) ||
        !isset($_POST['number']) ||
        !isset($_POST['email']) ||
        !isset($_POST['message'])
    ) {
        problem('We are sorry, but there appears to be a problem with the form you submitted.');
    }

    $name = $_POST['name']; // required
    $number = $_POST['number']; // required
    $email = $_POST['email']; // required
    $message = $_POST['message']; // required

    $error_message = "";

    $string_exp = "/^[A-Za-z .'-]+$/";

    if (!preg_match($string_exp, $name)) {
        $error_message .= 'The name you entered does not appear to be valid.<br>';
    }

    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if (!preg_match($email_exp, $email)) {
        $error_message .= 'The email address you entered does not appear to be valid.<br>';
    }

    $number_exp = '/^[0-9]+$/';

    if (!preg_match($number_exp, $number)) {
        $error_message .= 'The phone number you entered does not appear to be valid.<br>';
    }

    if (strlen($message) < 2) {
        $error_message .= 'The Message you entered do not appear to be valid.<br>';
    }

    if (strlen($error_message) > 0) {
        problem($error_message);
    }

    $email_message = "You have a message from a potential client. Here are the details.\n\n";
    $email_message .= "Name: " . $name . "\n";
    $email_message .= "Email: " . $email . "\n";
    $email_message .= "Phone Number: " . $number . "\n";
    $email_message .= "Message: " . $message . "\n";

    if (mail($email_to, $email_subject, $email_message)) {
        $message_sent = 'Sent';
        echo "<script type='text/javascript'>alert('Thank you. Your message has been sent.');window.location.href='/index.html';</script>";
    } else {
        $message_sent = 'Not Sent';
        echo "script type='text/javascript'>alert('Error. Your message could not be sent. Please try again.');window.location.href='/index.html';</script>";
    }

    

 }
?>
