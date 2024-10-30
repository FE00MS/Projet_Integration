<?php
require_once 'BD/BD.php';

class Employee
{
    private $conn;

    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getApplications($idEmp)
    {
        try {
            $sql = $this->conn->prepare("EXEC getApplication @id = :id");
            $sql->bindParam(':id', $idEmp, PDO::PARAM_INT);
            
            $sql->execute();
            
            $applications = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if ($applications) {
                return $applications;  
            } else {
                return null;
            }
        } catch (PDOException $e) {
            throw new EmployeeException("Error retrieving applications: " . $e->getMessage());
        }
    }

    public function addApplication($idEmp, $idOffer)
    {
        try {
            $sql = $this->conn->prepare("EXEC addApplication @idEmp = :idEmp, @idOffer = :idOffer");
            $sql->bindParam(':idEmp', $idEmp, PDO::PARAM_INT);
            $sql->bindParam(':idOffer', $idOffer, PDO::PARAM_INT);
            
            $sql->execute();
            
            return "Application added successfully.";
        } catch (PDOException $e) {
            throw new EmployeeException("Error adding application: " . $e->getMessage());
        }
    }
    public function getCandidates($offerId) {
        $sql = "SELECT e.Id as EmployeeId, e.Name as CandidateName, e.LastName as CandidateLastName, c.Email as CandidateEmail
                FROM Application a
                JOIN Employee e ON a.IdEmp = e.Id
                JOIN Compte c ON e.Id = c.Id
                WHERE a.IdOffer = :offerId";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':offerId', $offerId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        public function removeApplication($idEmp, $idOffer)
    {
        try {
            $sql = $this->conn->prepare("EXEC removeApplication @idEmp = :idEmp, @idOffer = :idOffer");
            $sql->bindParam(':idEmp', $idEmp, PDO::PARAM_INT);
            $sql->bindParam(':idOffer', $idOffer, PDO::PARAM_INT);
            
            $sql->execute();
            
            return "Application removed successfully.";
        } catch (PDOException $e) {
            throw new EmployeeException("Error removing application: " . $e->getMessage());
        }
    }
    public function GetEmployee($Email, $Password)
    {
        try {
            $sql = $this->conn->prepare("EXEC GetCompte @Email = :email, @Password = :password");
            $sql->bindParam(':email', $Email, PDO::PARAM_STR);
            $sql->bindParam(':password', $Password, PDO::PARAM_STR);
            
            $sql->execute();
            
            $result = $sql->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return $result; 
            } else {
                return "No employee found with this information.";
            }
        } catch (PDOException $e) {
            throw new EmployeeException("Error retrieving employee data: " . $e->getMessage());
        }
    }
    public function GetEmployeeByIds($ids)
    {
        try {
            $sql = $this->conn->prepare("EXEC GetEmployeeById @idemp = :ids");
            $sql->bindParam(':ids', $ids, PDO::PARAM_INT);
            
            $sql->execute();
            
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                return $result; 
            } else {
                return "No employee found with this information.";
            }
        } catch (PDOException $e) {
            throw new EmployeeException("Error retrieving employee data: " . $e->getMessage());
        }
    }
    public function CreateEmployee($email, $password, $name, $lastname, $age, $expsalary, $hours)
    {
        try {
            $sql = $this->conn->prepare("EXEC CreateEmploye @email = :email, @password = :password, @name = :name, @lastname = :lastname, @age = :age, @expsalary = :expsalary, @hours = :hours");
            
            $sql->bindParam(':email', $email, PDO::PARAM_STR);
            $sql->bindParam(':password', $password, PDO::PARAM_STR);
            $sql->bindParam(':name', $name, PDO::PARAM_STR);
            $sql->bindParam(':lastname', $lastname, PDO::PARAM_STR);
            $sql->bindParam(':age', $age, PDO::PARAM_INT);
            $sql->bindParam(':expsalary', $expsalary, PDO::PARAM_INT);
            $sql->bindParam(':hours', $hours, PDO::PARAM_INT);
            
            $sql->execute();
            
            return "Employee created successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error creating employee: " . $e->getMessage());
        }
    }

    public function EditEmployee($id,$email, $password, $name, $lastname, $age, $expsalary, $hours)
    {
        try {
            $sql = $this->conn->prepare("EXEC EditEmploye @id = :id, @email = :email, @password = :password, @name = :name, @lastname = :lastname, @age = :age, @expsalary = :expsalary, @hours = :hours");
            
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->bindParam(':email', $email, PDO::PARAM_STR);
            $sql->bindParam(':password', $password, PDO::PARAM_STR);
            $sql->bindParam(':name', $name, PDO::PARAM_STR);
            $sql->bindParam(':lastname', $lastname, PDO::PARAM_STR);
            $sql->bindParam(':age', $age, PDO::PARAM_INT);
            $sql->bindParam(':expsalary', $expsalary, PDO::PARAM_INT);
            $sql->bindParam(':hours', $hours, PDO::PARAM_INT);
            
            $sql->execute();
            
            return "L'employé a été modifié avec succès";
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de l'employé : " . $e->getMessage());
        }
    }
}

class EmployeeException extends Exception {}