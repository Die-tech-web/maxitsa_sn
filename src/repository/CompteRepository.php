<?php
namespace App\Repository;

use App\Core\Database;
use PDO;

class CompteRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnexion();
    }

    /**
     * Crée un compte principal pour un utilisateur
     */
    public function createComptePrimaire(int $idUser, int $idTelephone, string $typeCompte = 'standard', string $password = null): array
    {
        try {
            $sql = "INSERT INTO compte (id_user, id_telephone, solde, type_compte, is_principal, password)
                    VALUES (:id_user, :id_telephone, :solde, :type_compte, :is_principal, :password)";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                'id_user' => $idUser,
                'id_telephone' => $idTelephone,
                'solde' => 0.00,
                'type_compte' => $typeCompte,
                'is_principal' => true,
                'password' => $password,
            ]);

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Compte principal créé avec succès',
                    'compte_id' => $this->pdo->lastInsertId(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Erreur lors de la création du compte',
            ];
        } catch (\PDOException $e) {
            error_log("Erreur création compte: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur de base de données : ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Trouve un compte principal par l'ID utilisateur
     */
    public function findComptePrincipalByUserId(int $idUser): ?array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM compte WHERE id_user = :id_user AND is_principal = TRUE');
            $stmt->execute(['id_user' => $idUser]);
            $compte = $stmt->fetch(PDO::FETCH_ASSOC);

            return $compte ?: null;
        } catch (\PDOException $e) {
            error_log("Erreur findComptePrincipalByUserId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Met à jour le solde d'un compte
     */
    public function updateSolde(int $compteId, float $nouveauSolde): bool
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE compte SET solde = :solde WHERE id = :id');
            return $stmt->execute([
                'solde' => $nouveauSolde,
                'id' => $compteId,
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur updateSolde: " . $e->getMessage());
            return false;
        }
    }
}
