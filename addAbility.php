<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$currentUserId =intval($_SESSION['currentUser']['Id']) ;
$Title = $_POST['Title'];
$LocationName =$_POST['LocationName'];
$FieldType = $_POST['FieldType'];
$Description = $_POST['Description'];
$Duration = $_POST['Duration'];
$Complete = $_POST['Complete'];

$Profile = New Profile();
$Profile->CreateExperienceEmp($currentUserId,
$Title,
$LocationName,
'Abi',
$FieldType,
$Description,
$Duration,
$Complete);
header('Location: profile.php');
