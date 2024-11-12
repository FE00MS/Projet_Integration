<?php
require_once 'Utilities/sessionManager.php';
require_once 'Models/field.php';

require_once 'modals/experienceModal.php';
require_once 'modals/formationModal.php';
require_once 'modals/abilityModal.php';
require_once 'modals/critereSalaireModal.php';
require_once 'modals/languageModal.php';
require_once 'modals/scheduleModal.php';
require_once 'modals/linkModal.php';



require_once 'Models/Editprofile.php';

require_once 'Models/attachement.php';


if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}


$currentUser = $_SESSION['currentUser'];
$type = $_SESSION["accountType"];

if(!isset($_SESSION['currentLanguage']))
    {
        $_SESSION['currentLanguage'] = "FR";
    }
$lang = $_SESSION['currentLanguage'];

$jsonFile = ($lang === "FR") ? "fr.json" : "en.json";

$jsonData = file_get_contents($jsonFile);

$translations = json_decode($jsonData, true);

$content = <<<HTML
  <section class="relative pt-40 pb-10">
    <img src="https://pagedone.io/asset/uploads/1705473908.png" alt="cover-image" class="w-full absolute top-0 left-0 z-0 h-40 object-cover">

</section>
HTML;

if( ($type === 'company')){
    $content .= <<<HTML
    <div class="flex flex-col sm:flex-row max-sm:gap-5 items-center justify-center mb-5">
        <div id="c"> </div>
        <ul class="flex items-center gap-5">
            <li> 
                <a href="javascript:;" class="flex items-center gap-2 cursor-pointer group " data-tab="home"> 
                    <span class="font-medium text-base leading-7 text-gray-400">Home</span>
                </a>
            </li>
        </ul>
    </div>
    
    HTML;
}
else{
    $content .= <<<HTML
<div class="flex flex-col sm:flex-row max-sm:gap-5 items-center justify-center mb-5">
<div id="e"> </div>

    <ul class="flex items-center gap-5">
        <li> 
            <a href="javascript:;" class="flex items-center gap-2 cursor-pointer group " data-tab="home"> 
                <span class="font-medium text-base leading-7 text-gray-400">Home</span>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="flex items-center gap-2 cursor-pointer group" data-tab="profile">
                <svg xmlns="http://www.w3.org/2000/svg" width="5" height="20" viewBox="0 0 5 20" fill="none">
                    <path d="M4.12567 1.13672L1 18.8633" stroke="#E5E7EB" stroke-width="1.6" stroke-linecap="round" />
                </svg>
                <span class="font-medium text-base leading-7 text-gray-400">Profile</span>
            </a>
        </li>

        <li> 
            <a href="javascript:;" class="flex items-center gap-2 cursor-pointer group " data-tab="note"> 
            <svg xmlns="http://www.w3.org/2000/svg" width="5" height="20" viewBox="0 0 5 20" fill="none">
                    <path d="M4.12567 1.13672L1 18.8633" stroke="#E5E7EB" stroke-width="1.6" stroke-linecap="round" />
                </svg>
                <span class="font-medium text-base leading-7 text-gray-400">Note</span>
            </a>
        </li>
    </ul>
</div>

HTML;
}



if ($type === 'company') {
    $companyName = $currentUser['CName'];
    $location = $currentUser['Location'];
    $email = $currentUser['Email'];
    $password = $currentUser['Password'];
    $description = $currentUser['Description'];
    $customInvite = $currentUser['CustomInvite'];


    //Attachement
    $a = new Attachement();
    $attachemnts = $a->GetAllAttachement(intval($_SESSION['currentUser']['Id']));
    $attachemntDiv = '';
    if ($attachemnts != null) {
        foreach ($attachemnts as $attachemnt) {
            $attachemntDiv .= '
            <div class="p-4 mb-8">
                <a class="link-blue" href="' . $attachemnt['Link'] . '" target="_blank"> • ' . $attachemnt["Name"] . '</a>
                
                <form method="POST" action="deleteLink.php" style="display:inline; margin-left: 8px;">
                    <input type="hidden" name="AttachementId" value="' . $attachemnt['Id'] . '">
                    <button type="submit" style="background: none; border: none; color: red; font-weight: bold; cursor: pointer;" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce lien ?\')">
                        &times;
                    </button>
                </form>
            </div>
        ';
        
        }
    }


    $content .= <<<HTML
    <div class="tab-content" id="home" style="display: block;">
        <div class="p-4 border rounded-lg shadow-md mb-8 max-w-lg mx-auto">
            <h2 class="text-xl font-semibold mb-2">{$translations['personalInfo']}</h2>
            <p><strong>{$translations['companyName']}:</strong> $companyName</p>
            <p><strong>{$translations['location']}:</strong> $location</p>
            <p><strong>{$translations['email']}:</strong> $email</p>
            <p><strong>{$translations['password']}:</strong> $password</p>
            <p><strong>Description:</strong> $description</p>
            <p class="break-words"><strong>{$translations['defaultInv']}:</strong>$customInvite</p>
            <button class="btn btn-neutral mt-4" onclick="window.location.href='logout.php'">{$translations['logout']}</button>
            <button class="btn btn-primary mt-4" onclick="document.getElementById('modal-update-info').showModal()">{$translations['modify']}</button>
            <form method="POST" action="deleteAccountAction">
                <button class="btn btn-neutral mt-4 bg-red-">{$translations['deleteAccount']}</button>
            </form>
        </div>

        
        <div class="p-4 border rounded-lg shadow-md mb-8 max-w-lg mx-auto">
            <h2 class="text-xl font-semibold mb-2 flex justify-center items-center">{$translations['links']}</h2>
            <div class="grid grid-cols-3 gap-4">
                $attachemntDiv
            </div>
            <button class="btn btn-neutral mt-auto" onclick="document.getElementById('modal-add-link').showModal()">{$translations['addLinks']}</button>
        </div>
    </div>

HTML;
} else {
    $name = $currentUser['Name'];
    $lastname = $currentUser['LastName'];
    $email = $currentUser['Email'];
    $password = $currentUser['Password'];
    $age = $currentUser['Age'];
    $Profile = new Profile();
    $f = new Field();
    $fields = $f->GetAllFields();
    $fieldNames = [];
    function getExperiencesForProfile($userId)
    {
        $profile = new Profile();
        $experienceData = $profile->GetAllExperienceEmp($userId);

        $experiences = [];
        $formations = [];
        $abilities = [];
        if ($experienceData != null) {
            foreach ($experienceData as $exp) {
                switch ($exp['TypeExp']) {
                    case 'For':
                        $formations[] = $exp;
                        break;
                    case 'Exp':
                        $experiences[] = $exp;
                        break;
                    case 'Abi':
                        $abilities[] = $exp;
                        break;
                }
            }

        }

        return [
            'experiences' => $experiences,
            'formations' => $formations,
            'abilities' => $abilities
        ];
    }

    $data = getExperiencesForProfile($_SESSION['currentUser']['Id']);
    //Exp
    $experiencesDiv = '';
    if ($data['experiences'] != null) {
        foreach ($data['experiences'] as $expe) {
            //Recup le nom du Field
            $fieldType = $expe['FieldType'];
            $fnni = ($expe['Complete'] == 1) ? 'Terminé' : 'En cours';

        
            $options = '';
            foreach ($fields as $field) {
                $fieldNames[$field['IdField']] = $field['FieldName'];
                $selected = ($field['IdField'] == $fieldType) ? 'selected' : '';
                $options .= '<option value="' . $field['IdField'] . '" ' . $selected . '>' . $field['FieldName'] . '</option>';
            }
    
            $experiencesDiv .= '
            <div class="p-4 border rounded-lg mb-8">
                <p><strong>Titre d\'emploi: </strong>' . $expe['Title'] . '</p>
                <p><strong>Lieu: </strong>' . $expe['LocationName'] . '</p>
                <p><strong>Domaine: </strong>' . $fieldNames[$fieldType] . '</p>
                <p><strong>Durée: </strong>' . $expe['Duration'] . ' années</p>
                <p><strong>Description: </strong>' . $expe['Description'] . '</p>
                <p><strong>État: </strong>' . $fnni . '</p>
                <br>
               
                <div class="">
                <form method="POST" action="deleteExperience.php" style="display:inline;">
                    <input type="hidden" name="ExperienceId" value="' . $expe['Id'] . '">
                    <button type="submit" class="w-24 btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette expérience ?\')">Supprimer</button>
                </form>
                 <button class="my-3 w-24 btn btn-primary" onclick="document.getElementById(\'modal-edit-exp-' . $expe['Id'] . '\').showModal()">Modifier</button>

                </div>
            </div>
    
             <dialog id="modal-edit-exp-' . $expe['Id'] . '" class="modal">
                <div class="modal-box">
                    <h3 class="text-lg font-bold">Modifier l\'expérience</h3>
                    <form method="POST" action="updateExperience.php">
                        <input type="hidden" name="ExperienceId" value="' . $expe['Id'] . '">
                         <input type="hidden" name="TypeExp" value="' . $expe['TypeExp'] . '">
                        <label class="block mb-2">
                            <span>Titre d\'emploi</span>
                            <input type="text" name="Title" value="' . $expe['Title'] . '" class="input input-bordered mt-1 block w-full" required>
                        </label>
                        <label class="block mb-2">
                            <span>Lieu</span>
                            <input type="text" name="LocationName" value="' . $expe['LocationName'] . '" class="input input-bordered mt-1 block w-full" required>
                        </label>
                        <label class="block mb-2">
                            <span>Domaine</span>
                            <select name="FieldType" class="input input-bordered mt-1 block w-full" required>
                                ' . $options . '
                            </select>
                        </label>
                        <label class="block mb-2">
                            <span>Durée (en années)</span>
                            <input type="number" name="Duration" value="' . $expe['Duration'] . '" class="input input-bordered mt-1 block w-full" required>
                        </label>
                        <label class="block mb-2">
                            <span>Description</span>
                            <textarea name="Description" class="textarea textarea-bordered mt-1 block w-full" required>' . $expe['Description'] . '</textarea>
                        </label>
                        <label class="block mb-2">
                            <span>Expérience terminée ?</span>
                            <select name="Complete" class="input input-bordered mt-1 block w-full" required>
                                <option value="1" ' . ($expe['Complete'] == 1 ? 'selected' : '') . '>Oui</option>
                                <option value="0" ' . ($expe['Complete'] == 0 ? 'selected' : '') . '>Non</option>
                            </select>
                        </label>
                        <div class="modal-action">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <button class="btn btn-default" type="button" onclick="document.getElementById(\'modal-edit-exp-' . $expe['Id'] . '\').close()">Annuler</button>
                        </div>
                    </form>
                </div>
            </dialog>
        ';
        }
    }

    //Formation
    $formationsDiv = '';
    if ($data['formations'] != null) {
        foreach ($data['formations'] as $for) {
            //Recup le nom du Field
            $fieldType = $for['FieldType'];
            $fnni = ($for['Complete'] == 1) ? 'Terminé' : 'En cours';

            $options = '';
            foreach ($fields as $field) {
                $fieldNames[$field['IdField']] = $field['FieldName'];
                $selected = ($field['IdField'] == $fieldType) ? 'selected' : '';
                $options .= '<option value="' . $field['IdField'] . '" ' . $selected . '>' . $field['FieldName'] . '</option>';
            }
            $formationsDiv .=
                '<div class="p-4 border rounded-lg  mb-8">
        <p><strong>Intitulé de la formation: </strong>' . $for['Title'] . ' </p>
        <p><strong>Organisme de formation: </strong>' . $for['LocationName'] . ' </p>
        <p><strong>Domaine: </strong>' . $fieldNames[$fieldType] . '</p>
        <p><strong>Durée: </strong>' . $for['Duration'] . ' années</p>
        <p><strong>Description: </strong>' . $for['Description'] . '</p>
        <p><strong>État: </strong>' . $fnni . '</p>
        <br>
        <form method="POST" action="deleteExperience.php" style="display:inline;">
            <input type="hidden" name="ExperienceId" value="' . $for['Id'] . '">
            <button type="submit" class="w-24 btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette formation ?\')">Supprimer</button>
        </form>
        <button class="my-3 w-24 btn btn-primary"  onclick="document.getElementById(\'modal-edit-for-' . $for['Id'] . '\').showModal()">Modifier</button>
        </div>
        

          <dialog id="modal-edit-for-' . $for['Id'] . '" class="modal">
                <div class="modal-box">
                    <h3 class="text-lg font-bold">Modifier la formation</h3>
                    <form method="POST" action="updateExperience.php">
                        <input type="hidden" name="ExperienceId" value="' . $for['Id'] . '">
                         <input type="hidden" name="TypeExp" value="' . $for['TypeExp'] . '">
                        <label class="block mb-2">
                            <span>Intitulé de la formation</span>
                            <input type="text" name="Title" value="' . $for['Title'] . '" class="input input-bordered mt-1 block w-full" required>
                        </label>
                        <label class="block mb-2">
                            <span>Nom de l\'organisme de formation</span>
                            <input type="text" name="LocationName" value="' . $for['LocationName'] . '" class="input input-bordered mt-1 block w-full" required>
                        </label>
                        <label class="block mb-2">
                            <span>Domaine</span>
                            <select name="FieldType" class="input input-bordered mt-1 block w-full" required>
                                ' . $options . '
                            </select>
                        </label>
                        <label class="block mb-2">
                            <span>Durée (en années)</span>
                            <input type="number" name="Duration" value="' . $for['Duration'] . '" class="input input-bordered mt-1 block w-full" required>
                        </label>
                        <label class="block mb-2">
                            <span>Description</span>
                            <textarea name="Description" class="textarea textarea-bordered mt-1 block w-full" required>' . $for['Description'] . '</textarea>
                        </label>
                        <label class="block mb-2">
                            <span>Formation terminée ?</span>
                            <select name="Complete" class="input input-bordered mt-1 block w-full" required>
                                <option value="1" ' . ($for['Complete'] == 1 ? 'selected' : '') . '>Oui</option>
                                <option value="0" ' . ($for['Complete'] == 0 ? 'selected' : '') . '>Non</option>
                            </select>
                        </label>
                        <div class="modal-action">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <button class="btn btn-default" type="button" onclick="document.getElementById(\'modal-edit-for-' . $for['Id'] . '\').close()">Annuler</button>
                        </div>
                    </form>
                </div>
            </dialog>
        ';
        }
    }

    //ABility 
    $abilitysdiv = '';
    if ($data['abilities'] != null) {
        foreach ($data['abilities'] as $ability) {
            //Recup le nom du Field
            $fieldType = $ability['FieldType'];
            $fnni = 'Débutant';
            if ($ability['Complete'] == 1) {
                $fnni = 'Avancé';
            }
            $options = '';
            foreach ($fields as $field) {
                $fieldNames[$field['IdField']] = $field['FieldName'];
                $selected = ($field['IdField'] == $fieldType) ? 'selected' : '';
                $options .= '<option value="' . $field['IdField'] . '" ' . $selected . '>' . $field['FieldName'] . '</option>';
            }
            $abilitysdiv .=
                '<div class="p-4 border rounded-lg  mb-8">
        <p><strong>Titre d\'emploi: </strong>' . $ability['Title'] . ' </p>
        <p><strong>Nom de la companie: </strong>' . $ability['LocationName'] . ' </p>
        <p><strong>Domaine: </strong>' . $fieldNames[$fieldType]  . '</p>
        <p><strong>Durée: </strong>' . $ability['Duration'] . ' mois</p>
        <p><strong>Description: </strong>' . $ability['Description'] . '</p>
        <p><strong>État: </strong>' . $fnni . '</p>
        <br>
        <form method="POST" action="deleteExperience.php" style="display:inline;">
            <input type="hidden" name="ExperienceId" value="' . $ability['Id'] . '">
            <button type="submit" class=" w-24 btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette expérience ?\')">Supprimer</button>
        </form>
            <button class="my-3 w-24 btn btn-primary" onclick="document.getElementById(\'modal-edit-abi-' . $ability['Id'] . '\').showModal()">Modifier</button>
        </div>

        

        <dialog id="modal-edit-abi-' . $ability['Id'] . '" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold">Modifier la compétence</h3>
            <form method="POST" action="updateExperience.php">
                <input type="hidden" name="ExperienceId" value="' . $ability['Id'] . '">
                 <input type="hidden" name="TypeExp" value="' . $ability['TypeExp'] . '">
                <label class="block mb-2">
                    <span>Nom de la compétence</span>
                    <input type="text" name="Title" value="' . $ability['Title'] . '" class="input input-bordered mt-1 block w-full" required>
                </label>
                <label class="block mb-2">
                    <span>Nom de la companie</span>
                    <input type="text" name="LocationName" value="' . $ability['LocationName'] . '" class="input input-bordered mt-1 block w-full" required>
                </label>
                <label class="block mb-2">
                    <span>Domaine</span>
                    <select name="FieldType" class="input input-bordered mt-1 block w-full" required>
                        ' . $options . '
                    </select>
                </label>
                <label class="block mb-2">
                    <span>Durée d\'apprentissage (en mois)</span>
                    <input type="number" name="Duration" value="' . $ability['Duration'] . '" class="input input-bordered mt-1 block w-full" required>
                </label>
                <label class="block mb-2">
                    <span>Description de la compétence</span>
                    <textarea name="Description" class="textarea textarea-bordered mt-1 block w-full" required>' . $ability['Description'] . '</textarea>
                </label>
                <label class="block mb-2">
                    <span>Niveau de maîtrise</span>
                    <select name="Complete" class="input input-bordered mt-1 block w-full" required>
                        <option value="1" ' . ($ability['Complete'] == 1 ? 'selected' : '') . '>Avancé</option>
                        <option value="0" ' . ($ability['Complete'] == 0 ? 'selected' : '') . '>Débutant</option>
                    </select>
                </label>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <button class="btn btn-default" type="button" onclick="document.getElementById(\'modal-edit-abi-' . $ability['Id'] . '\').close()">Annuler</button>
                </div>
            </form>
        </div>
    </dialog>

        ';
        }
    }


    //LanguageEMp
    $lemp = new Profile();
    $languagesEmp = $lemp->GetAllLanguageEmp(intval($_SESSION['currentUser']['Id']));

    $l = new Language();
    $languages = $l->GetAllLanguages();

    $languageEmpdiv = '';
    if ($languagesEmp != null) {
        foreach ($languagesEmp as $languageEmp) {
            //Recup le nom du language
            $languageName = $l->GetLanguage($languageEmp['LId']);
            $currentLanguageId = $languageEmp['LId'];
            $currentLevel = $languageEmp['Niveau'];

            $languagesOption = '';
            foreach ($languages as $language) {
                $selected = ($language['LId'] == $currentLanguageId) ? 'selected' : '';
                $languagesOption .= '<option value="' . $language['LId'] . '" ' . $selected . '>' . $language['LanguageName'] . '</option>';
            }

            $levelOptions = '
            <option value="Rudimentaire" ' . ($currentLevel == 'Rudimentaire' ? 'selected' : '') . '>Rudimentaire</option>
            <option value="Moyen" ' . ($currentLevel == 'Moyen' ? 'selected' : '') . '>Moyen</option>
            <option value="Élevé" ' . ($currentLevel == 'Élevé' ? 'selected' : '') . '>Élevé</option>
             ';

            $languageEmpdiv .= '
          <div class="p-4 border rounded-lg  mb-8">
          <p><strong>Langue parlée: </strong>' . $languageName[0]['LanguageName'] . '</p>
            <p><strong>Niveau: </strong>' . $languageEmp['Niveau'] . '</p>
          <br>
          <form method="POST" action="deletelanguageEmp.php" style="display:inline;">
              <input type="hidden" name="languageEmpId" value="' . $languageEmp['EId'] . '">
             <input type="hidden" name="languageId" value="' . $languageEmp['LId'] . '">
              <button type="submit" class=" w-24 btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette langue ?\')">Supprimer</button>
          </form>
              <button class="my-3 w-24 btn btn-primary" onclick="document.getElementById(\'modal-edit-lg-' . $languageEmp['EId'] . '-' . $languageEmp['LId'] . '\').showModal()">Modifier</button>
              </div>


       <dialog id="modal-edit-lg-' . $languageEmp['EId'] . '-' . $languageEmp['LId'] . '" class="modal">
              <div class="modal-box">
                  <h3 class="text-lg font-bold">Modifier la langue</h3>
                  <form method="POST" action="updateLanguage.php">
                    <input type="hidden" name="OldLId" value="' . $languageEmp['LId'] . '">
                      <label class="block mb-2">
                          <span>Langues</span>
                          <select name="Language" class="input input-bordered mt-1 block w-full">
                             ' . $languagesOption . '
                          </select>                    
                      </label>
                      <label class="block mb-2">
                          <span>Niveau de maîtrise</span>
                          <select name="Niveau" required class="input input-bordered mt-1 block w-full">
                              ' . $levelOptions . '
                          </select>
                      </label>
                      <div class="modal-action">
                          <button type="submit" class="btn btn-primary">Mettre à jour</button>
                          <button class="btn btn-default" type="button" onclick="document.getElementById(\'modal-edit-lg-' . $languageEmp['EId'] . '-' . $languageEmp['LId'] . '\').close()">Annuler</button>
                      </div>
                  </form>
              </div>
          </dialog>
          ';
        }
    }

    //Salary 
    $salarys = $Profile->GetAllSalary(intval($_SESSION['currentUser']['Id']));
    $salaryDiv = '';
    $SalaryChangeText = $translations['addCrit'];
    if ($salarys != null) {
        foreach ($salarys as $salary) {
            if ($salary['ExpSalary'] !== '0') {
                switch ($salary['SalaryType']) {
                    case 'horaire':
                        $salaryType = 'Salaire horaire';
                        break;
                    case 'commission':
                        $salaryType = 'Commission';
                        break;
                    case 'pourboire':
                        $salaryType = 'Pourboire';
                        break;
                    case 'contract':
                        $salaryType = 'Salaire à contrat';
                        break;
                    case 'fixe':
                        $salaryType = 'Salaire fixe';
                        break;
                }
                $salaryDiv .= '
          <div class="p-4 border rounded-lg  mb-8">
          <p><strong>Type de salaire: </strong>' . $salaryType . '</p>
          <br>
            <p><strong>Montant: </strong>' . $salary['ExpSalary'] . ' $</p>
          <br>
          <form method="POST" action="deletesalary.php" style="display:inline;">
              <input type="hidden" name="salaryId" value="' . $salary['Id'] . '">
              <button type="submit" class="btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce critère de salaire ?\')">Supprimer</button>
          </form>
          </div>
          ';
          $SalaryChangeText = $translations['modifyCrit'];
            } else {
                $salaryDiv .= '';
            }
        }
    }

    //Schedule
    $schedules = $Profile->GetAllSchedule(intval($_SESSION['currentUser']['Id']));
    $scheduleDiv = '';
    $scheduleChangeText = $translations['addSchedule'];

    if ($schedules != null) {
        foreach ($schedules as $schedule) {
            $scheduleDiv .= '
        <div class="p-4 border rounded-lg mb-8">
            <p><strong>Nombre d\'heures par semaine : </strong>' . $schedule['Hours'] . ' heures</p>
            <p><strong>Travail en semaine : </strong>' . ($schedule['WeekDay'] ? 'Oui' : 'Non') . '</p>
            <p><strong>Travail le weekend : </strong>' . ($schedule['WeekEnd'] ? 'Oui' : 'Non') . '</p>
            <p><strong>Travail en soirée : </strong>' . ($schedule['Evening'] ? 'Oui' : 'Non') . '</p>
            <p><strong>Travail de nuit : </strong>' . ($schedule['Night'] ? 'Oui' : 'Non') . '</p>

            <br>
            <form method="POST" action="deleteschedule.php" style="display:inline;">
                <input type="hidden" name="scheduleId" value="' . $schedule['IdEmp'] . '">
                <button type="submit" class="btn btn-danger" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet horaire ?\')">Supprimer</button>
            </form>

        </div>
        ';
            $scheduleChangeText = $translations['modifySchedule'];
        }
    }

    //Attachement
    $a = new Attachement();
    $attachemnts = $a->GetAllAttachement(intval($_SESSION['currentUser']['Id']));
    $attachemntDiv = '';
    if ($attachemnts != null) {
        foreach ($attachemnts as $attachemnt) {
            $attachemntDiv .= '
            <div class="p-4 mb-8">
                <a class="link-blue" href="' . $attachemnt['Link'] . '" target="_blank"> • ' . $attachemnt["Name"] . '</a>
                
                <form method="POST" action="deleteLink.php" style="display:inline; margin-left: 8px;">
                    <input type="hidden" name="AttachementId" value="' . $attachemnt['Id'] . '">
                    <button type="submit" style="background: none; border: none; color: red; font-weight: bold; cursor: pointer;" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce lien ?\')">
                        &times;
                    </button>
                </form>
            </div>
        ';
        
        }
    }


    $hiddenPassword = str_repeat('•', strlen($password));

    $P = new Profile();
    $notes = $P->GetAllNote($currentUser["Id"]);
    $notesDiv = "";
    
    if ($notes != null) {
        foreach ($notes as $note) {
            $text = $note["note"];
            $noteId = $note["ID"]; 
    
            $notesDiv .= <<<HTML
            <div class="page">
                <div class="margin"></div>
                <p>$text</p>
                <form method="post" action="deleteNote.php" class="delete-button">
                    <input type="hidden" name="noteId" value="$noteId">
                    <button type="submit" class="delete-button">🗑️</button>
                </form>
            </div>
        HTML;
        
        
        }
    }
    
    $content .= <<<HTML

<div class="tab-content" id="home" style="display: none;">
    <div class="p-4 border rounded-lg shadow-md mb-8 max-w-lg mx-auto">
        <h2 class="text-xl font-semibold mb-2">{$translations['personalInfo']}</h2>
        <p><strong>{$translations['name']}:</strong> $name</p>
        <p><strong>{$translations['lastName']}:</strong> $lastname</p>
        <p><strong>Age:</strong> $age</p>
        <p><strong>{$translations['email']}:</strong> $email</p>
        <p><strong>{$translations['password']}: </strong>$hiddenPassword</p>
        <button class="btn btn-neutral mt-4" onclick="window.location.href='logout.php'">{$translations['logout']}</button>
        <button class="btn btn-primary mt-4" onclick="document.getElementById('modal-update-info').showModal()">{$translations['modify']}</button>
        <form method="POST" action="deleteAccountAction.php">
            <button class="btn btn-neutral mt-4 bg-red-">{$translations['deleteAccount']}</button>
        </form>
    </div>

</div>


    <div class="tab-content" id="note" style="display: none;">
        <div class="flex gap-8">
            <div class="note-form p-4 border rounded-lg mb-8 max-w-sm" style="margin-left: 10px;">
                <form method="post" action="addNote.php">
                    <h2 class="text-xl font-semibold mb-2 flex justify-center items-center">Ajout d'une note</h2>
                    <textarea id="textarea" name="note" rows="6" maxlength="250"  class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Écrivez votre note ici..."></textarea>
                    <div class="flex justify-end mt-2">
                        <button type="submit" class="bg-blue-500 text-white py-1 px-3 rounded-lg">
                            <span class="text-lg font-bold">+</span>
                        </button>
                    </div>
                </form>
            </div>
    
            <div class="p-4 max-w-lg mx-auto">
                <h2 class="text-xl font-semibold mb-4">Vos Notes</h2>
                <div class="grid gap-4">
                    $notesDiv
                </div>
            </div>
        </div>
    </div>

<div class="tab-content" id="profile" style="display: none;">

<div class="grid grid-cols-3 gap-4 mb-8 mx-auto">
    <div class="p-4 border rounded-lg shadow-md flex flex-col justify-between h-full">
        <h2 class="text-xl font-semibold mb-2 flex justify-center items-center">{$translations['exp']}</h2>
        <div class="grid grid-cols-2 gap-4">
            $experiencesDiv
        </div>
        <button class="btn btn-neutral mt-auto" onclick="document.getElementById('modal-add-exp').showModal()">{$translations['addExp']}</button>
    </div>

    <div class="p-4 border rounded-lg shadow-md flex flex-col justify-between h-full">
        <h2 class="text-xl font-semibold mb-2 flex justify-center items-center">{$translations['formation']}</h2>
        <div class="grid grid-cols-2 gap-4">
            $formationsDiv
        </div>
        <button class="btn btn-neutral mt-auto" onclick="document.getElementById('modal-add-formation').showModal()">{$translations['addFormation']}</button>
    </div>

    <div class="p-4 border rounded-lg shadow-md flex flex-col justify-between h-full">
        <h2 class="text-xl font-semibold mb-2 flex justify-center items-center">{$translations['comp']}</h2>
        <div class="grid grid-cols-2 gap-4">
            $abilitysdiv
        </div>
        <button class="btn btn-neutral mt-auto" onclick="document.getElementById('modal-add-ability').showModal()">{$translations['addComp']}</button>
    </div>

    <div class="p-4 border rounded-lg shadow-md flex flex-col justify-between h-full">
        <h2 class="text-xl font-semibold mb-2 flex justify-center items-center">{$translations['lang']}</h2>
        <div class="grid grid-cols-2 gap-4">
            $languageEmpdiv
        </div>
        <button class="btn btn-neutral mt-auto" onclick="document.getElementById('modal-add-language').showModal()">{$translations['addLang']}</button>
    </div>

    <div class="p-4 border rounded-lg shadow-md flex flex-col justify-between h-full">
        <h2 class="text-xl font-semibold mb-2 flex justify-center items-center">{$translations['crit']}</h2>
        <div class="grid grid-cols-1 gap-4">
            $salaryDiv
        </div>
        <button class="btn btn-neutral mt-auto" onclick="document.getElementById('modal-add-critereSalaire').showModal()">$SalaryChangeText </button>
    </div>

    <div class="p-4 border rounded-lg shadow-md flex flex-col justify-between h-full">
        <h2 class="text-xl font-semibold mb-2 flex justify-center items-center">{$translations['schedule']}</h2>
        <div class="grid grid-cols-1 gap-4">
            $scheduleDiv
        </div>
        <button class="btn btn-neutral mt-auto" onclick="document.getElementById('modal-add-schedule').showModal()">$scheduleChangeText</button>
    </div>

    <div class="p-4 border rounded-lg shadow-md flex flex-col justify-between h-full">
        <h2 class="text-xl font-semibold mb-2 flex justify-center items-center">{$translations['links']}</h2>
        <div class="grid grid-cols-3 gap-4">
            $attachemntDiv
        </div>
        <button class="btn btn-neutral mt-auto" onclick="document.getElementById('modal-add-link').showModal()">{$translations['addLinks']}</button>
    </div>
</div>
</div>


HTML;
}


if ($type === 'company') {
    $content .= <<<HTML
        <dialog id="modal-update-info" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold">{$translations['editInfo']}</h3>
            <form method="POST" action="updateCompany.php">
                <label class="block mb-2">
                    <span>{$translations['companyName']}</span>
                    <input type="text" name="CName" value="$companyName" class="input input-bordered mt-1 block w-full">
                </label>
                <label class="block mb-2">
                    <span>{$translations['location']}</span>
                    <input type="text" name="Location" value="$location" class="input input-bordered mt-1 block w-full">
                </label>
                <label class="block mb-2">
                    <span>{$translations['email']}</span>
                    <input type="email" name="Email" value="$email" class="input input-bordered mt-1 block w-full">
                </label>
                <label class="block mb-2">
                    <span>{$translations['password']}</span>
                    <input type="password" name="Password" value="$password" class="input input-bordered mt-1 block w-full">
                </label>
                <label class="block mb-2">
                    <span>Description</span>
                    <input type="text" name="Description" value="$description" class="input input-bordered mt-1 block w-full">
                </label>
                <label class="block mb-2">
                    <span>{$translations['defaultInv']}</span>
                    <textarea name="CustomInvite" rows="15" class="input input-bordered mt-1 block w-full">$customInvite</textarea>
                </label>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">{$translations['modify']}</button>
                    <button class="btn btn-default" type="button" onclick="document.getElementById('modal-update-info').close()">{$translations['cancel']}</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>


                        <!-- Modale Ajouter un horaire -->
                             $LinkModals
HTML;
} else {

    $content .= <<<HTML
        <dialog id="modal-update-info" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold">{$translations['editInfo']}</h3>
            <form method="POST" action="updateEmployee.php">
                <label class="block mb-2">
                    <span>{$translations['name']}</span>
                    <input type="text" name="Name" value="$name" class="input input-bordered mt-1 block w-full">
                </label>
                <label class="block mb-2">
                    <span>{$translations['lastName']}</span>
                    <input type="text" name="LastName" value="$lastname" class="input input-bordered mt-1 block w-full">
                </label>
                <label class="block mb-2">
                    <span>Age</span>
                    <input type="text" name="Age" value="$age" class="input input-bordered mt-1 block w-full">
                </label>
                <label class="block mb-2">
                    <span>{$translations['email']}</span>
                    <input type="email" name="Email" value="$email" class="input input-bordered mt-1 block w-full">
                </label>
                <label class="block mb-2">
                    <span>{$translations['password']}</span>
                    <input type="password" name="Password" value="$password" class="input input-bordered mt-1 block w-full">
                </label>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">{$translations['modify']}</button>
                    <button class="btn btn-default" type="button" onclick="document.getElementById('modal-update-info').close()">{$translations['cancel']}</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>
        
    <!-- Model Exp -->
        $ExpModals

    <!-- Model Formation -->
        $FormationModals

      <!-- Model Ability -->
        $AbilityModals

      <!-- Model language-->
        $LanguageModals

      <!-- Model Critere de salaire-->
        $CritereSalaireModals

        <!-- Modale Ajouter un horaire -->
        $ScheduleModals

        <!-- Modale Ajouter un horaire -->
        $LinkModals

</div>
HTML;
}
include "Views/master.php";


?>
<script>
   document.addEventListener('DOMContentLoaded', function () {
       // Obtenez les éléments c et e pour déterminer le type d'utilisateur
       let c = document.getElementById('c');
       let e = document.getElementById('e');
       let activeTab;

       function switchTab(tabId) {
           // Masquez tous les onglets
           document.querySelectorAll('.tab-content').forEach(content => {
               content.style.display = 'none';
           });
           
           // Affichez l'onglet spécifié
           document.getElementById(tabId).style.display = 'block';

           // Sauvegardez l'onglet actif en fonction du type d'utilisateur
           if (c) {
               localStorage.setItem('activeTabC', tabId);
           } else if (e) {
               localStorage.setItem('activeTabE', tabId);
           }
       }

       // Récupérez l'onglet actif sauvegardé ou définissez par défaut à "home"
       if (c) {
           activeTab = localStorage.getItem('activeTabC') || 'home';
       } else if (e) {
           activeTab = localStorage.getItem('activeTabE') || 'home';
       } else {
           activeTab = 'home'; // Si ni c ni e ne sont définis, par défaut à "home"
       }

       // Activez l'onglet initial
       switchTab(activeTab);
       document.querySelector(`a[data-tab="${activeTab}"]`).classList.add('active');

       // Gérer le clic sur chaque onglet
       document.querySelectorAll('a[data-tab]').forEach(function (tab) {
           tab.addEventListener('click', function () {
               // Supprimez la classe active de tous les onglets
               document.querySelectorAll('a[data-tab]').forEach(function (tab) {
                   tab.classList.remove('active');
               });

               // Ajoutez la classe active à l'onglet cliqué
               tab.classList.add('active');

               // Basculez vers l'onglet cliqué
               const tabId = tab.getAttribute('data-tab');
               switchTab(tabId);
           });
       });
   });
</script>


<style>
    /* Classe pour le bouton actif */
    .active span {
        color: black;

    }
    .tab-content {
        display: none;
    }
    .page {
  position: relative;
  box-sizing: border-box;
  max-width: 300px;
  font-family: cursive;
  font-size: 20px;
  border-radius: 10px;
  background: #fff;
  background-image: linear-gradient(#f5f5f0 1.1rem, #ccc 1.2rem);
  background-size: 100% 1.2rem;
  line-height: 1.2rem;
  padding: 1.4rem 0.5rem 0.3rem 3rem; /* Réduit le padding à gauche */
  margin-bottom: 20px; /* Ajoute de l'espacement entre chaque note */
}
.link-blue {
    color: blue;
    text-decoration: underline;
}
.delete-button {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 1.2rem;
  color: #e3342f;
  cursor: pointer;
  background: none;
  border: none;
}

.margin {
  position: absolute;
  border-left: 1px solid #d88;
  height: 100%;
  left: 2.5rem; /* Aligne la marge avec le texte décalé */
  top: 0;
}

.page p {
  margin: 0;
  padding-right: 2rem; /* Ajoute de l'espace à droite pour éviter le bouton */
  padding-bottom: 1.2rem;
  color: black;
  line-height: 20px;
}
.note-form {
  width: 300px;
  height: 300px; /* Fixez la largeur souhaitée */
  flex-shrink: 0; /* Empêche le rétrécissement du conteneur */
}

</style>