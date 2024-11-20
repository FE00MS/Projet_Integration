<?php
include 'BD/BD.php';
include 'Models/offer.php';

$data = json_decode(file_get_contents('php://input'), true);
$languageId = $data['LId'];
$offerId =$data['offerId'];

$offermodel = new Offer();
$offermodel->DeleteLangueoffer($offerId,$languageId);

echo json_encode(['success' => true]);
?>