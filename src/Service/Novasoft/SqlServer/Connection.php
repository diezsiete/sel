<?php


namespace App\Service\Novasoft\SqlServer;


use PDO;
use PDOStatement;

class Connection
{
    /**
     * @var PDO
     */
    private $conn;
    private $serverName;
    private $database;
    private $user;
    private $password;

    public function __construct($serverName, $database, $user, $password)
    {
        $this->serverName = $serverName;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @param $tsql
     * @param array $params
     * @return bool|PDOStatement
     */
    public function execute($tsql, $params = [])
    {
        $statement = $this->connection()->prepare($tsql);
        $statement->execute($params);
        return $statement;
    }

    /**
     * @return PDO
     */
    private function connection()
    {
        if(!$this->conn) {
            $this->conn = new PDO( "sqlsrv:server=$this->serverName ; Database=$this->database", $this->user, $this->password);
            $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        return $this->conn;
    }
}