<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$currentUserId =intval($_SESSION['currentUser']['Id']) ;
$note = $_POST['note'];

$P = new Profile();
$P->CreateNote($currentUserId,$note);
header('Location: profile.php');
