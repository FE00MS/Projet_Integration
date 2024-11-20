<?php
require_once 'Models/Offer.php';
include 'Utilities/sessionManager.php';
include 'Utilities/formUtilities.php';


header('Content-Type: application/json');
if (!isset($_SESSION['currentUser'])) {
    echo json_encode(["success" => false, "message" => "Utilisateur non connecté"]);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(["success" => false, "message" => "Aucune donnée reçue"]);
    exit;
}

$offer = new Offer();
$fieldsData = $data['fieldsData'];


if (!isset($data['languages']) || !is_array($data['languages'])) {
    $data['languages'] = []; 
}

$languages= $data['languages'];


if (!isset($data['offerId'])) {
    // Création d'une nouvelle offre
    $idc = $_SESSION["currentUser"]["Id"];
    $job = $data['job'];
    $location = $data['location'];
    $salary = $data['salary'];
    $description = $data['description'];
    $hours = $data['hours'];

    $OId = $offer->createOffer($idc, $job, $location, $salary, $description, $hours);
    if ($OId === false) {
        echo json_encode(["success" => false, "message" => "Erreur lors de la création de l'offre"]);
        exit();
    }

    if (!$offer->createPonderation($OId, $fieldsData)) {
        echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout des pondérations"]);
        exit();
    }


    foreach($languages as $languageId){
       if( !$offer->Add_langageOffer($OId,$languageId)){
        echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout des langues"]);
        exit();
       }
    }


    echo json_encode(["success" => true, "message" => "Offre créée avec succès"]);
} else {
    //Edit offre

    $offerId = $data['offerId'];
    $job = $data['job'];
    $location = $data['location'];
    $salary = $data['salary'];
    $description = $data['description'];
    $hours = $data['hours'];

    if (!$offer->EditOffer($offerId, $job, $location, $salary, $description, $hours)) {
        echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour de l'offre"]);
        exit();
    }

    //prérequis
    $existingPrerequisites = $offer->getPrerequisites($offerId);

    $existingIds = array_map(fn($prerequisite) => $prerequisite['Id'], $existingPrerequisites);
    $receivedIds = array_map(fn($field) => $field['id'], $fieldsData);


    $idsToDelete = array_diff($existingIds, $receivedIds);
    // Sup prérequis
    foreach ($idsToDelete as $idToDelete) {
        if (!$offer->DeletePrerequisite($idToDelete)) {
            echo json_encode(["success" => false, "message" => "Erreur lors de la suppression d'un prérequis"]);
            exit();
        }
    }

    // ajout ou edit prérequis
    
    foreach ($fieldsData as $field) {
        if (empty($field['id']) || $field['id'] === 'new') {
            if (!$offer->createPonderation($offerId, $field)) {
                echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout d'un nouveau prérequis"]);
                exit();
            }
        } else {
            if (!$offer->editPonderation($field)) {
                echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour d'un prérequis"]);
                exit();
            }
        }
    }

    echo json_encode(["success" => true, "message" => "Offre mise à jour avec succès"]);
}

?>