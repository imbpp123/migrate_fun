<?php

namespace App\Factory;

use LogicException;
use PDO;

class DbFactory
{
    private array $connections = [];

    private function createConnection(string $alias): PDO
    {
        $connection = null;

        switch($alias) {
            case 'src':
                $connection = new PDO("pgsql:host=postgres-src;port=5432;dbname=src_db");
                break;
            case 'dst':
                $connection = new PDO("mysql:dbname=dst_db;host=mysql-dst", 'root', 'password');
                break;
            case 'data':
                $connection = new PDO("mysql:dbname=db_data;host=mysql-data", 'root', 'password');
                break;
            default:
                throw new LogicException("Wow!!! Wrong DB alias, man!");
        }

        return $connection;
    }

    public function getConnection(string $alias): PDO
    {
        if (!array_key_exists($alias, $this->connections)) {
            $this->connections[$alias] = $this->createConnection($alias);
        }
        return $this->connections[$alias];
    }

    public function getConnectionData(): PDO
    {
        return $this->getConnection('data');
    }

    public function createTable(string $alias, string $sql): void
    {
        $connection = $this->getConnection($alias);

        echo $sql . PHP_EOL;
        $chunkInfoCreated = $connection->exec($sql);
        if ($chunkInfoCreated !== false) {
            echo "Table created!" . PHP_EOL;
        } else {
            print_r($connection->errorInfo());
        }
    }
}
