<?php
include 'Utilities/sessionManager.php'; 
include 'Models/Editprofile.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$Id = $_POST['noteId'];

$P = new Profile();
$P->DeleteNote($Id);
header('Location: profile.php');
