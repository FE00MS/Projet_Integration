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
$offers = new Offer();
$employeeModel = new Employee();
$company = new Company();

// Récupérer l'offre d'emploi
$offer = $offers->GetOffer($offerId);
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
    $content .= <<<HTML
        <a href="apply.php?id={$offerId}" class="inline-block mt-6 bg-blue-500 text-white py-2 px-6 rounded-lg shadow hover:bg-blue-600 transition">Postuler Maintenant</a>
HTML;
}

// Affichage des notes des utilisateurs
$ratingModel = new Rating();
$ratings = $ratingModel->GetAllRating($offer['IdC']);
if ($ratings) {
    $content .= '<h2 class="text-2xl font-semibold text-gray-800 mt-10 mb-4">Notes des Utilisateurs</h2><ul class="space-y-4">';
    foreach ($ratings as $rating) {
        $ratingValue = $rating['Rating'];
        $authorName = "{$rating['Name']} {$rating['LastName']}";
        $deleteButton = $rating['IdEmp'] == $employeeId ? <<<HTML
        <form method="post" action="deleteRating.php" class="inline-block">
            <input type="hidden" name="IdCompany" value="{$offer['IdC']}">
            <input type="hidden" name="offerId" value="{$offerId}">
            <button type="submit" class="text-red-500 hover:underline">Supprimer</button>
        </form>
HTML : '';
        $content .= <<<HTML
        <li class="border-b border-gray-300 py-2">
            <span class="text-gray-700">Note: {$ratingValue}/5</span> - <span class="text-gray-600">Par: {$authorName}</span> {$deleteButton}
        </li>
HTML;
    }
    $content .= '</ul>';
} else {
    $content .= '<p class="text-gray-500">Aucune note disponible.</p>';
}

// Formulaire de signalement
$content .= <<<HTML
        <form method="post" action="addReport.php" class="bg-gray-50 mt-10 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Signaler une Offre</h3>
            <input type="hidden" name="IdReported" value="{$offer['IdC']}">
            <input type="hidden" name="IdSender" value="{$employeeId}">
            <input type="hidden" name="offerId" value="{$offerId}">
            <label for="ReportType" class="block text-gray-700 font-semibold mb-2">Type de Signalement :</label>
            <select name="ReportType" id="ReportType" required class="w-full border-gray-300 rounded-lg p-2 mb-4">
                <option value="" disabled selected>Sélectionner</option>
                <option value="Spam">Spam</option>
                <option value="Fake">Fausse offre</option>
                <option value="Inappropriate">Contenu inapproprié</option>
            </select>
            <label for="Reason" class="block text-gray-700 font-semibold mb-2">Raison :</label>
            <textarea name="Reason" id="Reason" rows="3" class="w-full border-gray-300 rounded-lg p-2 mb-4" placeholder="Décrivez la raison..."></textarea>
            <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg shadow hover:bg-red-600 transition">Signaler</button>
        </form>
    </div>
</div>
HTML;

include "Views/master.php";
?>
