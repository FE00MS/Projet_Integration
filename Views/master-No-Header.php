<?php
include 'Views/header.php';
$styles = file_get_contents("views/styles.html");
$scripts = file_get_contents("views/scripts.html");

echo <<<HTML
<!DOCTYPE html>
<html data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boussole à emploi</title>
    $styles
    $scripts
</head>
<body>
    $content
</body>
</html>
HTML;


