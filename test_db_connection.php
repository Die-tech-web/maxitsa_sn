<?php

require_once 'app/core/Database.php';

use App\Core\Database;

try {
    echo "Test de connexion à la base de données...\n";
    
    $database = Database::getInstance();
    $pdo = $database->getConnexion();
    
    echo "✅ Connexion réussie !\n";
    echo "Version PostgreSQL : ";
    
    $stmt = $pdo->query('SELECT version()');
    $version = $stmt->fetchColumn();
    echo substr($version, 0, 50) . "...\n";
    
    // Tester les tables
    echo "\nVérification des tables...\n";
    
    $tables = ['utilisateur', 'compte'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "✅ Table '$table' : $count enregistrements\n";
        } catch (Exception $e) {
            echo "❌ Table '$table' : " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage() . "\n";
}

?>
