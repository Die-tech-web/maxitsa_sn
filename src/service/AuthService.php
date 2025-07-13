<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Core\Validator;

class AuthService
{
    private UserRepository $userRepository;
    private Validator $validator;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->validator = new Validator();
    }

    /**
     * Authentifie un utilisateur
     */
    public function authenticate(string $telephone, string $password): array
    {
        // Validation des données d'entrée
        $this->validator->validator($telephone, 'telephone');
        $this->validator->validator($password, 'password');

        if ($this->validator->hasErrors()) {
            return [
                'success' => false,
                'errors' => $this->validator->getErrors(),
                'message' => 'Données invalides'
            ];
        }

        // Recherche de l'utilisateur

        $user = $this->userRepository->findByTelephone($telephone);
        var_dump($user);
        die();
        if (!$user) {
            $this->validator->addError('login', 'Aucun compte trouvé avec ce numéro de téléphone.');
            return [
                'success' => false,
                'errors' => $this->validator->getErrors(),
                'message' => 'Utilisateur non trouvé'
            ];
        }

        // Vérification du mot de passe
        if (!password_verify($password, $user['password'])) {
            $this->validator->addError('login', 'Mot de passe incorrect.');
            return [
                'success' => false,
                'errors' => $this->validator->getErrors(),
                'message' => 'Mot de passe incorrect'
            ];
        }

        // Authentification réussie
        return [
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'telephone' => $user['telephone'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'cni' => $user['cni'],
                'adresse' => $user['adresse'],
                'role' => 'client'
            ],
            'message' => 'Connexion réussie'
        ];
    }

    /**
     * Crée une session utilisateur
     */
    public function createUserSession(array $userData): void
    {
        $_SESSION['user'] = $userData;
        $_SESSION['authenticated'] = true;
        $_SESSION['login_time'] = time();
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public function isAuthenticated(): bool
    {
        return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(): void
    {
        session_destroy();
    }
}