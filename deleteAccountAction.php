<?php
require_once 'Models/Account.php';
require_once 'Utilities/sessionManager.php';


if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}


$accountId = intval($_SESSION['currentUser']['Id']);

$accountModel = new Account();

try {
    $result = $accountModel->deleteAccount($accountId);

    if ($result) {
        session_destroy();
        header('Location: index.php'); 

        exit();
    } else {
        throw new Exception("Failed to delete the account.");
    }
} catch (Exception $e) {
    echo "Error deleting the account: " . $e->getMessage();
}