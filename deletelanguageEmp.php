<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$languageEmpId =intval($_POST['languageEmpId']) ;
$languageId = $_POST['languageId'] ;

$Profile = New Profile();
$Profile->DeleteLanguageEmp($languageEmpId,$languageId);
    
header('Location: profile.php');
