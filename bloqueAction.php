<?php
include 'Utilities/sessionManager.php'; 
include 'Models/admin.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}

$compteId = intval($_GET['id']);
$action = intval($_GET['action']);

$admin = new Admin();
var_dump($action,$compteId);
if($action == 1){
    $admin->BlockSomeone($compteId);
}else{
    $admin->UnBlockSomeone($compteId);
}


header('Location: gestionUser.php');
