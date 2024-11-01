<?php
    $isLoggedIn = isset($_SESSION['currentUser']);
    $accountType = $_SESSION["accountType"] ?? null;

    if($accountType === "company"){
        $header = <<< HTML
            <div class="navbar bg-base-100 w-full sticky top-0 shadow-md z-50 mb-6">
                <div class="navbar-start">
                    <div class="dropdown">
                        <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                            </svg>
                        </div>
                        <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                            <li><a>Page d'acceuil</a></li>
                            <li><a>Mes offres</a></li>
                            <li><a href="createOffer.php">Créer une offre</a></li>
                            <li><a href="myCandidates.php">Mes candidats</a></li>
                        </ul>
                    </div>
                    <a href="homepage.php" class="btn btn-ghost text-xl flex items-center gap-2">
                        <img src="images/compass.png" alt="Compass Icon" class="h-6 w-6">
                        La boussole à emploi
                    </a>
                </div>
                <div class="navbar-center hidden lg:flex">
        HTML;
    } else {
        $header = <<< HTML
            <div class="navbar bg-base-100 w-full sticky top-0 shadow-md z-50 mb-6">
                <div class="navbar-start">
                    <div class="dropdown">
                        <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                            </svg>
                        </div>
                        <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                            <li><a>Page d'acceuil</a></li>
                            <li><a href="myApplications.php">Mes applications</a></li>
                            <li><a>A propos</a></li>
                        </ul>
                    </div>
                    <a href="homepage.php" class="btn btn-ghost text-xl flex items-center gap-2">
                        <img src="images/compass.png" alt="Compass Icon" class="h-6 w-6">
                        La boussole à emploi
                    </a>
                </div>
                <div class="navbar-center hidden lg:flex">
        HTML;
    }

    //Affichage milieu
    if ($accountType === "company") {
        $header .= <<<HTML
        <ul class="menu menu-horizontal px-1">
                    <li><a href="myOffers.php">Mes offres</a></li>
                    <li><a href="createOffer.php">Créer une offre</a></li>
                    <li><a href="myCandidates.php">Mes candidats</a></li>
                </ul>
            </div>
            <div class="navbar-end flex items-center gap-4">
        HTML;
    }elseif ($accountType === "employee") {
        $header .= <<<HTML
        <ul class="menu menu-horizontal px-1">
                    <li><a href="homepage.php">Page d'accueil</a></li>
                    <li><a href="myApplications.php">Mes applications</a></li>
                    <li><a>A propos</a></li>
                </ul>
            </div>
            <div class="navbar-end flex items-center gap-4">
        HTML;
    }elseif ($accountType === "admin")  {
        $header = <<<HTML
            <div class="navbar bg-base-100 w-full sticky top-0 shadow-md z-50">
                <div class="navbar-start">
                    <a href="adminpage.php" class="btn btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>      
                    </a>
                </div>
            </div>
        HTML;
    }else{
        $header .= <<<HTML
        <ul class="menu menu-horizontal px-1">
                    <li><a href="homepage.php">Page d'accueil</a></li>
                    <li><a href="myApplications.php">Mes applications</a></li>
                    <li><a>A propos</a></li>
                </ul>
            </div>
            <div class="navbar-end flex items-center gap-4">
        HTML;
    }

    if ($isLoggedIn && $accountType != "admin") {
            $header .= <<<HTML
                    <a href="profile.php" class="btn btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>      
                    </a>
                    <a href="#" class="btn btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3a2.032 2.032 0 01-.595 1.595L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </a>
                    <a href="#" class="btn btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>      
                    </a>
                </div>
            </div>
    HTML;
        
    }elseif(!$isLoggedIn){
        $header .= <<<HTML
                <a href="signupChoices.php" class="btn">S'inscrire</a>
                <a href="login.php" class="btn btn-neutral">Se connecter</a>
            </div>
        </div>
    HTML;
    }