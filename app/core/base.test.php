<?php

require_once __DIR__ . '/Database.php';

use App\Core\Database;

echo "=== TEST DE CONNEXION À LA BASE DE DONNÉES ===\n";

try {
$pdo = DataBase::getInstance()->getConnexion();
    $stmt = $pdo->query("SELECT NOW()");
    $result = $stmt->fetchColumn();

    echo "Connexion réussie  | Heure actuelle dans la base : $result\n";

} catch (Exception $e) {
    echo " Échec de la connexion : " . $e->getMessage() . "\n";
}