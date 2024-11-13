<?php
include 'Utilities/sessionManager.php';
if (!isset($_SESSION['currentUser'])) {
  header('Location: login.php');
  exit();
}
$content = <<<HTML
<nav class="w3-sidebar w3-bar-block w3-small w3-hide-small w3-center">
  <a href="#preparation" class="w3-bar-item w3-button w3-padding-large w3-black w3-hover-light-grey">
    <i class="fa fa-cogs w3-xxlarge"></i>
    <span>Préparation</span>
  </a>
  <a href="#cv" class="w3-bar-item w3-button w3-padding-large w3-black w3-hover-light-grey">
    <i class="fa fa-file w3-xxlarge"></i>
    <span>CV</span>
  </a>
  <a href="#pratique" class="w3-bar-item w3-button w3-padding-large w3-black w3-hover-light-grey">
    <i class="fa fa-eye w3-xxlarge"></i>
    <span>Pratique</span>
  </a>
  <a href="#attitude" class="w3-bar-item w3-button w3-padding-large w3-black w3-hover-light-grey">
    <i class="fa fa-smile w3-xxlarge"></i>
    <span>Attitude</span>
  </a>
  <a href="#contacts" class="w3-bar-item w3-button w3-padding-large w3-black w3-hover-light-grey">
    <i class="fa fa-address-book w3-xxlarge"></i>
    <span>Contacts</span>
  </a>
  <a href="#perfectionnement" class="w3-bar-item w3-button w3-padding-large w3-black w3-hover-light-grey">
    <i class="fa fa-graduation-cap w3-xxlarge"></i>
    <span>Perfectionnement</span>
  </a>
  <a href="#apres" class="w3-bar-item w3-button w3-padding-large w3-black w3-hover-light-grey">
    <i class="fa fa-check w3-xxlarge"></i>
    <span>Après</span>
  </a>
</nav>
<!-- Page Content -->
<div class="w3-padding-large" id="main">
    <div class="w3-content w3-justify w3-text-grey w3-padding-32" id="preparation">
        <h2 class="w3-text-black w3-fontsize-24 w3-padding-16">Préparation avant l'entrevue</h2>
        <hr style="width:200px" class="w3-opacity">
        
        <h3 class="w3-text-black w3-padding-16">Recherche sur l'entreprise :</h3>
        <p class="w3-padding-16">Connaissance approfondie : Apprends autant que possible sur l'entreprise avant ton entrevue. Consulte leur site web, lis leur section "À propos de nous", explore leurs services ou produits, 
            et découvre leurs valeurs et leur mission. Tu peux également consulter leur page LinkedIn et lire des articles récents à leur sujet.
        </p>
        
        <h3 class="w3-text-black w3-padding-16">Vision et culture :</h3>
        <p class="w3-padding-16">Découvre la culture de l'entreprise pour savoir si elle correspond à tes attentes. 
            Les avis sur des sites comme Glassdoor peuvent t'aider à avoir une idée de l'environnement de travail et des expériences des employés.
        </p>
        
        <h3 class="w3-text-black w3-padding-16">Actualités récentes :</h3>
        <p class="w3-padding-16">Informe-toi sur les récents succès, lancements de produits ou événements importants pour l'entreprise. Mentionner ces informations dans l'entrevue montre ton intérêt et ton engagement.
        </p>
        
        <h3 class="w3-text-black w3-padding-16">Préparation des réponses aux questions courantes :</h3>
        <p class="w3-padding-16">"Parlez-moi de vous" : Prépare une présentation succincte qui résume ton parcours, tes compétences principales et ce que tu cherches. Concentre-toi sur les points pertinents pour le poste. Par exemple : 
            "Je suis développeur web avec une spécialisation en PHP et Tailwind. J'ai travaillé sur des projets variés, allant de la création de sites e-commerce à des API complexes. J'aime relever des défis techniques et je recherche un poste où je pourrai continuer à développer mes compétences."
        </p>
        <p class="w3-padding-16">"Pourquoi voulez-vous travailler ici ?" : Explique pourquoi l'entreprise t'intéresse, en mentionnant leurs valeurs, leur culture d'innovation ou un projet spécifique sur lequel tu aimerais travailler.
        </p>
        <p class="w3-padding-16">"Quelles sont vos forces et vos faiblesses ?" : Mets en avant des forces pertinentes pour le poste (comme ton sens de l'organisation, ta capacité à résoudre des problèmes, ou tes compétences techniques). Pour la faiblesse, mentionne quelque chose que tu es en train d'améliorer, par exemple : 
            "J'ai tendance à me concentrer intensément sur les détails, mais j'apprends à mieux équilibrer mon temps pour respecter les délais tout en maintenant la qualité."
        </p>
        
        <h3 class="w3-text-black w3-padding-16">Préparation technique (pour les postes techniques) :</h3>
        <p class="w3-padding-16">Révise les compétences spécifiques demandées dans l'offre d'emploi (langages de programmation, outils, frameworks). Pratique des exercices de codage sur des plateformes comme LeetCode, HackerRank ou CodeSignal si l'entrevue comporte un test technique. Prépare-toi à parler de projets techniques passés et à expliquer tes choix de conception.
        </p>
    </div>
    
    <div class="w3-content w3-justify w3-text-grey w3-padding-32" id="cv">
        <h2 class="w3-text-black w3-fontsize-24 w3-padding-16">Créer un CV et un portfolio percutants</h2>
        <hr style="width:200px" class="w3-opacity">
        
        <h3 class="w3-text-black w3-padding-16">CV clair et précis :</h3>
        <p class="w3-padding-16">Mise en page professionnelle : Utilise une structure claire avec des sections bien définies : expérience, compétences, éducation et projets. Adapte ton CV : Personnalise ton CV pour chaque poste. Utilise des mots-clés de la description du poste pour te démarquer dans les systèmes de suivi des candidatures (ATS). Réalisations chiffrées : Indique tes réalisations avec des chiffres pour montrer ton impact. Par exemple : "Augmentation de l'efficacité du processus de traitement des données de 20 % grâce à l'automatisation en Python."
        </p>
        
        <h3 class="w3-text-black w3-padding-16">Portfolio en ligne (si pertinent) :</h3>
        <p class="w3-padding-16">Crée un portfolio en ligne pour montrer tes projets, surtout pour les emplois dans les domaines créatifs ou techniques. Utilise un site comme GitHub, Behance, ou un site web personnel. Montre des exemples de ton travail et ajoute une description de chaque projet, en expliquant les défis et les solutions.
        </p>
    </div>
    
    <div class="w3-content w3-justify w3-text-grey w3-padding-32" id="pratique">
        <h2 class="w3-text-black w3-fontsize-24 w3-padding-16">Pendant l'entrevue</h2>
        <hr style="width:200px" class="w3-opacity">
        
        <h3 class="w3-text-black w3-padding-16">Communication claire et confiante :</h3>
        <p class="w3-padding-16">Écoute active : Écoute attentivement les questions de l'intervieweur et prends une seconde pour formuler ta réponse. Ne coupe pas la parole et pose des questions pour clarifier si nécessaire. Structure tes réponses : Utilise la méthode STAR (Situation, Tâche, Action, Résultat) pour structurer tes réponses : Situation : Explique le contexte. Tâche : Décris le problème ou la tâche à accomplir. Action : Mentionne ce que tu as fait pour résoudre le problème. Résultat : Partage le résultat et ce que tu as appris.
        </p>
        
        <h3 class="w3-text-black w3-padding-16">Langage corporel positif :</h3>
        <p class="w3-padding-16">Maintiens un contact visuel avec l'intervieweur, souris et montre de l'enthousiasme. Une attitude ouverte et positive peut créer une bonne première impression. Adopte une posture droite et évite les mouvements nerveux (comme jouer avec tes cheveux ou toucher ton visage).
        </p>
        
        <h3 class="w3-text-black w3-padding-16">Poser des questions à l'intervieweur :</h3>
        <p class="w3-padding-16">Prépare des questions à poser à la fin de l'entrevue pour montrer ton intérêt. Par exemple : "Comment décririez-vous la culture de l'entreprise ?" "Quels sont les principaux défis auxquels l'équipe est confrontée actuellement ?" "Quelles sont les opportunités de développement professionnel pour ce poste ?" Ces questions montrent que tu es réfléchi et intéressé par l'entreprise, pas seulement par le poste.
        </p>
    </div>
    
    <div class="w3-content w3-justify w3-text-grey w3-padding-32" id="attitude">
        <h2 class="w3-text-black w3-fontsize-24 w3-padding-16">Utilisation du réseau professionnel</h2>
        <hr style="width:200px" class="w3-opacity">
        
        <h3 class="w3-text-black w3-padding-16">Développer son réseau :</h3>
        <p class="w3-padding-16">LinkedIn : Améliore ton profil LinkedIn, connecte-toi avec des professionnels de ton secteur, et interagis avec des publications pour augmenter ta visibilité. Participer à des événements : Assiste à des meetups, conférences, ou webinaires dans ton domaine. Cela te permet de rencontrer des gens et de te créer des opportunités de carrière. Groupes et forums : Rejoins des groupes professionnels sur LinkedIn ou des forums spécifiques à ton domaine (par exemple, Stack Overflow pour les développeurs). Participe aux discussions et partage tes connaissances.
        </p>
        
        <h3 class="w3-text-black w3-padding-16">Demander des recommandations :</h3>
        <p class="w3-padding-16">Si tu connais quelqu'un dans l'entreprise où tu postules, demande une recommandation. Les recommandations internes peuvent augmenter tes chances d'être retenu pour une entrevue. Sur LinkedIn, demande des recommandations à tes anciens collègues, managers ou enseignants. Les recommandations renforcent la crédibilité de ton profil.
        </p>
    </div>
    
    <div class="w3-content w3-justify w3-text-grey w3-padding-32" id="contacts">
        <h2 class="w3-text-black w3-fontsize-24 w3-padding-16">Améliorer ses compétences en continu</h2>
        <hr style="width:200px" class="w3-opacity">
        
        <h3 class="w3-text-black w3-padding-16">Formations et certifications :</h3>
        <p class="w3-padding-16">Investis dans des cours en ligne pour améliorer tes compétences (Udemy, Coursera, OpenClassrooms). Par exemple, tu pourrais suivre des formations sur des langages de programmation ou des outils spécifiques utilisés dans ton secteur. Obtiens des certifications pour des compétences spécifiques (comme Google Analytics, Microsoft Excel, AWS, etc.) pour te démarquer.
        </p>
        
        <h3 class="w3-text-black w3-padding-16">Projets personnels :</h3>
        <p class="w3-padding-16">Si tu es développeur, crée des projets personnels ou contribue à des projets open-source sur GitHub. Pour d'autres secteurs, pense à des projets qui démontrent tes compétences, comme des études de cas ou des travaux de bénévolat.
        </p>
    </div>
    
    <div class="w3-content w3-justify w3-text-grey w3-padding-32" id="perfectionnement">
        <h2 class="w3-text-black w3-fontsize-24 w3-padding-16">Réussir les tests techniques ou les exercices pratiques</h2>
        <hr style="width:200px" class="w3-opacity">
        
        <h3 class="w3-text-black w3-padding-16">Préparation pour les tests techniques :</h3>
        <p class="w3-padding-16">Pratique les types de tests que tu pourrais rencontrer (exercices de codage, études de cas, tests psychométriques). Fais des simulations d'entrevue technique avec des amis ou utilise des sites comme Pramp pour des interviews en live.
        </p>
        
        <h3 class="w3-text-black w3-padding-16">Démonstration de tes compétences :</h3>
        <p class="w3-padding-16">Sois prêt à expliquer ton raisonnement et tes choix lors des tests techniques. Par exemple, explique pourquoi tu as choisi une approche spécifique ou comment tu as optimisé ton code. Reste calme et concentré. Si tu fais une erreur, corrige-la et explique ce que tu as appris.
        </p>
    </div>
    
    <div class="w3-content w3-justify w3-text-grey w3-padding-32" id="apres">
        <h2 class="w3-text-black w3-fontsize-24 w3-padding-16">Suivi après l'entrevue</h2>
        <hr style="width:200px" class="w3-opacity">
        
        <h3 class="w3-text-black w3-padding-16">E-mail de remerciement :</h3>
        <p class="w3-padding-16">Envoie un e-mail de remerciement dans les 24 heures suivant l'entrevue. Remercie l'intervieweur pour son temps, mentionne un point positif de l'entretien, et réaffirme ton intérêt pour le poste. "Merci pour l'opportunité de discuter aujourd'hui. J'ai particulièrement apprécié en apprendre plus sur vos projets et je suis convaincu que mes compétences en [compétence spécifique] peuvent apporter une réelle valeur à votre équipe."
        </p>
        <p class="w3-padding-16">Si tu n'as pas de nouvelles après une semaine, envoie un e-mail de suivi pour montrer ton intérêt continu.
        </p>
    </div>
</div>

HTML;
include "Views/master.php";
?>

<style>
body, h1,h2,h3,h4,h5,h6 {font-family: "Montserrat", sans-serif;}
.w3-row-padding img {margin-bottom: 120px; }
h1{font-size: 20px;}
/* Set the width of the sidebar to 120px */
.w3-sidebar {width: 120px; height: 295px; background: #222; margin-top: 15%;}
/* Add a left margin to the "page content" that matches the width of the sidebar (120px) */
#main {margin-left: 120px}
/* Remove margins from "page content" on small screens */
@media only screen and (max-width: 600px) {#main {margin-left: 0}}
/* Add smooth scrolling */
html {
  scroll-behavior: smooth;
}
hr {
  border: 0;
  height: 1px;
  background: #ddd;
  margin: 20px 0;
}
/* Custom style for h2 */
h2 {
  font-size: 24px; /* Taille de la police plus grande */
  background-color: #d0d0d0; /* Couleur de fond plus foncée */
  padding: 10px; /* Ajoute du padding */
  transition: background-color 0.3s; /* Animation de transition pour la couleur de fond */
}

h2:hover {
  background-color: #c0c0c0; /* Couleur de fond légèrement plus foncée au survol */
}

/* Custom style for h3 */
h3 {
  font-size: 18px; /* Taille de la police légèrement réduite */
  background-color: #f1f1f1; /* Couleur de fond plus claire */
  padding: 8px; /* Ajoute un peu de padding */
  transition: background-color 0.3s; /* Animation de transition pour la couleur de fond */
}

h3:hover {
  background-color: #e0e0e0; /* Couleur de fond légèrement plus foncée au survol */
}
p {
  font-size: 16px; /* Taille de la police */
  line-height: 1.6; /* Hauteur de ligne pour améliorer la lisibilité */
  color: #444; /* Couleur du texte */
  margin-bottom: 16px; /* Espace en bas des paragraphes */
}
span{
  align-items: center;
  color: white;
}
</style>