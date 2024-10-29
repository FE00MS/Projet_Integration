<?php
require_once 'BD/BD.php';


class Language{
    private $conn;

    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function GetAllLanguages(){
        try {
            $sql = $this->conn->prepare("EXEC GetAllLanguages");
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }

    public function GetLanguage($LId){
        try {
            $sql = $this->conn->prepare("EXEC GetLanguage
            @LId = :LId");
            $sql->bindParam(':LId', $LId, PDO::PARAM_STR);
         
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