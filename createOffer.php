<?php
include 'Utilities/sessionManager.php'; 

if(!isset($_SESSION['currentLanguage']))
{
    $_SESSION['currentLanguage'] = "FR";
}
$lang = $_SESSION['currentLanguage'];

$jsonFile = ($lang === "FR") ? "fr.json" : "en.json";

$jsonData = file_get_contents($jsonFile);

$translations = json_decode($jsonData, true);

$content = <<<HTML
    <div class="flex justify-between items-center pt-12">
        <h1 class="text-4xl font-bold pl-56">{$translations['createOffer']}</h1>
        <button class="text-3xl pr-56">✕</button>
    </div>

    <form id="offerForm" method="POST" action="offerAction.php">
        <div class="flex justify-center items-start mt-8 " >
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
                        <label class="block text-sm font-medium">{$translations['jobType']}</label>
                        <input type="text" name="type" class="mt-1 w-full border border-gray-300 rounded p-2" placeholder="Value">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">{$translations['salary']} (range)</label>
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
                </form>
            </div>
            <div id="dynamicForm" class = "pl-16 border-indigo-500">
                <p><button type="button" onclick="addField()">Ajouter</button></p>
                <div>Somme des cercles : <span id="sommeAffichee">0</span></div>
                <div id="errorMessage" style="color: red; display: none;">La somme des cercles ne doit pas dépasser 100.</div>
                    <div class="form-group flex items-center space-x-4">
                        <input type="text" name="textInput1" placeholder="Requis">
                        <input class = "w-48"type="number" name="numberInput1" placeholder="années d'expériences" min = "0" max = "50">
                        <svg id="svg1" width="100" height="100">
                            <circle cx="50" cy="50" r="40" stroke-width="4" stroke="#e0e0e0" fill="none"/>
                            <circle id="circle1" cx="50" cy="50" r="40" stroke-width="4" stroke="#4a90e2" fill="none" stroke-dasharray="62.8" stroke-dashoffset="0" stroke-linecap="round"/>
                            <text id="text1" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#000" font-size="15" font-family="Arial"></text>
                        </svg>
                        <input type="range" min="0" max="100" value="0" id="input1" class="slider" oninput="updateCircle('circle1', this.value)">
                        <button type="button" onclick="removeField(this)">Supprimer</button>
                    </div>
            </div>
    </form>
    <script>
        function addField() {
            const form = document.getElementById('dynamicForm');
            const newFieldId = 'circle' + (form.children.length + 1);
            const newTextId = 'text' + (form.children.length + 1);
            const newInputId = 'input' + (form.children.length + 1);
            const newTextInputName = 'textInput' + (form.children.length + 1);
            const newNumberInputName = 'numberInput' + (form.children.length + 1);
            const newField = document.createElement('div');
            newField.className = 'form-group flex items-center space-x-4';
            newField.innerHTML = `
                <input type="text" name="` + newTextInputName + `" placeholder="Requis" >
                <input class = "w-48" type="number" name="` + newNumberInputName + `" placeholder="années" min = "0" max = "50" >
                <svg id="svg` + (form.children.length + 1) + `" width="100" height="100">
                    <circle cx="50" cy="50" r="40" stroke-width="4" stroke="#e0e0e0" fill="none"/>
                    <circle id="` + newFieldId + `" cx="50" cy="50" r="40" stroke-width="4" stroke="#4a90e2" fill="none" stroke-dasharray="62.8" stroke-dashoffset="0" stroke-linecap="round"/>
                    <text id="` + newTextId + `" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#000" font-size="15" font-family="Arial">0</text>
                </svg>
                <input type="range" min="0" max="100" value="0" id="` + newInputId + `" class="slider" oninput="updateCircle('` + newFieldId + `', this.value)">
                <button type="button" onclick="removeField(this)">Supprimer</button>
            `;
            form.appendChild(newField);

            calculerCercle();
            updateCircle();
        }

        function removeField(button) {
            const formGroup = button.closest('.form-group');
            formGroup.remove();
            calculerCercle();
            validateSum();
        }

        function updateCircle(circleId, value) {
            const circle = document.getElementById(circleId);
            const radius = 40;
            const circumference = 2 * Math.PI * radius;

            const offset = circumference * (1 - value / 100);
            circle.style.strokeDashoffset = offset;
            circle.style.strokeDasharray = circumference;

            const textId = 'text' + circleId.match(/\d+/)[0]; 
            const textElement = document.getElementById(textId);
            textElement.textContent = value; 

            const somme = calculerCercle();
            validateSum(somme);

            // Adjust the max attribute of all inputs based on the remaining value
            const inputs = document.querySelectorAll('#dynamicForm .slider');
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
            const sliders = document.querySelectorAll('#dynamicForm .slider');
            const tab = Array.from(sliders); 

            let somme = 0;

            tab.forEach(slider => {
                somme += parseInt(slider.value, 10);
            });

            const temp = tab.length > 0 ? somme / tab.length : 0;

            const moyenne = roundToTwo(temp)

            document.getElementById('sommeAffichee').textContent = "Somme: " + somme + ", Moyenne: " + moyenne;

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
            updateCircle('circle1', document.getElementById('input1').value);
            calculerCercle();
        });
    </script>
HTML;

include "Views/master.php";