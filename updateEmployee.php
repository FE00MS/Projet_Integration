<?php
include 'Utilities/sessionManager.php'; 
include 'Models/employee.php';

if (!isset($_SESSION['currentUser'])) {
    header('Location: login.php');
    exit();
}
$currentUser = $_SESSION['currentUser'];
$id = $currentUser['Id'];
$hours = $currentUser['Hours'];
$expsalary = $currentUser['ExpSalary'];
$email = $_POST['Email'];
$password = $_POST['Password'];
$age = $_POST["Age"];
$name = $_POST['Name'];
$lastname = $_POST['LastName'];


$employee = New Employee();
$employee->EditEmployee($id, $email, $password, $name, $lastname, $age, $expsalary, $hours);
$_SESSION['currentUser'] = $employee->GetEmployee($email,$password);
header('Location: profile.php');
