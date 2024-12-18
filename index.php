<?php
require_once 'Models/company.php';
require_once 'BD/BD.php';
include 'Utilities/sessionManager.php';

$offers = new Database();
$allOffers;
$company = new Company();

if (!isset($_SESSION['currentUser'])) {
    $allOffers = $offers->GetAllOffers('comp');
} else {
    $allOffers = $offers->GetAllOffers('pond');
}

if (isset($_GET['report']) && $_GET['report'] === 'success') {
    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative " role="alert">
            <strong class="font-bold">Signalement envoyé !</strong>
            <span class="block sm:inline">Votre signalement a bien été pris en compte.</span>
          </div>';
}

if (!isset($_SESSION['currentLanguage'])) {
    $_SESSION['currentLanguage'] = "FR";
}
$lang = $_SESSION['currentLanguage'];

$jsonFile = ($lang === "FR") ? "fr.json" : "en.json";
$jsonData = file_get_contents($jsonFile);
$translations = json_decode($jsonData, true);

$content = <<<HTML
<div id="mainContainer" class="flex flex-col items-center justify-center bg-gray-100 p-6">
    <div class="w-full max-w-screen-lg mx-auto">
        <div class="flex flex-col md:flex-row items-center gap-4 mb-6">
            <select id="searchCriteria" 
                    class="w-full md:w-auto border border-gray-300 rounded-lg p-2 shadow-sm focus:ring focus:ring-indigo-200" 
                    onchange="filterOffers()" 
                    aria-label="Select search criteria">
                <option value="job-title">{$translations['offer']}</option>
                <option value="company-name">{$translations['offerName']}</option>
                <option value="salary">{$translations['salary']}</option>
                <option value="location">{$translations['location']}</option>
                <option value="hours">Heures</option>
            </select>

            <input type="text" 
                id="searchInput" 
                class="w-full flex-grow border border-gray-300 rounded-lg p-2 shadow-sm focus:ring focus:ring-indigo-200" 
                placeholder="Rechercher" 
                oninput="filterOffers()" 
                aria-label="Search input" />

            <button class="hidden md:block p-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition duration-200 shadow-md focus:ring focus:ring-indigo-200" 
                    aria-label="Search">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-5 w-5" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0a3.5 a3.5 0 0 1-7 0Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>


        <h1 class="text-3xl font-bold text-gray-800 mb-6">{$translations['jobOffers']}</h1>

        <div id="offersGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
HTML;
function getPonderationClass($ponderation)
{
    if ($ponderation >= 90) {
        return 'bg-green-500';
    } elseif ($ponderation >= 70) {
        return 'bg-yellow-500';
    } elseif ($ponderation >= 50) {
        return 'bg-orange-500';
    } else {
        return 'bg-red-500';
    }
}

foreach ($allOffers as $offer) {
    $ponderation = htmlspecialchars($offer['Ponderation'] ?? 'Na');
    $ponderationClass = getPonderationClass((int) $ponderation);

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

        <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition duration-200 p-6 flex flex-col justify-between h-full job-offer relative">
            <div class="relative flex-grow">
                <div class="absolute top-0 right-0 {$ponderationClass} text-white text-sm font-bold rounded-full w-8 h-8 flex items-center justify-center shadow-md">
                    {$ponderation}
                </div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2 truncate job-title" style="max-width: calc(100% - 2.5rem);" title="{$jobTitle}">
                    {$jobTitle}
                </h2>
                <p class="text-sm text-gray-600 mb-1 company-name">{$companyName}</p>
                <p class="text-sm text-gray-500 mb-1 location">{$location}</p>
                <p class="text-sm text-indigo-500 font-semibold mb-1 salary">{$salary} $/hr</p>
                <p class="text-sm text-gray-500 mb-1 hours">{$hours} heures/semaine</p>
                <p class="text-sm text-gray-600 flex-grow mb-6">{$shortDescription}</p> <!-- Ajout de la marge inférieure plus grande -->
            </div>
            <div class="flex justify-center w-full absolute bottom-0 left-0 p-6 pt-10"> <!-- Augmentation du padding-top ici -->
                <button onclick="loadDetails('{$offerLink}')" class="details-button bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600 transition duration-200 w-full">
                    Détails
                </button>
            </div>
        </div>

        
HTML;
}

$content .= <<<HTML
        </div>
    </div>
</div>

<script>
    function filterOffers() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const searchCriteria = document.getElementById('searchCriteria').value;
        const offers = document.querySelectorAll('.job-offer');

        offers.forEach(offer => {
            let textToSearch = '';
            let showOffer = false;

            if (searchInput === '' || searchCriteria === '') {
                showOffer = true;
            } else  {
            switch (searchCriteria) {
                case 'job-title':
                    textToSearch = offer.querySelector('.job-title')?.textContent.toLowerCase() || '';
                    showOffer = textToSearch.includes(searchInput);
                    break;
                case 'company-name':
                    textToSearch = offer.querySelector('.company-name')?.textContent.toLowerCase() || '';
                    showOffer = textToSearch.includes(searchInput);
                    break;
                case 'location':
                    textToSearch = offer.querySelector('.location')?.textContent.toLowerCase() || '';
                    showOffer = textToSearch.includes(searchInput);
                    break;
                case 'salary':
                    const salaryValue = parseFloat(offer.querySelector('.salary')?.textContent.replace(/[^0-9.]/g, '') || 0);
                    const searchValue = parseFloat(searchInput);
                    showOffer = !isNaN(salaryValue) && !isNaN(searchValue) && salaryValue >= searchValue;
                    break;
                case 'hours':
                    const hoursValue = parseFloat(offer.querySelector('.hours')?.textContent.replace(/[^0-9.]/g, '') || 0);
                    const searchValueHours = parseFloat(searchInput);
                    showOffer = !isNaN(hoursValue) && !isNaN(searchValueHours) && hoursValue >= searchValueHours;
                    break;
                default:
                    break;
            }
        }

            offer.style.display = showOffer ? 'block' : 'none';
        });
    }

    function loadDetails(url) {
        window.location.href = url;
    }

    document.getElementById('searchCriteria').addEventListener('change', () => {
        document.getElementById('searchInput').value = '';
        filterOffers();
    });
</script>
HTML;

include "Views/master.php";



