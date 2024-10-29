<?php
require_once 'BD/BD.php';

class   Profile{

    private $conn;

    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function DeleteExperienceEmp($Id){
        try {
            $sql = $this->conn->prepare("EXEC DeleteExperienceEmp 
            @Id = :Id");
            
            $sql->bindParam(':Id', $Id, PDO::PARAM_INT);

            $sql->execute();
            
            return "Experience deleted successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error deleting experience: " . $e->getMessage());
        }
    }
    public function CreateExperienceEmp($IdEmp,$Title,$LocationName,$TypeExp,$FieldType,$Description,$Duration,$Complete){
        try {
            $sql = $this->conn->prepare("EXEC CreateExperienceEmp 
            @IdEmp = :IdEmp
            , @Title = :Title
            , @LocationName = :LocationName,
             @TypeExp = :TypeExp,
              @FieldType = :FieldType, 
              @Description = :Description,
               @Duration = :Duration,
                @Complete = :Complete");
            
            $sql->bindParam(':IdEmp', $IdEmp, PDO::PARAM_INT);
            $sql->bindParam(':Title', $Title, PDO::PARAM_STR);
            $sql->bindParam(':LocationName', $LocationName, PDO::PARAM_STR);
            $sql->bindParam(':TypeExp', $TypeExp, PDO::PARAM_STR);
            $sql->bindParam(':FieldType', $FieldType, PDO::PARAM_STR);
            $sql->bindParam(':Description', $Description, PDO::PARAM_STR);
            $sql->bindParam(':Duration', $Duration, PDO::PARAM_INT);
            $sql->bindParam(':Complete', $Complete, PDO::PARAM_INT);

            $sql->execute();
            
            return "Experience created successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error creating experience: " . $e->getMessage());
        }
    }
    public function UpdateExperienceEmp($Id,$Title,$LocationName,$TypeExp,$FieldType,$Description,$Duration,$Complete){
        try {
            $sql = $this->conn->prepare("EXEC UpdateExperienceEmp 
            @Id = :Id
            , @Title = :Title
            , @LocationName = :LocationName,
             @TypeExp = :TypeExp,
              @FieldType = :FieldType, 
              @Description = :Description,
               @Duration = :Duration,
                @Complete = :Complete");
            
            $sql->bindParam(':Id', $Id, PDO::PARAM_INT);
            $sql->bindParam(':Title', $Title, PDO::PARAM_STR);
            $sql->bindParam(':LocationName', $LocationName, PDO::PARAM_STR);
            $sql->bindParam(':TypeExp', $TypeExp, PDO::PARAM_STR);
            $sql->bindParam(':FieldType', $FieldType, PDO::PARAM_STR);
            $sql->bindParam(':Description', $Description, PDO::PARAM_STR);
            $sql->bindParam(':Duration', $Duration, PDO::PARAM_INT);
            $sql->bindParam(':Complete', $Complete, PDO::PARAM_INT);

            $sql->execute();
            
            return "Experience created successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error creating experience: " . $e->getMessage());
        }
    }
    public function GetAllExperienceEmp($IdEmp){
        try {
            $sql = $this->conn->prepare("EXEC GetAllExperienceEmp
             @IdEmp = :IdEmp");
             $sql->bindParam(':IdEmp', $IdEmp, PDO::PARAM_INT);

            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }
    public function GetExperienceEmp($Id){
        try {
            $sql = $this->conn->prepare("EXEC GetExperienceEmp
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


    public function DeleteLanguageEmp($EId,$LId){
        try {
            $sql = $this->conn->prepare("EXEC DeleteLanguageEmp 
            @EId = :EId,
              @LId = :LId");
            
            $sql->bindParam(':EId', $EId, PDO::PARAM_INT);
            $sql->bindParam(':LId', $LId, PDO::PARAM_STR);

            $sql->execute();
            
            return "LanguageEmp deleted successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error deleting LanguageEmp: " . $e->getMessage());
        }
    }
    public function CreateLanguageEmp($EId,$LId,$Niveau){
        try {
            $sql = $this->conn->prepare("EXEC CreateLanguageEmp
            @EId = :EId,
            @LId = :LId,
            @Niveau = :Niveau");
            
            $sql->bindParam(':EId', $EId, PDO::PARAM_INT);
            $sql->bindParam(':LId', $LId, PDO::PARAM_INT);
            $sql->bindParam(':Niveau', $Niveau, PDO::PARAM_STR);

            $sql->execute();
            
            return "LanguageEmp created successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error creating LanguageEmp: " . $e->getMessage());
        }
    }

    public function UpdateLanguageEmp($EId,$OldLId,$NewLId,$Niveau){
        try {
            $sql = $this->conn->prepare("EXEC UpdateLanguageEmp
            @EId = :EId,
            @OldLId = :OldLId,
            @NewLId = :NewLId,
            @Niveau = :Niveau");
            
            $sql->bindParam(':EId', $EId, PDO::PARAM_INT);
            $sql->bindParam(':OldLId', $OldLId, PDO::PARAM_INT);
            $sql->bindParam(':NewLId', $NewLId, PDO::PARAM_INT);
            $sql->bindParam(':Niveau', $Niveau, PDO::PARAM_STR);


            $sql->execute();
            
            return "LanguageEmp created successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error creating LanguageEmp: " . $e->getMessage());
        }
    }

    public function GetAllLanguageEmp($EId){
        try {
            $sql = $this->conn->prepare("EXEC GetAllLanguageEmp
             @EId = :EId");
             $sql->bindParam(':EId', $EId, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }


    public function UpdateSalary($EId,$SalaryType,$SalaryAmount){
        try {
            $sql = $this->conn->prepare("EXEC UpdateSalary
            @EId = :EId,
            @SalaryType = :SalaryType,
            @SalaryAmount = :SalaryAmount");
            
            $sql->bindParam(':EId', $EId, PDO::PARAM_INT);
            $sql->bindParam(':SalaryType', $SalaryType, PDO::PARAM_STR); 
            $sql->bindParam(':SalaryAmount', $SalaryAmount, PDO::PARAM_INT);

            $sql->execute();
            
            return "Salray updated successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error updating Salray: " . $e->getMessage());
        }
    }
    public function GetAllSalary($EId){
        try {
            $sql = $this->conn->prepare("EXEC GetAllSalary
             @EId = :EId");
             $sql->bindParam(':EId', $EId, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }



    public function CreateSchedule($IdEmp,$WeekDay,$WeekEnd,$Evening,$Night,$Hours){
        try {
            $sql = $this->conn->prepare("EXEC CreateOrUpdateSchedule
            @IdEmp = :IdEmp,
            @WeekDay = :WeekDay,
            @WeekEnd = :WeekEnd,
            @Evening = :Evening,
            @Night = :Night,
            @Hours = :Hours");
            
            $sql->bindParam(':IdEmp', $IdEmp, PDO::PARAM_INT);
            $sql->bindParam(':WeekDay', $WeekDay, PDO::PARAM_INT);
            $sql->bindParam(':WeekEnd', $WeekEnd, PDO::PARAM_INT);
            $sql->bindParam(':Evening', $Evening, PDO::PARAM_INT);
            $sql->bindParam(':Night', $Night, PDO::PARAM_INT);
            $sql->bindParam(':Hours', $Hours, PDO::PARAM_INT);


            $sql->execute();
            
            return "Schedule created successfully.";
        } catch (PDOException $e) {
            throw new Exception("Error creating Schedule: " . $e->getMessage());
        }
    }
    public function GetAllSchedule($IdEmp){
        try {
            $sql = $this->conn->prepare("EXEC GetAllSchedule
             @IdEmp = :IdEmp");
             $sql->bindParam(':IdEmp', $IdEmp, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }
    public function DeleteSchedule($IdEmp){
        try {
            $sql = $this->conn->prepare("EXEC DeleteSchedule
             @IdEmp = :IdEmp");
             $sql->bindParam(':IdEmp', $IdEmp, PDO::PARAM_INT);
            $sql->execute();
      
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la destruction des données : " . $e->getMessage());
        }
    }
    public function GetAllNote($IdEmp){
        try {
            $sql = $this->conn->prepare("EXEC GetAllNote
             @IdEmp = :IdEmp");
             $sql->bindParam(':IdEmp', $IdEmp, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                return $result; 
            } 
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }

    public function CreateNote($IdEmp,$note){
        try {
            $sql = $this->conn->prepare("EXEC CreateNote
             @IdEmp = :IdEmp,
             @note =:note");
             $sql->bindParam(':IdEmp', $IdEmp, PDO::PARAM_INT);
             $sql->bindParam(':note', $note, PDO::PARAM_STR);

             $sql->execute();
      
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la creation des données : " . $e->getMessage());
        }
    }


    public function DeleteNote($Id){
        try {
            $sql = $this->conn->prepare("EXEC DeleteNote
             @Id = :Id");
             $sql->bindParam(':Id', $Id, PDO::PARAM_INT);

             $sql->execute();
      
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la destruction des données : " . $e->getMessage());
        }
    }

}