<?php
include 'Utilities/sessionManager.php'; 
include 'Models/admin.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$compteId = intval($_GET['Id']);
$action = intval($_GET['action']);

$admin = new Admin();

if($action == 0){
    $admin->BlockSomeone($compteId);
}else{
    $admin->UnBlockSomeone($compteId);
}


header('Location: gestionUser.php');
