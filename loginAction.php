<?php
require_once 'Models/Account.php';
require_once 'Models/employee.php';
require_once 'Models/company.php';
require_once 'Models/admin.php';
include 'Utilities/sessionManager.php';
include 'Utilities/formUtilities.php';

try {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = sanitizeString($_POST['email']);
        $password = sanitizeString($_POST['password']);

        $account = new Account();
        $account->AddConnection();
        $accountData = $account->GetAccount($email, $password);
        if ($accountData["data"]["IsBlocked"] == 0) {
            if (isset($accountData["type"])) {

                if ($accountData["type"] === "Employee") {
                    $employee = new Employee();
                    $_SESSION["currentUser"] = $employee->GetEmployee($email, $password);
                    $_SESSION["accountType"] = "employee";
                    header('Location: homepage.php');
                    exit();
                } elseif ($accountData["type"] === "Company") {
                    $company = new Company();
                    $_SESSION["currentUser"] = $company->GetCompany($email, $password);
                    $_SESSION["accountType"] = "company";
                    header('Location: myOffers.php');
                    exit();
                } elseif ($accountData["type"] === "Admin") {
                    $admin = new Admin();
                    $_SESSION["currentUser"] = $admin->GetAdmin($email, $password);
                    $_SESSION["accountType"] = "admin";
                    header('Location: adminpage.php');
                    exit();
                }

            }
        }else{
            $_SESSION['LoginError'] = "Votre compte est actuellement bloqué, veuillez contacter un administrateur pour plus de détails";
            header('Location: login.php');
            exit();
        }

        throw new Exception("Error Processing Request", 1);



    }
} catch (Exception $e) {
    $_SESSION['LoginError'] = "Erreur dans le courriel ou dans le mot de passe. ";
    header('Location: login.php');
    exit();

}