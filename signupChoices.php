<?php
include "Utilities/sessionManager.php";

if(!isset($_SESSION['currentLanguage']))
{
    $_SESSION['currentLanguage'] = "FR";
}
$lang = $_SESSION['currentLanguage'];

$jsonFile = ($lang === "FR") ? "fr.json" : "en.json";

$jsonData = file_get_contents($jsonFile);

$translations = json_decode($jsonData, true);
$content = <<<HTML
<div class="min-h-screen flex flex-col items-center">
    <h1 class="text-4xl font-bold mt-20">{$translations['accountType']}</h1>
    
    <div class="flex gap-6 mt-20">
        <a href="signupEmployee.php" class="btn btn-neutral w-64 h-20 text-lg flex items-center justify-center">
        {$translations['jobfinder']}
        </a>
        
        <a href="signupCompany.php" class="btn btn-neutral w-64 h-20 text-lg flex items-center justify-center">
        {$translations['companySignUp']}
        </a>
    </div>
</div>
HTML;

include "Views/master.php";
