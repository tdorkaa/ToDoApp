<?php

namespace ToDoApp;

class PdoFactory
{
    private static $pdo = null;

    public function getPDO()
    {
        if (!self::$pdo) {
            $userName =  'academy';
            $password = 'academy';
            try {
                self::$pdo = new \PDO($this->getDsn(), $userName, $password);
                self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $exception) {
                var_dump($exception->getMessage());
            }
        }

        return self::$pdo;
    }

    private function getDsn(): string
    {
        $host = "mysql";
        $dbName = 'todos';
        $charset = "utf8mb4";
        return "mysql:host={$host};dbname={$dbName};charset={$charset}";
    }
}