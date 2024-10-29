<?php
include 'Utilities/sessionManager.php';
require_once 'BD/BD.php';
include 'Models/offer.php';

if (isset($_POST['offerId'], $_POST['job'], $_POST['location'], $_POST['salary'], $_POST['description'], $_POST['hours'])) {
    $currentUser = $_SESSION["currentUser"];
    $idC = $currentUser['Id'];
    $offerId = htmlspecialchars($_POST['offerId']);
    $jobTitle = htmlspecialchars($_POST['job']);
    $location = htmlspecialchars($_POST['location']);
    $salary = htmlspecialchars($_POST['salary']);
    $description = htmlspecialchars($_POST['description']);
    $hours = htmlspecialchars($_POST['offerId']);
    
    $offerModel = new Offer();
        
    $offerModel->EditOffer($offerId,$idC, $jobTitle, $location,  $salary, $description, $hours);
    
    header('Location: myoffers.php');
    exit();
}else {
    header('Location: myoffers.php');
    exit();
}

