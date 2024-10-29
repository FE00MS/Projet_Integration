<?php
include 'Utilities/sessionManager.php';  
require_once 'BD/BD.php';  
include 'Models/employee.php';  

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['empId']) && isset($_POST['offerId'])) {
    $employeeId = htmlspecialchars($_POST['empId']);  
    $offerId = htmlspecialchars($_POST['offerId']);  
} else {
    die("Missing required parameters.");
}

$employeeModel = new Employee();  

try {
    $result = $employeeModel->removeApplication($employeeId, $offerId);

    if ($result) {
        header('Location: myApplications.php');
        exit();
    } else {
        throw new Exception("Failed to remove the application.");
    }
} catch (Exception $e) {
    echo "Error removing the application: " . $e->getMessage();
}