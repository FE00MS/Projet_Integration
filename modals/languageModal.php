<?php
require_once 'Models/language.php';

$l = new Language();
$languages = $l->GetAllLanguages();
$languagesOption = '';
foreach ($languages as $language) {
    $languagesOption .= '<option value="' . $language['LId'] . '">' . $language['LanguageName'] . '</option>';
}


$LanguageModals = <<<HTML
      <!-- Model language (Possibilité d'ajouter le niveau de langue parlé)-->
      <dialog id="modal-add-language" class="modal">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Ajouter une langue parlée</h3>
                <form method="POST" action="addLanguageEmp.php">
                <label class="block mb-2">
                        <span>Langues</span>
                        <select name="Language" class="input input-bordered mt-1 block w-full">
                           $languagesOption
                        </select>                    
                    </label>
                    <label class="block mb-2">
                <span>Niveau de maîtrise</span>
                <select name="Level" required class="input input-bordered mt-1 block w-full">
                    <option value="" disabled selected>Sélectionnez</option>
                    <option value="Rudimentaire">Rudimentaire</option>
                    <option value="Moyen">Moyen</option>
                    <option value="Élevé">Élevé</option>
                </select>
            </label>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <button class="btn btn-default" type="button" onclick="document.getElementById('modal-add-language').close()">Annuler</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
HTML;