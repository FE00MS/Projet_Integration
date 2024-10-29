<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$currentUserId =intval($_SESSION['currentUser']['Id']) ;

$Profile = New Profile();
$Profile->DeleteSchedule($currentUserId);
header('Location: profile.php');
