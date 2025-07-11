<?php

namespace App\Core\Abstract;
use  App\Core\Session;
abstract class AbstractController
{
    protected string $layout = 'base.layout.php';
    protected Session $session;

    public function __construct()
    {
        $this->session = Session::getInstance();
    }

    abstract public function index();

    abstract public function store();

    abstract public function create();


    abstract public function destroy();

    abstract public function show();

    abstract public function edit();



    /**
     * Rendre un template HTML
     */
    protected function render(string $template, array $data = [])
    {
        extract($data);
        
        // Chemin vers le dossier templates depuis app/core/abstract/
        $templatePath = __DIR__ . '/../../../templates/';
        
        // Essayer différents formats de fichiers
        $possibleFiles = [
            $templatePath . $template . '.html.php',
            $templatePath . $template . '.html',
            $templatePath . $template . '.php'
        ];
        
        foreach ($possibleFiles as $file) {
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
        
        throw new \Exception("Template '{$template}' non trouvé dans : " . $templatePath);
    }

    /**
     * Redirection
     */
    protected function redirect(string $url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Retourner une réponse JSON
     */
    protected function json(array $data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
