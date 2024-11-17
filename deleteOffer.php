<?php
include 'Utilities/sessionManager.php'; 
include 'Models/offer.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$offerId = intval($_GET['Id']);

$offerModel = new Offer();
$offerModel->deleteOffer($offerId);
header('Location: myOffers.php');
