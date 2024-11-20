<?php
require_once 'Models/Offer.php';
include 'Utilities/sessionManager.php';
include 'Utilities/formUtilities.php';

$offer = new Offer();
$idC = $_SESSION['currentUser']['Id'];

$sql = $offer->conn->prepare("EXEC GetCompanyById @Id= :Id");
$sql->bindParam(':Id', $idC, PDO::PARAM_INT);
$sql->execute();
$myCompany = $sql->fetch(PDO::FETCH_ASSOC);

$sql = $offer->conn->prepare("EXEC GetLastNewOffer @IdC= :IdC");
$sql->bindParam(':IdC', $idC, PDO::PARAM_INT);
$sql->execute();
$lastOffer = $sql->fetch(PDO::FETCH_ASSOC);

$OId = $lastOffer['Id'];

$sql = $offer->conn->prepare("EXEC GetOfferById @Oid= :Oid");
$sql->bindParam(':Oid', $OId, PDO::PARAM_INT);
$sql->execute();
$currentOffer = $sql->fetch(PDO::FETCH_ASSOC);


$job = $currentOffer["Job"];

$prerequisites = $offer->getPrerequisites($OId);

$sql = $offer->conn->prepare("EXEC GetLangueByOfferId @Oid= :Oid");
$sql->bindParam(':Oid', $OId, PDO::PARAM_INT);
$sql->execute();
$offerLanguages = $sql->fetchAll(PDO::FETCH_ASSOC);

$sql = $offer->conn->prepare("EXEC GetAllExperience");
$sql->execute();
$empExperiences = $sql->fetchAll(PDO::FETCH_ASSOC);

$sql = $offer->conn->prepare("EXEC GetLangageEmp");
$sql->execute();
$empLangues = $sql->fetchAll(PDO::FETCH_ASSOC);

$sql = $offer->conn->prepare("EXEC GetAllEmployeeId");
$sql->execute();
$allEmployeeIds = $sql->fetchAll(PDO::FETCH_ASSOC);
$empToNotify = [];
foreach ($allEmployeeIds as $IdEmp) {
    $empExperience = [];
    $empLanguage = [];
    foreach ($empExperiences as $empExp) {
        if ($empExp['IdEmp'] == $IdEmp['Id']) {

            array_push($empExperience,$empExp);
        }
    }
    foreach ($empLangues as $language) {
        if ($language['EId'] == $IdEmp['Id']) {

            array_push($empLanguage,$language);
        }
    }
    if(80<=$offer->ponderateOffer($prerequisites, $empExperience, $empLanguage, $offerLanguages)){
        array_push($empToNotify,$IdEmp);       
    }
    
}
$message = "L'offre pour le poste de ".  $job ." de la compagnie ". $myCompany['CName'] ." pourrait vous interesser!";
foreach($empToNotify as $emp){
    $offer->AddOfferNotification($idC, $emp['Id'], $message, $OId);
}

header('Location:myOffers.php');