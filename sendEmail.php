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

$senderEmail = 'laboussoleaemploi@gmail.com';  
$companyName = $_SESSION['currentUser']['CName']; 
$companyContact = $_SESSION['currentUser']['Email'];  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidateEmail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $jobTitle = filter_input(INPUT_POST, 'jobTitle', FILTER_SANITIZE_STRING);
    $messageContent = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $subject = "Offre d'emploi de La Boussole à Emploi";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'laboussoleaemploi@gmail.com';
        $mail->Password = 'ckne pxvi kuvh jcrx';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($senderEmail, 'La Boussole à Emploi');  
        $mail->addAddress($candidateEmail);  

        $mail->Subject = $subject;
        
        $mail->Body = <<<EOD
Bonjour,

La compagnie "{$companyName}" vous contacte pour le poste de "{$jobTitle}".

Contact de l'entreprise :
{$companyContact}

Message :
{$messageContent}

Merci de votre intérêt,
La Boussole à Emploi
EOD;

        $mail->send();
        header('Location: myCandidates.php');
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
    }
}
