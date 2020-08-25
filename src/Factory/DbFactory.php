<?php

namespace App\Factory;

use LogicException;
use PDO;
use PDOStatement;

class DbFactory
{
    private static array $connections = [];

    private function createConnection(string $alias): PDO
    {
        $connection = null;

        switch($alias) {
            case 'src':
                $connection = new PDO("pgsql:host=postgres-src;port=5432;dbname=src_db", 'user', 'password');
                break;
            case 'dst':
                $connection = new PDO("mysql:dbname=dst_db;host=mysql-dest", 'root', 'password');
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
        if (!array_key_exists($alias, self::$connections)) {
            self::$connections[$alias] = $this->createConnection($alias);
        }
        return self::$connections[$alias];
    }

    public function getConnectionData(): PDO
    {
        return $this->getConnection('data');
    }

    public function createTable(string $alias, string $sql): void
    {
        $connection = $this->getConnection($alias);

        echo $sql . PHP_EOL;
        $execChunk = $connection->exec($sql);
        if ($execChunk !== false) {
            echo "SQL created!" . PHP_EOL;
        } else {
            print_r($connection->errorInfo());
        }
        echo PHP_EOL;
    }

    public function exec(string $alias, string $sql): void
    {
        $connection = $this->getConnection($alias);

        $execChunk = $connection->exec($sql);
        if ($execChunk === false) {
            print_r($connection->errorInfo());
        }
    }

    public function getSingleNumber(string $alias, string $sql)
    {
        $connection = $this->getConnection($alias);

        $stmt = $connection->query($sql);
        $number = $stmt->fetch(PDO::FETCH_NUM);
        return $number[0];
    }

    public function querySql(string $alias, string $sql): PDOStatement
    {
        echo "SQL: " . $sql . PHP_EOL;
        return $this->getConnection($alias)->query($sql);
    }
}
