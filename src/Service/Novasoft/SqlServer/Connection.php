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

    public function __construct($serverName, $database, $user, $password)
    {
        $this->conn = new PDO( "sqlsrv:server=$serverName ; Database=$database", $user, $password);
        $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    /**
     * @param $tsql
     * @param array $params
     * @return bool|PDOStatement
     */
    public function execute($tsql, $params = [])
    {
        $statement = $this->conn->prepare($tsql);
        $statement->execute($params);
        return $statement;
    }
}