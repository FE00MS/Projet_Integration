<?php
require_once 'BD/BD.php';

class Offer
{

    public $conn;
    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();

    }
    function deleteOffer($offerId) {
        try {
            $stmt = $this->conn->prepare("EXEC DeleteOffer @OId = :offerId");
    
            $stmt->bindParam(':offerId', $offerId, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return true;
    
        } catch (PDOException $e) {
            error_log("Erreur lors de l'exécution de DeleteOffer: " . $e->getMessage());
            return false;
        }
    }

    function DeletePrerequisite($Id) {
        try {
            $stmt = $this->conn->prepare("EXEC DeletePrerequisite @Id = :Id");
    
            $stmt->bindParam(':Id', $Id, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return true;
    
        } catch (PDOException $e) {
            error_log("Erreur lors de l'exécution de DeletePrerequisite: " . $e->getMessage());
            return false;
        }
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
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de l'offre : " . $e->getMessage());
        }
    }
    function editPonderation($field)
    {
    
            $stmt = $this->conn->prepare("EXEC dbo.editPrerequisite :Id, :Type, :FieldType, :Ponderation, :Duration, :Complete");

         
                $fieldType = '';
                if ($field['type'] === 'experience') {
                    $fieldType = 'E';
                } elseif ($field['type'] === 'formation') {
                    $fieldType = 'F';
                }
                $stmt->bindParam(':Id', $field['id'], PDO::PARAM_INT);
                $stmt->bindParam(':Type', $fieldType, PDO::PARAM_STR); 
                $stmt->bindParam(':FieldType', $field['fieldType'], PDO::PARAM_STR);
                $stmt->bindParam(':Ponderation', $field['ponderation'], PDO::PARAM_INT);
                $stmt->bindParam(':Duration', $field['duration'], PDO::PARAM_INT); 
                $stmt->bindParam(':Complete', $field['complete'], PDO::PARAM_INT); 

                if (!$stmt->execute()) {
                    return false;
                }
            
            return true;
      
    }
    function createPonderation($OId, $fieldsData)
    {
    
            $stmt = $this->conn->prepare("EXEC dbo.createPonderation :OId, :Type, :FieldType, :Ponderation, :Duration, :Complete");

            foreach ($fieldsData as $fieldData) {
                $fieldType = '';
                if ($fieldData['type'] === 'experience') {
                    $fieldType = 'E';
                } elseif ($fieldData['type'] === 'formation') {
                    $fieldType = 'F';
                }
                $stmt->bindParam(':OId', $OId, PDO::PARAM_INT);
                $stmt->bindParam(':Type', $fieldType, PDO::PARAM_STR); 
                $stmt->bindParam(':FieldType', $fieldData['fieldType'], PDO::PARAM_STR);
                $stmt->bindParam(':Ponderation', $fieldData['ponderation'], PDO::PARAM_INT);
                $stmt->bindParam(':Duration', $fieldData['duration'], PDO::PARAM_INT); 
                $stmt->bindParam(':Complete', $fieldData['complete'], PDO::PARAM_INT); 

                if (!$stmt->execute()) {
                    return false;
                }
            }
            return true;
      
    }
    function createPonderation2($OId, $field)
    {
    
            $stmt = $this->conn->prepare("EXEC dbo.createPonderation :OId, :Type, :FieldType, :Ponderation, :Duration, :Complete");

     
                $fieldType = '';
                if ($field['type'] === 'experience') {
                    $fieldType = 'E';
                } elseif ($field['type'] === 'formation') {
                    $fieldType = 'F';
                }
                $stmt->bindParam(':OId', $OId, PDO::PARAM_INT);
                $stmt->bindParam(':Type', $fieldType, PDO::PARAM_STR); 
                $stmt->bindParam(':FieldType', $field['fieldType'], PDO::PARAM_STR);
                $stmt->bindParam(':Ponderation', $field['ponderation'], PDO::PARAM_INT);
                $stmt->bindParam(':Duration', $field['duration'], PDO::PARAM_INT); 
                $stmt->bindParam(':Complete', $field['complete'], PDO::PARAM_INT); 

                if (!$stmt->execute()) {
                    return false;
                }
            
            return true;
      
    }


    public function AddOfferNotification($idS, $idR, $message, $Oid){
        $title = "Nouvelle offre pour vous";
        var_dump($Oid);
        try {
            $sql = $this->conn->prepare("EXEC AddNotificationWithOffer @idS = :idS, @idR = :idR, @message = :message, @title = :title, @linkedOffer = :linkedoffer");
            $sql->bindParam(':idS', $idS, PDO::PARAM_INT);
            $sql->bindParam(':idR', $idR, PDO::PARAM_INT);
            $sql->bindParam(':message', $message, PDO::PARAM_STR);
            $sql->bindParam(':title', $title, PDO::PARAM_STR);
            $sql->bindParam(':linkedoffer', $Oid, PDO::PARAM_INT);
            $sql->execute();
            return "Notification added successfully.";
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout de la notification : " . $e->getMessage());
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
    public function EditOffer($id, $job, $location, $salary, $description, $hours)
    {
        try {
            $sql = $this->conn->prepare("EXEC EditOffer  @id = :id, @job = :job, @location = :location, @salary = :salary, @description = :description, @hours = :hours");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
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

    function getPrerequisites($OId)
    {

        $stmt = $this->conn->prepare("EXEC GetPrerequisite :OId");
        $stmt->bindParam(":OId", $OId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getPrerequisitesWithRestrictions($OId){
        $myPrerequisites = $this->getPrerequisites($OId);
        $stmt = $this->conn->prepare("EXEC getAllExperience");
        $stmt->execute();
        $allExp = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $compteurPrerequis = 0;
        foreach($myPrerequisites as $prerequisite){
            $compteurValide = 0;
            $compteurPartiel = 0;
            foreach($allExp as $experience){

                if($prerequisite['Type'] == $experience['TypeExp']){
                    if ($experience['FieldType'] == $prerequisite['FieldType'] || $prerequisite['FieldType'] == 'Au') {
                        if ($experience['Complete'] == $prerequisite['Complete'] || $prerequisite['Complete'] != 1) {
                            if ($experience['Duration'] >= $prerequisite['Duration']) {
                                $compteurValide = $compteurValide + 1;    
                            }
                            else{
                                $compteurPartiel = $compteurPartiel + 1;
                            }
                        }
                        else{
                            $compteurPartiel = $compteurPartiel + 1;
                        }
                    }
                }
            }
            if($compteurValide == 0){
                $myPrerequisites[$compteurPrerequis]['Validite'] = 1;
            }
            if($compteurPartiel > $compteurValide && $compteurValide == 0){
                $myPrerequisites[$compteurPrerequis]['Validite'] = 2;
            }
            if($compteurPartiel >= $compteurValide && $compteurValide != 0){
                $myPrerequisites[$compteurPrerequis]['Validite'] = 3;
            }
            if($compteurPartiel < $compteurValide){
                $myPrerequisites[$compteurPrerequis]['Validite'] = 4;
            }
            $compteurPrerequis += 1;
        }
        return $myPrerequisites;
        
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
                if ($critere == "pond") {
                    foreach ($allOffers as $offer) {
                        $offerPrerequisite = [];
                        $offerLanguage = [];
                        foreach ($allPrerequisite as $prerequisite) {
                            if ($prerequisite['OId'] == $offer['Id']) {
                                $offerPrerequisite . array_push($prerequisite);
                            }
                        }
                        foreach ($offersLanguages as $language) {
                            if ($language['OId'] == $offer['Id']) {
                                $offerPrerequisite . array_push($language);
                            }
                        }
                        $offer['Ponderation'] = $this->ponderateOffer($offerPrerequisite, $myExperience, $myLanguage, $offerLanguage);
                    }
                    return $allOffers;
                }
                //possibilité d'ajouter des tri differents que pondéré
                elseif ($critere == "comp") {
                    return $allOffers;
                }

            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }

    public function ponderateOffer($offerPrerequisite, $myExperience, $myLanguages, $offerLanguages)
    {
        $totalPonderation = 0.01;
        $actualPonderation = 0;
        foreach ($offerLanguages as $offerlanguage) {
            $totalPonderation += 6;
            foreach ($myLanguages as $language) {
                if ($offerlanguage['Lid'] == $language['Lid']) {
                    if ($language['Niveau'] == 'Rudimentaire') {
                        $actualPonderation += 2;
                    }
                    if ($language['Niveau'] == 'Moyen') {
                        $actualPonderation += 4;
                    }
                    if ($language['Niveau'] == 'Élevé') {
                        $actualPonderation += 6;
                    }
                }
            }
        }
        foreach ($offerPrerequisite as $prerequisite) {
            $totalPonderation += $prerequisite['Ponderation'];
            foreach ($myExperience as $experience) {
                if ($experience['TypeExp'] == $prerequisite['Type']) {
                    if ($experience['FieldType'] == $prerequisite['FieldType'] || $prerequisite['FieldType'] == 'Au') {
                        if ($experience['Complete'] == $prerequisite['Complete'] || $prerequisite['Complete'] != 1) {
                            if ($experience['Duration'] >= $prerequisite['Duration']) {
                                $actualPonderation += $prerequisite['Ponderation'];
                            } else {
                                $pourcentagePonderation = $experience['Duration'] / $prerequisite['Duration'];
                                $actualPonderation += $prerequisite['Ponderation'] * $pourcentagePonderation;
                            }
                        }
                    }
                }
            }
        }
        return ($actualPonderation / $totalPonderation) * 100;
    }
}
