<?php
require_once 'Models/field.php';

// Dropbox
$f = new Field();
$fields = $f->GetAllFields();
$fieldNames = [];
$options = '';
foreach ($fields as $field) {
    $fieldNames[$field['IdField']] = $field['FieldName'];
    $options .= '<option value="' . $field['IdField'] . '">' . $field['FieldName'] . '</option>';
}

$FormationModals = <<<HTML
 <dialog id="modal-add-formation" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold">Ajouter une formation reçue</h3>
        <form method="POST" action="addFormation.php">
        
        <label class="block mb-2">
                <span>Intitulé de la formation</span>
                <input required type="text" name="Title" class="input input-bordered mt-1 block w-full">
            </label>

            <label class="block mb-2">
                <span>Nom de l'organisme de formation</span>
                <input required type="text" name="LocationName" class="input input-bordered mt-1 block w-full">
            </label>
            
        
            <label class="block mb-2">
                <span>Domaine</span>
                <select name="FieldType" class="input input-bordered mt-1 block w-full">
                   $options
                </select>                    
            </label>

            <label class="block mb-2">
                <span>Durée (en années)</span><!-- En année -->
                <input required type="number" name="Duration" class="input input-bordered mt-1 block w-full">
            </label>
            
            <label class="block mb-2">
                <span>Description</span>
                <input required type="text" name="Description" class="input input-bordered mt-1 block w-full">
            </label>
            
            <label class="block mb-2">
                <span>Formation terminée ?</span>
                <select name="Complete" required class="input input-bordered mt-1 block w-full">
                    <option value="" disabled selected>Sélectionnez</option>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </label>

            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Ajouter</button>
                <button class="btn btn-default" type="button" onclick="document.getElementById('modal-add-formation').close()">Annuler</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>Fermer</button>
    </form>
</dialog>
HTML;
?>
