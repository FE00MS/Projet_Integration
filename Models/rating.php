<?php
require_once 'BD/BD.php';


class Rating{
    private $conn;

    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function CreateRating($IdEmp, $Name, $LastName, $IdCompany, $Rate) {
        try {
            $sql = $this->conn->prepare("EXEC CreateRating 
                @IdEmp = :IdEmp,
                @Name = :Name,
                @LastName = :LastName,
                @IdCompany = :IdCompany,
                @Rate = :Rate");
            $sql->bindParam(':IdEmp', $IdEmp, PDO::PARAM_INT);
            $sql->bindParam(':Name', $Name, PDO::PARAM_STR);
            $sql->bindParam(':LastName', $LastName, PDO::PARAM_STR);
            $sql->bindParam(':IdCompany', $IdCompany, PDO::PARAM_INT);
            $sql->bindParam(':Rate', $Rate, PDO::PARAM_INT);
    
            $sql->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout de la note : " . $e->getMessage());
        }
    }
    

    public function DeleteRating($IdEmp, $IdCompany) {
        try {
            $sql = $this->conn->prepare("EXEC DeleteRating @IdEmp = :IdEmp, @IdCompany = :IdCompany");
            $sql->bindParam(':IdEmp', $IdEmp, PDO::PARAM_INT);
            $sql->bindParam(':IdCompany', $IdCompany, PDO::PARAM_INT);
            $sql->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression du rating : " . $e->getMessage());
        }
    }

    public function GetAllRating($IdCompany) {
        try {
            $sql = $this->conn->prepare("EXEC GetAllRating @IdCompany = :IdCompany");
            $sql->bindParam(':IdCompany', $IdCompany, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération de tous les ratings : " . $e->getMessage());
        }
    }

}