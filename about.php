<?php 
include 'Utilities/sessionManager.php';
$content= <<<HTML

    <div class="max-w-5xl mx-auto p-8 bg-white shadow-md rounded-lg mt-8">

      
    <!-- Title -->
    <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">À propos du Projet</h1>

<!-- Video Section -->
<div class="flex justify-center mb-8">
    <video controls class="w-full max-w-3xl rounded-lg shadow-lg">
        <source src="images/video.mkv" type="video/mp4">
       
    </video>
</div>

<!-- Project Description -->
<div class="mb-12">
    <h2 class="text-2xl font-semibold mb-4 text-gray-700">Description du Projet</h2>
    <p class="text-gray-600 leading-relaxed mb-4">
    La Boussole à Emploi est une plateforme web innovante qui vise à optimiser la recherche d'emploi pour les candidats et les recruteurs. Grâce à un algorithme sophistiqué, la plateforme permet aux employeurs de trouver les candidats idéaux en se basant sur des critères multiples et spécifiques, et aux chercheurs d'emploi de découvrir les opportunités qui correspondent vraiment à leurs compétences et aspirations. L'objectif principal de La Boussole à Emploi est de rendre la recherche d'emploi plus efficace et pertinente, en facilitant la mise en relation entre les deux parties.
</p>
</div>


<div class="p-6 bg-gray-50 rounded-lg shadow-lg mb-12">
    <h2 class="text-3xl font-semibold text-gray-800 mb-6">Fonctionnalités principales</h2>
    <ul class="space-y-4">
        <li class="flex items-center">
            <span class="text-blue-500 mr-4 text-xl"><i class="fas fa-user-circle"></i></span>
            <p><strong>Création de profil :</strong> Les employeurs et chercheurs d'emploi peuvent personnaliser leur espace pour mieux refléter leurs besoins et compétences.</p>
        </li>
        <li class="flex items-center">
            <span class="text-blue-500 mr-4 text-xl"><i class="fas fa-bell"></i></span>
            <p><strong>Notifications personnalisées :</strong> Restez informé des opportunités qui vous correspondent le mieux.</p>
        </li>
        <li class="flex items-center">
            <span class="text-blue-500 mr-4 text-xl"><i class="fas fa-search"></i></span>
            <p><strong>Système de "match" :</strong> Simplifiez votre recherche d'emploi grâce à un algorithme intelligent qui identifie les meilleures offres pour vous.</p>
        </li>
        <li class="flex items-center">
            <span class="text-blue-500 mr-4 text-xl"><i class="fas fa-clipboard-list"></i></span>
            <p><strong>Suivi des candidatures :</strong> Gardez une trace de vos candidatures pour rester organisé et maximiser vos chances de succès.</p>
        </li>
        <li class="flex items-center">
            <span class="text-blue-500 mr-4 text-xl"><i class="fas fa-edit"></i></span>
            <p><strong>Gestion des offres :</strong> Créez, modifiez et publiez des offres rapidement et facilement.</p>
        </li>
        <li class="flex items-center">
            <span class="text-blue-500 mr-4 text-xl"><i class="fas fa-sticky-note"></i></span>
            <p><strong>Personnalisation et bloc-notes :</strong> Adaptez votre profil et utilisez un bloc-notes intégré pour conserver des informations importantes.</p>
        </li>
        <li class="flex items-center">
            <span class="text-blue-500 mr-4 text-xl"><i class="fas fa-filter"></i></span>
            <p><strong>Recherche avancée :</strong> Trouvez l'emploi idéal grâce à des filtres détaillés (intitulé du poste, entreprise, salaire, localisation, taux horaire).</p>
        </li>
        <li class="flex items-center">
            <span class="text-blue-500 mr-4 text-xl"><i class="fas fa-language"></i></span>
            <p><strong>Multilingue :</strong> Une plateforme entièrement disponible en deux langues pour une accessibilité maximale.</p>
        </li>
        <li class="flex items-center">
            <span class="text-blue-500 mr-4 text-xl"><i class="fas fa-shield-alt"></i></span>
            <p><strong>Modération efficace :</strong> Un système intégré pour signaler et gérer les contenus ou utilisateurs non conformes.</p>
        </li>
        <li class="flex items-center">
            <span class="text-blue-500 mr-4 text-xl"><i class="fas fa-star"></i></span>
            <p><strong>Évaluation des offres :</strong> Notez les offres d'emploi pour aider les autres utilisateurs à faire des choix éclairés.</p>
        </li>
    </ul>
</div>



        <!-- Screenshots Section -->
        <div class="mb-12">
        <h2 class="text-2xl font-semibold mb-6 text-gray-700">Captures d'écran du Projet</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="images/S1.png" data-lightbox="project-screenshots" data-title="Page d'accueil">
                <img src="images/S1.png" alt="Page d'accueil" class="w-full rounded-lg shadow-md hover:shadow-lg">
            </a>
            <a href="images/S2.png" data-lightbox="project-screenshots" data-title="Détails d'une offre">
                <img src="images/S2.png" alt="Détails d'une offre" class="w-full rounded-lg shadow-md hover:shadow-lg">
            </a>
            <a href="images/S3.png" data-lightbox="project-screenshots" data-title="Création d'une offre">
                <img src="images/S3.png" alt="Création d'une offre" class="w-full rounded-lg shadow-md hover:shadow-lg">
            </a>
        </div>
    </div>

        <!-- Team Presentation (Optional) -->
        <div class="mt-12">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">Présentation de l'Équipe</h2>
            <div class="space-y-8">
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800">Jacob Lebel-Frenette</h3>
                    <p class="text-gray-600">Rôle: PHP, HTML, CSS, Javascript </p>
                    <a class="lien" href="https://github.com/jacoblf314" alt="Lien Github" target="_blank"><u>Lien vers ma page Github</u></a>
                    <br>
                    <a class="lien" href="https://www.linkedin.com/in/jacob-lebel-frenette-851b15324/" alt="Lien LinkedIn" target="_blank"><u>Lien vers ma page LinkedIn</u></a>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800">Loïc Lompo</h3>
                    <p class="text-gray-600">Rôle: SQL Server, PHP </p>
                    <a class="lien" href="https://github.com/FE00MS" alt="Lien Github" target="_blank"><u>Lien vers ma page Github</u></a>
                </div>
                
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800">Mathieu Roy</h3>
                    <p class="text-gray-600">Rôle: HTML, PHP, Javascript, CSS </p>
                    <a class="lien" href="https://github.com/M0th1euR0y" alt="Lien Github" target="_blank"><u>Lien vers ma page Github</u></a>
                    <br>
                    <a class="lien" href="https://ca.linkedin.com/in/mathieu-roy-25111233b" alt="Lien LinkedIn" target="_blank"><u>Lien vers ma page LinkedIn</u></a>
                </div>
                
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800">Dereck Desjardins</h3>
                    <p class="text-gray-600">Rôle: SQL Server, PHP, JavaScript</p>
                    <a class="lien" href="https://github.com/Dereck-Desjardins" alt="Lien Github" target="_blank"><u>Lien vers ma page Github</u></a>
                </div>
            </div>
        </div>
    </div>
HTML;
include 'Views/master.php';
?>

<style>
    .lien{
        color: blue;
    }
</style>
