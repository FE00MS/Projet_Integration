<?php

//Les fonctions/procédures générales (All) se trouveront ici
class Database
{
    private $conn;

    public function __construct()
    {

        // try {
        //     $this->conn = new PDO("sqlsrv:server=tcp:serveurjobfinder.database.windows.net,1433;Database=dbjobfinder", "azureuser", "banane2005?");
        //     $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // } catch (PDOException $e) {
        //     print("Error connecting to SQL Server.");
        //     die(print_r($e, true));
        // }


        $serverName = "THEBIGBOSSLAPTO\SQLEXPRESS";
        $database = "jobfinder";
        $username = "me2";
        $password = "banane2005?";

        try {
            $this ->conn  = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "Erreur de connexion: " . $e->getMessage();
        }


    }

    public function getConnection()
    {
        return $this->conn;
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
