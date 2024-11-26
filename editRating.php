<?php
include 'Utilities/sessionManager.php'; 
include 'Models/rating.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$currentUserId =intval($_SESSION['currentUser']['Id']) ;
$Name= $_SESSION['currentUser']['Name'];
$LastName = $_SESSION['currentUser']['LastName'];
$companyId = $_POST['IdCompany'];
$ratingValue = $_POST['rating'];

$ratingModel = new Rating();
$ratingModel->EditRating($currentUserId, $Name, $LastName,$companyId,$ratingValue);
header('Location: offerDetails.php?id=' . $_POST['offerId']);
exit();
