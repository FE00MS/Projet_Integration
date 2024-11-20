<?php

// A revoir 
$AbilityModals = <<<HTML
  <dialog id="modal-add-ability" class="modal">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Ajouter une compétence aquise</h3>
                <form method="POST" action="addAbility.php">
                
                <label class="block mb-2">
                        <span>Nom de la compétence</span>
                        <input required type="text" name="Title"  class="input input-bordered mt-1 block w-full">
                    </label>
                    <label class="block mb-2">
                        <span>Nom de la companie</span>
                        <input required type="text" name="LocationName"class="input input-bordered mt-1 block w-full">
                    </label>
            
                    <label class="block mb-2">
                        <span>Domaine </span>
                        <select name="FieldType" class="input input-bordered mt-1 block w-full">
                           $options
                        </select>                    
                    </label>
                    <label class="block mb-2">
                        <span>Durée d'apprentissage (en mois)</span><!-- En mois pas en heure -->
                        <input required  type="number" name="Duration"  class="input input-bordered mt-1 block w-full">
                    </label>
                    <label class="block mb-2">
                        <span>Description de la compétence</span>
                        <input required type="text" name="Description"  class="input input-bordered mt-1 block w-full">
                    </label>
                    <label class="block mb-2">
                        <span>Niveau de maîtrise</span>
                            <select name="Complete" required class="input input-bordered mt-1 block w-full">
                            <option value="" disabled selected>Sélectionnez</option>
                             <option value="0">Débutant</option> <!-- Possibilité d'ajouter plus de niveau  -->
                            <option value="1">Avancé</option>
                            </select>
                    </label>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <button class="btn btn-default" type="button" onclick="document.getElementById('modal-add-ability').close()">Annuler</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
HTML;