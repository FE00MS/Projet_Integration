<?php
require_once 'Models/rating.php';
require_once 'Utilities/sessionManager.php';

$employeeId = $_SESSION['currentUser']['Id'];
$companyId = $_POST['IdCompany'];

$ratingModel = new Rating();
$ratingModel->DeleteRating($employeeId, $companyId);

header('Location: offerDetails.php?id=' . $_POST['offerId']);
exit();
