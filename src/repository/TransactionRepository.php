<?php
namespace App\Repository;

use App\Core\DataBase;
use PDO;

class TransactionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance()->getConnexion();
    }

    public function insert($id_compte, $montant, $type, $description, $reference)
    {
        try {
            $query = "INSERT INTO transactions (id_compte, montant, type_transactions, description, reference)
                      VALUES (:id_compte, :montant, :type, :description, :reference)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_compte', $id_compte);
            $stmt->bindParam(':montant', $montant);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':reference', $reference);

            return $stmt->execute();
        } catch (\Exception $e) {
            error_log("Erreur TransactionRepository::insert: " . $e->getMessage());
            return false;
        }
    }

    public function findByCompte($id_compte)
    {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id_compte = :id_compte ORDER BY date_transaction DESC");
        $stmt->bindParam(':id_compte', $id_compte);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


public function findTransactionsByTelephone(string $telephone): array
{
   $sql = "
        SELECT 
            t.type_transactions AS type,
            t.description AS description,
            t.montant AS amount,
            TO_CHAR(t.created_at, 'YYYY-MM-DD') AS date,
            TO_CHAR(t.created_at, 'HH24:MI') AS time
        FROM transactions t
        JOIN compte c ON t.id_compte = c.id
        JOIN \"user\" u ON u.id = c.id_user
        WHERE u.telephone = ?
        ORDER BY t.created_at DESC
        LIMIT 10
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$telephone]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



                   public function getSoldeByTelephone(string $telephone): string
{
    $sql = "
        SELECT SUM(solde) as total_solde
        FROM compte c
        JOIN \"user\" u ON c.id_user = u.id
        WHERE u.telephone = ?
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$telephone]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

return isset($result['total_solde']) ? (string)$result['total_solde'] : '0';
}


}
