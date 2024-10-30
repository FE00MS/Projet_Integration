<?php
require_once 'Models/company.php';
require_once 'Models/offer.php';
require_once 'Models/employee.php';
include 'Utilities/sessionManager.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit;
} 


$employeeId = $_POST['empId'];
$offerId = $_POST['offerId'];
$employeeModel = new Employee();

try {
    $result = $employeeModel->removeApplication($employeeId, $offerId);

    if ($result) {
        header('Location: myCandidates.php');
        exit();
    } else {
        throw new Exception("Failed to remove the application.");
    }
} catch (Exception $e) {
    echo "Error removing the application: " . $e->getMessage();
}