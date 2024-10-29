<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$Id = $_POST['ExperienceId'];
$Title = $_POST['Title'];
$LocationName =$_POST['LocationName'];
$TypeExp = $_POST['TypeExp'];
$FieldType = $_POST['FieldType'];
$Description = $_POST['Description'];
$Duration = $_POST['Duration'];
$Complete = $_POST['Complete'];

$Profile = New Profile();
$Profile->UpdateExperienceEmp($Id,
$Title,
$LocationName,
$TypeExp,
$FieldType,
$Description,
$Duration,
$Complete);
header('Location: profile.php');
