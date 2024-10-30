<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'Models/employee.php';
include 'Utilities/sessionManager.php';


require 'vendor/autoload.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit;
}
$senderEmail = $_SESSION['currentUser']['Email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidateEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject');
    $messageContent = filter_input(INPUT_POST, 'content');

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                      // Use SMTP
        $mail->Host = 'smtp.gmail.com';                      // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                // Enable SMTP authentication
        $mail->Username = 'jacoblf314@gmail.com';            // SMTP username
        $mail->Password = 'baiihlypyzgyykpa';                     // SMTP password
        $mail->SMTPSecure = 'tls';    // Enable TLS encryption; use PHPMailer::ENCRYPTION_SMTPS for `ssl`
        $mail->Port = 587;                                     // TCP port to connect to

        $mail->setFrom($senderEmail);           // Set sender of the mail
        $mail->addAddress($candidateEmail);           // Add a recipient

        $mail->Subject = $subject;
        $mail->Body    = $messageContent;

        $mail->send();
        header('Location: myCandidates.php');
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
    }
}