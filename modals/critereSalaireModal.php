<?php
 //Ajouter les types de salaires dans la table Employe et Offre 

$CritereSalaireModals = <<<HTML
  <dialog id="modal-add-critereSalaire" class="modal">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Ajouter le salaire recherché</h3>
                <form method="POST" action="updateEmployeeSalary.php">
                <label class="block mb-2">
                        <span>Types de salaire</span>
                        <select name="TypeSalaire" class="input input-bordered mt-1 block w-full">
                         <option value="horaire">Salaire horaire</option> 
                         <option value="commission">Commission</option>
                         <option value="pourboire">Pourboire</option>
                         <option value="contract">Salaire à forfait ou à contrat</option>
                         <option value="fixe">Salaire fixe</option>
                        </select>                    
                    </label>
                    <label class="block mb-2">
                    <span>Montant</span>
                    <input type="number" min="0" name="Amount" class="input input-bordered mt-1 block w-full" required>
                        </label>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <button class="btn btn-default" type="button" onclick="document.getElementById('modal-add-critereSalaire').close()">Annuler</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

HTML;