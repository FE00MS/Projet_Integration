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
    
    public function GetAllOffers($critere = 'pond')
    {
        $currentUserId = $_SESSION['currentUser']['Id'];

        try {
            $sql = $this->conn->prepare("EXEC GetAllOffers");
            $sql->execute();
            $allOffers = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql = $this->conn->prepare("EXEC GetAllPrerequisite");
            $sql->execute();
            $allPrerequisite = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql = $this->conn->prepare("EXEC GetMyExperience @idemp = :id");
            $sql->bindParam(':id', $currentUserId, PDO::PARAM_INT);
            $sql->execute();
            $myExperience = $sql->fetch(PDO::FETCH_ASSOC);
            $sql = $this->conn->prepare("EXEC GetMyLanguage @idemp = :id");
            $sql->bindParam(':id', $currentUserId, PDO::PARAM_INT);
            $sql->execute();
            $myLanguage = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql = $this->conn->prepare("EXEC GetOffersLanguages");
            $sql->execute();
            $offersLanguages = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($allOffers) {
                if($critere == "pond"){
                    foreach($allOffers as $offer){
                        $offerPrerequisite = [];
                        $offerLanguage = [];
                        foreach($allPrerequisite as $prerequisite){
                            if($prerequisite['OId'] == $offer['Id']){
                                $offerPrerequisite.array_push($prerequisite);
                            }
                        }
                        foreach($offersLanguages as $language){
                            if($language['OId'] == $offer['Id']){
                                $offerPrerequisite.array_push($language);
                            }
                        }
                        $offer['Ponderation'] = $this->ponderateOffer($offerPrerequisite, $myExperience, $myLanguage, $offerLanguage);
                    }
                    return $allOffers;
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
                if($offerlanguage['Lid'] == $language['Lid']){
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
                if($experience['Type'] == $prerequisite['Type']){
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
}
