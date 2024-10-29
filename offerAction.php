<?php
require_once 'Models/Offer.php';
include 'Utilities/sessionManager.php';  
include 'Utilities/formUtilities.php';   



    if (!isset($_SESSION['currentUser'])) {
        header('Location: login.php');
        exit();
    }

    $idc = $_SESSION["currentUser"]["Id"];  
    $job = sanitizeString($_POST['job']);
    $location = sanitizeString($_POST['location']);
    $salary = isset($_POST['salary']) ? $_POST['salary'] : null;
    $description = isset($_POST['description']) ? sanitizeString($_POST['description']) : null;
    $hours = isset($_POST['hours']) ?$_POST['hours'] : null;

    $offer = new Offer();
    $offer->CreateOffer($idc, $job, $location, $salary, $description, $hours);

    header('Location: myOffers.php');
    exit();
