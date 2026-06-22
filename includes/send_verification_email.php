<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendVerificationEmail($userEmail, $userName, $verification_url)
{
    $mail = new PHPMailer(true);
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $senderMail = $_ENV['SMTP_EMAIL'];
    $SMTPPassword = $_ENV['SMTP_PASSWORD'];

    $templatePath = __DIR__ . "/../email_template/template.html";

    try {
        //SMTP Server Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $senderMail;
        $mail->Password = $SMTPPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        //Sender and Recipient Specifying
        $mail->setFrom($senderMail, 'MYIntern Portal'); //Sender
        $mail->addAddress($userEmail, $userName); //Recipient
        
        $mail->isHTML(true);
        $mail->Subject = 'Verify your MYIntern account';

        $emailBody = file_get_contents($templatePath);

        $emailBody = str_replace('{USER_NAME}', htmlspecialchars($userName), $emailBody);
        $emailBody = str_replace('{VERIFICATION_URL}', $verification_url, $emailBody);

        $mail->Body = $emailBody;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;        
    }
}
