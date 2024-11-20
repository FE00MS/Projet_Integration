<?php
// Inclure les modèles nécessaires
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

// Récupération des données utilisateur
$currentUser = $_SESSION['currentUser'];
$type = $_SESSION["accountType"];

$company = new Company();
$employeeModel = new Employee();
$offerModel = new Offer();
$offer = $offerModel->GetOffer($offerId);

if (!$offer) {
    die("Offer not found.");
}

$jobTitle = htmlspecialchars($offer['Job']);
$companyName = $company->GetCompanyById($offer['IdC'])['CName'];  
$location = htmlspecialchars($offer['Location']);
$hours = htmlspecialchars($offer['Hours']);
$salary = htmlspecialchars($offer['Salary']);
$description = htmlspecialchars($offer['Description']);
$schedule = htmlspecialchars($offer['Hours']);

$employeeId = $_SESSION['currentUser']['Id'];
$applications = $employeeModel->getApplications($employeeId) ?? [];
$hasApplied = array_filter($applications, fn($application) => $application['IdOffer'] == $offerId);

// Génération du contenu de la page
$content = <<<HTML
<div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-6 sm:px-8 lg:px-12 py-10 max-w-screen-lg bg-white shadow-lg rounded-lg">
        <h1 class="text-4xl font-bold text-blue-600 mb-6">{$jobTitle}</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <p><span class="font-semibold text-gray-700">🏢 Companie :</span> {$companyName}</p>
            <p><span class="font-semibold text-gray-700">📍 Lieu :</span> {$location}</p>
            <p><span class="font-semibold text-gray-700">💰 Salaire :</span> {$salary} $/hr</p>
            <p><span class="font-semibold text-gray-700">⏱️ Horaire :</span> {$schedule} heures/semaines</p>
        </div>

        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Description</h2>
        <p class="text-gray-600 leading-relaxed mb-8 border-t border-b py-4">{$description}</p>
HTML;

if ($hasApplied) {
    $content .= <<<HTML
        <p class="text-green-600 font-semibold">Vous avez déjà postulé à cette offre.</p>
        <form method="post" action="addRating.php" class="mt-6">
            <input type="hidden" name="IdCompany" value="{$offer['IdC']}">
            <input type="hidden" name="offerId" value="{$offerId}">
            <label for="rating" class="block mb-2 text-lg font-semibold text-gray-800">Votre note (1 à 5) :</label>
            <select name="rating" id="rating" class="block w-full border-gray-300 rounded-lg p-2 mb-4" required>
                <option value="" disabled selected>Sélectionner</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-600 transition">Laisser une note</button>
        </form>
HTML;
} else {
    if ($type === 'employee') {
        $content .= <<<HTML
        <a href="apply.php?id={$offerId}" class="inline-block mt-6 bg-blue-500 text-white py-2 px-6 rounded-lg shadow hover:bg-blue-600 transition">Postuler Maintenant</a>
HTML;
    }
}

// Affichage des notes des utilisateurs
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

if ($type === 'employee') {
    $content .= <<<HTML
    <form method="post" action="addReport.php" class="bg-gray-100 p-6 rounded-lg shadow-md mt-10 transform transition-transform duration-500 hover:scale-105">
        <input type="hidden" name="IdReported" value="{$offer['Id']}">
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
HTML;
}

$content .= '</div></div>';

include "Views/master.php";
?>