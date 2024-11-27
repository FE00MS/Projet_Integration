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
        $offerLink = "editOffer.php?id={$offerId}";
        $content .= <<<HTML
            <a href="editOffer.php?id={$offerId}" class="card bg-base-100 shadow-lg rounded-lg overflow-hidden hover:shadow-xl hover:scale-105 transition-transform duration-200 mb-6"> 
            <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition duration-200 p-6 flex flex-col justify-between h-full job-offer">
            <div class="relative flex-grow">
       
                <h2 class="text-xl font-semibold text-gray-800 mb-2 truncate job-title" style="max-width: calc(100% - 2.5rem);" title="{$jobTitle}">
                    {$jobTitle}
                </h2>
                <p class="text-sm text-gray-500 mb-1 location">{$location}</p>
                <p class="text-sm text-indigo-500 font-semibold mb-1 salary">{$salary} $/hr</p>
                <p class="text-sm text-gray-500 mb-1 hours">{$hours} heures/semaine</p>
                <p class="text-sm text-gray-600 flex-grow">{$shortDescription}</p>

            </div>
              <button onclick="loadDetails('{$offerLink}')" class="mt-4 bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600 transition duration-200">
                         Éditer
                     </button>
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
?>
<script>
    
    function loadDetails(url) {
        window.location.href = url;
    }
</script>