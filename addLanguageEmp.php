<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$currentUserId =intval($_SESSION['currentUser']['Id']) ;
$LId = $_POST['Language']; 
$Niveau = $_POST['Level']; 



$Profile = New Profile();
$Profile->CreateLanguageEmp($currentUserId,$LId,$Niveau);
header('Location: profile.php');
