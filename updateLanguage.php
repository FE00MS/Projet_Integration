<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$currentUserId =intval($_SESSION['currentUser']['Id']) ;
$NewLId = $_POST['Language']; 
$Niveau = $_POST['Niveau']; 
$OldLId = $_POST['OldLId']; 


$Profile = New Profile();
$Profile->UpdateLanguageEmp($currentUserId,$OldLId,$NewLId,$Niveau);
header('Location: profile.php');
