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
if (isset($_GET['report']) && $_GET['report'] === 'success') {
    echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative animate-bounce" role="alert">
            <strong class="font-bold">Signalement envoyé !</strong>
            <span class="block sm:inline">Votre signalement a bien été pris en compte.</span>
          </div>';
}
if(!isset($_SESSION['currentLanguage']))
    {
        $_SESSION['currentLanguage'] = "FR";
    }
$lang = $_SESSION['currentLanguage'];

$jsonFile = ($lang === "FR") ? "fr.json" : "en.json";

$jsonData = file_get_contents($jsonFile);

$translations = json_decode($jsonData, true);

$content = <<<HTML
<div id="mainContainer" class="flex flex-col lg:flex-row h-screen justify-center bg-gray-100">
    <div id="detailsContainer" class="none lg:block w-full lg:w-1/2 px-4 sm:px-6 md:px-8 lg:px-12 max-w-screen-lg overflow-y-auto bg-rgb(240, 243, 248) shadow-lg rounded-lg mx-auto">
        <!-- Le contenu de la page de détails sera chargé ici -->
    </div>
    <div id="offersContainer" class="w-full lg:w-1/2 max-w-screen-lg mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <select id="searchCriteria" class="input input-bordered border-gray-300 rounded-lg p-2 shadow-sm focus:ring focus:ring-indigo-200" onchange="filterOffers()" aria-label="Select search criteria">
                <option value="job-title">{$translations['offer']}</option>
                <option value="company-name">{$translations['offerName']}</option>
                <option value="salary">{$translations['salary']}</option>
                <option value="location">{$translations['location']}</option>
                <option value="hours">Heures</option>
            </select>
            <input type="text" id="searchInput" class="input input-bordered border-gray-300 rounded-lg p-2 flex-grow shadow-sm focus:ring focus:ring-indigo-200" placeholder="Rechercher" oninput="filterOffers()" aria-label="Search input" />
            <button class="p-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors duration-200 shadow-md focus:ring focus:ring-indigo-200" aria-label="Search">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-5 w-5">
                    <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0a3.5 a3.5 0 0 1-7 0Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-6">{$translations['jobOffers']}</h1>

        <div id="offersGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12">
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
    <div class="card bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-transform duration-200 job-offer relative hover:scale-105 w-full sm:w-[300px] h-[400px] p-[2rem] border-2 border-[#c3c6ce] transition-all duration-500 ease-out overflow-visible">
        <div class="ponderation absolute bg-red-500 text-white rounded-full h-8 w-8 flex items-center justify-center text-xs font-bold shadow-md" style="top: -1rem; right: -1rem;">
            <p>{$ponderation}</p>
        </div>
        <div class="p-5">
            <h2 class="text-2xl font-semibold text-gray-800 job-title">{$jobTitle}</h2>
            <p class="text-sm font-medium text-gray-500 mt-1 company-name">{$companyName}</p>
            <p class="text-sm text-gray-500 location mt-1">{$location}</p>
            <p class="text-sm text-indigo-500 salary font-semibold mt-1">{$salary} $/hr</p>
            <p class="text-sm text-gray-500 hours mt-1">{$hours} heures/semaine</p>
            <p class="text-sm text-gray-600 mt-3 description">{$shortDescription}</p>
        </div>
        <button class="card-button" onclick="loadDetails('{$offerLink}')">
            Détails
        </button>
    </div>
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
    function loadDetails(url) {
        const detailsContainer = document.getElementById('detailsContainer');
        const mainContainer = document.getElementById('mainContainer');
        const offersContainer = document.getElementById('offersContainer');
        fetch(url)
            .then(response => response.text())
            .then(data => {
                detailsContainer.innerHTML = data;
                detailsContainer.classList.remove('hidden');
                mainContainer.classList.add('justify-between');
                offersContainer.classList.add('overflow-y-auto');
            })
            .catch(error => console.error('Error loading details:', error));
    }
    document.getElementById('searchCriteria').addEventListener('change', () => {
        document.getElementById('searchInput').value = '';
        filterOffers();
    });
</script>
<style>
    .card-button {
 transform: translate(-50%, 125%);
 width: 60%;
 border-radius: 1rem;
 border: none;
 background-color: #008bf8;
 color: #fff;
 font-size: 1rem;
 padding: .5rem 1rem;
 position: absolute;
 left: 50%;
 bottom: 0;
 opacity: 0;
 transition: 0.3s ease-out;
}
.card {
    width: 100%;
    height: auto;
    padding: 1rem;
    border: 2px solid #c3c6ce;
    border-radius: 1rem;
    background-color: #fff;
    box-shadow: 0 4px 18px 0 rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
}
.card:hover {
    border-color: #008bf8;
    box-shadow: 0 4px 18px 0 rgba(0, 0, 0, 0.25);
    transform: scale(1.05);
}
.card:hover .card-button {
    transform: translate(-50%, 50%);
    opacity: 1;
}
.card .p-5 {
    padding: 1rem;
}
.card .job-title {
    font-size: 1.5rem;
}
.card .company-name, .card .location, .card .salary, .card .hours, .card .description {
    font-size: 0.875rem;
}
@media (max-width: 1024px) {
    #mainContainer {
        flex-direction: column;
    }
    #detailsContainer, #offersContainer {
        width: 100%;
        max-width: none;
        padding: 1rem;
    }
}
@media (max-width: 768px) {
    .card {
        width: 100%;
        height: auto;
        padding: 1rem;
    }
    .card .p-5 {
        padding: 0.75rem;
    }
    .card .job-title {
        font-size: 1.25rem;
    }
    .card .company-name, .card .location, .card .salary, .card .hours, .card .description {
        font-size: 0.75rem;
    }
    .card-button {
        width: 80%;
        font-size: 0.875rem;
    }
}
@media (max-width: 480px) {
    .card {
        padding: 0.5rem;
    }
    .card .p-5 {
        padding: 0.5rem;
    }
    .card .job-title {
        font-size: 1rem;
    }
    .card .company-name, .card .location, .card .salary, .card .hours, .card .description {
        font-size: 0.625rem;
    }
    .card-button {
        width: 100%;
        font-size: 0.75rem;
    }
}
#mainContainer {
    background-color: #f0f4f8;
}
.none {
    display: none;
}
.justify-between .none {
    display: block;
}
@media (max-width: 1024px) {
    #mainContainer {
        flex-direction: column;
    }
    #detailsContainer, #offersContainer {
        width: 100%;
        max-width: none;
        padding: 1rem;
    }
}
@media (max-width: 768px) {
    .card {
        width: 100%;
        height: auto;
        padding: 1rem;
    }
    .card-button {
        width: 80%;
        font-size: 0.875rem;
    }
}
@media (max-width: 480px) {
    .card {
        padding: 0.5rem;
    }
    .card-button {
        width: 100%;
        font-size: 0.75rem;
    }
}
</style>
HTML;

include "Views/master.php";