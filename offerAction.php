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
// Creation d'offre
$idc = $_SESSION["currentUser"]["Id"];
$job = $data['job'];
$location = $data['location'];
$salary = $data['salary'];
$description = $data['description'];
$hours = $data['hours'];
$offer = new Offer();

$OId = $offer->createOffer($idc, $job, $location, $salary, $description, $hours);
if ($OId === false) {
    echo json_encode(["success" => false, "message" => "Erreur lors de la création de l'offre"]);
    exit();
}

$fieldsData = $data['fieldsData'];
if (!$offer->createPonderation($OId, $fieldsData)) {
    echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout des pondérations"]);
    exit();
}


$response = [
    "success" => true,
    "message" => "Offre reçue et ajoutée avec succès",
    "data_received" => $data
];

echo json_encode($response);
?>