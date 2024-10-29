<?php
include 'Utilities/sessionManager.php';
require_once 'BD/BD.php';
include 'Models/offer.php';
include 'Models/company.php';

$offers = new Offer();
$company = new Company();
$idC = $_SESSION['currentUser'];
$allOffers = $offers->GetOfferByCompagny($idC['Id']);

$content = <<<HTML
    <div class="px-4 sm:px-6 md:px-8 lg:px-12 max-w-screen-lg mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"> 
HTML;
if($allOffers != null){
    foreach ($allOffers as $offer) {
        $jobTitle = htmlspecialchars($offer['Job']);
        $companyName = htmlspecialchars($offer['CName']); 
        $location = htmlspecialchars($offer['Location']);
        $hours = htmlspecialchars($offer['Hours']);
        $salary = htmlspecialchars($offer['Salary']);
        $description = htmlspecialchars($offer['Description']);
        $shortDescription = substr($description, 0, 100) . '...'; 
        $offerId = htmlspecialchars($offer['Id']); 
        $content .= <<<HTML
            <a href="editOffer.php?id={$offerId}" class="card bg-base-100 shadow-lg rounded-lg overflow-hidden hover:shadow-xl hover:scale-105 transition-transform duration-200 mb-6"> <!-- Updated href to point to editOffer.php -->
                <div class="p-4">
                    <h2 class="text-xl font-semibold">{$jobTitle}</h2>
                    <p class="font-medium text-gray-500">{$companyName}</p>
                    <p class="text-gray-500">{$location}</p>
                    <p class="text-gray-500">{$salary} $/hr</p>
                    <p class="text-gray-500">{$hours} hours/week</p>
                    <p class="text-gray-700 mt-2">{$shortDescription}</p>
                </div>
            </a>
    HTML;
    }
}
else{
    header('Location: createOffer.php');

}


$content .= <<<HTML
        </div> 
    </div>
HTML;

include "Views/master.php";