<?php
use App\Core\Router;
use App\Controller\SecurityController;
use App\Controller\ClientController;

// Page d'accueil - Choix entre Client et Service Commercial
Router::get('/', SecurityController::class, 'index'); // Affiche client.agent.html

// Routes Client
Router::get('/client', SecurityController::class, 'clientChoice'); // Redirige vers login si pas connecté
Router::get('/login', SecurityController::class, 'loginForm'); // Affiche login.html.php
Router::post('/login', SecurityController::class, 'authenticate'); // Traite la connexion
Router::get('/register', SecurityController::class, 'registerForm'); // Affiche register.html.php  
Router::post('/register', SecurityController::class, 'register'); // Traite l'inscription
Router::get('/logout', SecurityController::class, 'logout');

// Routes Client protégées (après connexion)
Router::get('/client/dashboard', ClientController::class, 'dashboard', ['auth', 'client']);

Router::resolve();
