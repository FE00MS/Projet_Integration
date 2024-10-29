<?php
require_once 'BD/BD.php';

class  Attachement{

    private $conn;

    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function DeleteAttachement($Id){
        try {
            $sql = $this->conn->prepare("EXEC DeleteAttachement 
            @Id = :Id");
            
            $sql->bindParam(':Id', $Id, PDO::PARAM_INT);

            $sql->execute();
            
            return "Attachement deleted successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error deleting attachement: " . $e->getMessage());
        }
    }
    public function CreateAttachement($IdC,$Name,$Link){
        try {
            $sql = $this->conn->prepare("EXEC CreateAttachement
            @IdC = :IdC,
            @Name = :Name
            , @Link = :Link");
            
            $sql->bindParam(':IdC', $IdC, PDO::PARAM_INT);
            $sql->bindParam(':Name', $Name, PDO::PARAM_STR);

            $sql->bindParam(':Link', $Link, PDO::PARAM_STR);

            $sql->execute();
            
            return "Attachement created successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error creating Attachement: " . $e->getMessage());
        }
    }
    public function GetAllAttachement($IdC){
        try {
            $sql = $this->conn->prepare("EXEC GetAllAttachement
             @IdC = :IdC");
             $sql->bindParam(':IdC', $IdC, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }
}