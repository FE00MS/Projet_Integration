<?php 
require_once 'Models/employee.php';  
require_once 'Models/offer.php';  
require_once 'Models/company.php';     
require_once 'BD/BD.php';            
include 'Utilities/sessionManager.php';  

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

if(!isset($_SESSION['currentLanguage'])) {
    $_SESSION['currentLanguage'] = "FR";
}
$lang = $_SESSION['currentLanguage'];

$jsonFile = ($lang === "FR") ? "fr.json" : "en.json";

$jsonData = file_get_contents($jsonFile);

$translations = json_decode($jsonData, true);

$employeeId = $_SESSION['currentUser']['Id'];

$employeeModel = new Employee();
$applications = $employeeModel->getApplications($employeeId); 
$companyModel = new Company();
$offerModel = new Offer();

$content = <<<HTML
<div class="px-4 sm:px-6 md:px-8 lg:px-12 max-w-screen-lg mx-auto mt-6">
    <h1 class="text-3xl font-bold mb-6">{$translations['applicationTitle']}</h1>
HTML;

if (!$applications) {
    $content .= <<<HTML
    <p class="text-lg text-gray-700">{$translations['noApplications']}</p>
HTML;
} else {
    $content .= <<<HTML
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
HTML;

    foreach ($applications as $application) {
        $offer = $offerModel->GetOffer($application['IdOffer']);

        if ($offer) {
            $jobTitle = htmlspecialchars($offer['Job']);
            $location = htmlspecialchars($offer['Location']);
            $salary = htmlspecialchars($offer['Salary']);
            $hours = htmlspecialchars($offer['Hours']);
            $shortDescription = htmlspecialchars(substr($offer['Description'], 0, 100)) . '...';
            $offerId = $offer['Id'];
            $company = $companyModel->GetCompanyById($offer['IdC']); 

            if ($company) {
                $companyName = htmlspecialchars($company['CName']); 

                $content .= <<<HTML
                <div class="card bg-white shadow-xl rounded-lg border border-gray-200 hover:bg-gray-50 transition-all duration-300 ease-in-out">
                    <div class="card-body">
                        <h2 class="card-title text-xl font-semibold text-gray-800 mb-2">{$jobTitle}</h2>
                        <p class="text-sm font-medium text-gray-500 mb-1">
                            <span class="text-gray-700 font-semibold">{$translations['company']}:</span> {$companyName}
                        </p>
                        <p class="text-sm font-medium text-gray-500 mb-1">
                            <span class="text-gray-700 font-semibold">{$translations['location']}:</span> {$location}
                        </p>
                        <p class="text-sm font-medium text-gray-500 mb-1">
                            <span class="text-gray-700 font-semibold">{$translations['salary']}:</span> {$salary} $/hr
                        </p>
                        <p class="text-sm font-medium text-gray-500 mb-1">
                            <span class="text-gray-700 font-semibold">{$translations['hours']}:</span> {$hours} hours/week
                        </p>
                        <p class="text-sm text-gray-600 mt-3">{$shortDescription}</p>

                        <div class="card-actions mt-4 flex justify-between">
                            <button onclick="removeModal.showModal()" class="m-auto btn btn-error btn-sm">{$translations['removeApplication']}</button>
                        </div>
                    </div>
                </div>
HTML;
            }
        }
    }

    $content .= <<<HTML
        </div>
    </div>
HTML;
}

if ($applications) {
    $content .= <<<HTML
        <dialog id="removeModal" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box">
                <h3 class="text-lg font-bold">{$translations['confirmApplication']}</h3>
                <p class="py-4">{$translations['sureApplication']}</p>
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn">{$translations['cancel']}</button>
                    </form>

                    <form action="removeApplication.php" method="POST">
                        <input type="hidden" name="empId" value="{$employeeId}">
                        <input type="hidden" name="offerId" value="{$offerId}">
                        <button type="submit" class="btn btn-error">{$translations['confirm']}</button>
                    </form>
                </div>
            </div>
        </dialog>
    HTML;
}

include "Views/master.php";
?>