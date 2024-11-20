<?php
require_once 'BD/BD.php';

class Account
{
    private $conn;

    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
    public function deleteAccount($id){
        try{
            $sql = $this->conn->prepare("EXEC delete_account @id = :id");
            $sql->bindParam(':id', $id, PDO::PARAM_INT);
            $sql->execute();
            return "Attachement deleted successfully.";
        } catch(PDOException $e){
            throw new Exception("Erreur lors de la suppression du compte : " . $e->getMessage());
        }
    }

    public function GetAccount($email, $password)
    {
        try {
            $sql = $this->conn->prepare("EXEC GetCompte @Email = :email, @Password = :password");
            $sql->bindParam(':email', $email, PDO::PARAM_STR);
            $sql->bindParam(':password', $password, PDO::PARAM_STR);
            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                if ($result['AccountType'] === 'C') {
                    return [
                        'type' => 'Company',
                        'data' => $result
                    ];
                } elseif  ($result['AccountType'] === 'E') {
                    return [
                        'type' => 'Employee',
                        'data' => $result
                    ];
                }else{
                    return [
                        'type' => 'Admin',
                        'data' => $result
                    ];
                }
                
            } else {
                return false; 
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }
    public function GetNotifications($idR)
    {
        try {
            $sql = $this->conn->prepare("EXEC GetMyNotification @IdR = :idR");
            $sql->bindParam(':idR', $idR, PDO::PARAM_INT);
            $sql->execute();
            $notifications = $sql->fetchAll(PDO::FETCH_ASSOC);

            return $notifications;
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des notifications : " . $e->getMessage());
        }
    }
    public function AddNotification($idS, $idR, $message, $title)
    {
        try {
            $sql = $this->conn->prepare("EXEC AddNotification @idS = :idS, @idR = :idR, @message = :message, @title = :title");
            $sql->bindParam(':idS', $idS, PDO::PARAM_INT);
            $sql->bindParam(':idR', $idR, PDO::PARAM_INT);
            $sql->bindParam(':message', $message, PDO::PARAM_STR);
            $sql->bindParam(':title', $title, PDO::PARAM_STR);
            $sql->execute();
            return "Notification added successfully.";
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de l'ajout de la notification : " . $e->getMessage());
        }
    }
    public function DeleteNotification($idN)
    {
        try {
            $sql = $this->conn->prepare("EXEC DeleteNotification @idN = :idN");
            $sql->bindParam(':idN', $idN, PDO::PARAM_INT);
            $sql->execute();
            return "Notification deleted successfully.";
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression de la notification : " . $e->getMessage());
        }
    }
    public function AddConnection(){
        try{
            $sql = $this->conn->prepare("EXEC DeleteNotification");
            $sql->execute();
            return 'Connection added';
        }catch(PDOException $e){
            throw new Exception("Erreur lors de la suppression de l'ajout d'une connection : " . $e->getMessage());
        }
        

    }
}

