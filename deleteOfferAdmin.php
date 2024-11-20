<?php
include 'Utilities/sessionManager.php'; 
include 'Models/offer.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
var_dump($_SESSION['currentUser']);
$IdSender = $_SESSION['currentUser']['Id'];

$IdReceiver = $_POST['Sender'];
$offerId = $_POST['offer'];
var_dump($offerId);
$offerModel = new Offer();
$offerModel->deleteOffer($offerId);
$offerModel->NotificationFromReports($IdSender, $IdReceiver);
header('Location: adminpage.php');
