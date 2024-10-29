<?php

//C'est quoi Categorie

$LinkModals = <<<HTML
      
<dialog id="modal-add-link" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold">Ajouter un lien</h3>
        <form method="POST" action="addLink.php">
        <label class="block mb-2">
                        <span>Liens</span>
                        <input required type="url" name="link"  class="input input-bordered mt-1 block w-full">                 
                    </label>
                    <label class="block mb-2">
                        <span>Nom du lien</span>
                        <input required type="text" name="Name"  class="input input-bordered mt-1 block w-full">                 
                    </label>
            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Ajouter</button>
                <button class="btn btn-default" type="button" onclick="document.getElementById('modal-add-link').close()">Annuler</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
HTML;