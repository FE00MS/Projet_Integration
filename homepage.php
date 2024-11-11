<?php
require_once 'Models/company.php';
require_once 'BD/BD.php';
include 'Utilities/sessionManager.php';  

$offers = new Database();
$allOffers;
$company = new Company();

if(!isset($_SESSION['currentUser'])){
    $allOffers = $offers->GetAllOffers('comp');
}else{
    $allOffers = $offers->GetAllOffers('pond');
}

$content = <<<HTML
<div class="px-4 sm:px-6 md:px-8 lg:px-12 max-w-screen-lg mx-auto">
    <div class="flex items-center gap-2 mb-8">
        <select id="searchCriteria" class="input input-bordered" onchange="filterOffers()">
            <option value="job-title">Offre</option>
            <option value="company-name">Nom</option>
            <option value="salary">Salaire</option>
            <option value="location">Localisation</option>
        </select>
        <input type="text" id="searchInput" class="input input-bordered grow" placeholder="Search" oninput="filterOffers()" />
        <button>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
                <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0a3.5 a3.5 0 0 1-7 0Z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <h1 class="text-2xl font-bold mb-4">Offres d'emploi</h1>

    <div id="offersContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
HTML;

foreach ($allOffers as $offer) {
    if(isset($offer['Ponderation'])){
        $ponderation = htmlspecialchars($offer['Ponderation']);
    }
    else{
        $ponderation = 'Na';
    }
    
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
        <a href="{$offerLink}" class="card bg-base-100 shadow-lg rounded-lg overflow-hidden hover:shadow-xl hover:scale-105 transition-transform duration-200 job-offer relative">
            <div class="p-4">
                <div class="absolute top-2 right-2 bg-red-500 text-white rounded-full h-10 w-10 flex items-center justify-center text-sm">
                    <p>{$ponderation}</p>
                </div>
                <h2 class="text-xl font-semibold job-title">{$jobTitle}</h2>
                <p class="font-medium text-gray-500 company-name">{$companyName}</p>
                <p class="text-gray-500 location">{$location}</p>
                <p class="text-gray-500 salary">{$salary} $/hr</p>
                <p class="text-gray-500 hours">{$hours} hours/week</p>
                <p class="text-gray-700 mt-2 description">{$shortDescription}</p>
            </div>
        </a>
HTML;
}

$content .= <<<HTML
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
            } else if (searchCriteria === 'job-title') {
                textToSearch = offer.querySelector('.job-title').textContent.toLowerCase();
                showOffer = textToSearch.startsWith(searchInput);
            } else if (searchCriteria === 'salary') {
                textToSearch = offer.querySelector('.salary').textContent.toLowerCase();
                const salaryValue = parseFloat(textToSearch.replace(/[^0-9.]/g, ''));
                const searchValue = parseFloat(searchInput);
                showOffer = !isNaN(salaryValue) && !isNaN(searchValue) && salaryValue >= searchValue;
            } else if (searchCriteria === 'location') {
                textToSearch = offer.querySelector('.location').textContent.toLowerCase();
                showOffer = textToSearch.startsWith(searchInput);
            } else if (searchCriteria === 'hours') {
                textToSearch = offer.querySelector('.hours').textContent.toLowerCase();
                const hoursValue = parseFloat(textToSearch.replace(/[^0-9.]/g, ''));
                const searchValue = parseFloat(searchInput);
                showOffer = !isNaN(hoursValue) && !isNaN(searchValue) && hoursValue >= searchValue;
            } else if (searchCriteria === 'company-name') {
                textToSearch = offer.querySelector('.company-name').textContent.toLowerCase();
                showOffer = textToSearch.startsWith(searchInput);
            }

            if (showOffer) {
                offer.style.display = 'block';
            } else {
                offer.style.display = 'none';
            }
        });
    }

    document.getElementById('searchCriteria').addEventListener('change', () => {
        document.getElementById('searchInput').value = '';
        filterOffers();
    });
</script>
HTML;

include "Views/master.php";