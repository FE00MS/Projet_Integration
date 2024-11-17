<?php

//Les fonctions/procédures générales (All) se trouveront ici
class Database
{
    private $conn;

    public function __construct()
    {

        try {
            $this->conn = new PDO("sqlsrv:server=tcp:svjobfinder.database.windows.net,1433;Database=dbjobfinder", "azureuser", "banane2005?");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print("Error connecting to SQL Server.");
            die(print_r($e, true));
        }


        // $serverName = "THEBIGBOSSLAPTO\SQLEXPRESS";
        // $database = "jobfinder";
        // $username = "me2";
        // $password = "banane2005?";

        // try {
        //     $this ->conn  = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password);
        //     $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // } catch (PDOException $e) {
        //     echo "Erreur de connexion: " . $e->getMessage();
        // }


    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function GetAllOffers($critere = 'pond')
    {
        try {
            $sql = $this->conn->prepare("EXEC GetAllOffers");
            $sql->execute();
            $allOffers = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql = $this->conn->prepare("EXEC GetAllPrerequisite");
            $sql->execute();
            $allPrerequisite = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql = $this->conn->prepare("EXEC GetOffersLanguages");
            $sql->execute();
            $offersLanguages = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($allOffers) {
                if($critere == "pond"){
                    $currentUserId = $_SESSION['currentUser']['Id'];
                    $sql = $this->conn->prepare("EXEC GetMyExperience @idemp = :id");
                    $sql->bindParam(':id', $currentUserId, PDO::PARAM_INT);
                    $sql->execute();
                    $myExperience = $sql->fetchAll(PDO::FETCH_ASSOC);
                    $sql = $this->conn->prepare("EXEC GetMyLanguage @idemp = :id");
                    $sql->bindParam(':id', $currentUserId, PDO::PARAM_INT);
                    $sql->execute();
                    $myLanguage = $sql->fetchAll(PDO::FETCH_ASSOC);
                    $newOfferList = array();
                    foreach($allOffers as $offer){
                        $offerPrerequisite = array();
                        $offerLanguage = array();
                        foreach($allPrerequisite as $prerequisite){
                            if($prerequisite['OId'] == $offer['Id']){
                                array_push($offerPrerequisite,$prerequisite);
                            }
                        }
                        foreach($offersLanguages as $language){
                            if($language['OId'] == $offer['Id']){
                                array_push($offerLanguage, $language);
                            }
                        }
                        $offer['Ponderation'] = round($this->ponderateOffer($offerPrerequisite, $myExperience, $myLanguage, $offerLanguage),0);
                        array_push($newOfferList,$offer);
                    }
                    return $this->orderOffers($newOfferList);
                }
                //possibilité d'ajouter des tri differents que pondéré
                elseif($critere == "comp"){
                    return $allOffers;
                }
                
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }

    public function ponderateOffer($offerPrerequisite, $myExperience, $myLanguages, $offerLanguages){
        $totalPonderation = 0.01;
        $actualPonderation = 0;
        foreach($offerLanguages as $offerlanguage){
            $totalPonderation += 6;
            foreach($myLanguages as $language){
                if($offerlanguage['LId'] == $language['LId']){
                    if($language['Niveau'] == 'Rudimentaire'){
                        $actualPonderation += 2;
                    }
                    if($language['Niveau'] == 'Moyen'){
                        $actualPonderation += 4;
                    }
                    if($language['Niveau'] == 'Élevé'){
                        $actualPonderation += 6;
                    }
                }
            }
        }
        foreach($offerPrerequisite as $prerequisite){
            $totalPonderation += $prerequisite['Ponderation'];
            foreach($myExperience as $experience){
                if($experience['TypeExp'] == $prerequisite['Type']){
                    if($experience['FieldType'] == $prerequisite['FieldType'] || $prerequisite['FieldType'] == 'Au'){
                        if($experience['Complete'] == $prerequisite['Complete'] || $prerequisite['Complete'] != 1){
                            if($experience['Duration'] >= $prerequisite['Duration']){
                                $actualPonderation += $prerequisite['Ponderation'];
                            }
                            else{
                                $pourcentagePonderation = $experience['Duration'] /$prerequisite['Duration'];
                                $actualPonderation += $prerequisite['Ponderation']*$pourcentagePonderation;
                            }
                        }
                    }
                }
            }
        }
        return ($actualPonderation / $totalPonderation) * 100;
    }
    public function orderOffers($offerList){
        $ponderation = array_map(function ($offer) {
            return $offer['Ponderation'];
        }, $offerList);
        array_multisort($ponderation, SORT_DESC, $offerList);
        return $offerList;
    }
}
