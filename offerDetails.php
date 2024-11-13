<?php
require_once 'Models/company.php';
require_once 'Models/offer.php';
include 'Models/rating.php';
require_once 'Models/employee.php';  
include 'Utilities/sessionManager.php';  

if (isset($_GET['id'])) {
    $offerId = htmlspecialchars($_GET['id']); 
} else {
    die("Offer ID is missing.");
}

$currentUser = $_SESSION['currentUser'];
$type = $_SESSION["accountType"];
$offers = new Offer();
$employeeModel = new Employee();
$company = new Company();

$offer = $offers->GetOffer($offerId);

if (!$offer) {
    die("Offer not found.");
}

$jobTitle = htmlspecialchars($offer['Job']);
$companyName = $company->GetCompanyById($offer['IdC'])['CName'] ;  
$location = htmlspecialchars($offer['Location']);
$hours = htmlspecialchars($offer['Hours']);
$salary = htmlspecialchars($offer['Salary']);
$description = htmlspecialchars($offer['Description']);
$schedule = htmlspecialchars($offer['Hours']);

$employeeId = $_SESSION['currentUser']['Id'];

$applications = $employeeModel->getApplications($employeeId);

if ($applications === null) {
    $applications = [];
}

$hasApplied = false;

foreach ($applications as $application) {
    if ($application['IdOffer'] == $offerId) {
        $hasApplied = true;
        break;
    }
}

$content = <<<HTML
<div class="px-4 sm:px-6 md:px-8 lg:px-12 max-w-screen-lg mx-auto mt-6  shadow-lg rounded-lg p-8 transform transition-transform duration-500 hover:scale-105">
    <h1 class="text-4xl font-bold mb-6 text-indigo-700 animate-pulse">{$jobTitle}</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <p class="text-lg font-semibold">Companie: <span class="font-normal text-gray-700">{$companyName}</span></p>
        <p class="text-lg font-semibold">Lieux: <span class="font-normal text-gray-700">{$location}</span></p>
        <p class="text-lg font-semibold">Salaire: <span class="font-normal text-gray-700">{$salary} $/hr</span></p>
        <p class="text-lg font-semibold">Horaire: <span class="font-normal text-gray-700">{$schedule} heures/semaines</span></p>
    </div>

    <h2 class="text-3xl font-bold mt-8 mb-4 text-indigo-700 animate-pulse">Description</h2>
    <p class="text-gray-700 leading-relaxed">{$description}</p>
HTML;

if ($hasApplied) {
    $content .= <<<HTML
    <div class="mt-8">
        <p class="text-green-500 font-semibold">Vous avez déjà postulé à cette offre.</p>
    </div>

    <div class="mt-8">
        <form method="post" action="addRating.php" class="bg-gray-100 p-6 rounded-lg shadow-md transform transition-transform duration-500 hover:scale-105">
            <input type="hidden" name="IdCompany" value="{$offer['IdC']}">
            <input type="hidden" name="offerId" value="{$offerId}">
            
            <label for="rating" class="block text-lg font-semibold mb-2">Votre note (1 à 5) :</label>
            <select name="rating" id="rating" required class="border border-gray-300 rounded px-3 py-2 mb-4">
                <option value="" disabled selected>Sélectionner</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md transform transition-transform duration-500 hover:scale-110">
                    <span class="text-lg font-bold">Laisser une note</span>
                </button>
            </div>
        </form>
    </div>
HTML;
}if ($type === 'company') {
    $content .= <<<HTML
    none    
HTML;
}else {
    $content .= <<<HTML
    <div class="mt-8">
        <a href="apply.php?id={$offerId}" class="btn btn-neutral bg-blue-500 text-white py-2 px-4 rounded-lg shadow-md transform transition-transform duration-500 hover:scale-110">Appliquer</a>
    </div>
    HTML;
}
$ratingModel = new Rating();
$ratings = $ratingModel->GetAllRating($offer['IdC']);
if ($ratings) {
    $content .= '<h2 class="text-3xl font-bold mt-10 mb-4 text-indigo-700 animate-pulse">Notes des utilisateurs sur cette companie</h2><ul class="space-y-4">';
    foreach ($ratings as $rating) {
        $ratingValue =   $rating['Rating'] ;
        $ratingAuthorId =   $rating['IdEmp'] ;
        $ratingAuthorName =   $rating['Name'] ;
        $ratingAuthorLastName =   $rating['LastName'] ;

        $deleteButton = '';
        if ($ratingAuthorId == $employeeId) {
            $deleteButton = <<<HTML
            <form method="post" action="deleteRating.php" class="inline">
                <input type="hidden" name="IdCompany" value="{$offer['IdC']}">
                <input type="hidden" name="offerId" value="{$offerId}">

                <button type="submit" class="text-red-500 ml-4">Supprimer</button>
            </form>
HTML;
        }

        $content .= <<<HTML
        <li class="border-b border-gray-300 py-4">
            <span class="font-semibold">Note:</span> {$ratingValue}/5
            <span class="font-semibold">Par:</span> {$ratingAuthorName} {$ratingAuthorLastName}
            {$deleteButton}
        </li>
HTML;
    }
    $content .= '</ul>';
} else {
    $content .= '<br><p class="text-gray-700">Aucune note pour cette entreprise.</p>';
}

$content .= <<<HTML
<form method="post" action="addReport.php" class="bg-gray-100 p-6 rounded-lg shadow-md mt-10 transform transition-transform duration-500 hover:scale-105">
        <input type="hidden" name="IdReported" value="{$offer['IdC']}">
        <input type="hidden" name="IdSender" value="{$employeeId}">
        <input type="hidden" name="offerId" value="{$offerId}">

        <label for="ReportType" class="block text-lg font-semibold mt-4">Type de Signalement :</label>
        <select name="ReportType" id="ReportType" required class="border border-gray-300 rounded px-3 py-2 mt-1 mb-4">
            <option value="" disabled selected>Sélectionner le type de signalement</option>
            <option value="Spam">Spam</option>
            <option value="Fake">Fausse offre</option>
            <option value="Inappropriate">Contenu inapproprié</option>
        </select>

        <label for="Reason" class="block text-lg font-semibold mt-4">Raison du signalement :</label>
        <textarea name="Reason" id="Reason" required class="border border-gray-300 rounded px-3 py-2 w-full mt-1 mb-4" rows="3" placeholder="Décrivez la raison du signalement"></textarea>

        <div class="flex justify-end">
            <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg shadow-md transform transition-transform duration-500 hover:scale-110">
                <span class="text-lg font-bold">Signaler</span>
            </button>
        </div>
    </form>
</div>
HTML;

include "Views/master-No-Header.php";
?>

<style>
.bg-custom-indigo {
    background-color: #5a67d8;
}
.text-white {
    color: #ffffff;
}
.text-gray-300 {
    color: #d1d5db;
}
</style>