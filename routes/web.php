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
Router::get('/client/dashboard', SecurityController::class, 'dashboard');

// ✅ ROUTES AGENT MODIFIÉES - Plus de registration, connexion directe
Router::get('/agent/choice', SecurityController::class, 'agentChoice'); // Ancienne route pour compatibilité
Router::get('/agent', SecurityController::class, 'agentChoice'); // Nouvelle route
Router::get('/agent/login', SecurityController::class, 'agentLoginForm'); // Formulaire de connexion agent
Router::post('/agent/login', SecurityController::class, 'agentAuthenticate'); // Traite la connexion agent
Router::get('/agent/dashboard', SecurityController::class, 'agentDashboard'); // Dashboard agent

Router::resolve();