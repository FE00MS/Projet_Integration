<?php
include 'Utilities/sessionManager.php'; 
include 'Models/admin.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$reportType = $_POST['ReportType'];
$reason = $_POST['Reason'];
$idReported = intval($_POST['IdReported']);
$idSender = intval($_POST['IdSender']);

$admin = new Admin();
$admin->CreateReport($reportType, $reason, $idReported, $idSender, 0); 

header('Location: homepage.php?report=success');
exit();
