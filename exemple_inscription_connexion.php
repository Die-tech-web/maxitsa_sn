<?php

require_once 'vendor/autoload.php';

// Inclure les classes nécessaires
require_once 'app/core/Database.php';
require_once 'src/repository/UserRepository.php';
require_once 'src/repository/CompteRepository.php';

use App\Repository\UtilisateurRepository;

// Exemple d'inscription
$userRepo = new UtilisateurRepository();

// Données d'exemple pour l'inscription
$telephone = "771234567";
$cni = "1234567890123";
$nom = "Diop";
$prenom = "Amadou";
$adresse = "Dakar, Sénégal";
$password = "motdepasse123";

// Simulation des fichiers photos (dans un vrai cas, cela viendrait de $_FILES)
$photoRecto = [
    'name' => 'carte_recto.jpg',
    'tmp_name' => '/tmp/uploaded_file_recto',
    'size' => 1024,
    'error' => 0
];

$photoVerso = [
    'name' => 'carte_verso.jpg',
    'tmp_name' => '/tmp/uploaded_file_verso',
    'size' => 1024,
    'error' => 0
];

// Inscription
echo "=== INSCRIPTION ===\n";
$resultInscription = $userRepo->inscrire($telephone, $cni, $nom, $prenom, $adresse, $password, $photoRecto, $photoVerso);

if ($resultInscription['success']) {
    echo "✅ Inscription réussie !\n";
    echo "User ID: " . $resultInscription['user_id'] . "\n";
    echo "Numéro de compte: " . $resultInscription['numero_compte'] . "\n";
    echo "Compte ID: " . $resultInscription['compte_id'] . "\n";
} else {
    echo "❌ Erreur d'inscription: " . $resultInscription['message'] . "\n";
}

echo "\n";

// Connexion
echo "=== CONNEXION ===\n";
$resultConnexion = $userRepo->connexion($telephone, $password);

if ($resultConnexion['success']) {
    echo "✅ Connexion réussie !\n";
    echo "Utilisateur: " . $resultConnexion['user']['prenom'] . " " . $resultConnexion['user']['nom'] . "\n";
    echo "Téléphone: " . $resultConnexion['user']['telephone'] . "\n";
    
    if ($resultConnexion['compte']) {
        echo "Compte principal: " . $resultConnexion['compte']['numero'] . "\n";
        echo "Solde: " . $resultConnexion['compte']['solde'] . " FCFA\n";
    }
} else {
    echo "❌ Erreur de connexion: " . $resultConnexion['message'] . "\n";
}

echo "\n";

// Test de connexion avec mauvais mot de passe
echo "=== TEST CONNEXION AVEC MAUVAIS MOT DE PASSE ===\n";
$resultMauvaisMotDePasse = $userRepo->connexion($telephone, "mauvais_mot_de_passe");

if ($resultMauvaisMotDePasse['success']) {
    echo "✅ Connexion réussie (ne devrait pas arriver)\n";
} else {
    echo "❌ Erreur attendue: " . $resultMauvaisMotDePasse['message'] . "\n";
}

echo "\n";

// Test d'inscription avec même téléphone (devrait échouer)
echo "=== TEST INSCRIPTION AVEC MÊME TÉLÉPHONE ===\n";
$resultDoublon = $userRepo->inscrire($telephone, "9876543210987", "Ndiaye", "Fatou", "Thiès, Sénégal", "password456", $photoRecto, $photoVerso);

if ($resultDoublon['success']) {
    echo "✅ Inscription réussie (ne devrait pas arriver)\n";
} else {
    echo "❌ Erreur attendue: " . $resultDoublon['message'] . "\n";
}

?>
