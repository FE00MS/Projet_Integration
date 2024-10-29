<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$currentUserId =intval($_SESSION['currentUser']['Id']) ;
$TypeSalaire = $_POST['TypeSalaire'];
$Amount = $_POST['Amount'];

$Profile = New Profile();
$Profile->UpdateSalary($currentUserId,$TypeSalaire,$Amount);
header('Location: profile.php');
