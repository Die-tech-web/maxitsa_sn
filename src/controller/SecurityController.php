<?php
namespace App\Controller;

require_once __DIR__ . '/../../app/core/abstract/AbstractController.php';
use App\Core\Abstract\AbstractController;
use App\Core\Validator;

class SecurityController extends AbstractController
{
    /*** Page d'accueil - Affiche client.agent.html*/
    public function index()
    {
        $this->render('client.agent');
    }

    public function clientChoice()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            $this->redirect('/client/dashboard');
        }
        $this->redirect('/login');
    }

    public function loginForm()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $errors = $_SESSION['errors'] ?? [];
        $oldTelephone = $_SESSION['old_telephone'] ?? '';
        unset($_SESSION['errors']);
        unset($_SESSION['old_telephone']);
        $this->render('login', [
            'errors' => $errors,
            'telephone' => $oldTelephone
        ]);
    }

    public function authenticate()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $telephone = $_POST['telephone'] ?? '';
        $password = $_POST['password'] ?? '';

        $validator = new Validator();
        $validator->validator($telephone, 'telephone');
        $validator->validator($password, 'password');

        if ($validator->hasErrors()) {
            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['old_telephone'] = $telephone;
            $this->redirect('/login');
            return;
        }

        $userRepo = new \App\Repository\UserRepository();
        $user = $userRepo->findByTelephone($telephone);

        if ($user && $user['password'] === $password) {
            $this->session->set('user', [
                'id' => $user['id'],
                'role' => 'client',
                'telephone' => $user['telephone'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom']
            ]);

            $_SESSION['message'] = "Connexion réussie avec succès";
            $_SESSION['type'] = 'success';
            $_SESSION['welcome_message'] = "Bienvenue dans MAXITSA !";
            header('Location: /client/dashboard');
            exit();
        } else {
            $_SESSION['errors'] = ['login' => 'Identifiants invalides'];
            $_SESSION['old_telephone'] = $telephone;
            $this->redirect('/login');
        }
    }

    public function registerForm()
    {
        $this->render('register');
    }

    public function register()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
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
        if ($userRepo->findByTelephone($telephone)) {
            $validator->addError('telephone', 'Ce numéro est déjà utilisé.');
        }

        if ($validator->hasErrors()) {
            $_SESSION['errors'] = $validator->getErrors();
            $this->redirect('/register');
            return;
        }

        $user = $userRepo->insert($telephone, $cni, $nom, $prenom, $adresse, $password, $photoRecto, $photoVerso);

        if (!$user || !isset($user['id'])) {
            $_SESSION['message'] = "Erreur lors de l'inscription.";
            $_SESSION['type'] = 'error';
            $this->redirect('/register');
            return;
        }

        $compteRepo = new \App\Repository\CompteRepository();
        $result = $compteRepo->createComptePrimaire((int)$user['id'], (int)$user['telephone'], 'standard', $password);

        if ($result['success']) {
            $_SESSION['success_message'] = "Inscription réussie avec succès !";
            $_SESSION['type'] = 'success';
        } else {
            $_SESSION['message'] = "Utilisateur créé, mais erreur création du compte";
            $_SESSION['type'] = 'warning';
        }

        $this->redirect('/login');
    }

    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        $this->redirect('/');
    }

     public function dashboard()
            {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $telephone = $_SESSION['user']['telephone'] ?? null;

                if (!$telephone) {
                    $this->redirect('/login');
                    return;
                }

                $transactionRepo = new \App\Repository\TransactionRepository();
                $transactions = $transactionRepo->findTransactionsByTelephone($telephone);
                $solde = $transactionRepo->getSoldeByTelephone($telephone);

                $this->render('client.dashbord', [
                    'transactions' => $transactions,
                    'solde' => $solde
                ]);
        }


    // ✅ CORRECTION : Vérifier et nettoyer la session agent
    public function agentChoice()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // ✅ FORCER LA DÉCONNEXION DE L'AGENT pour tester
        unset($_SESSION['agent']);
        
        // Redirection directe vers la page de connexion agent
        $this->redirect('/agent/login');
    }

    /*** Affiche le formulaire de connexion agent*/
    public function agentLoginForm()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $errors = $_SESSION['errors'] ?? [];
        $oldTelephone = $_SESSION['old_telephone'] ?? '';
        unset($_SESSION['errors']);
        unset($_SESSION['old_telephone']);
        
        $this->render('agent.login', [
            'errors' => $errors,
            'telephone' => $oldTelephone
        ]);
    }

    /*** Traite la connexion agent*/
    public function agentAuthenticate()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $telephone = $_POST['telephone'] ?? '';
        $password = $_POST['password'] ?? '';

        $validator = new \App\Core\Validator();
        if (empty($telephone)) $validator->addError('telephone', 'Téléphone requis.');
        if (empty($password)) $validator->addError('password', 'Mot de passe requis.');

        if ($validator->hasErrors()) {
            $_SESSION['errors'] = $validator->getErrors();
            $_SESSION['old_telephone'] = $telephone;
            $this->redirect('/agent/login');
            return;
        }

        // Récupération depuis la base de données
        $agentRepo = new \App\Repository\AgentRepository();
        $agent = $agentRepo->findByTelephone($telephone);

        if ($agent && $agent['password'] === $password) {
            $_SESSION['agent'] = [
                'id' => $agent['id'],
                'role' => 'agent',
                'telephone' => $agent['telephone'],
                'nom' => $agent['nom'],
                'prenom' => $agent['prenom']
            ];

            $_SESSION['message'] = "Connexion agent réussie !";
            $_SESSION['type'] = 'success';
            header('Location: /agent/dashboard');
            exit();
        } else {
            $_SESSION['errors'] = ['login' => 'Identifiants invalides'];
            $_SESSION['old_telephone'] = $telephone;
            $this->redirect('/agent/login');
        }
    }

    /*** Dashboard agent commercial*/
    public function agentDashboard()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['agent'])) {
            $this->redirect('/agent/login');
            return;
        }
        $this->render('agent.commercial');
    }

    // Méthodes abstraites obligatoires
    public function store() {}
    public function create() {}
    public function destroy() {}
    public function show() {}
    public function edit() {}
}