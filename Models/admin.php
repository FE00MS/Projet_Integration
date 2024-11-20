<?php
require_once 'BD/BD.php';

class Admin {
    private $conn;

    function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function CreateReport($ReportType, $Reason, $IdReported, $IdSender, $isComplete) {
        try {
            $sql = $this->conn->prepare("EXEC CreateReport 
                @ReportType = :ReportType,
                @Reason = :Reason,
                @IdReported = :IdReported,
                @IdSender = :IdSender,
                @isComplete = :isComplete");

            $sql->bindParam(':ReportType', $ReportType, PDO::PARAM_STR);
            $sql->bindParam(':Reason', $Reason, PDO::PARAM_STR);
            $sql->bindParam(':IdReported', $IdReported, PDO::PARAM_INT);
            $sql->bindParam(':IdSender', $IdSender, PDO::PARAM_INT);
            $sql->bindParam(':isComplete', $isComplete, PDO::PARAM_INT);

            $sql->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création du rapport : " . $e->getMessage());
        }
    }

    
    public function GetAdmin($Email, $Password)
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
                return $Email + $Password;
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }

    public function GetReport()
    {
        try {
            $sql = $this->conn->prepare("EXEC GetReport ");

            $sql->execute();
            
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des signalements :  " . $e->getMessage());
        }
    }
    public function GetStatAdmin()
    {
        try {
            $sql = $this->conn->prepare("EXEC GetStatAdmin");
            $sql->execute();
            $stats = $sql->fetch(PDO::FETCH_ASSOC);
            return $stats;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des notifications : " . $e->getMessage());
        }
    }
    public function CreateAdmin($email, $password){
        try {
            $sql = $this->conn->prepare("EXEC CreateAdmin @email = :email, @password = :password");
            $sql->bindParam(':email', $email, PDO::PARAM_STR);
            $sql->bindParam(':password', $password, PDO::PARAM_STR);
            $sql->execute();
            
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la creation d'un administrateur : " . $e->getMessage());
        }
    }
}
