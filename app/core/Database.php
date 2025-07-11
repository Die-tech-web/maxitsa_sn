<?php




// namespace App\Core;

// use PDO;
// use PDOException;

// class Database {
//     private static $instance = null;
//     private $conn;

//     private function __construct() {
//         $host = $_ENV['DB_HOST'];
//         $port = $_ENV['DB_PORT'];
//         $dbName = $_ENV['DB_DATABASE'];
//         $username = $_ENV['DB_USERNAME'];
//         $password = $_ENV['DB_PASSWORD'];

//         $dsn = "pgsql:host=$host;port=$port;dbname=$dbName";

//         try {
//             $this->conn = new PDO($dsn, $username, $password);
//             $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//             // Vous pouvez ajouter d'autres attributs PDO si nécessaire
//         } catch (PDOException $e) {
//             die("Connection failed: " . $e->getMessage());
//         }
//     }

//     public static function getInstance() {
//         if (!self::$instance) {
//             self::$instance = new Database();
//         }
//         return self::$instance;
//     }

//     public function getConnection() {
//         return $this->conn;
//     }
// }


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
  echo "Connection successful";  
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