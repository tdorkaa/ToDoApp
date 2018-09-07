<?php

namespace ToDoApp\Dao;


use ToDoApp\Entity\Todo;

class TodosDao
{

    private $PDO;

    public function __construct(\PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    /**
     * @return Todo[]
     */
    public function listTodos()
    {
        $todos = $this->fetchTodos();

        foreach ($todos as $index => $todo) {
            $todos[$index] = Todo::fromArray($todo);
        }

        return $todos;
    }

    private function fetchTodos()
    {
        $sql = '
            SELECT name, description, status, due_at 
            FROM todos 
        ';
        $statement = $this->PDO->query($sql);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}