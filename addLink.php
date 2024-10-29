<?php
include 'Utilities/sessionManager.php'; 
include 'Models/attachement.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$currentUserId =intval($_SESSION['currentUser']['Id']) ;
$Link = $_POST['link'];
$Name = $_POST['Name'];



$attachemnt = New Attachement();
$attachemnt->CreateAttachement(
    $currentUserId,
    $Name,
 $Link);
header('Location: profile.php');
