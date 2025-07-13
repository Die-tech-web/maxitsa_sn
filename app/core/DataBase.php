<?php
namespace App\Core;

use PDO ;
use PDOException;
class DataBase {
private ?PDO $pdo=null;
private static ?DataBase $instance=null;

private function __construct() {
    //echo "Connecting to the database...<br>";
try {
  $as= $this->pdo = new PDO(
        'pgsql:host=localhost;dbname=maxit_sa;port=5432',
        'madie',
        'passer123',
       
    );
$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//   echo "Connection successful";  
} 

catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();

    }
}


public static function getInstance(): DataBase {
    if (self::$instance === null) {
        self::$instance = new DataBase();
    }
   if (self::$instance->pdo === null) {
        throw new PDOException("Database connection is not established.");
    }
    return self::$instance;
}

public function getConnexion(): PDO {
    if ($this->pdo === null) {
        throw new PDOException("Database connection is not established.");
    }
    return $this->pdo;

}


}