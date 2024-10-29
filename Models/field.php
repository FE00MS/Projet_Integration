<?php
require_once 'BD/BD.php';


class Field{
    private $conn;

    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function GetAllFields(){
        try {
            $sql = $this->conn->prepare("EXEC GetAllFields");
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }
    public function GetField($Id){
        try {
            $sql = $this->conn->prepare("EXEC GetField 
                @Id = :Id");
            $sql->bindParam(':Id', $Id, PDO::PARAM_INT);
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