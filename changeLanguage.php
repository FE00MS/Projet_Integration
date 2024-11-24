<?php
include 'Utilities/sessionManager.php';

if (isset($_GET['lang'])) {
    $_SESSION['currentLanguage'] = $_GET['lang'];
    $redirectUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php'; 
    header("Location: $redirectUrl");
} else {
    echo json_encode(['success' => false]);
}
