<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$Id = $_POST['salaryId'];



$Profile = New Profile();
$Profile->UpdateSalary($Id,'fixe',0);
header('Location: profile.php');
