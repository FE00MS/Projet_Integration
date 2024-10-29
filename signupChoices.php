<?php

$content = <<<HTML
<div class="min-h-screen flex flex-col items-center">
    <h1 class="text-4xl font-bold mt-20">Choisissez le type de compte</h1>
    
    <div class="flex gap-6 mt-20">
        <a href="signupEmployee.php" class="btn btn-neutral w-64 h-20 text-lg flex items-center justify-center">
            S'inscrire comme Chercheur d'emploi
        </a>
        
        <a href="signupCompany.php" class="btn btn-neutral w-64 h-20 text-lg flex items-center justify-center">
            S'inscrire comme Entreprise
        </a>
    </div>
</div>
HTML;

include "Views/master.php";
