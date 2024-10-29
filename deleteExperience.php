<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$ExperienceId = $_POST['ExperienceId'];


$Profile = New Profile();
$Profile->DeleteExperienceEmp($ExperienceId);
header('Location: profile.php');
