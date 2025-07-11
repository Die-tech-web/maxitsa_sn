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

        $validator = new \App\Core\Validator();
        
        if (empty($telephone)) $validator->addError('telephone', 'Téléphone requis.');
        if (empty($password)) $validator->addError('password', 'Mot de passe requis.');

        if ($validator->hasErrors()) {
            $this->renderHtml('security/login.html.php', [
                'errors' => $validator->getErrors(),
                'telephone' => $telephone,
                'title' => 'Connexion - MAXIT SA',
                'showNavbar' => false
            ]);
            return;
        }

        // Vérification en base de données
        $userRepo = new \App\Repository\UserRepository();
        $user = $userRepo->findByTelephoneAndPassword($telephone, $password);

        if ($user) {
            session_start();
            $_SESSION['user'] = [
                'id' => $user['id'],
                'role' => 'client',
                'telephone' => $user['telephone'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom']
            ];
            
            $_SESSION['message'] = "Connexion réussie avec succès";
            $_SESSION['type'] = 'success';
            
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
        $telephone = $_POST['phone'] ?? '';
        $nom = $_POST['name'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $cni = $_POST['cni'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $photoRecto = $_FILES['photo_recto'] ?? null;
        $photoVerso = $_FILES['photo_verso'] ?? null;

        $validator = new \App\Core\Validator();
        $userRepo = new \App\Repository\UserRepository();

        if (empty($nom)) $validator->addError('nom', 'Nom requis.');
        if (empty($prenom)) $validator->addError('prenom', 'Prénom requis.');
        if (empty($telephone)) $validator->addError('telephone', 'Téléphone requis.');
        if (empty($cni)) $validator->addError('cni', 'CNI requis.');
        if (empty($adresse)) $validator->addError('adresse', 'Adresse requise.');
        if (empty($password)) $validator->addError('password', 'Mot de passe requis.');
        if (empty($confirmPassword)) $validator->addError('confirm_password', 'Confirmation du mot de passe requise.');

        if ($password !== $confirmPassword) {
            $validator->addError('confirm_password', 'Les mots de passe ne correspondent pas.');
        }

        if (!empty($cni) && !preg_match('/^[12]\d+$/', $cni)) {
            $validator->addError('cni', 'Le CNI doit commencer par 1 (homme) ou 2 (femme).');
        }

        if (!empty($telephone) && $userRepo->telephoneExists($telephone)) {
            $validator->addError('telephone', 'Ce numéro de téléphone est déjà utilisé.');
        }

        if (!empty($cni) && $userRepo->cniExists($cni)) {
            $validator->addError('cni', 'Ce CNI est déjà utilisé.');
        }

        if (!$photoRecto || !$photoRecto['name'] || !$photoVerso || !$photoVerso['name']) {
            $validator->addError('photos', 'Photos recto et verso obligatoires.');
        }

        if (!$validator->isValid()) {
            $_SESSION['errors'] = $validator->getErrors();
            $this->redirect('/register');
            return;
        }

        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $user = $userRepo->insert($telephone, $cni, $nom, $prenom, $adresse, $hashedPassword, $photoRecto, $photoVerso);

            if ($user) {
                $compteRepo = new \App\Repository\CompteRepository();
                $result = $compteRepo->createComptePrimaire($user['id']);

                if ($result['success']) {
                    $_SESSION['message'] = "Inscription réussie avec succès ! Vous pouvez maintenant vous connecter.";
                    $_SESSION['type'] = 'success';
                } else {
                    $_SESSION['message'] = "Utilisateur créé, mais erreur lors de la création du compte.";
                    $_SESSION['type'] = 'warning';
                }
            } else {
                $_SESSION['message'] = "Erreur lors de l'inscription.";
                $_SESSION['type'] = 'error';
            }

        } catch (\Exception $e) {
            $_SESSION['message'] = "Erreur lors de l'inscription : " . $e->getMessage();
            $_SESSION['type'] = 'error';
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