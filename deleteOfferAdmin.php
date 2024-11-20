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

$CId = $offerModel->GetCompanyByOffer($offerId)['Id'];
$offer = $offerModel->GetOffer($offerId);
$offerTitle = $offer["Job"];

$offerModel->deleteOffer($offerId);
$offerModel->NotificationFromReports($IdSender, $CId, 'C',$offerTitle);
$offerModel->NotificationFromReports($IdSender, $IdReceiver, 'E', $offerTitle);
$offerModel->DeleteNotificationWithOfferId($offerId);
$offerModel->deleteReport($offerId);
header('Location: adminpage.php');
