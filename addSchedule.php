<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$currentUserId =intval($_SESSION['currentUser']['Id']) ;

$hours = $_POST['hours'] ;

$weekday = isset($_POST['weekday']) ? 1 : 0;

$weekend = isset($_POST['weekend']) ? 1 : 0;

$evening = isset($_POST['evening']) ? 1 : 0;

$night = isset($_POST['night']) ? 1 : 0;


$Profile = New Profile();
$Profile->CreateSchedule($currentUserId,$weekday,$weekend,$evening,$night,$hours);
header('Location: profile.php');
