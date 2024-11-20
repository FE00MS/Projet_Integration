<?php
include 'Utilities/sessionManager.php'; 
require_once 'BD/BD.php';
include 'Models/employee.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}


if (isset($_GET['id'])) {
    $offerId = htmlspecialchars($_GET['id']); 
} else {
    die("Offer ID is missing.");
}

$employeeId = $_SESSION['currentUser']['Id']; 

$employeeModel = new Employee();

try {
    $result = $employeeModel->addApplication($employeeId, $offerId);

   redirect("offerDetails.php?id=" + $offerId);
    exit();
} catch (Exception $e) {
    echo "Error applying for the job: " . $e->getMessage();
}