<?php
namespace App\Repository;

use App\Core\DataBase; // Attention, ici c’est bien DataBase (avec un B majuscule) si ton fichier s'appelle ainsi
use PDO;
use PDOException;

class AgentRepository
{
    private $db;

  public function __construct()
{
    $database = DataBase::getInstance();
    $this->db = $database->getConnexion(); // ✔️ bon nom utilisé
}


public function findByTelephone($telephone)
{
    try {
        $query = "
            SELECT u.*
            FROM \"user\" u
            JOIN profil p ON u.id = p.id_user
            WHERE u.telephone = :telephone AND p.agent_commercial IS NOT NULL
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $result : false;
    } catch (\Exception $e) {
        error_log("Erreur AgentRepository::findByTelephone: " . $e->getMessage());
        return false;
    }
}



    public function findAll()
    {
        try {
            $query = "SELECT * FROM agents ORDER BY nom, prenom";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            error_log("Erreur AgentRepository::findAll: " . $e->getMessage());
            return [];
        }
    }

    public function findById($id)
    {
        try {
            $query = "SELECT * FROM agents WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result ? $result : false;
        } catch (\Exception $e) {
            error_log("Erreur AgentRepository::findById: " . $e->getMessage());
            return false;
        }
    }

    public function insert($nom, $prenom, $telephone, $email, $password)
    {
        try {
            $query = "INSERT INTO agents (nom, prenom, telephone, email, password, created_at) 
                     VALUES (:nom, :prenom, :telephone, :email, :password, NOW())";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            
            if ($stmt->execute()) {
                return $this->findById($this->db->lastInsertId());
            }
            return false;
        } catch (\Exception $e) {
            error_log("Erreur AgentRepository::insert: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $nom, $prenom, $telephone, $email)
    {
        try {
            $query = "UPDATE agents SET nom = :nom, prenom = :prenom, telephone = :telephone, 
                     email = :email, updated_at = NOW() WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':email', $email);
            
            return $stmt->execute();
        } catch (\Exception $e) {
            error_log("Erreur AgentRepository::update: " . $e->getMessage());
            return false;
        }
    }
}
