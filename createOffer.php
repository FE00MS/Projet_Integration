<?php
include 'Utilities/sessionManager.php';
require_once 'Models/field.php';

if (!isset($_SESSION['currentLanguage'])) {
    $_SESSION['currentLanguage'] = "FR";
}
$lang = $_SESSION['currentLanguage'];

$jsonFile = ($lang === "FR") ? "fr.json" : "en.json";
$jsonData = file_get_contents($jsonFile);
$translations = json_decode($jsonData, true);

$f = new Field();
$fields = $f->GetAllFields();
$fieldNames = [];
$options = '';
foreach ($fields as $field) {
    $fieldNames[$field['IdField']] = $field['FieldName'];
    $options .= '<option value="' . $field['IdField'] . '">' . $field['FieldName'] . '</option>';
}

$content = <<<HTML
<div id="loadingOverlay" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="spinner border-t-4 border-b-4 border-blue-500 w-16 h-16 rounded-full animate-spin"></div>
</div>

    <div class="flex justify-between items-center pt-12">
        <h1 class="text-4xl font-bold pl-56">{$translations['createOffer']}</h1>
    </div>

    <form id="offerForm" method="POST" action="offerAction.php" class="border-2 p-6 rounded-lg shadow-lg w-full max-w-5xl mx-auto mt-8 space-y-8">

        <div class="flex flex-col md:flex-row justify-between items-start gap-8">
            <div class="w-full md:w-1/2 space-y-6 pr-8 md:pr-16">
                <div class="form-control">
                    <label class="label font-semibold">{$translations['offerName']}</label>
                    <input type="text" name="job" class="input input-bordered w-full" placeholder="Nom de l'offre" required>
                </div>

                <div class="form-control">
                    <label class="label font-semibold">{$translations['location']}</label>
                    <input type="text" name="location" class="input input-bordered w-full" placeholder="Lieu du poste" required>
                </div>

                <div class="form-control">
                    <label class="label font-semibold">{$translations['salary']}</label>
                    <input type="number" min="0" name="salary" class="input input-bordered w-full" placeholder="Salaire proposé" required>
                </div>

                <div class="form-control">
                    <label class="label font-semibold">Description</label>
                    <textarea name="description" class="textarea textarea-bordered w-full" placeholder="Description du poste" required></textarea>
                </div>

                <div class="form-control">
                    <label class="label font-semibold">{$translations['hours']}</label>
                    <input type="number" min="0" max="168" name="hours" class="input input-bordered w-full" placeholder="Heures de travail" required>
                </div>

                <button type="submit" class="btn btn-neutral w-full">{$translations['create']}</button>
            </div>

                <div id="FormDynam" class="w-full md:w-1/2 pl-0 md:pl-16 space-y-6">
                        <button type="button" onclick="addField()" class="btn btn-success mb-4">Ajouter</button>

                        <div class="font-semibold">Somme des cercles : <span id="sommeAffichee">0</span></div>
                        <div id="errorMessage" class="text-error hidden">La somme des cercles ne doit pas dépasser 100.</div>

                        <div class="dynamic-field form-group p-4 border rounded-lg shadow-md space-y-4 bg-gray-100">
                            <div class="flex gap-4 items-center">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="type1" value="experience" class="radio radio-primary" required> Expérience
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="type1" value="formation" class="radio radio-primary"> Formation
                                </label>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="complete1" class="checkbox checkbox-primary">
                                <label>Compléter</label>
                            </div>

                            <div class="flex gap-4">
                                <select name="FieldType1" class="select select-bordered w-full">
                                    {$options}
                                </select>
                                <input class="input input-bordered w-20 md:w-24" type="number" name="year1" placeholder="Années" min="0" max="50" required>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-full">
                                    <input type="range" min="0" max="100" value="0" id="input1" class="range range-primary" oninput="updateCircle('circle1', this.value,'hiddenText1')">
                                    <input type="hidden" id="hiddenText1" name="textInput1" value="0">
                                </div>
                                <div class="flex justify-center items-center">
                                    <svg id="svg1" class="w-24 h-24" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="40" stroke-width="4" stroke="#e0e0e0" fill="none" />
                                        <circle id="circle1" cx="50" cy="50" r="40" stroke-width="4" stroke="#4a90e2" fill="none" stroke-dasharray="251.2" stroke-dashoffset="0" stroke-linecap="round" />
                                        <text id="text1" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#000" font-size="15" font-family="Arial">0</text>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" onclick="removeField(this)" class="btn btn-error">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
HTML;

include "Views/master.php";
?>

<script>
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
        updateCircle('circle1', document.getElementById('input1').value, 'hiddenText1');
        calculerCercle();
    });

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

            fieldsData.push({
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
            .then(response => response.json())
            .then(data => {
                overlay.classList.add('hidden');

                if (data.success) {
                    alert('Offre créée avec succès');
                    window.location.href = 'myOffers.php';
                } else {
                    alert('Erreur lors de la création de l\'offre');
                }
                console.log(data)
            })
            .catch(error => {
                console.error('Erreur:', error);
                overlay.classList.add('hidden'); 
                alert('Une erreur est survenue. Veuillez réessayer.');
            } );
    });


</script>
<style>
    .spinner {
        border-top-color: #3498db;
        /* Couleur du spinner */
    }
</style>