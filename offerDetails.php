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
if (isset($_GET['report']) && $_GET['report'] === 'success') {
    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Signalement envoyé !</strong>
            <span class="block sm:inline">Votre signalement a bien été pris en compte.</span>
          </div>';
}

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
<div class="px-4 sm:px-6 md:px-8 lg:px-12 max-w-screen-lg mx-auto mt-6">
    <h1 class="text-3xl font-bold mb-4">{$jobTitle}</h1>
    <p class="text-lg font-semibold">Companie: <span class="font-normal">{$companyName}</span></p>
    <p class="text-lg font-semibold">Lieux: <span class="font-normal">{$location}</span></p>
    <p class="text-lg font-semibold">Salaire: <span class="font-normal">{$salary} $/hr</span></p>
    <p class="text-lg font-semibold">Horaire: <span class="font-normal">{$schedule} heures/semaines</span></p>

    <h2 class="text-2xl font-bold mt-6 mb-2">Description</h2>
    <p class="text-gray-700">{$description}</p>

    <form method="post" action="addReport.php">
        <input type="hidden" name="IdReported" value="{$offer['IdC']}">
        <input type="hidden" name="IdSender" value="{$employeeId}">
        <input type="hidden" name="offerId" value="{$offerId}">

        <label for="ReportType" class="block text-lg font-semibold mt-4">Type de Signalement :</label>
        <select name="ReportType" id="ReportType" required class="border border-gray-300 rounded px-3 py-2">
            <option value="" disabled selected>Sélectionner le type de signalement</option>
            <option value="Spam">Spam</option>
            <option value="Fake">Fausse offre</option>
            <option value="Inappropriate">Contenu inapproprié</option>
        </select>

        <label for="Reason" class="block text-lg font-semibold mt-4">Raison du signalement :</label>
        <textarea name="Reason" id="Reason" required class="border border-gray-300 rounded px-3 py-2 w-full" rows="3" placeholder="Décrivez la raison du signalement"></textarea>

        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded-lg">
                <span class="text-lg font-bold">Signaler</span>
            </button>
        </div>
    </form>
HTML;

if ($hasApplied) {
    $content .= <<<HTML
    <div class="mt-6">
        <p class="text-green-500 font-semibold">Vous avez déjà postulé à cette offre.</p>
    </div>

    <div class="mt-6">
        <form method="post" action="addRating.php">
            <input type="hidden" name="IdCompany" value="{$offer['IdC']}">
            <input type="hidden" name="offerId" value="{$offerId}">
            
            <label for="rating" class="block text-lg font-semibold">Votre note (1 à 5) :</label>
            <select name="rating" id="rating" required class="border border-gray-300 rounded px-3 py-2">
                <option value="" disabled selected>Sélectionner</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            
            <div class="flex justify-end mt-2">
                <button type="submit" class="bg-blue-500 text-white py-1 px-3 rounded-lg">
                    <span class="text-lg font-bold">Laisser une note</span>
                </button>
            </div>
        </form>
    </div>
HTML;
} else {
    $content .= <<<HTML
    <div class="mt-6">
        <a href="apply.php?id={$offerId}" class="btn btn-neutral">Appliquer</a>
    </div>
HTML;
}
$ratingModel = new Rating();
$ratings = $ratingModel->GetAllRating($offer['IdC']);
if ($ratings) {
    $content .= '<h2 class="text-2xl font-bold mt-8 mb-2">Notes des utilisateurs sur cette companie</h2><ul>';
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
        <li class="border-b border-gray-300 py-2">
            <span class="font-semibold">Note:</span> {$ratingValue}/5
            <span class="font-semibold">Par:</span> {$ratingAuthorName} {$ratingAuthorLastName}
            {$deleteButton}
        </li>
HTML;
    }
    $content .= '</ul>';
} else {
    $content .= '<br><p>Aucune note pour cette entreprise.</p>';
}

$content .= <<<HTML
</div>
HTML;

include "Views/master.php";