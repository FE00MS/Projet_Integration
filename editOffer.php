<?php
include 'Utilities/sessionManager.php';
require_once 'BD/BD.php';
include 'Models/offer.php';
require_once 'Models/field.php';
require_once 'Models/language.php';

if (!isset($_SESSION['currentLanguage'])) {
    $_SESSION['currentLanguage'] = "FR";
}
$lang = $_SESSION['currentLanguage'];

$jsonFile = ($lang === "FR") ? "fr.json" : "en.json";

$jsonData = file_get_contents($jsonFile);

$translations = json_decode($jsonData, true);


if (isset($_GET['id'])) {
    $offerId = intval($_GET['id']);

    $l = new Language();
    $languages = $l->GetAllLanguages();
    $languageMap = [];
    foreach ($languages as $language) {
        $languageMap[$language['LId']] = $language['LanguageName'];
    }

    $offerModel = new Offer();
    $offerDetails = $offerModel->getOffer($offerId);
    $offerLangue = $offerModel->getLangueByOfferId($offerId);
    $prerequisites = $offerModel->getPrerequisitesWithRestrictions($offerId);
    if ($offerDetails) {
        $jobTitle = htmlspecialchars($offerDetails['Job']);
        $location = htmlspecialchars($offerDetails['Location']);
        $salary = htmlspecialchars($offerDetails['Salary']);
        $description = htmlspecialchars($offerDetails['Description']);
        $hours = htmlspecialchars($offerDetails['Hours']);
    } else {
        echo "Offer not found.";
        exit();
    }
} else {
    header('Location: myoffers.php');
    exit();
}

$content = <<<HTML
<div id="loadingOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="spinner border-t-4 border-b-4 border-blue-500 w-16 h-16 rounded-full animate-spin"></div>
</div>

    
    <div class="flex justify-between items-center pt-12">
        <h1 class="text-4xl font-bold pl-56">{$translations['createOffer']}</h1>
    </div>

    <form id="offerForm" method="POST" action="offerAction.php" class="border-2 p-6 rounded-lg shadow-lg w-full max-w-5xl mx-auto mt-8 space-y-8">
    <input type="hidden" name="offerId" value="$offerId">
  
        <div class="flex flex-col md:flex-row justify-between items-start gap-8">
            <div class="w-full md:w-1/2 space-y-6 pr-8 md:pr-16">
                <div class="form-control">
                    <label class="label font-semibold">{$translations['offerName']}</label>
                    <input type="text" name="job" class="input input-bordered w-full" placeholder="Nom de l'offre" value="$jobTitle" required>
                </div>

                <div class="form-control">
                    <label class="label font-semibold">{$translations['location']}</label>
                    <input type="text" name="location" class="input input-bordered w-full" placeholder="Lieu du poste"  value="$location"  required>
                </div>

                <div class="form-control">
                    <label class="label font-semibold">{$translations['salary']}</label>
                    <input type="number" min="0" name="salary" class="input input-bordered w-full" placeholder="Salaire proposé"  value="$salary"  required>
                </div>

                <div class="form-control">
                    <label class="label font-semibold">Description</label>
                    <textarea name="description" class="textarea textarea-bordered w-full" placeholder="Description du poste"    required> $description</textarea>
                </div>

                <div class="form-control">
                    <label class="label font-semibold">{$translations['hours']}</label> 
                    <input type="number" min="0" max="168" name="hours" class="input input-bordered w-full" placeholder="Heures de travail"  value="$hours" required>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="btn btn-neutral">{$translations['updateOffer']}</button>
                    <button type="button" onclick="confirmDeletion($offerId)"  class="btn btn-error">{$translations['deleteOffer']}</button>
                </div>
            </div>


            
        
HTML;


$content .= <<<HTML
                <div id="FormDynam" class="w-full md:w-1/2 pl-0 md:pl-16 space-y-6">


    HTML;

//langue

if ($offerLangue != null) {
    $content .= <<<HTML
 <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4">Langues requises</h2>
        <div class="flex flex-wrap gap-2">
HTML;
    foreach ($offerLangue as $langue) {
        $langueId = $langue["LId"];
        $langueTxt = $languageMap[$langueId];
        $content .= <<<HTML
    <span id="badge-{$langueId}" class="badge badge-primary px-4 py-2 text-white bg-blue-600 rounded-full shadow-sm">
            <p id=$langueId>$langueTxt</p>
            <button type="button" onclick="removeLanguage('{$langueId}')" class="ml-2 text-white text-lg">x</button>
        </span>

HTML;
    }
}




$content .= <<<HTML

                    </div>
                    <button type="button" onclick="addLanguage()" class="btn btn-success mt-4">Ajouter une langue</button>
                    
                    </div>
                    <hr >
                        <button type="button" onclick="addField()" class="btn btn-success mb-4">Ajouter une pondération</button>

                        <div class="font-semibold">Somme des cercles : <span id="sommeAffichee">0</span></div>
                        <div id="errorMessage" class="text-error hidden">La somme des cercles ne doit pas dépasser 100.</div>
    HTML;

$f = new Field();
$fields = $f->GetAllFields();
$options = '';
foreach ($fields as $field) {
    $fieldNames[$field['IdField']] = $field['FieldName'];
    $options .= '<option value="' . $field['IdField'] . '">' . $field['FieldName'] . '</option>';
}
$count = 1;
foreach ($prerequisites as $index => $prerequisite) {
    $type = htmlspecialchars($prerequisite['Type']);
    if ($type == 'F') {
        $type = "Formation";
    } else {
        $type = "Expérience";
    }
    $duration = htmlspecialchars($prerequisite['Duration']);
    $ponderation = htmlspecialchars($prerequisite['Ponderation']);
    $fieldType = htmlspecialchars($prerequisite['FieldType']);
    $complete = htmlspecialchars($prerequisite['Complete']);
    $PId = $prerequisite['Id'];

    $experienceChecked = ($type == "Expérience") ? "checked" : "";
    $formationChecked = ($type == "Formation") ? "checked" : "";

    $completeChecked = ($complete == 1) ? "checked" : "";

    $fieldTypeSelected = $fieldType ? "selected" : "";
    if ($prerequisite['Validite'] == 1) {
        $content .= <<<HTML
        <div class="dynamic-field form-group p-4 border rounded-lg shadow-md space-y-4 bg-gray-100">
        <div class="dot" style="background-color:#8B0000" title="Critere de recherche trop restrictif"></div>
    HTML;
    } elseif ($prerequisite['Validite'] == 2) {
        $content .= <<<HTML
        <div class="dynamic-field form-group p-4 border rounded-lg shadow-md space-y-4 bg-gray-100">
        <div class="dot" style="background-color:#A35D03" title="Prerequis majoritairement restrictif"></div>
    HTML;
    } elseif ($prerequisite['Validite'] == 3) {
        $content .= <<<HTML
        <div class="dynamic-field form-group p-4 border rounded-lg shadow-md space-y-4 bg-gray-100">
        <div class="dot" style="background-color:#FED000" title="Prerequis partiellement restrictif"></div>
    HTML;
    } elseif ($prerequisite['Validite'] == 4) {
        $content .= <<<HTML
        <div class="dynamic-field form-group p-4 border rounded-lg shadow-md space-y-4 bg-gray-100">
        <div class="dot" style="background-color:#008000" title="Prerequis acceptable"></div>
    HTML;
    }
    $content .= <<<HTML
                    <input type="hidden" name="PId$count" value="$PId">
                    <div class="flex gap-4 items-center">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="type$count" value="experience" class="radio radio-primary" $experienceChecked required> Expérience
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="type$count" value="formation" class="radio radio-primary" $formationChecked> Formation
                        </label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="complete$count" class="checkbox checkbox-primary" $completeChecked>
                        <label>Compléter</label>
                    </div>

                    <div class="flex gap-4">
                        <select name="FieldType$count" class="select select-bordered w-full">
                            <option value="" disabled>Select Field Type</option>
    HTML;


    foreach ($fields as $field) {
        $selected = ($field['IdField'] == $fieldType) ? "selected" : "";
        $content .= "<option value='{$field['IdField']}' $selected>{$field['FieldName']}</option>";
    }

    $content .= <<<HTML
                        </select>
                        <input class="input input-bordered w-20 md:w-24" type="number" name="year$count" placeholder="Années" min="0" max="50" value="$duration" required>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-full">
                            <input type="range" min="0" max="100" value="$ponderation" id="input$count" class="range range-primary" oninput="updateCircle('circle$count', this.value,'hiddenText$count')">
                            <input type="hidden" id="hiddenText$count" name="textInput$count" value="$ponderation">
                        </div>
                        <div class="flex justify-center items-center">
                            <svg id="svg$count" class="w-24 h-24" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="40" stroke-width="4" stroke="#e0e0e0" fill="none" />
                                <circle id="circle$count" cx="50" cy="50" r="40" stroke-width="4" stroke="#4a90e2" fill="none" stroke-dasharray="251.2" stroke-dashoffset="0" stroke-linecap="round" />
                                <text id="text$count" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#000" font-size="15" font-family="Arial">$ponderation</text>
                            </svg>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" onclick="removeField(this)" class="btn btn-error">Supprimer</button>
                    </div>
                </div>
HTML;
    $count++;
}





$content .= <<<HTML



            </div>
                </div>
            </div>
        </div>
    </form>

<div id="languageOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50">
<div id="offerContainer" data-offer-id=$offerId></div>
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-xl font-semibold mb-4">Sélectionnez une langue</h2>
        <select id="languageSelect" class="select select-bordered w-full">
            
        </select>
        <div class="flex justify-between mt-4">
            <button type="button" onclick="closeLanguageOverlay()" class="btn btn-neutral">Annuler</button>
            <button type="button" onclick="saveLanguage()" class="btn btn-success">Sauvegarder</button>
        </div>
    </div>
</div>
HTML;

include "Views/master.php";

?>

<script>
    //spinner
    function showSpinner() {
        const overlay = document.getElementById('loadingOverlay');
        overlay.classList.remove('hidden');
    }

    function hideSpinner() {
        const overlay = document.getElementById('loadingOverlay');
        overlay.classList.add('hidden');
    }

    function addField() {
        const form = document.getElementById('FormDynam');
        const fieldIndex = form.querySelectorAll('.dynamic-field').length + 1;

        const newFieldId = 'circle' + fieldIndex;
        const newTextId = 'text' + fieldIndex;
        const newInputId = 'input' + fieldIndex;
        const newTextInputName = 'textInput' + fieldIndex;
        const newNumberInputName = 'year' + fieldIndex;
        const hiddenInputId = 'hiddenText' + fieldIndex;

        const newField = document.createElement('div');
        newField.className = 'dynamic-field form-group p-4 border rounded-lg shadow-md space-y-4 bg-gray-100';

        newField.innerHTML = `
        <input type="hidden" name="PId${fieldIndex}" value="new">

        <!-- Radio Buttons for Type Selection -->
        <div class="flex gap-4 items-center">
            <label class="flex items-center gap-2">
                <input type="radio" name="type${fieldIndex}" value="experience" class="radio radio-primary" required> Expérience
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="type${fieldIndex}" value="formation" class="radio radio-primary"> Formation
            </label>
        </div>

        <!-- Checkbox for Completion -->
        <div class="flex items-center gap-2">
            <input type="checkbox" name="complete${fieldIndex}" class="checkbox checkbox-primary">
            <label>Compléter</label>
        </div>

        <!-- Field Type and Years Input -->
        <div class="flex gap-4">
            <select name="FieldType${fieldIndex}" class="select select-bordered w-full">
                <?php echo $options; ?>
            </select>
            <input class="input input-bordered w-20 md:w-24" type="number" name="${newNumberInputName}" placeholder="Années" min="0" max="50" required>
        </div>

        <!-- Progress Circle and Slider -->
        <div class="flex items-center gap-4">
            <div class="w-full">
                <input type="range" min="0" max="100" value="0" id="${newInputId}" class="range range-primary" oninput="updateCircle('${newFieldId}', this.value, '${hiddenInputId}')">
                <input type="hidden" id="${hiddenInputId}" name="${newTextInputName}" value="0">
            </div>
            <div class="flex justify-center items-center">
                <svg id="svg${fieldIndex}" class="w-24 h-24" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" stroke-width="4" stroke="#e0e0e0" fill="none" />
                    <circle id="${newFieldId}" cx="50" cy="50" r="40" stroke-width="4" stroke="#4a90e2" fill="none" stroke-dasharray="251.2" stroke-dashoffset="0" stroke-linecap="round" />
                    <text id="${newTextId}" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#000" font-size="15" font-family="Arial">0</text>
                </svg>
            </div>
        </div>

        <!-- Remove Field Button -->
        <div class="flex justify-end">
            <button type="button" onclick="removeField(this)" class="btn btn-error">Supprimer</button>
        </div>
    `;

        form.appendChild(newField);

        calculerCercle();
        updateCircle(newFieldId, 0, hiddenInputId);
    }

    function removeField(button) {
        const formGroup = button.closest('.dynamic-field');

        if (formGroup) {
            formGroup.remove();
            const somme = calculerCercle();
            validateSum(somme);
            adjustMaxValues(somme);
        }
    }

    function updateCircle(circleId, value, hiddenInputId) {
        const circle = document.getElementById(circleId);
        const radius = 40;
        const circumference = 2 * Math.PI * radius;
        const offset = circumference * (1 - value / 100);

        circle.style.strokeDashoffset = offset;
        circle.style.strokeDasharray = circumference;

        const textId = 'text' + circleId.match(/\d+/)[0];
        const textElement = document.getElementById(textId);
        textElement.textContent = value;

        const hiddenInput = document.getElementById(hiddenInputId);
        if (hiddenInput) {
            hiddenInput.value = value;
        }

        const somme = calculerCercle();
        validateSum(somme);
        adjustMaxValues(somme);
    }

    function adjustMaxValues(somme) {
        const inputs = document.querySelectorAll('#FormDynam .range');
        const remainingValue = 100 - somme;
        inputs.forEach(input => {
            const currentValue = parseInt(input.value, 10);
            input.max = currentValue + remainingValue;
        });
    }

    function roundToTwo(num) {
        return +(Math.round(num + "e+2") + "e-2");
    }

    function calculerCercle() {
        const sliders = document.querySelectorAll('#FormDynam .range');
        const tab = Array.from(sliders);

        let somme = 0;

        tab.forEach(slider => {
            somme += parseInt(slider.value, 10);
        });

        const temp = tab.length > 0 ? somme / tab.length : 0;

        const moyenne = roundToTwo(temp)

        document.getElementById('sommeAffichee').textContent = "Somme: " + somme;

        return somme;
    }

    function validateSum(somme) {
        const errorMessage = document.getElementById('errorMessage');

        if (somme > 100) {
            errorMessage.style.display = 'block';
            return false;
        } else {
            errorMessage.style.display = 'none';
            return true;
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const circle1 = document.getElementById('input1');

        if (circle1 != null) {
            updateCircle('circle1', document.getElementById('input1').value, 'hiddenText1');
            calculerCercle();
        }

    });

    function confirmDeletion(offerId) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette offre ? Cette action est irréversible.")) {
            window.location.href = 'deleteOffer.php?Id=' + offerId;
        }
    }

    //Partie du form
    document.getElementById('offerForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const overlay = document.getElementById('loadingOverlay');
        overlay.classList.remove('hidden');

        const formData = new FormData(this);
        const fieldsData = [];


        let fieldIndex = 1;
        document.querySelectorAll('.dynamic-field').forEach(function (field) {
            const type = formData.get(`type${fieldIndex}`);
            const fieldType = formData.get(`FieldType${fieldIndex}`);
            const ponderation = formData.get(`textInput${fieldIndex}`);
            const duration = formData.get(`year${fieldIndex}`);
            const complete = formData.get(`complete${fieldIndex}`) ? 1 : 0;
            const id = formData.get(`PId${fieldIndex}`);

            fieldsData.push({
                id: id,
                type: type,
                fieldType: fieldType,
                ponderation: ponderation,
                duration: duration,
                complete: complete
            });

            fieldIndex++;
        });

        fetch('offerAction.php', {
            method: 'POST',
            body: JSON.stringify({
                offerId: formData.get('offerId'),
                job: formData.get('job'),
                location: formData.get('location'),
                salary: formData.get('salary'),
                description: formData.get('description'),
                hours: formData.get('hours'),
                fieldsData: fieldsData
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json().catch(() => {
                throw new Error('Invalid JSON response');
            }))
            .then(data => {
                overlay.classList.add('hidden');

                if (data.success) {
                    alert('Offre mise à jour avec succès');
                    window.location.href = 'editOffer.php?id=' + formData.get('offerId');
                } else {
                    alert('Erreur lors de la mise à jour de l\'offre');
                }
                console.log(data);
            })
            .catch(error => {
                console.error('Erreur:', error);
                overlay.classList.add('hidden');
                alert('Une erreur est survenue. Veuillez réessayer.');
            });
    });


    //Partie Langue

    function addLanguage() {


        fetch('getLanguages.php')
            .then(response => response.json())
            .then(data => {

                // Ajoute les options de langues au select
                const languageSelect = document.getElementById('languageSelect');
                languageSelect.innerHTML = '';  // Réinitialise les options
                data.languages.forEach(language => {
                    const option = document.createElement('option');
                    option.value = language.LId;
                    option.textContent = language.LanguageName;
                    languageSelect.appendChild(option);
                });

                // Affiche l'overlay
                const overlay = document.getElementById('languageOverlay');
                overlay.classList.remove('hidden');
            })
            .catch(error => {
                alert('Erreur lors du chargement des langues');
            });
    }


    function closeLanguageOverlay() {
        const overlay = document.getElementById('languageOverlay');
        overlay.classList.add('hidden');
    }

    function removeLanguage(id) {
        const selectedLanguageId = id;

        const offerContainer = document.getElementById('offerContainer');
        const offerId = offerContainer.getAttribute('data-offer-id');
        showSpinner();

        fetch('deleteLanguageOffer.php', {
            method: 'POST',
            body: JSON.stringify({ LId: selectedLanguageId, offerId: offerId }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                hideSpinner();
                if (data.success) {
                    alert('Langue retirée avec succès');
                    const languageBadge = document.getElementById(`badge-${selectedLanguageId}`);
                    if (languageBadge) {
                        languageBadge.remove();
                    }
                } else {
                    alert('Erreur lors de la supression de la langue');
                }
            })
            .catch(error => {
                hideSpinner();
                alert('Erreur lors de la supression de la langue');
            });
    }

    function saveLanguage() {
        const languageSelect = document.getElementById('languageSelect');
        const selectedLanguageId = languageSelect.value;

        const offerContainer = document.getElementById('offerContainer');
        const offerId = offerContainer.getAttribute('data-offer-id');

        if (!offerId || !selectedLanguageId) {
            alert('Veuillez sélectionner une langue.');
            return;
        }
        //verif
        if (document.getElementById(`badge-${selectedLanguageId}`)) {
            alert('Cette langue a déjà été ajoutée.');
            return;
        }
        fetch('saveLanguage.php', {
            method: 'POST',
            body: JSON.stringify({ LId: selectedLanguageId, offerId: offerId }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Langue ajoutée avec succès');
                    closeLanguageOverlay();
                    location.reload()
                } else {
                    alert('Erreur lors de la sauvegarde de la langue');
                }
            })
            .catch(error => {
                alert('Erreur lors de la sauvegarde de la langue');
            });
    }

</script>

<style>
    hr {
        border: 3px solid;
        border-radius: 5px;
    }

    .spinner {
        border-top-color: #3498db;

    }

    .badge {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        padding: 0.5rem 1.5rem;
        color: white;
        background-color: #3490dc;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-transform: capitalize;
        white-space: nowrap;
    }

    .flex-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
</style>