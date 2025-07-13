<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\CompteRepository;
use App\Service\FileUploadService;

class CompteController
{
    private UserRepository $userRepository;
    private CompteRepository $compteRepository;
    private FileUploadService $fileUploadService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->compteRepository = new CompteRepository();
        $this->fileUploadService = new FileUploadService();
    }

    public function index()
    {
        $comptes = $this->compteRepository->findAll();
        $this->render('compte/index.php', ['comptes' => $comptes]);
    }

    public function create()
    {
        $this->render('compte/create.php', ['title' => 'Créer un compte']);
    }

    public function store()
    {
        $data = $_POST;
        $file = $_FILES['photo'] ?? null;

        // Validation simple
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email']) || empty($data['telephone']) || empty($data['id_user'])) {
            $this->render('compte/create.php', [
                'errors' => ['Tous les champs (nom, prenom, email, telephone, utilisateur) sont requis.'],
                'title' => 'Créer un compte'
            ]);
            return;
        }

        // Upload fichier photo (optionnel)
        if ($file && !$this->fileUploadService->upload($file)) {
            $this->render('compte/create.php', [
                'errors' => $this->fileUploadService->getErrors(),
                'title' => 'Créer un compte'
            ]);
            return;
        }

        // Récupération des infos nécessaires
        $idUser = (int) $data['id_user'];
        $idTelephone = isset($data['id_telephone']) ? (int) $data['id_telephone'] : 0; // tu dois gérer cette donnée correctement
        $typeCompte = $data['type_compte'] ?? 'standard'; // valeur par défaut
        $password = $data['password'] ?? null;

        // Créer le compte principal via CompteRepository
        $result = $this->compteRepository->createComptePrimaire($idUser, $idTelephone, $typeCompte, $password);

        if ($result['success']) {
            $_SESSION['message'] = "Compte créé avec succès.";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['message'] = "Erreur lors de la création du compte : " . $result['message'];
            $_SESSION['type'] = "danger";
        }

        header('Location: /comptes');
        exit();
    }

    public function show($id)
    {
        $compte = $this->compteRepository->findById($id);
        if (!$compte) {
            header('HTTP/1.0 404 Not Found');
            echo 'Compte non trouvé';
            return;
        }
        $this->renderHtml('compte/show.php', ['compte' => $compte]);
    }

   

 

}
