<?php
require_once 'Models/company.php';
require_once 'Models/offer.php';
require_once 'Models/employee.php';
require_once 'Models/Editprofile.php';
include 'Utilities/sessionManager.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

if(!isset($_SESSION['currentLanguage']))
    {
        $_SESSION['currentLanguage'] = "FR";
    }
$lang = $_SESSION['currentLanguage'];

$jsonFile = ($lang === "FR") ? "fr.json" : "en.json";

$jsonData = file_get_contents($jsonFile);

$translations = json_decode($jsonData, true);

$companyId = $_SESSION['currentUser']['Id'];
$offersModel = new Offer();
$employeeModel = new Employee();
$editProfile = new Profile();
$defaultInv = $_SESSION['currentUser']['CustomInvite'];

function getExperiencesForProfile($userId) {
    $profile = new Profile();
    $experienceData = $profile->GetAllExperienceEmp($userId);

    $experiences = [];
    $formations = [];
    if ($experienceData != null) {
        foreach ($experienceData as $exp) {
            switch ($exp['TypeExp']) {
                case 'For':
                    $formations[] = $exp;
                    break;
                case 'Exp':
                    $experiences[] = $exp;
                    break;
               
            }
        }
    }

    return [
        'experiences' => $experiences,
        'formations' => $formations
    ];
}

$offers = $offersModel->GetOfferByCompagny($companyId);

$content = <<<HTML
<div class="px-4 sm:px-6 md:px-8 lg:px-12 max-w-screen-lg mx-auto">
    <h1 class="text-3xl font-bold mb-6">{$translations['headerCandidate']}</h1>
HTML;

if (empty($offers)) {
    $content .= <<<HTML
    <p class="text-lg text-gray-700">{$translations['noOffers']}</p>
HTML;
} else {
    foreach ($offers as $offer) {
        $offerId = htmlspecialchars($offer['Id']);
        $jobTitle = htmlspecialchars($offer['Job']);
        $location = htmlspecialchars($offer['Location']);
        $salary = htmlspecialchars($offer['Salary']);
        $hours = htmlspecialchars($offer['Hours']);

        $content .= <<<HTML
        <div class="mb-8  test shadow-lg rounded-lg border-2 border-gray-200 mb-6 p-4">
            <h2 class="text-2xl font-semibold">{$jobTitle}</h2>
            <p class="text-lg text-gray-600">{$translations['location']}: {$location} | {$translations['salary']}: {$salary} $/hr | {$translations['hours']}: {$hours} hours/week</p>
            <h3 class="text-xl font-bold mt-4 mb-2">{$translations['candidates']}</h3>
HTML;

        $offerCandidates = $employeeModel->getCandidates($offer['Id']);

        if (empty($offerCandidates)) {
            $content .= <<<HTML
            <p class="text-lg text-gray-700">{$translations['noCandidates']}</p>
HTML;
        } else {
            foreach ($offerCandidates as $application) {
                $candidateName = htmlspecialchars($application['CandidateName']);
                $employeeId = htmlspecialchars($application['EmployeeId']);
                $candidateLastName = htmlspecialchars($application['CandidateLastName']);
                $candidateEmail = htmlspecialchars($application['CandidateEmail']);
               
                $data = getExperiencesForProfile($employeeId);
                            
                $content .= <<<HTML
                <div class="card bg-white shadow-lg rounded-lg border border-gray-200 p-4 mb-6">
                    <div class="flex flex-wrap md:flex-nowrap">
                        <div class="w-full md:w-1/4 pr-4 mb-4 md:mb-0">
                            <h4 class="text-lg font-semibold">{$candidateName} {$candidateLastName}</h4>
                        </div>
            HTML;
            
                $content .= <<<HTML
                    <div class="w-full md:w-1/4 pr-4 mb-4 md:mb-0">
            HTML;
            
                if (!empty($data['experiences'])) {
                    $expCounter = 0;
                    
                    foreach ($data['experiences'] as $exp) {
                        if ($expCounter >= 2) {
                            break;
                        }
                        
                        $expTitle = htmlspecialchars($exp['Title']);
                        $expLocation = htmlspecialchars($exp['LocationName']);
            
                        $content .= <<<HTML
                            <p class="text-sm font-medium"><strong>{$expTitle}</strong></p>
                            <p class="text-sm text-gray-500">{$expLocation}</p>
            HTML;
                        
                        $expCounter++;
                    }
                } else {
                    $content .= "<p>{$translations['noExp']}</p>";
                }
            
                $content .= <<<HTML
                    </div>
            HTML;
            
                $content .= <<<HTML
                    <div class="w-full md:w-1/4 pr-4 mb-4 md:mb-0">
            HTML;
            
                if (!empty($data['formations'])) {
                    $formCounter = 0;
                    
                    foreach ($data['formations'] as $formation) {
                        if ($formCounter >= 2) {
                            break;
                        }
                        
                        $formTitle = htmlspecialchars($formation['Title']);
                        $formLocation = htmlspecialchars($formation['LocationName']);
            
                        $content .= <<<HTML
                            <p class="text-sm font-medium"><strong>{$formTitle}</strong></p>
                            <p class="text-sm text-gray-500">{$formLocation}</p>
            HTML;
                        
                        $formCounter++;
                    }
                } else {
                    $content .= "<p>{$translations['noForm']}</p>";
                }
            
                $content .= <<<HTML
                    </div>
            HTML;
            
                $content .= <<<HTML
                    <div class="w-full md:w-1/4 flex flex-col items-center justify-center space-y-2">
                        <button onclick="openMailModal('$candidateName $candidateLastName', '$candidateEmail', '$defaultInv', '$jobTitle', '$employeeId')" class="btn btn-primary w-full">{$translations['contactCandidate']}</button>                    
                        <button onclick="openRemoveModal('$employeeId', '$offerId')" class="btn btn-neutral w-full">{$translations['notInterested']}</button>
                    </div>
                </div>
            </div>
            HTML;
            }
        }

        $content .= <<<HTML
        </div>
HTML;
    }
}

$content .= <<<HTML
</div>
HTML;

if(!empty($offers)) {
    $content .= <<<HTML
    <dialog id="removeModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Confirmation de Suppression</h3>
            <p class="py-4">Êtes-vous sûr de vouloir retirer cette candidature ?</p>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Annuler</button>
                </form>

                <form action="notInterested.php" method="POST">
                    <input type="hidden" name="empId" id="empId" value="">
                    <input type="hidden" name="offerId" id="offerId" value="">
                    <button type="submit" class="btn btn-error">Confirmer</button>
                </form>
            </div>
        </div>
    </dialog>
HTML;
}


$content .= <<<HTML
    <dialog id="mailModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box">
            <h3 class="text-lg font-bold mb-4">Envoyer un Message à <span id="modalCandidateName"></span></h3>
            <form action="sendEmail.php" method="POST">
                <label class="block mb-2 font-semibold" id='poste'>Poste : <span id="posteValue"></span></label>

                <input type="hidden" name="jobTitle" id="jobTitle">
                <input type="hidden" name="employeeId" id="employeeId">
            
                <label class="block mb-2 font-semibold" for="content">Message</label>
                <textarea name="content" id="content" placeholder="Rédigez votre message ici..." class="textarea textarea-bordered w-full h-48 mb-4" required></textarea>

                <input type="hidden" name="email" id="modalCandidateEmail">

                <div class="modal-action">
                    <button class="btn" type="button" onclick="document.getElementById('mailModal').close()">Annuler</button>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </form>
        </div>
    </dialog>
    HTML;
include "Views/master.php";
?>
<script>
    function openMailModal(name, email, defaultInv, poste, idemp) {
        var posteLabel = document.getElementById('posteValue');

        document.getElementById('employeeId').value = idemp;
        document.getElementById('modalCandidateName').innerText = name;
        document.getElementById('modalCandidateEmail').value = email;
        document.getElementById('content').innerText = defaultInv;
        posteLabel.innerText = poste; 
        document.getElementById('jobTitle').value = poste;
        document.getElementById('mailModal').showModal();
    }
    function openRemoveModal(employeeId, offerId) {
        document.getElementById('empId').value = employeeId;
        document.getElementById('offerId').value = offerId;
        document.getElementById('removeModal').showModal();
    }
    
</script>
<style>
    .test{
        background-color: #E9EEF4;
    }
</style>