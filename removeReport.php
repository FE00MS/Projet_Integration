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

$offer = $offerModel->GetOffer($offerId);
$offerTitle = $offer["Job"];

$offerModel->NotificationFromReports($IdSender, $IdReceiver, 'E', $offerTitle);
$offerModel->deleteReport($offerId);
header('Location: adminpage.php');