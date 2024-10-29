<?php

//Mieux faire table Schedules car c quoi Category ?

$ScheduleModals = <<<HTML
      
<dialog id="modal-add-schedule" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold">Ajouter un horaire</h3>
        <form method="POST" action="addSchedule.php">
        <label class="block mb-2">
                        <span>Temps par semaine (en heures)</span>
                        <input required  min="0" max="168" type="number" name="hours"  class="input input-bordered mt-1 block w-full">                 
                    </label>
                    <label class="block mb-2">
                <span>Moments disponibles :</span>
                <div class="flex flex-col gap-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="weekday" class="checkbox checkbox-primary">
                        <span class="ml-2">En semaine</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="weekend" class="checkbox checkbox-primary">
                        <span class="ml-2">Fin de semaine</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="evening"  class="checkbox checkbox-primary">
                        <span class="ml-2">En soirée</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="night" class="checkbox checkbox-primary">
                        <span class="ml-2">De nuit</span>
                    </label>
                </div>
            </label>
            <div class="modal-action">
                <button type="submit" class="btn btn-primary">Ajouter</button>
                <button class="btn btn-default" type="button" onclick="document.getElementById('modal-add-schedule').close()">Annuler</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
HTML;