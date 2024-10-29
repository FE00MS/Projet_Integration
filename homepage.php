<?php
require_once 'Models/company.php';
require_once 'BD/BD.php';
include 'Utilities/sessionManager.php';  

$offers = new Database();
$allOffers = $offers->GetAllOffers();
$company = new Company();

$content = <<<HTML
<div class="px-4 sm:px-6 md:px-8 lg:px-12 max-w-screen-lg mx-auto">
    <label class="input input-bordered flex items-center gap-2 mb-8">
        <input type="text" class="grow" placeholder="Search" />
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
            <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0a3.5 a3.5 0 0 1-7 0Z" clip-rule="evenodd" />
        </svg>
    </label>

    <h1 class="text-2xl font-bold mb-4">Offres d'emploi</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
HTML;

foreach ($allOffers as $offer) {
    $jobTitle = htmlspecialchars($offer['Job']);
    $companyName = htmlspecialchars($offer['CName']); 
    $location = htmlspecialchars($offer['Location']);
    $hours = htmlspecialchars($offer['Hours']);
    $salary = htmlspecialchars($offer['Salary']);
    $description = htmlspecialchars($offer['Description']);
    $shortDescription = substr($description, 0, 100) . '...'; 
    $offerId = htmlspecialchars($offer['Id']); 

    $offerLink = isset($_SESSION['currentUser']) 
    ? "offerDetails.php?id={$offerId}" 
    : "signupChoices.php"; 


    $content .= <<<HTML
         <a href="{$offerLink}" class="card bg-base-100 shadow-lg rounded-lg overflow-hidden hover:shadow-xl hover:scale-105 transition-transform duration-200">
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

$content .= <<<HTML
    </div>
</div>
HTML;

include "Views/master.php";