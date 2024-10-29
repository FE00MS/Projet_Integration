<?php
include 'Utilities/sessionManager.php'; 
require_once 'BD/BD.php';
include 'Models/offer.php';

if (isset($_GET['id'])) {
    $offerId = intval($_GET['id']);  

    $offerModel = new Offer();
    $offerDetails = $offerModel->getOffer($offerId);
    
    if ($offerDetails) {
        $jobTitle = htmlspecialchars($offerDetails['Job']); 
        $location = htmlspecialchars($offerDetails['Location']);
        $salary = htmlspecialchars($offerDetails['Salary']);
        $description = htmlspecialchars($offerDetails['Description']);
        $hours = htmlspecialchars($offerDetails['Hours']);
    } else {
        echo "Offer not found.";
        exit();
    }
} else {
    header('Location: myoffers.php');
    exit();
}

$content = <<<HTML
    <div class="flex justify-between items-center pt-12">
        <h1 class="text-4xl font-bold pl-56">Édition d’offre</h1>
        <button class="text-3xl pr-56">✕</button>
    </div>

    <form id="offerForm" method="POST" action="updateOffer.php">
        <input type="hidden" name="offerId" value="$offerId">
        <div class="flex justify-center items-start mt-8">
            <div class="w-1/3 space-y-4 border-r-4 pr-48 border-indigo-500">
                    <div>
                        <label class="block text-sm font-medium">Titre d'emploi</label>
                        <input type="text" name="job" class="mt-1 w-full border border-gray-300 rounded p-2" value="$jobTitle" placeholder="Titre d'emploi" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Emplacement</label>
                        <input type="text" name="location" class="mt-1 w-full border border-gray-300 rounded p-2" value="$location" placeholder="Emplacement" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Salaire (range)</label>
                        <input type="number" name="salary" class="mt-1 w-full border border-gray-300 rounded p-2" value="$salary" placeholder="Salaire" required min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" class="mt-1 w-full border border-gray-300 rounded p-2" placeholder="Description" required>$description</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Heures</label>
                        <input type="number" name="hours" class="mt-1 w-full border border-gray-300 rounded p-2" value="$hours" placeholder="Heures" required min="0">
                    </div>
                    <button type="submit" class="btn btn-neutral w-full">Mettre à jour l'offre</button>
            </div>
            <div id="dynamicForm" class="pl-16 border-indigo-500">
            </div>
    </form>
HTML;

include "Views/master.php";