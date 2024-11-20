<?php
include 'Utilities/sessionManager.php'; 
include 'Models/offer.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$offerModel = new Offer();

$IdSender = $_SESSION['currentUser']['Id'];
$IdReceiver = $_POST['Sender'];
$offerId = $_POST['offerId'];


$offerModel->NotificationFromReports($IdSender, $IdReceiver);
$offerModel->deleteReport($offerId);
header('Location: adminpage.php');