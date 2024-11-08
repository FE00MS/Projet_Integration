<?php
include 'Utilities/sessionManager.php'; 
require_once 'Models/account.php';

$accountModel = new Account();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    try {
        $result = $accountModel->DeleteNotification($id);
    } catch (Exception $e) {
        return 'error';
    }
} else {
    return 'id missing';
}