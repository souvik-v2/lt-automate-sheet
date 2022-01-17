<?php
/*
Page: db.php
Author: Souvik Patra
*/
class DB
{
    public $pdo;

    public function __construct($db, $username = NULL, $password = NULL, $host = 'localhost', $port = 3306, $options = [])
    {
        $default_options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        $options = array_replace($default_options, $options);
        $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";

        try {
            $this->pdo = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    public function run($sql, $args = NULL)
    {
        if (!$args)
        {
            return $this->pdo->query($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    public function quote($value) {
        return $this->pdo->quote($value);
    }

    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }
}
//
$db_name = "lt-automate-sheet";
$db_user = "root";
$db_pass = "";
$con = new DB($db_name, $db_user, $db_pass);
?>