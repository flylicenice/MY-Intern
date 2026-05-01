<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require '../vendor/autoload.php'; //Path to composer files

//Creating dotenv instance
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

//Send Email Function
function sendInternshipEmail($to, $subject, $messageBody)
{
    $mail = new PHPMailer(true);

    try {

        //Server Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_EMAIL'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Sender & Recipients
        $mail->setFrom($_ENV['SMTP_EMAIL'], 'MyIntern');
        $mail->addAddress($to);

        //Set email content as HTML and its subject
        $mail->isHTML(true);
        $mail->Subject = $subject;
        
        //Email Content
        $mail->Body = 
            "<div style='font-family: Arial; border: 1px solid #ddd; padding: 20px;'>
                <h2 style='color: #3498db;'>MyIntern Notification</h2>
                <p>{$messageBody}</p>
                <hr>
                <small>This is an automated message from MYIntern.</small>
            </div>";

        //Finally send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        //Show error message if there is an error occured
        return "Message could not be sent. Malier Error: {$mail->ErrorInfo}";
    }
}

//User Log in and enter the email
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Get user email from the form: name: "user-email" 
    //Set the email subject and the message in the email
    $userEmail = $_POST["user-email"];
    $subject = "Welcome to MYIntern!";
    $message = "Hi! You have successfully logged into the system.";

    //Send email and return true if success | return false if failed
    $result = sendInternshipEmail($userEmail, $subject, $message);

    //If success then end this file
    if ($result === true) {
        //Return success message to the auth.js
        echo "success";
    } else {
    //If failed then display error
        http_response_code(500);
        echo $result;
    }
    exit();
}

?>