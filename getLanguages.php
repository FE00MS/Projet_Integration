<?php
include 'BD/BD.php';
include 'Models/language.php';

$l = new Language();
$languages = $l->GetAllLanguages();

echo json_encode(['languages' => $languages]);
?>


