<?php
namespace App\Core\Middlewares;
class Auth{
    public function __invoke(){
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }
    }
}
