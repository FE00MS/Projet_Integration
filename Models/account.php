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
}

