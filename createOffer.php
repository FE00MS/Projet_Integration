<?php
include 'Utilities/sessionManager.php'; 
require_once 'Models/field.php';

if(!isset($_SESSION['currentLanguage']))
{
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
    <div class="flex justify-between items-center pt-12">
        <h1 class="text-4xl font-bold pl-56">{$translations['createOffer']}</h1>
        <button class="text-3xl pr-56">✕</button>
    </div>

    <form id="offerForm" method="POST" action="offerAction.php" class="border-2 border-blue-500 p-6 rounded-lg shadow-lg">
        <div class="flex justify-center items-start mt-8">
            <div class="w-1/3 space-y-4 border-r-4 pr-48 border-indigo-500">
            <div>
                    <label class="block text-sm font-medium">{$translations['offerName']}</label>
                    <input type="text" name="job" class="mt-1 w-full border border-gray-300 rounded p-2" placeholder="Value">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">{$translations['location']}</label>
                        <input type="text" name="location" class="mt-1 w-full border border-gray-300 rounded p-2" placeholder="Value">
                    </div>
            
                    <div>
                        <label class="block text-sm font-medium">{$translations['salary']}</label>
                        <input type="text" name="salary" class="mt-1 w-full border border-gray-300 rounded p-2" placeholder="Value">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" class="mt-1 w-full border border-gray-300 rounded p-2" placeholder="Value"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">{$translations['hours']}</label>
                        <input type="text" name="hours" class="mt-1 w-full border border-gray-300 rounded p-2" placeholder="Value">
                    </div>
                    <button type="submit" class="btn btn-neutral w-full">{$translations['create']}</button>
            </div>
            <div id="FormDynam" class="pl-16 border-indigo-500 space-y-4">
                <button type="button" onclick="addField()" class="bg-green-500 text-white py-2 px-4 rounded">Ajouter</button>
                <div>Somme des cercles : <span id="sommeAffichee">0</span></div>
                <div id="errorMessage" class="text-red-500" style="display: none;">La somme des cercles ne doit pas dépasser 100.</div>
                <div class="dynamic-field form-group flex items-center space-x-4">
            <div>
                    <label class="block mb-2">
                        <input type="radio" name="type1" value="experience" class="mr-2" required> Expérience
                    </label>
                    <label class="block mb-2">
                        <input type="radio" name="type1" value="formation" class="mr-2"> Formation
                    </label>
                </div>
                <input type="hidden" name="complete1" value="0"> 
                <label class="block mb-2">
                    <input type="checkbox" name="complete1" class="mr-2"> Compléter
                </label>
                <label class="block mb-2">
                    <select name="FieldType1" class="input input-bordered mt-1 block w-full">
                        <?php echo $options; ?>
                    </select>
                </label>
                <input class="w-48 border border-gray-300 rounded p-2" type="number" name="year1" placeholder="années d'expériences" min="0" max="50" required>
                <svg id="svg1" width="120" height="120">
                    <circle cx="60" cy="60" r="40" stroke-width="4" stroke="#e0e0e0" fill="none"/>
                    <circle  id="circle1" cx="60" cy="60" r="40" stroke-width="4" stroke="#4a90e2" fill="none" stroke-dasharray="251.2" stroke-dashoffset="0" stroke-linecap="round"/>
                    <text id="text1" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#000" font-size="15" font-family="Arial"></text>
                </svg>
                <input type="range" min="0" max="100" value="0" id="input1" class="slider" oninput="updateCircle('circle1', this.value,'hiddenText1')">
                <input type="hidden" id="hiddenText1" name="textInput1" value="0">

                <button type="button" onclick="removeField(this)" class="bg-red-500 text-white py-2 px-4 rounded">Supprimer</button>
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

        const fieldIndex = form.querySelectorAll('.dynamic-field').length + 1;        const newFieldId = 'circle' + fieldIndex;
        const newTextId = 'text' + fieldIndex;
        const newInputId = 'input' + fieldIndex;
        const newTextInputName = 'textInput' + fieldIndex;
        const newNumberInputName = 'year' + fieldIndex;
        const hiddenInputId = 'hiddenText' + fieldIndex;

        const newField = document.createElement('div');
        newField.className = 'dynamic-field form-group flex items-center space-x-4';
        newField.innerHTML = `
              <div>
                <label class="block mb-2">
                    <input type="radio" name="type${fieldIndex}" value="experience" class="mr-2" required> Expérience
                </label>
                <label class="block mb-2">
                    <input type="radio" name="type${fieldIndex}" value="competence" class="mr-2"> Formation
                </label>
            </div>
            <input type="hidden" name="complete${fieldIndex}" value="0">
            <label class="block mb-2">
                <input type="checkbox" name="complete${fieldIndex}" class="mr-2"> Compléter
            </label>
            <label class="block mb-2">
                <select name="FieldType${fieldIndex}" class="input input-bordered mt-1 block w-full">
                    <?php echo $options; ?>
                </select>
            </label>
            <input class="w-48 border border-gray-300 rounded p-2" type="number" name="${newNumberInputName}" placeholder="années d'expériences" min="0" max="50" required>
            <svg id="svg${fieldIndex}" width="120" height="120">
                <circle cx="60" cy="60" r="40" stroke-width="4" stroke="#e0e0e0" fill="none"/>
                <circle id="${newFieldId}" cx="60" cy="60" r="40" stroke-width="4" stroke="#4a90e2" fill="none" stroke-dasharray="251.2" stroke-dashoffset="0" stroke-linecap="round"/>
                <text id="${newTextId}" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#000" font-size="15" font-family="Arial">0</text>
            </svg>
            <input type="range" min="0" max="100" value="0" id="${newInputId}" class="slider" oninput="updateCircle('${newFieldId}', this.value, '${hiddenInputId}')">
            <input type="hidden" id="${hiddenInputId}" name="${newTextInputName}" value="0">
            <button type="button" onclick="removeField(this)" class="bg-red-500 text-white py-2 px-4 rounded">Supprimer</button>
        `;
        form.appendChild(newField);

        calculerCercle();
        updateCircle(newFieldId, 0,hiddenInputId);
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

    function updateCircle(circleId, value,hiddenInputId) {
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
        const inputs = document.querySelectorAll('#FormDynam .slider');
        const remainingValue = 100 - somme;
        inputs.forEach(input => {
            const currentValue = parseInt(input.value, 10);
            input.max = currentValue + remainingValue;
        });
    }

    function roundToTwo(num) {
        return +(Math.round(num + "e+2")  + "e-2");
    }

    function calculerCercle() {
        const sliders = document.querySelectorAll('#FormDynam .slider');
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

    document.addEventListener("DOMContentLoaded", function() {
        updateCircle('circle1', document.getElementById('input1').value,'hiddenText1');
        calculerCercle();
    });

//Partie du form
    document.getElementById('offerForm').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const formData = new FormData(this);
    const fieldsData = [];

    
    let fieldIndex = 1;
    document.querySelectorAll('.dynamic-field').forEach(function(field) {
        const type = formData.get(`type${fieldIndex}`);
        const fieldType = formData.get(`FieldType${fieldIndex}`);
        const ponderation = formData.get(`textInput${fieldIndex}`);
        const duration = formData.get(`year${fieldIndex}`);
        const complete = formData.get(`complete${fieldIndex}`) ? 0 : 1;

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
        if (data.success) {
            alert('Offre créée avec succès');
            window.location.href = 'myOffers.php';
        } else {
            alert('Erreur lors de la création de l\'offre');
        }
        console.log(data)
    })
    .catch(error => console.error('Erreur:', error));
});


</script>