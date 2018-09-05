<?php

namespace ToDoApp\Dao;


class TodosDao
{

    private $PDO;

    public function __construct(\PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function listTodos()
    {
        $sql = '
            SELECT name, description, status, due_at 
            FROM todos 
        ';
        $statement = $this->PDO->query($sql);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}