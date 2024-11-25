<?php
include 'Views/header.php';
$styles = file_get_contents("views/styles.html");
$scripts = file_get_contents("views/scripts.html");

echo <<<HTML
<!DOCTYPE html>
<html data-theme="light"style="background-color: #f0f4f8;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/compass.png" type="image/png">

    <title>Boussole à emploi</title>
    $styles
    $scripts
</head>
<body>
    $header
    $content
</body>
</html>
HTML;