<?php
include 'Utilities/sessionManager.php'; 
include 'Models/company.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$currentUser = $_SESSION['currentUser'];
var_dump($currentUser);
$id = $currentUser['Id'];
$cname = $_POST['CName'];
$location = $_POST['Location'];
$email = $_POST['Email'];
$password = $_POST['Password'];
$description = $_POST['Description'];
$customInv = $_POST['CustomInvite'];



$company = New Company();
$company->EditCompany($id, $email, $password, $location, $cname, "", $customInv, $description);
$_SESSION['currentUser'] = $company->GetCompany($email,$password);
header('Location: profile.php');