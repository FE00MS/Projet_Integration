<?php
require_once 'Models/Account.php';
require_once 'Models/Employee.php';
require_once 'Models/Admin.php';
require_once 'Models/Company.php';
include 'Utilities/sessionManager.php';
include 'Utilities/formUtilities.php';

try {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = sanitizeString($_POST['email']);
        $password = sanitizeString($_POST['password']);
        $type = sanitizeString($_POST['type']);

        if ($type === 'employee') {
            if (isset($_POST['name']) && isset($_POST['lastname'])) {
                $name = sanitizeString($_POST['name']);
                $lastname = sanitizeString($_POST['lastname']);
                $age = 0;
                $expsalary = 0;
                $hours = 0;

                $employee = new Employee();
                try {
                    $employee->CreateEmployee($email, $password, $name, $lastname, $age, $expsalary, $hours);
                    $_SESSION["currentUser"] = $employee->GetEmployee($email, $password);
                    $_SESSION["accountType"] = "employee";
                    header('Location: homepage.php');

                } catch (PDOException $e) {
                    $_SESSION['signupError'] = "Problème à la création de compte";
                    header('Location: signupEmployee.php');
                }

            } else {

                throw new Exception();
            }
        } elseif ($type === 'company') {
            if (isset($_POST['company_name']) && isset($_POST['location'])) {
                $companyName = sanitizeString($_POST['company_name']);
                $location = sanitizeString($_POST['location']);

                $company = new Company();
                try {
                    $company->CreateCompany($email, $password, $location, $companyName, '', '', '');
                    $_SESSION['currentUser'] = $company->GetCompany($email, $password);
                    $_SESSION["accountType"] = "company";
                    header('Location: createOffer.php');

                } catch (Exception $e) {
                    $_SESSION['signupError'] = "Probleme à la création de compte";
                    header('Location: signupCompany.php');
                }

            } else {
                throw new Exception();
            }
        }elseif ($type === 'admin') {
            $admin = new Admin();
            try {
                $admin->CreateAdmin($email, $password);
                header('Location: adminpage.php');
            } catch (Exception $e) {
                $_SESSION['signupError'] = "Probleme à la création de compte";
                header('Location: signupAdmin.php');
            }
        }
        exit();
    }
} catch (Exception $e) {

    if ($type = 'employee') {
        $_SESSION['signupError'] = "Veuillez remplir tous les champs pour l'employé.";
        header('Location: signupEmployee.php');

    } else {
        $_SESSION['signupError'] = "Veuillez remplir tous les champs pour le companie .";
        header('Location: signupCompany.php');
    }
    exit();
}