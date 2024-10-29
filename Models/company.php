<?php
require_once 'BD/BD.php';
class Company
{
    //Pour  l'instant tout les variables sont privvate mais peuvent etre changer plus tard 
    //et le seul moyen d'y avoir accès est grace à GetAll()
    private $conn;

    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
        
    }

    public function GetCompany($Email, $Password)
    {
        try {
            $sql = $this->conn->prepare("EXEC GetCompte @Email = :email, @Password = :password");
             $sql->bindParam(':email', $Email, PDO::PARAM_STR);
             $sql->bindParam(':password', $Password, PDO::PARAM_STR);
 
            
            $sql->execute();
            
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result; 
            } else {
                return $Email + $Password;
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }

    public function GetCompanyById($id){
        try{
            $sql = $this->conn->prepare("EXEC GetCompanyById @id = :id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $e){
            throw new Exception();
        }
    }
    public function CreateCompany($email, $password, $location, $cname, $photo, $customInvite, $description)
    {
        try {
            $sql = $this->conn->prepare("EXEC CreateCompany @Email = :email, @Password = :password, @Location = :location, @CName = :cname, @Photo = :photo, @CustomInvite = :customInvite, @Description = :description");
            $sql->bindParam(':email', $email, PDO::PARAM_STR);
            $sql->bindParam(':password', $password, PDO::PARAM_STR);
            $sql->bindParam(':location', $location, PDO::PARAM_STR);
            $sql->bindParam(':cname', $cname, PDO::PARAM_STR);
            $sql->bindParam(':photo', $photo, PDO::PARAM_STR);
            $sql->bindParam(':customInvite', $customInvite, PDO::PARAM_STR);
            $sql->bindParam(':description', $description, PDO::PARAM_STR);
            $sql->execute();
            
            return "Company created successfully";
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de l'entreprise : " . $e->getMessage());
        }
    }

    public function EditCompany($id,$email, $password, $location, $cname, $photo, $customInvite, $description)
    {
        try {
            $sql = $this->conn->prepare("EXEC EditCompany @id = :id, @Email = :email, @Password = :password, @Location = :location, @CName = :cname, @Photo = :photo, @CustomInvite = :customInvite, @Description = :description");
            
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->bindParam(':email', $email, PDO::PARAM_STR);
            $sql->bindParam(':password', $password, PDO::PARAM_STR);
            $sql->bindParam(':location', $location, PDO::PARAM_STR);
            $sql->bindParam(':cname', $cname, PDO::PARAM_STR);
            $sql->bindParam(':photo', $photo, PDO::PARAM_STR);
            $sql->bindParam(':customInvite', $customInvite, PDO::PARAM_STR);
            $sql->bindParam(':description', $description, PDO::PARAM_STR);
            $sql->execute();
            
            return "La compagnie à été modifié avec succès";
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la modification de l'entreprise : " . $e->getMessage());
        }
    }
}

