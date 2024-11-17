<?php 

$content= <<<HTML

    <div class="max-w-5xl mx-auto p-8 bg-white shadow-md rounded-lg mt-8">

        <!-- Title -->
        <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">À propos du Projet</h1>

        <!-- Video Section -->
        <div class="flex justify-center mb-8">
            <video controls class="w-full max-w-3xl rounded-lg shadow-lg">
                <source src="votre_video.mp4" type="video/mp4">
                Votre navigateur ne supporte pas la vidéo.
            </video>
        </div>

        <!-- Project Description -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">Description du Projet</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                La Boussole à Emploi est une plateforme web innovante qui vise à optimiser la recherche d'emploi pour les candidats et les recruteurs. Grâce à un algorithme sophistiqué, la plateforme permet aux employeurs de trouver les candidats idéaux en se basant sur des critères multiples et spécifiques, et aux chercheurs d'emploi de découvrir les opportunités qui correspondent vraiment à leurs compétences et aspirations. L'objectif principal de La Boussole à Emploi est de rendre la recherche d'emploi plus efficace et pertinente, en facilitant la mise en relation entre les deux parties.
                Parmi les principales fonctionnalités, on trouve :
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
                <li>1: </li>
                <li>2:]</li>
                <li>3:</li>
                <li>4:</li>
            </ul>
        </div>

        <!-- Screenshots Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">Captures d'écran du Projet</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <img src="screenshot1.png" alt="Screenshot 1" class="w-full rounded-lg shadow-md hover:shadow-lg">
                <img src="screenshot2.png" alt="Screenshot 2" class="w-full rounded-lg shadow-md hover:shadow-lg">
                <img src="screenshot3.png" alt="Screenshot 3" class="w-full rounded-lg shadow-md hover:shadow-lg">
            </div>
        </div>

        <!-- Team Presentation (Optional) -->
        <div class="mt-12">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">Présentation de l'Équipe</h2>
            <div class="space-y-8">
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800">Jacob Lebel-Frenette</h3>
                    <p class="text-gray-600">Brève biographie et rôle dans le projet.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800">Loic Lompo</h3>
                    <p class="text-gray-600">Brève biographie et rôle dans le projet.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800">Mathieu Roy</h3>
                    <p class="text-gray-600">Brève biographie et rôle dans le projet.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-gray-800">Dereck Desjardins</h3>
                    <p class="text-gray-600">Brève biographie et rôle dans le projet.</p>
                </div>
            </div>
        </div>
    </div>
HTML;
include 'Views/master.php';