<?php
namespace App\Repository;

use PDO;
use PDOException;
use App\Core\DataBase;

class UserRepository {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = DataBase::getInstance()->getConnexion();
    }

    public function insert(
        string $telephone,
        string $cni,
        string $nom,
        string $prenom,
        string $adresse,
        string $password,
        ?array $photoRecto,
        ?array $photoVerso
    ): ?array {
        try {
            // Créer le dossier d'upload si non existant
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Renommer les fichiers avec des noms uniques
            $photoRectoName = uniqid('recto_') . '_' . basename($photoRecto['name']);
            $photoVersoName = uniqid('verso_') . '_' . basename($photoVerso['name']);

            // Déplacer les fichiers uploadés
            move_uploaded_file($photoRecto['tmp_name'], $uploadDir . $photoRectoName);
            move_uploaded_file($photoVerso['tmp_name'], $uploadDir . $photoVersoName);

            
            $sqlUser = 'INSERT INTO "user" (nom, prenom, telephone, cni, adresse, password, photo_recto, photo_verso)
                        VALUES (:nom, :prenom, :telephone, :cni, :adresse, :password, :photo_recto, :photo_verso)';

            $stmtUser = $this->pdo->prepare($sqlUser);
            $stmtUser->bindParam(':nom', $nom);
            $stmtUser->bindParam(':prenom', $prenom);
            $stmtUser->bindParam(':telephone', $telephone);
            $stmtUser->bindParam(':cni', $cni);
            $stmtUser->bindParam(':adresse', $adresse);
            $stmtUser->bindParam(':password', $password);
            $stmtUser->bindParam(':photo_recto', $photoRectoName);
            $stmtUser->bindParam(':photo_verso', $photoVersoName);
            $stmtUser->execute();

            $lastId = (int) $this->pdo->lastInsertId();

            // Récupérer l'utilisateur nouvellement inséré
            $stmt = $this->pdo->prepare('SELECT * FROM "user" WHERE id = :id');
            $stmt->bindParam(':id', $lastId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new \Exception("Erreur lors de l'insertion de l'utilisateur : " . $e->getMessage());
        }
    }










    /**
     * Trouve un utilisateur par téléphone
     */
    public function findByTelephone($telephone)
    {
        try {
            $sql = "SELECT * FROM \"user\" WHERE telephone = :telephone LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->execute();
        
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (Exception $e) {
            error_log("Erreur findByTelephone: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifie si un téléphone existe déjà
     */
    public function telephoneExists($telephone)
    {
        $sql = "SELECT COUNT(*) FROM \"user\" WHERE telephone = :telephone";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Vérifie si un CNI existe déjà
     */
    public function cniExists($cni)
    {
        $sql = "SELECT COUNT(*) FROM \"user\" WHERE cni = :cni";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cni', $cni);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    public function register()
{
    $data = $_POST;
    $files = $_FILES;

    var_dump($data, $files); // ← ici pour voir si les données arrivent
    exit;
}












    public function findByTelephoneAndPassword(string $telephone, string $password): ?array {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM "user" WHERE telephone = :telephone');
            $stmt->bindParam(':telephone', $telephone);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // var_dump($user);
            // die(); // Debugging line to check user data
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return null;
        } catch (PDOException $e) {
            return null;
        }
    }










}
