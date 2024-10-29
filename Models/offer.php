<?php
require_once 'BD/BD.php';

class Offer{

    private $conn;
    
    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
        
    }

    
    public function CreateOffer($idc, $job, $location, $salary, $description, $hours)
    {
        try {
            $sql = $this->conn->prepare("EXEC CreateOffer @idc = :idc, @job = :job, @location = :location, @salary = :salary, @description = :description, @hours = :hours");
            $sql->bindParam(':idc', $idc, PDO::PARAM_INT);
            $sql->bindParam(':job', $job, PDO::PARAM_STR);
            $sql->bindParam(':location', $location, PDO::PARAM_STR);
            $sql->bindParam(':salary', $salary, PDO::PARAM_INT);
            $sql->bindParam(':description', $description, PDO::PARAM_STR);
            $sql->bindParam(':hours', $hours, PDO::PARAM_INT);
            $sql->execute();
            
            return "Création de l'offre réussi";
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de l'offre : " . $e->getMessage());
        }
    }
    public function GetOfferByCompagny($idC)
    {
        
        try {
            $sql = $this->conn->prepare("EXEC GetMyOffers @idc = :idC");
            $sql->bindParam(':idC', $idC, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }
    public function EditOffer($id,$idc, $job, $location, $salary, $description, $hours)
    {
        try {
            $sql = $this->conn->prepare("EXEC EditOffer  @id = :id, @idc = :idc, @job = :job, @location = :location, @salary = :salary, @description = :description, @hours = :hours");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->bindParam(':idc', $idc, PDO::PARAM_INT);
            $sql->bindParam(':job', $job, PDO::PARAM_STR);
            $sql->bindParam(':location', $location, PDO::PARAM_STR);
            $sql->bindParam(':salary', $salary, PDO::PARAM_INT);
            $sql->bindParam(':description', $description, PDO::PARAM_STR);
            $sql->bindParam(':hours', $hours, PDO::PARAM_INT);
            $sql->execute();
            
            return "Création de l'offre réussi";
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de l'offre : " . $e->getMessage());
        }
    }
    public function GetOffer($id)
    {
        try {
            $sql = $this->conn->prepare("EXEC GetOffer @id = :id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }
    
    public function GetAllOffers()
    {
        try {
            $sql = $this->conn->prepare("EXEC GetAllOffers");
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
