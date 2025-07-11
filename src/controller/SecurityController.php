<?php

namespace App\Controller;

require_once __DIR__ . '/../../app/core/abstract/AbstractController.php';

use App\Core\Abstract\AbstractController;

class SecurityController extends AbstractController
{
    /**
     * Page d'accueil - Affiche client.agent.html
     */
    public function index()
    {
        $this->render('client.agent');
    }

    /**
     * Choix client - redirige vers login
     */
    public function clientChoice()
    {
        // Vérifier si déjà connecté
        session_start();
        if (isset($_SESSION['user'])) {
            $this->redirect('/client/dashboard');
        }
        
        // Sinon rediriger vers login
        $this->redirect('/login');
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function loginForm()
    {
        $this->render('login');
    }

    /**
     * Traite la connexion
     */
    public function authenticate()
    {
        $telephone = $_POST['telephone'] ?? '';
        $password = $_POST['password'] ?? '';

        $validator = new Validator();
        $validator->validator($telephone, 'telephone');
        $validator->validator($password, 'password');

        if ($validator->hasErrors()) {
            $this->renderHtml('security/login.html.php', [
                'errors' => $validator->getErrors(),
                'telephone' => $telephone,
                'title' => 'Connexion - MAXIT SA',
                'showNavbar' => false
            ]);
            return;
        }

        // Test de connexion simple
        if ($telephone === '771234567' && $password === 'password') {
            $this->session->login([
                'id' => 1,
                'role' => 'client',
                'telephone' => $telephone,
                'nom' => 'Niang',
                'prenom' => 'Dié'
            ]);

            header('Location: /client/dashboard');
            exit();
        } else {
            $validator->addError('login', 'Identifiants invalides');
            $this->renderHtml('security/login.html.php', [
                'errors' => $validator->getErrors(),
                'telephone' => $telephone,
                'title' => 'Connexion - MAXIT SA',
                'showNavbar' => false
            ]);
        }
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function registerForm()
    {
        $this->render('register');
    }

    /**
     * Traite l'inscription
     */
public function register()
{
    session_start();

    $telephone = $_POST['telephone'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $cni = $_POST['cni'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $password = $_POST['password'] ?? '';

    $photoRecto = $_FILES['photo_recto'] ?? null;
    $photoVerso = $_FILES['photo_verso'] ?? null;

    $validator = new \App\Core\Validator();

    if (empty($nom)) $validator->addError('nom', 'Nom requis.');
    if (empty($prenom)) $validator->addError('prenom', 'Prénom requis.');
    if (empty($telephone)) $validator->addError('telephone', 'Téléphone requis.');
    if (empty($cni)) $validator->addError('cni', 'CNI requis.');
    if (empty($adresse)) $validator->addError('adresse', 'Adresse requise.');
    if (empty($password)) $validator->addError('password', 'Mot de passe requis.');
    if (!$photoRecto || !$photoRecto['name'] || !$photoVerso || !$photoVerso['name']) {
        $validator->addError('photos', 'Photos recto et verso obligatoires.');
    }

    $userRepo = new \App\Repository\UserRepository();

    // ✅ Vérifie si le téléphone existe déjà
    if ($userRepo->findByTelephone($telephone)) {
        $validator->addError('telephone', 'Ce numéro est déjà utilisé.');
    }

    if (!$validator->isValid()) {
        $_SESSION['errors'] = $validator->getErrors();
        $this->redirect('/register');
        return;
    }

    // ✅ Insertion de l'utilisateur
    $user = $userRepo->insert($telephone, $cni, $nom, $prenom, $adresse, $password, $photoRecto, $photoVerso);

    if (!$user || !isset($user['id'])) {
        $_SESSION['message'] = "Erreur lors de l'inscription.";
        $_SESSION['type'] = 'error';
        $this->redirect('/register');
        return;
    }

    // ✅ Création du compte principal
    $compteRepo = new \App\Repository\CompteRepository();
    $result = $compteRepo->createComptePrimaire((int)$user['id'], (int)$user['telephone'], 'standard', $password);

    if ($result['success']) {
        $_SESSION['message'] = "Inscription réussie avec succès";
        $_SESSION['type'] = 'success';
    } else {
        $_SESSION['message'] = "Utilisateur créé, mais erreur création du compte";
        $_SESSION['type'] = 'warning';
    }

    $this->redirect('/login');
}




    /**
     * Déconnexion
     */
    public function logout()
    {
        session_start();
        session_destroy();
        $this->redirect('/');
    }

    // Méthodes abstraites obligatoires
    public function store() {}
    public function create() {}
    public function destroy() {}
    public function show() {}
    public function edit() {}
}
