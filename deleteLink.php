<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Attachement.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$AttachementId = $_POST['AttachementId'];

$Attachement = New Attachement();
$Attachement->DeleteAttachement(
    $AttachementId);
header('Location: profile.php');
